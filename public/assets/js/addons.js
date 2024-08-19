define([], function () {
    require.config({
    paths: {
        'ldcms-jquery-tagsinput': '../addons/ldcms/admin/libs/jquery.tagsinput',
        'ldcms-jquery-autocomplete': '../addons/ldcms/admin/libs/jquery.autocomplete',
        'ldcms-editable': '../libs/bootstrap-table/dist/extensions/editable/bootstrap-table-editable.min',
        'x-editable': '../addons/ldcms/admin/libs/bootstrap-editable.min',
    },
    shim: {
        'ldcms-jquery-autocomplete': {
            deps: ['jquery'],
            exports: '$.fn.extend'
        },
        'ldcms-jquery-tagsinput': {
            deps: ['jquery', 'ldcms-jquery-autocomplete', 'css!../addons/ldcms/admin/libs/jquery.tagsinput.min.css'],
            exports: '$.fn.extend'
        },
        'ldcms-editable': {
            deps: ['x-editable', 'bootstrap-table']
        },
        "x-editable": {
            deps: ["css!../addons/ldcms/admin/libs/bootstrap-editable.css"],
        }
    }
});
require(['jquery','template'], function ($,template) {
    if(Config.ldcmslang){
        var tpl="<div style=\"position: absolute;top: 15px;right: 15px;width: 100px;text-align: center; background: #444c69;border-radius: 10px;\">\n" +
            "        <a href=\"javascript:;\" class=\"dropdown-toggle\" style=\"color: #fff;\" data-toggle=\"dropdown\" aria-expanded=\"false\"><i class=\"fa fa-language\"></i> <%=langs[language] %> <i class=\"fa fa-angle-down\"></i></a>\n" +
            "        <ul class=\"dropdown-menu\" style=\"left: auto;right:0;min-width: 100px\">\n" +
            "     <% for(var i  in langs ){%> <li <% if(i==language){%> class=\"active\"<% } %> >\n" +
            "                <a href=\"?ref=addtabs&ldcms_lang=<%=i %>\"><%=langs[i] %></a>\n" +
            "            </li>\n <% } %>" +
            "        </ul>\n" +
            "    </div>";
        var html = template.render(tpl, {
            langs:Config.ldcms.langs,
            language:Config.ldcmslang
        });
        $('.ldcmslang').html(html);
        /*父框架刷新*/
        $('.ldcmslang .dropdown-menu li a').on('click',function (){
            window.parent.location.href=$(this).attr('href');
            return false;
        });
    }

});
require.config({
    paths: {
        'summernote': '../addons/summernote/lang/summernote-zh-CN.min'
    },
    shim: {
        'summernote': ['../addons/summernote/js/summernote.min', 'css!../addons/summernote/css/summernote.min.css'],
    }
});
require(['form', 'upload'], function (Form, Upload) {
    var _bindevent = Form.events.bindevent;
    Form.events.bindevent = function (form) {
        _bindevent.apply(this, [form]);
        try {
            //绑定summernote事件
            if ($(Config.summernote.classname || '.editor', form).length > 0) {
                var selectUrl = typeof Config !== 'undefined' && Config.modulename === 'index' ? 'user/attachment' : 'general/attachment/select';
                require(['summernote'], function () {
                    var imageButton = function (context) {
                        var ui = $.summernote.ui;
                        var button = ui.button({
                            contents: '<i class="fa fa-file-image-o"/>',
                            tooltip: __('Choose'),
                            click: function () {
                                parent.Fast.api.open(selectUrl + "?element_id=&multiple=true&mimetype=image/", __('Choose'), {
                                    callback: function (data) {
                                        var urlArr = data.url.split(/\,/);
                                        $.each(urlArr, function () {
                                            var url = Fast.api.cdnurl(this, true);
                                            context.invoke('editor.insertImage', url);
                                        });
                                    }
                                });
                                return false;
                            }
                        });
                        return button.render();
                    };
                    var attachmentButton = function (context) {
                        var ui = $.summernote.ui;
                        var button = ui.button({
                            contents: '<i class="fa fa-file"/>',
                            tooltip: __('Choose'),
                            click: function () {
                                parent.Fast.api.open(selectUrl + "?element_id=&multiple=true&mimetype=*", __('Choose'), {
                                    callback: function (data) {
                                        var urlArr = data.url.split(/\,/);
                                        $.each(urlArr, function () {
                                            var url = Fast.api.cdnurl(this, true);
                                            var node = $("<a href='" + url + "'>" + url + "</a>");
                                            context.invoke('insertNode', node[0]);
                                        });
                                    }
                                });
                                return false;
                            }
                        });
                        return button.render();
                    };

                    $(Config.summernote.classname || '.editor', form).each(function () {
                        $(this).summernote($.extend(true, {}, {
                            height: isNaN(Config.summernote.height) ? null : parseInt(Config.summernote.height),
                            minHeight: parseInt(Config.summernote.minHeight || 250),
                            toolbar: Config.summernote.toolbar,
                            followingToolbar: parseInt(Config.summernote.followingToolbar),
                            placeholder: Config.summernote.placeholder || '',
                            airMode: parseInt(Config.summernote.airMode) || false,
                            lang: 'zh-CN',
                            fontNames: [
                                'Arial', 'Arial Black', 'Serif', 'Sans', 'Courier',
                                'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande',
                                "Open Sans", "Hiragino Sans GB", "Microsoft YaHei",
                                '微软雅黑', '宋体', '黑体', '仿宋', '楷体', '幼圆',
                            ],
                            fontNamesIgnoreCheck: [
                                "Open Sans", "Microsoft YaHei",
                                '微软雅黑', '宋体', '黑体', '仿宋', '楷体', '幼圆'
                            ],
                            buttons: {
                                image: imageButton,
                                attachment: attachmentButton,
                            },
                            dialogsInBody: true,
                            callbacks: {
                                onChange: function (contents) {
                                    $(this).val(contents);
                                    $(this).trigger('change');
                                },
                                onInit: function () {
                                },
                                onImageUpload: function (files) {
                                    var that = this;
                                    //依次上传图片
                                    for (var i = 0; i < files.length; i++) {
                                        Upload.api.send(files[i], function (data) {
                                            var url = Fast.api.cdnurl(data.url, true);
                                            $(that).summernote("insertImage", url, 'filename');
                                        });
                                    }
                                }
                            }
                        }, $(this).data("summernote-options") || {}));
                    });
                });
            }
        } catch (e) {

        }

    };
});

});