define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    Fast.config.openArea = ['98%', '95%'];
    var addParams = $('.btn-add').data('params');
    var editParams = $('.btn-edit').data('params');
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ldcms/document/index/mid/'+Config.mid + location.search,
                    add_url: 'ldcms/document/add?' + addParams,
                    edit_url: 'ldcms/document/edit?' + editParams,
                    del_url: 'ldcms/document/del',
                    multi_url: 'ldcms/document/multi',
                    import_url: 'ldcms/document/import',
                    translate_url:'ldcms/document/translate',
                    sync_data_url:'ldcms/copy_langs/document',
                    table: 'ldcms_document',
                }
            });

            var table = $("#table");
            var tableParams = table.data('params');
            var columns = [
                { checkbox: true },
                { field: 'id', title: __('Id'), width: 60, operate: false },
                {field: 'sort', title: __('Sort'), width: 80, operate: false, formatter: Controller.api.formatter.input, events: {
                        "dblclick .text-sort": function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }}
                },
                // {field: 'mid', title: __('Mid'), width: 90, operate: false, formatter: function (value, row, index) {
                //         return Config.mname;
                // }},
                {field: 'cid', title: __('Cid'), addclass: 'selectpage',
                    extend: 'data-source="ldcms/document/category?' + tableParams + '" data-select-only="true"  data-field="icon_name"',
                    operate: 'in',
                    visible: false,
                },
                { field: 'cname', title: __('Cid'), width: 120, operate: false },
                { field: 'title', title: __('Title'), operate: 'LIKE', align: 'left', customField: 'flag',formatter:function (value, row, index) {
                    value.length > 20 ? tit = value.substring(0, 20) + '...' : tit = value; //截取20个字符
                    var html='<div class="archives-title"><a href="' + row.url + '" target="_blank" title="'+value+'"><span style="color:' + (row.style_color ? row.style_color : 'inherit') + ';font-weight:' + (row.style_bold ? 'bold' : 'normal') + '">' + tit + '</span></a></div>';

                    if(row['flag']){
                        var flagObj = $.extend({}, this, {searchList: Config.flags});
                        html+='<div class="archives-label">' + Table.api.formatter.flag.call(flagObj, row['flag'], row, index) + '</div>';
                    }
                    return html;
                } },
                {field: 'flag', title: __('Flag'), operate: 'find_in_set', visible: false, searchList: Config.flags, formatter: Table.api.formatter.flag},
                { field: 'image', title: __('Image'), width: 100, operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image },
            ];

            //动态追加字段
            $.each(Config.fields, function (i, j) {
                var data = { field: j.field, title: j.title, table: table, operate: (j.type === 'number' ? '=' : 'like') ,visible:j.islist};
                //如果是图片,加上formatter
                if (j.type == 'image') {
                    data.events = Table.api.events.image;
                    data.formatter = Table.api.formatter.image;
                } else if (j.type == 'images') {
                    data.events = Table.api.events.image;
                    data.formatter = Table.api.formatter.images;
                } else if (j.type == 'radio' || j.type == 'checkbox' || j.type == 'select' || j.type == 'selects') {
                    if(j.type == 'selects'||j.type == 'checkbox'){
                        data.operate = 'find_in_set';
                    }
                    data.formatter = Controller.api.formatter.content;
                    var content_list_arr=Object.assign({},j.content_list_arr);
                    data.extend = content_list_arr;
                    data.searchList = content_list_arr;
                } else {
                    data.formatter = Controller.api.formatter.info;
                }

                data.width = '150px'
                columns.push(data);
            });

            var rightColumn = [
                {field: 'gidname', width:100, operate:false,  title: __('浏览权限'), formatter: function (value,row,index) {
                    value=row.gid;
                    if(value==0){
                        return '公开';
                    }
                    var arr=[];
                    value.split(',').forEach(function (item){
                        arr.push('会员等级'+item)
                    })
                    return arr.join(',')
                }},
                {field: 'gid', title: __('浏览权限'), addclass: 'selectpage',
                    extend: 'data-source="ldcms/document/getUserLevels" data-field="title"',
                    operate: 'in',
                    visible: false,
                },
                {field: 'author', title: __('作者') ,width: 100,},
                { field: 'status', width: 80,searchList:Object.assign({}, Config.status) ,title: __('Status'),formatter: function (value,row,index) {
                    var colorarr=["primary", "success", "danger", "warning", "info", "gray"];
                    var color=colorarr[value];
                    var html = '<span class="text-' + color + '"><i class="fa fa-circle"></i> ' + row.status_text + '</span>';
                    return html;
                } },
                { field: 'show_time', width: 150, title: __('Show_time'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                {field: 'operate', width: 120, title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate, buttons: [
                    {
                        name: 'edit',
                        icon: 'fa fa-pencil',
                        title: __('Edit'),
                        extend: 'data-toggle="tooltip"',
                        classname: 'btn btn-xs btn-success btn-editone',
                    },
                    {
                        name: 'del',
                        icon: 'fa fa-trash',
                        title: __('Del'),
                        extend: 'data-toggle="tooltip"',
                        classname: 'btn btn-xs btn-danger btn-delone',
                        visible: function (row) {
                            return row.mid != 1;
                        }
                    }]
                }
            ];
            //追加操作字段
            columns = columns.concat(rightColumn)

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'sort',
                sortOrder: 'asc',
                fixedColumns: true,
                escape: false,
                searchFormVisible: true,
                fixedRightNumber: 1,
                fixedNumber:3,
                columns: columns
            });

            $(document).on("change", ".text-sort", function () {
                $(this).data("params", { sort: $(this).val() });
                Table.api.multi('', [$(this).data("id")], table, this);
                return false;
            });

            /*移动数据*/
            $(document).on('click', '.btn-move', function () {
                var ids = Table.api.selectedids(table);
                Layer.open({
                    title: __('Move'),
                    content: Template("categorytpl", {}),
                    btn: [__('Move')],
                    yes: function (index, layero) {
                        var cid = $("select[name='cid']", layero).val();
                        if (cid == 0) {
                            Toastr.error(__('Please select channel'));
                            return;
                        }
                        Fast.api.ajax({
                            url: "ldcms/document/move/ids/" + ids.join(","),
                            type: "post",
                            data: { cid: cid },
                        }, function () {
                            table.bootstrapTable('refresh', {});
                            Layer.close(index);
                        });
                    },
                    success: function (layero, index) {
                    }
                });
            });

            /*复制数据*/
            $(document).on('click', '.btn-copyselected', function () {
                var ids = Table.api.selectedids(table);
                var params = $(this).data('params');
                Layer.confirm(__("Are you sure you want to copy %s records?", ids.length), { icon: 3 }, function (index, layero) {
                    Fast.api.ajax({
                        url: "ldcms/document/copy/ids/" + ids.join(",") + "?" + params,
                        type: "post",
                    }, function () {
                        table.bootstrapTable('refresh', {});
                    });
                });
                return false;
            });

            /*百度推送*/
            $(document).on('click', '.btn-baidupush', function () {
                var ids = Table.api.selectedids(table);
                var params = $(this).data('params');
                Fast.api.ajax({
                    url: "ldcms/document/baidupush/ids/" + ids.join(",") + "?" + params,
                    type: "post",
                });
                return false;
            });

            // 跨语言复制数据
            $(document).on("click", ".btn-sync_data", function () {
                var params = $(this).data('params');
                var options = table.bootstrapTable('getOptions');
                var url = options.extend.sync_data_url+"?" + params;
                Fast.api.open(url, $(this).data("original-title") || $(this).attr("title") || __('Add'), $(this).data() || {});
            });

            // api翻译
            $(document).on("click",".btn-translation",function(){
                var ids = Table.api.selectedids(table);
                if(ids.length==0){
                    Toastr.error(__('Please select the data to be translated'));
                    return false;
                }
                var options = table.bootstrapTable('getOptions');
                var params = $(this).data('params');
                var url = options.extend.translate_url+"?" + params + '&ids='+ ids.join(",");
                Fast.api.open(url, $(this).data("original-title") || $(this).attr("title"),  {
                    area:['400px','350px']
                });
                return false;
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'ldcms/document/recyclebin',
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        { field: 'title', title: __('Title'), align: 'left', operate: 'like' },
                        { field: 'thumb', title: __('Image'), operate: false, formatter: Table.api.formatter.image },
                        {
                            field: 'delete_time',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'ldcms/document/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'ldcms/document/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent($("form[role=form]"));
        },
        edit: function () {
            Controller.api.bindevent($("form[role=form]"));
        },
        translate:function(){
            Controller.api.bindevent($("form[role=form]"));
        },
        api: {
            formatter: {
                input: function (value, row, index) {
                    value = value == null || value.length === 0 ? '' : value.toString();
                    return '<input type="text" class="form-control input-sm text-sort" data-id="' + row.id + '" value="' + value + '">';
                },
                content: function (value, row, index) {
                    var extend = this.extend;
                    if (!value) {
                        return '';
                    }

                    var valueArr = value.toString().split(/\,/);
                    var result = [];
                    $.each(valueArr, function (i, j) {
                        result.push(typeof extend[j] !== 'undefined' ? extend[j] : j);
                    });
                    return result.join(',');
                },
                //解决列表页富文本框为空时显示null
                info: function (value,row,index){
                    if (!value) {
                        return '';
                    }

                    return value;
                }
            },
            bindevent: function (form, success, error, submit) {
                require(['ldcms-jquery-tagsinput'], function () {
                    //标签输入
                    var elem = "#c-tag";
                    var tags = $(elem);
                    tags.tagsInput({
                        width: 'auto',
                        defaultText: '输入后空格确认',
                        minInputWidth: 110,
                        height: '36px',
                        placeholderColor: '#999',
                        onChange: function (row) {
                            if (typeof callback === 'function') {

                            } else {
                                $(elem + "_addTag").focus();
                                $(elem + "_tag").trigger("blur.autocomplete").focus();
                            }
                        },
                        autocomplete: {
                            url: 'ldcms/document/getTags',
                            minChars: 1,
                            menuClass: 'autocomplete-tags'
                        }
                    });
                });

                Form.api.bindevent($("form[role=form]"),'','',function (){
                    var isSubmit=true;
                    /*敏感词检测*/
                    if (Config.sensitive_check==1){
                        var postFields =[];
                        /*取文本域的值进行验证*/
                        $('textarea[name]').each(function (index,item){
                            var obj={
                                title:$(item).parents('.form-group').find('.control-label').text().trim().replace(/\s/g,""),
                                content:$(item).val()
                            }
                            postFields.push(obj);
                        });
                        if (postFields.length>0){
                            //调用Ajax请求方法
                            Fast.api.ajax({
                                type: 'POST',
                                url: 'ldcms/document/sensitive',
                                data: {'fileds':postFields},
                                dataType: 'json',
                                async:false,
                                success:function (ret){
                                    Layer.closeAll('loading');
                                    ret = Fast.events.onAjaxResponse(ret);
                                    if (ret.code === 1) {
                                        isSubmit=true;
                                    } else {
                                        var html = '<div style="padding:10px;"><style>.card-body img{max-width: 100%}</style>'
                                        $.each(ret.data, function (index, item) {
                                            html += '<div class="card" style="background: #fbfbfb;margin:5px 0 ;padding:8px;border:1px solid #eee;">\n' +
                                                '  <div class="card-body" style="width: 100%;overflow: hidden"><span style="color: red">' + item.title + '</span>：'
                                            html += item.content;
                                            html += '</div>\n' +
                                                '</div>';
                                        });
                                        html += '</div>';

                                        layer.open({
                                            type: 1,
                                            shade: [0.3],
                                            title: '广告法敏感词警告',
                                            area: ['800px', '80%'],
                                            anim: 2,
                                            shadeClose: true, //开启遮罩关闭
                                            content: html,
                                            btn: ['继续提交', '关闭并修改'],
                                            yes: function (index, layero) {
                                                Form.api.submit($("form[role=form]"),'','','');
                                                layer.close(index);
                                                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                                                parent.layer.close(index); //再执行关闭
                                            }
                                        });
                                        isSubmit=false;
                                    }
                                }
                            });
                        }
                    }

                    return isSubmit;
                });
            }
        }
    };
    return Controller;
});
