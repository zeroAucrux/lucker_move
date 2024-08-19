define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    Fast.config.openArea = ['1300px', '80%'];
    var Controller = {
        _queryString: '',
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ldcms/tagaction/index' + Controller._queryString,
                    add_url: 'ldcms/tagaction/add' + Controller._queryString,
                    edit_url: 'ldcms/tagaction/edit',
                    del_url: 'ldcms/tagaction/del',
                    multi_url: 'ldcms/tagaction/multi',
                    import_url: 'ldcms/tagaction/import',
                    table: 'ldcms_tagaction',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), width: '60px'},
                        {field: 'action', title: __('Action'), width: '200px', operate: 'LIKE'},
                        {
                            field: 'type',
                            title: __('Type'),
                            width: '100px',
                            searchList: {"sql": __('Sql'), "func": __('Func')},
                            formatter: Table.api.formatter.normal
                        },
                        {
                            field: 'setting', title: __('Params'), formatter: function (value, row, index) {
                                var attr = [];
                                if (value.params&&value.params.length > 0) {
                                    value.params.map(function (item) {
                                        attr.push(item[0]);
                                    });
                                    return attr.join(',');
                                }
                                return '';
                            }
                        },
                        {
                            field: 'create_time',
                            title: __('Create_time'),
                            width: '130px',
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            autocomplete: false,
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            width: '100px',
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'copy',
                                    text: __('Copy'),
                                    title: __('Copy'),
                                    icon: 'fa fa-copy',
                                    url: 'ldcms/tagaction/copy',
                                    classname: 'btn btn-xs btn-primary btn-ajax'
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
                $('input[name="row[setting_db_type]"]').on('click', function () {
                    var val = $(this).val();
                    $('input[name="row[setting][db_type]"]').val(val);
                });

                $(document).on('click', '.params-value .input-group-addon', function () {
                    var input = $(this).prev('input');
                    if (input.is(':disabled')) {
                        input.removeAttr('disabled');
                        $(this).text('前端传值');
                    } else {
                        input.attr('disabled', true);
                        $(this).text('后端填写');
                    }
                    input.val('');
                });

                $(document).on("fa.event.appendfieldlist", '.btn-append', function (e, obj) {
                    //通用的表单组件事件绑定和组件渲染
                    // Form.api.bindevent(obj);
                    Form.events.selectpicker(obj);
                });

                Form.api.bindevent($("form[role=form]"));
                setTimeout(function () {
                    $('.selectpicker', $("form[role=form]")).each(function (item) {
                        if ($(this).data('value')) {
                            $(this).selectpicker('val', $.trim($(this).data('value')));
                        }
                    });
                }, 50);
            },
            queryString: function () {
                return location.search.replace("dialog=1", "").split('&').filter(function (item) {
                    return !!item;
                }).join("&");
            }
        }
    };
    Controller._queryString = Controller.api.queryString();
    return Controller;
});