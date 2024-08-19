define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ldcms/diyform_fields/index/diyform_id/'+Fast.api.query("diyform_id") + location.search,
                    add_url: 'ldcms/diyform_fields/add/diyform_id/'+Fast.api.query("diyform_id"),
                    edit_url: 'ldcms/diyform_fields/edit',
                    del_url: 'ldcms/diyform_fields/del',
                    multi_url: 'ldcms/diyform_fields/multi',
                    import_url: 'ldcms/diyform_fields/import',
                    table: 'ldcms_diyform_fields',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                commonSearch:false,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),operate: false},
                        {field: 'field', title: __('Field'),operate: false},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'diyform_text', title: __('Diyform_id'),operate: false},
                        {field: 'rule', title: __('Rule'), operate: false},
                        {field: 'sort', title: __('Sort'),operate: false,width:80,formatter: Controller.api.formatter.input,events: {"dblclick .text-sort": function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }}},
                        {field: 'create_time', title: __('Create_time'),width:120, operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });
            $(document).on("change", ".text-sort", function () {
                $(this).data("params", {sort: $(this).val()});
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
        api: {
            formatter:{
                input: function (value, row, index) {
                    value = value == null || value.length === 0 ? '' : value.toString();
                    return '<input type="text" class="form-control input-sm text-sort" data-id="'+row.id+'" value="' + value + '">';
                },
            },
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
