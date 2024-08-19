define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ldcms/slider/index' + location.search,
                    add_url: 'ldcms/slider/add',
                    edit_url: 'ldcms/slider/edit',
                    del_url: 'ldcms/slider/del',
                    multi_url: 'ldcms/slider/multi',
                    import_url: 'ldcms/slider/import',
                    table: 'ldcms_ad',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'sort',
                fixedColumns: true,
                commonSearch:true,
                searchFormVisible:true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),width:80, operate:false},
                        {field: 'type', title: __('Type'), width: 180,operate: 'LIKE',formatter:function (value, row, index) {
                            return '<a href="javascript:;" class="searchit" data-field="type" data-value="' + value + '">' + value + '</a>';
                        },
                            addclass: 'selectpage',
                            extend: 'data-source="ldcms/slider/selectpageType"  data-field="title" data-select-only="true"',
                        },
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'image', title: __('Image'), width: 150,operate: false, events: Table.api.events.image, formatter: Table.api.formatter.images},
                        {field: 'status', title: __('Status'),width: 80,searchList: {"1": __('Yes'), "0": __('No')}, formatter: Table.api.formatter.toggle},
                        {field: 'sort', title: __('Sort'),width: 80,operate: false,formatter: Controller.api.formatter.input,events: {"dblclick .text-sort": function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }}},
                        {field: 'create_time', title: __('Create_time'),width: 200, operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'),width: 100, table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
                $(document).on("keyup", "#c-type_text", function () {
                    $("#c-type").val($(this).val());
                });

                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
