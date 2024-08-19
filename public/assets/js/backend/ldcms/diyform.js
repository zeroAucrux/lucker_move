define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ldcms/diyform/index' + location.search,
                    add_url: 'ldcms/diyform/add',
                    edit_url: 'ldcms/diyform/edit',
                    del_url: 'ldcms/diyform/del',
                    multi_url: 'ldcms/diyform/multi',
                    import_url: 'ldcms/diyform/import',
                    table: 'ldcms_diyform',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                searchFormVisible: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id'), operate: false },
                        { field: 'name', title: __('Name') },
                        { field: 'title', title: __('Title'), operate: 'LIKE' },
                        { field: 'table', title: __('Table'), operate: false },
                        { field: 'needlogin', width: '140px', title: __('Needlogin'), operate: false, searchList: { "1": __('Yes'), "0": __('No') }, formatter: Table.api.formatter.toggle },
                        { field: 'iscaptcha', width: '140px', title: __('Iscaptcha'), operate: false, searchList: { "1": __('Yes'), "0": __('No') }, formatter: Table.api.formatter.toggle },
                        { field: 'status', width: '100px', title: __('Status'), operate: false, searchList: { "1": __('Yes'), "0": __('No') }, formatter: Table.api.formatter.toggle },
                        { field: 'create_time', width: '120px', title: __('Create_time'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        {
                            field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate, buttons: [
                                {
                                    name: 'data', //唯一标识、权限标识
                                    text: '表单数据', //按钮显示的文字，支持function
                                    title:function (row) {
                                        return row.title+'列表';
                                    },
                                    classname: 'btn btn-info btn-xs btn-addtabs', //按钮的class，支持btn-dialog/btn-ajax/btn-addtabs
                                    icon: 'fa fa-list-alt', //按钮的图标
                                    // url: 'ldcms/diyform_data/index?diyform_id={ids}&ref=addtabs', //按钮的链接，支持使用{字段名}来占位替换，支持`function`
                                    url: function (row) {
                                        console.log(row);
                                        return 'ldcms/diyform_data/index/diyform_id/'+row.id;
                                    }
                                },
                                {
                                    name: 'field', //唯一标识、权限标识
                                    text: '表单字段管理', //按钮显示的文字，支持function
                                    classname: 'btn btn-warning btn-xs btn-addtabs', //按钮的class，支持btn-dialog/btn-ajax/btn-addtabs
                                    icon: 'fa fa-list', //按钮的图标
                                    url: 'ldcms/diyform_fields/index/diyform_id/{id}', //按钮的链接，支持使用{字段名}来占位替换，支持`function`
                                },
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
                                }
                            ]
                        }
                    ]
                ]
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
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
