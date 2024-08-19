define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    Fast.config.openArea = ['70%', '80%'];
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ldcms/fields/index' + location.search,
                    add_url: 'ldcms/fields/add?mid=' + Fast.api.query("mid"),
                    edit_url: 'ldcms/fields/edit',
                    del_url: 'ldcms/fields/del',
                    multi_url: 'ldcms/fields/multi',
                    import_url: 'ldcms/fields/import',
                    table: 'ldcms_fields',
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
                search: false,
                commonSearch: false,
                fixedRightNumber: 1,
                columns: [
                    [
                        {
                            checkbox: true, formatter: function (value, row, index) {
                                return {
                                    disabled: row.isdefault,
                                }
                            }
                        },
                        {
                            field: 'id', title: __('Id'), formatter: function (value, row, index) {
                                return value ? value : '-';
                            }
                        },
                        {field: 'mid_text', title: __('Mid'), formatter: Controller.api.formatter.textmuted},
                        {field: 'field', title: __('Field'), formatter: Controller.api.formatter.textmuted},
                        {
                            field: 'type',
                            title: __('Type'),
                            operate: 'LIKE',
                            formatter: Controller.api.formatter.textmuted
                        },
                        {
                            field: 'title',
                            title: __('Title'),
                            operate: 'LIKE',
                            formatter: Controller.api.formatter.textmuted
                        },
                        {field: 'rule', title: __('Rule'), operate: 'LIKE'},
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
                            field: 'isfilter',
                            width: 120,
                            title: __('Isfilter'),
                            operate: false,
                            searchList: {"1": __('Yes'), "0": __('No')},
                            formatter: function (value, row, index) {
                                if (row.isdefault) {
                                    return '-';
                                }
                                return Table.api.formatter.toggle.call(this, value, row, index);
                            }
                        },
                        {
                            field: 'islist',
                            width: 120,
                            title: __('数据列表显示'),
                            operate: false,
                            searchList: {"1": __('Yes'), "0": __('No')},
                            formatter: function (value, row, index) {
                                if (row.isdefault) {
                                    return '-';
                                }
                                return Table.api.formatter.toggle.call(this, value, row, index);
                            }
                        },
                        {
                            field: 'status',
                            width: 100,
                            title: __('Status'),
                            searchList: {"1": __('Yes'), "0": __('No')},
                            formatter: function (value, row, index) {
                                if(Config.ignore_fields.indexOf(row.field)!=-1){
                                    return '-';
                                }
                                this.url = 'ldcms/fields/multi?field=' + row.field + '&mid=' + row.mid;
                                return Table.api.formatter.toggle.call(this, value, row, index);
                            }
                        },
                        {
                            field: 'create_time',
                            width: 150,
                            title: __('Create_time'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            autocomplete: false,
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: function (value, row, index) {
                                var that = $.extend({}, this);
                                if (row.isdefault) {
                                    that.buttons = [
                                        {name: 'del', visible: false},
                                        {name: 'edit', visible: false}
                                    ];
                                }

                                return Table.api.formatter.operate.call(that, value, row, index);
                            }
                        }
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
            formatter: {
                input: function (value, row, index) {
                    if (row.isdefault) {
                        return '-';
                    }
                    value = value == null || value.length === 0 ? '' : value.toString();
                    return '<input type="text" class="form-control input-sm text-sort" data-id="' + row.id + '" value="' + value + '">';
                },
                textmuted: function (value, row, index) {
                    return row.isdefault ? "<span class='text-muted'>" + value + "</span>" : value;
                }
            },
            bindevent: function () {

                //渲染关联显示字段和存储字段
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

                var lengths = {};
                $.get('ldcms/fields/getLengths', function (res) {
                    lengths = res
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
