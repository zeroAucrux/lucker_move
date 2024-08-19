define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template'], function ($, undefined, Backend, Table, Form, Template) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ldcms/themes/index',
                    add_url: '',
                    edit_url: '',
                    del_url: '',
                    multi_url: '',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                templateView: true,
                columns: [
                    [
                        {field: 'id', title: 'ID', operate: false},
                        //直接响应搜索
                        {field: 'username', title: __('Username'), formatter: Table.api.formatter.search},
                        //模糊搜索
                        {field: 'title', title: __('Title'), operate: 'LIKE %...%', placeholder: '模糊搜索，*表示任意字符', style: 'width:200px'},
                        //通过Ajax渲染searchList
                        {field: 'url', title: __('Url'), align: 'left', formatter: Controller.api.formatter.url},
                        //点击IP时同时执行搜索此IP,同时普通搜索使用下拉列表的形式
                        {field: 'ip', title: __('IP'), searchList: ['127.0.0.1', '127.0.0.2'], events: Controller.api.events.ip, formatter: Controller.api.formatter.ip},
                        //browser是一个不存在的字段
                        //通过formatter来渲染数据,同时为它添加上事件
                        {field: 'browser', title: __('Browser'), operate: false, events: Controller.api.events.browser, formatter: Controller.api.formatter.browser},
                        //启用时间段搜索
                        {field: 'createtime', title: __('Create time'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ],
                ],
                //禁用默认搜索
                search: false,
                //启用普通表单搜索
                commonSearch: false,
                //可以控制是否默认显示搜索单表,false则隐藏,默认为false
                searchFormVisible: false,
                showExport: false,
                showToggle: false,
                showColumns: false,
                //分页大小
                pageSize: 12
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            require(['upload'], function (Upload) {
                Upload.api.upload("#faupload-theme", function (data, ret) {
                    Layer.alert(__('Offline installed tips'), {
                        btn: [__('OK')],
                        title: __('Warning'),
                        yes: function (index) {
                            table.bootstrapTable('refresh', {});
                            Layer.close(index);
                        },
                        icon: 1
                    });
                    return false;
                }, function (data, ret) {

                });
            });


        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        enable:function (){
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {

            },
            events: {

            }
        }
    };
    return Controller;
});