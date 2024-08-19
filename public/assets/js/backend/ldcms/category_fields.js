define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ldcms/category_fields/index' + location.search,
                    add_url: 'ldcms/category_fields/add',
                    edit_url: 'ldcms/category_fields/edit',
                    del_url: 'ldcms/category_fields/del',
                    multi_url: 'ldcms/category_fields/multi',
                    import_url: 'ldcms/category_fields/import',
                    table: 'ldcms_category_fields',
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
                        // {checkbox: true},
                        {
                            field: 'state', checkbox: true, formatter: function (value, row, index) {
                                return {
                                    disabled: row.state === false ? true : false,
                                }
                            }
                        },
                        {field: 'id', title: __('Id')},
                        {field: 'field', title: __('Field')},
                        {field: 'type', title: __('Type'), operate: 'LIKE'},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
            //             {field: 'sort', title: __('Sort'),width:80, operate: false,formatter: Controller.api.formatter.input,events: {"dblclick .text-sort": function (e) {
            //     e.preventDefault();
            //     e.stopPropagation();
            //     return false;
            // }}},
                        {field: 'sort', title: __('Sort'),width:80, operate: false,formatter:function (value, row, index) {
                                return row.issystem ? "-" : Controller.api.formatter.input.call(this, value, row, index);
                        },events: {"dblclick .text-sort": function (e) {
                                e.preventDefault();
                                e.stopPropagation();
                                return false;
                        }}},
                        {field: 'status', title: __('Status'),searchList:{1:'启用',0:'关闭'},formatter: function (value, row, index) {
                                return row.issystem ? "-" : Table.api.formatter.toggle.call(this, value, row, index);
                            }},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            formatter: function (value, row, index) {
                                var that = $.extend({}, this);
                                if (row.issystem) {
                                    return '-';
                                    that.buttons = [{name: 'del', visible: false}];
                                }
                                return Table.api.formatter.operate.call(that, value, row, index);
                            }}
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

                var renderselect = function (id, data, defaultvalue) {
                    var html = [];
                    for (var i = 0; i < data.length; i++) {
                        html.push("<option value='" + data[i].name + "' " + (defaultvalue == data[i].name ? "selected" : "") + " data-subtext='" + data[i].title + "'>" + data[i].name + "</option>");
                    }
                    var select = $(id);
                    $(select).html(html.join(""));

                    if (select.data("selectpicker")) {
                        select.selectpicker('refresh');
                        select.selectpicker('val', defaultvalue);
                        select.selectpicker('refresh');
                    }
                    select.trigger("change");
                };

                //关联表切换
                $(document).on('change', "#c-setting-table", function (e, first) {
                    var that = this;
                    Fast.api.ajax({
                        url: "ldcms/fields/getFields",
                        data: {table: $(that).val()},
                    }, function (data, ret) {
                        renderselect("#c-setting-primarykey", data.fieldList, first ? $("#c-setting-primarykey").data("value") : '');
                        renderselect("#c-setting-field", data.fieldList, first ? $("#c-setting-field").data("value") : '');
                        return false;
                    });
                    return false;
                });

                //如果编辑模式则渲染已知数据
                if (['selectpage', 'selectpages'].indexOf($("#c-type").val()) > -1) {
                    $("#c-setting-table").trigger("change", true);
                }

                var lengths={};
                $.get('ldcms/fields/getLengths',function (res){
                    lengths=res
                })

                $(document).on("change", "#c-type", function () {
                    $('#c-length').val(lengths[$(this).val()])
                });

                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
