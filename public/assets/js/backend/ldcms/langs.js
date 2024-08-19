define(['jquery', 'bootstrap', 'backend','table', 'form','ldcms-editable'], function ($, undefined, Backend, Table, Form,undefined) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ldcms/langs/index' + location.search,
                    add_url: 'ldcms/langs/add',
                    edit_url: 'ldcms/langs/edit',
                    del_url: 'ldcms/langs/del',
                    multi_url: 'ldcms/langs/multi',
                    import_url: 'ldcms/langs/import',
                    table: 'ldcms_langs',
                }
            });

            var table = $("#table");
            //行内修改表格
            $.fn.bootstrapTable.defaults.onEditableSave = function (field, row, oldValue, $el) {
                var data = {};
                data["row[id]"] = row.id;
                data["row[title]"] = row.title;
                data["row[code]"] = row.code;
                data["row[domain]"] = row.domain;
                Fast.api.ajax({
                    url: "ldcms/langs/editDomain/ids/" + row[this.pk],
                    data: data
                });
            };

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'sort',
                sortOrder: 'asc',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'sub_title', title: __('Sub_title'),width: 100, align:'center', operate: 'LIKE',formatter: Controller.api.formatter.subTitleInput, events: {
                                "dblclick .text-subtitle": function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    return false;
                                }}
                        },
                        {field: 'code', title: __('Code'), operate: 'LIKE'},
                        {field: 'status', title: __('Status'),searchList:{1:'启用',0:'关闭'},formatter: Table.api.formatter.toggle},
                        {field: 'is_default', title: __('Is_default'), operate: false, formatter: Table.api.formatter.toggle},
                        {field: 'domain', title: __('Domain'), operate: false,editable:{type:'text',emptytext:'点击绑定'}},
                        {field: 'sort', title: __('Sort'), width: 80, operate: false, formatter: Controller.api.formatter.sortInput, events: {
                                "dblclick .text-sort": function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    return false;
                                }}
                        },
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', width:250, align:'left', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons:[
                                {
                                    name: 'file',
                                    icon: 'fa fa-pencil',
                                    title: '语言文件管理',
                                    text: '语言文件管理',
                                    extend: 'data-toggle="tooltip"',
                                    classname: 'btn btn-xs btn-warning btn-dialog',
                                    url: 'ldcms/langs/editLangFile/code/{code}',
                                },
                                {
                                    name: 'edit',
                                    icon: 'fa fa-pencil',
                                    title: __('Edit'),
                                    extend: 'data-toggle="tooltip"',
                                    classname: 'btn btn-xs btn-success btn-editone',
                                    visible: function (row) {
                                        return row.id != 1 && row.id !=2;
                                    }
                                },
                                {
                                    name: 'del',
                                    icon: 'fa fa-trash',
                                    title: __('Del'),
                                    extend: 'data-toggle="tooltip"',
                                    classname: 'btn btn-xs btn-danger btn-delone',
                                    visible: function (row) {
                                        return row.id != 1 && row.id !=2;
                                    }
                                }
                            ]}
                    ]
                ]
            });

            //在表格内容渲染完成后回调的事件
            table.on('post-body.bs.table', function (e, json) {
                $("tbody tr[data-index]", this).each(function () {
                    if ([1,2].indexOf(parseInt($("td:eq(1)", this).text())) !== -1) {
                        $("input[type=checkbox]", this).prop("disabled", true);
                    }
                });
            });
            //排序
            $(document).on("change", ".text-sort", function () {
                $(this).data("params", { sort: $(this).val() });
                Table.api.multi('', [$(this).data("id")], table, this);
                return false;
            });

            //简称
            $(document).on("change", ".text-subtitle", function () {
                $(this).data("params", { sub_title: $(this).val() });
                Table.api.multi('', [$(this).data("id")], table, this);
                return false;
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        editlangfile:function (){
            $("#c-file").data("params", function (obj) {
                var type=$('[name="row[type]"]:checked').val();
                //obj为SelectPage对象
                return {custom: {type: type}};
            });

            $.ajax({
                url: 'ldcms/langs/getLangContent',
                type: 'get',
                data: {
                    lang_file:Config.file
                },
                success: function (res) {
                    if (res.code) {
                        $('dl > .form-inline').remove();
                        $("[name='row[lang_content]']").text(JSON.stringify(res.data));
                        $(document).off('change keyup changed', ".fieldlist input,.fieldlist textarea,.fieldlist select");
                        $(".fieldlist", $("form[role=form]")).off("click", ".btn-append,.append");
                        $(".fieldlist", $("form[role=form]")).off("click", ".btn-remove");
                        Form.events.fieldlist($("form[role=form]"));
                    }

                }
            })
            Controller.api.bindevent();
        },
        api: {
            formatter: {
                sortInput: function (value, row, index) {
                    return '<input type="text" class="form-control text-sort" value="' + value + '" data-id="' + row.id + '">';
                },
                subTitleInput: function (value, row, index) {
                    return '<input type="text" class="form-control text-subtitle" value="' + value + '" data-id="' + row.id + '">';
                }
            },
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
