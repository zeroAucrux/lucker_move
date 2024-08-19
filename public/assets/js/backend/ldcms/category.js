define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    Fast.config.openArea = ['70%', '80%'];

    function getPidToMid(pid) {
        Fast.api.ajax({
            loading: false,
            url: "ldcms/category/getCategoryInfo",
            data: {id: pid}
        }, function (data, ret) {
            if (ret.data.mid) {
                $('#c-mid').val(ret.data.mid);
                $('#c-mid').trigger('change');
            }
            return false;
        }, function (data, ret) {
            return false;
        });
    }

    function forceDelPopup(table, options, ids) {
        Fast.api.ajax(options, function (data, ret) {
            table.trigger("uncheckbox");
            table.bootstrapTable('refresh');
        }, function (data, ret) {
            if (ret.code == 0) {
                Toastr.error(ret.msg);
            }
            if (ret.code == 2) {
                Layer.open({
                    content: Template("deltpl", ret.data),
                    title: __('Warning'),
                    btn: ['确认', '取消'],
                    end: function () {

                    },
                    yes: function () {
                        Fast.api.ajax({
                            url: 'ldcms/category/del',
                            data: {ids: ids, force: 1}
                        }, function () {
                            Layer.closeAll();
                            table.bootstrapTable('refresh');
                        }, function () {
                            Layer.closeAll();
                        })
                    }
                });
            }
            return false;
        });
    }

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ldcms/category/index' + location.search,
                    add_url: 'ldcms/category/add',
                    edit_url: 'ldcms/category/edit',
                    del_url: 'ldcms/category/del',
                    multi_url: 'ldcms/category/multi',
                    import_url: 'ldcms/category/import',
                    copy_url: 'ldcms/copy_langs/category',
                    table: 'ldcms_category',
                    fields_url: 'ldcms/category_fields/index',
                    translate_url: 'ldcms/category/translate',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                pagination: false,
                escape: false,
                fixedRightNumber: 1,
                commonSearch: false,
                search: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false, width: 60},
                        {
                            field: 'id',
                            title: '<a href="javascript:;" class="btn btn-success btn-xs btn-toggle"><i class="fa fa-chevron-up"></i></a>',
                            operate: false,
                            width: 60,
                            formatter: Controller.api.formatter.subnode
                        },
                        {field: 'name', title: __('Name'), align: 'left', width: 150},
                        {
                            field: 'urlname',
                            title: __('Urlname'),
                            width: 180,
                            align: "left",
                            formatter: function (value, row, index) {
                                if (row.type == 1) {
                                    return '<a href="' + row.url + '" target="_blank">' + row.outlink + '</a>';
                                }
                                return '<a href="' + row.url + '" target="_blank">' + value + '</a>';
                            }
                        },
                        {
                            field: 'mid_text',
                            title: __('Mid') + '/类型',
                            width: 130,
                            align: 'left',
                            formatter: function (value, row, index) {
                                if (row['type'] == 0) {
                                    return '<a href="ldcms/document/index/mid/' + row['mid'] + '" class="addtabsit label label-' + Config.customColor[row['mid']] + '"><i class="fa fa-file-text-o"></i> ' + value + '</a>';
                                }
                                var mhtml='';
                                if(row.mid){
                                    mhtml+='<a href="ldcms/document/index/mid/'+row['mid']+'" class="addtabsit label label-' + Config.customColor[row['mid']] + '" style="opacity: 0.75;"> <i class="fa fa-file-text-o"></i> '+value+'</a>';
                                }

                                return mhtml+' <a href="' + row.url + '" target="_blank" class="label label-primary"  style="opacity: 0.75;"><i class="fa fa-link"></i> 链接</a>';
                            }
                        },
                        {field: 'template_list', width: 180, title: __('Template_list'), operate: false},
                        {field: 'template_detail', width: 180, title: __('Template_detail'), operate: false},
                        {
                            field: 'is_nav',
                            width: 80,
                            title: __('Is_nav'),
                            searchList: {"1": __('Yes'), "0": __('No')},
                            formatter: Table.api.formatter.toggle
                        },
                        {
                            field: 'status',
                            width: 80,
                            title: __('Status'),
                            searchList: {"1": __('Yes'), "0": __('No')},
                            formatter: Table.api.formatter.toggle
                        },
                        {
                            field: 'is_home',
                            width: 80,
                            title: __('Is_home'),
                            searchList: {"1": __('Yes'), "0": __('No')},
                            formatter: function (value, row, index) {
                                if (row.pid == 0) {
                                    return Table.api.formatter.toggle.call(this, value, row, index);
                                }
                                return '';
                            }
                        },
                        {
                            field: 'sort',
                            title: __('Sort'),
                            width: 80,
                            operate: false,
                            formatter: Controller.api.formatter.input,
                            events: {
                                "dblclick .text-sort": function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    return false;
                                }
                            }
                        },
                        {
                            field: 'create_time',
                            width: 140,
                            title: __('Create_time'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            autocomplete: false,
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'gid', width: 100, title: __('Gid'), formatter: function (value, row, index) {
                                if (value == 0) {
                                    return '公开';
                                }
                                var arr = [];
                                value.split(',').forEach(function (item) {
                                    arr.push('会员等级' + item)
                                })
                                return arr.join(',')
                            }
                        },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: $.extend(Table.api.events.operate, Controller.api.events.operate),
                            formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'addson',
                                    icon: 'fa fa-plus-square',
                                    text: '添加子栏目', //按钮显示的文字，支持function
                                    title: __('添加子栏目'),
                                    extend: 'data-toggle="tooltip"',
                                    classname: 'btn btn-xs btn-primary btn-addson',
                                    url: 'ldcms/category/add/pid/{ids}', //按钮的链接，支持使用{字段名}来占位替换，支持`function`
                                },
                            ]
                        }
                    ]
                ]
            });
            //当内容渲染完成后
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                // //默认隐藏所有子节点
                // $("a.btn[data-id][data-pid][data-pid!=0]").closest("tr").hide();
                // $(".btn-node-sub.disabled[data-pid!=0]").closest("tr").hide();

                //显示隐藏子节点
                $(".btn-node-sub").off("click").on("click", function (e) {
                    var status = $(this).data("shown") || $("a.btn[data-pid='" + $(this).data("id") + "']:visible").size() > 0 ? true : false;
                    $("a.btn[data-pid='" + $(this).data("id") + "']").each(function () {
                        $(this).closest("tr").toggle(!status);
                        if (!$(this).hasClass("disabled")) {
                            $(this).trigger("click");
                        }
                    });
                    $(this).data("shown", !status);
                    return false;
                });
            });

            //展开隐藏一级
            $(document.body).on("click", ".btn-toggle", function (e) {
                $("a.btn[data-id][data-pid][data-pid!=0].disabled").closest("tr").hide();
                var that = this;
                var show = $("i", that).hasClass("fa-chevron-down");
                $("i", that).toggleClass("fa-chevron-down", !show);
                $("i", that).toggleClass("fa-chevron-up", show);
                $("a.btn[data-id][data-pid][data-pid!=0]").closest("tr").toggle(show);
                $(".btn-node-sub[data-pid=0]").data("shown", show);
            });
            // //展开隐藏全部
            // $(document.body).on("click", ".btn-toggle-all", function (e) {
            //     var that = this;
            //     var show = $("i", that).hasClass("fa-plus");
            //     $("i", that).toggleClass("fa-plus", !show);
            //     $("i", that).toggleClass("fa-minus", show);
            //     $(".btn-node-sub.disabled[data-pid!=0]").closest("tr").toggle(show);
            //     $(".btn-node-sub[data-pid!=0]").data("shown", show);
            // });


            $(document).on("change", ".text-sort", function () {
                $(this).data("params", {sort: $(this).val()});
                Table.api.multi('', [$(this).data("id")], table, this);
                return false;
            });


            // 批量删除按钮事件
            $(document).on('click', '.btn-del-verify', function (e) {
                var that = this;
                var ids = Table.api.selectedids(table);
                Layer.confirm(
                    __('Are you sure you want to delete the %s selected item?', ids.length),
                    {icon: 3, title: __('Warning'), offset: 0, shadeClose: true, btn: [__('OK'), __('Cancel')]},
                    function (index) {
                        var options = {
                            url: 'ldcms/category/del',
                            data: {ids: ids},
                        }
                        //强制删除
                        forceDelPopup(table, options, ids);
                        Layer.close(index);
                    }
                );
                return false;
            });

            // 复制栏目数据
            $(document).on("click", ".btn-copy", function () {
                var options = table.bootstrapTable('getOptions');
                var url = options.extend.copy_url;
                Fast.api.open(url, $(this).data("original-title") || $(this).attr("title") || __('Add'), $(this).data() || {});
            });

            // api翻译
            $(document).on("click", ".btn-translation", function () {
                var ids = Table.api.selectedids(table);
                if (ids.length == 0) {
                    Toastr.error(__('Please select the data to be translated'));
                    return false;
                }
                var options = table.bootstrapTable('getOptions');
                var params = $(this).data('params');
                var url = options.extend.translate_url + "?" + params + '&ids=' + ids.join(",");
                Fast.api.open(url, $(this).data("original-title") || $(this).attr("title"), {
                    area: ['400px', '350px']
                });
                return false;
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
            //获取栏目拼音
            var si;
            $(document).on("keyup", "#c-name", function () {
                var value = $(this).val();
                if (value != '' && !value.match(/\n/)) {
                    clearTimeout(si);
                    si = setTimeout(function () {
                        Fast.api.ajax({
                            loading: false,
                            url: "ldcms/category/getTitlePinyin",
                            data: {title: value}
                        }, function (data, ret) {
                            $("#c-urlname").val(data.pinyin.substr(0, 100));
                            return false;
                        }, function (data, ret) {
                            return false;
                        });
                    }, 200);
                }
            });
            /*选择父栏目，自动获取父栏目的模型*/
            $(document).on("change", "#c-pid", function () {
                var pid = parseInt($(this).val());
                if (pid) {
                    getPidToMid(pid);
                }
            });
            var pid = $('#c-pid').val();
            if (pid > '0') {
                getPidToMid(pid);
            }
        },

        adds: function () {
            Controller.api.bindevent();

            var si;
            $(document).on("keyup", "#c-name input", function () {
                var value = $(this).val();
                // $(this).next('input').val(value);
                // console.log(value)
                var this_ = $(this);

                if (value != '' && !value.match(/\n/)) {
                    clearTimeout(si);
                    si = setTimeout(function () {
                        Fast.api.ajax({
                            loading: false,
                            url: "ldcms/category/getTitlePinyin",
                            data: {title: value}
                        }, function (data, ret) {
                            this_.next('input').val(data.pinyin.substr(0, 100));

                            return false;
                        }, function (data, ret) {
                            return false;
                        });
                    }, 200);
                }

            })

            /*选择父栏目，自动获取父栏目的模型*/
            $(document).on("change", "#c-pid", function () {
                var pid = parseInt($(this).val());
                if (pid) {
                    getPidToMid(pid);
                }
            });
            var pid = $('#c-pid').val();
            if (pid > '0') {
                getPidToMid(pid);
            }
        },
        edit: function () {
            Controller.api.bindevent();
        },
        translate: function () {
            Controller.api.bindevent($("form[role=form]"));
        },
        api: {
            events: {
                operate: {
                    'click .btn-addson': function (e, value, row, index) {
                        e.stopPropagation();
                        e.preventDefault();
                        var table = $(this).closest('table');
                        var options = table.bootstrapTable('getOptions');
                        var ids = row[options.pk];
                        row = $.extend({}, row ? row : {}, {ids: ids});
                        var url = $(this).attr('href');
                        Fast.api.open(Table.api.replaceurl(url, row, table), $(this).data("original-title") || $(this).attr("title") || __('Edit'), $(this).data() || {});
                    },
                    'click .btn-delone': function (e, value, row, index) {
                        e.stopPropagation();
                        e.preventDefault();
                        var that = this;
                        var top = $(that).offset().top - $(window).scrollTop();
                        var left = $(that).offset().left - $(window).scrollLeft() - 260;
                        if (top + 154 > $(window).height()) {
                            top = top - 154;
                        }
                        if ($(window).width() < 480) {
                            top = left = undefined;
                        }
                        Layer.confirm(
                            __('Are you sure you want to delete this item?'),
                            {
                                icon: 3,
                                title: __('Warning'),
                                offset: [top, left],
                                shadeClose: true,
                                btn: [__('OK'), __('Cancel')]
                            },
                            function (index) {
                                var table = $(that).closest('table');
                                var tableoptions = table.bootstrapTable('getOptions');
                                var options = {
                                    url: 'ldcms/category/del',
                                    data: {ids: row[tableoptions.pk]},
                                }
                                forceDelPopup(table, options, row[tableoptions.pk]);
                                Layer.close(index);
                            }
                        );
                    }
                }
            },
            formatter: {
                url: function (value, row, index) {
                    return '<div class="input-group input-group-sm" style="width:250px;"><input type="text" class="form-control input-sm" value="' + value + '"><span class="input-group-btn input-group-sm"><a href="' + value + '" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-link"></i></a></span></div>';
                },
                input: function (value, row, index) {
                    value = value == null || value.length === 0 ? '' : value.toString();
                    return '<input type="text" class="form-control input-sm text-sort" data-id="' + row.id + '" value="' + value + '">';
                },
                subnode: function (value, row, index) {
                    return '<a href="javascript:;" data-toggle="tooltip" title="' + __('Toggle sub menu') + '" data-id="' + row.id + '" data-pid="' + row.pid + '" class="btn btn-xs '
                        + (row.haschild == 1 || row.ismenu == 1 ? 'btn-success' : 'btn-default disabled') + ' btn-node-sub"><i class="fa fa-' + (row.haschild == 1 || row.ismenu == 1 ? 'sitemap' : 'list') + '"></i></a>';
                }
            },
            bindevent: function () {
                var models = {};
                $.ajax({
                    url: 'ldcms/category/getModels',
                    type: 'get',
                    async: false,
                    success: function (data) {
                        models = data;
                    }
                });

                Form.api.bindevent($("form[role=form]"));
                $(document).on('change', '#c-mid', function () {
                    var mid = $(this).val()
                    if (models[mid]['template_list'])
                        $('#c-template_list').val(models[mid]['template_list']).selectPageRefresh()
                    else
                        $('#c-template_list').selectPageClear()
                    $('#c-template_detail').val(models[mid]['template_detail']).selectPageRefresh()
                });
            }
        }
    };
    return Controller;
});
