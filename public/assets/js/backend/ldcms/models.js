define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ldcms/models/index' + location.search,
                    add_url: 'ldcms/models/add',
                    edit_url: 'ldcms/models/edit',
                    del_url: 'ldcms/models/del',
                    multi_url: 'ldcms/models/multi',
                    import_url: 'ldcms/models/import',
                    table: 'ldcms_models',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        { field: 'name', title: __('Name'), operate: 'LIKE', formatter: function (value,row,index){
                            return '<span class="label label-'+Config.customColor[row['id']]+'">'+value+'</span>';
                            }
                        },
                        { field: 'table_name', title: __('Table_name'), operate: 'LIKE' },
                        { field: 'template_list', title: __('Template_list'), operate: 'LIKE' },
                        { field: 'template_detail', title: __('Template_detail'), operate: 'LIKE' },
                        { field: 'ismenu', title: __('Ismenu'),searchList:{1:'启用',0:'关闭'}, formatter: Table.api.formatter.toggle },
                        { field: 'status', title: __('Status'),searchList:{1:'启用',0:'关闭'},formatter: Table.api.formatter.toggle },
                        { field: 'operate', title: __('Operate'), align:'left', table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons:[
                                {
                                    name: 'field', //唯一标识、权限标识
                                    text: '字段管理', //按钮显示的文字，支持function
                                    classname: 'btn btn-warning btn-xs btn-addtabs', //按钮的class，支持btn-dialog/btn-ajax/btn-addtabs
                                    icon: 'fa fa-list', //按钮的图标
                                    url: 'ldcms/fields?mid={id}', //按钮的链接，支持使用{字段名}来占位替换，支持`function`
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
                                    visible: function (row) {
                                        return row.id != 1;
                                    }
                                }
                            ]}
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
            },
            formatter:{
            }
        }
    };
    return Controller;
});
