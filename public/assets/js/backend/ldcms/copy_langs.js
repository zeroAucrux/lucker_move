define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    var Controller = {
        category: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ldcms/copy_langs/getCategoryData',
                    add_url: 'ldcms/category/add',
                    edit_url: 'ldcms/category/edit',
                    del_url: 'ldcms/category/del',
                    multi_url: 'ldcms/category/multi',
                    import_url: 'ldcms/category/import',
                    table: 'ldcms_category',
                }
            });

            var table = $("#table");
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                toolbar: '#toolbar',
                sortName: 'id',
                pagination: false,
                search: false,
                commonSearch: false,
                showColumns: false,
                showToggle: false,
                showExport: false,
                escape: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: 'ID', width: '100px'},
                        {field: 'name', title: __('栏目名称'), align: 'left', width: '300px'},
                        {field: 'urlname', title: __('url名称'), align: 'left'}
                    ]
                ],
                queryParams: function (params) {
                    //这里可以追加搜索条件
                    var filter = params.filter ? JSON.parse(params.filter) : {};
                    var op = params.op ? JSON.parse(params.op) : {};
                    //这里可以动态赋值，比如从URL中获取admin_id的值，filter.admin_id=Fast.api.query('admin_id');
                    filter.lg = $('[name=select_lang]').val();
                    op.lg = "=";
                    params.filter = JSON.stringify(filter);
                    params.op = JSON.stringify(op);
                    return params;
                }
            });

            Table.api.bindevent(table);

            /*选择语言*/
            $('select[name=select_lang]').on('change', function () {
                var options = table.bootstrapTable('getOptions');
                var queryParams = options.queryParams;
                options.queryParams = function (params) {
                    //这一行必须要存在,否则在点击下一页时会丢失搜索栏数据
                    params = queryParams(params);
                    //如果希望追加搜索条件,可使用
                    var filter = params.filter ? JSON.parse(params.filter) : {};
                    var op = params.op ? JSON.parse(params.op) : {};
                    filter.lg = $('[name=select_lang]').val();
                    op.lg = "=";
                    params.filter = JSON.stringify(filter);
                    params.op = JSON.stringify(op);
                    return params;
                };
                table.bootstrapTable('refresh', {});
            });

            // 复制栏目数据
            $(document).on("click", ".btn-copy-submit", function () {
                var array = $(this).parents('form').serializeArray();
                var data = {};
                var url = $(this).attr('action');
                var ids = Table.api.selectedids(table);
                if (ids.length === 0) {
                    Toastr.error('请先选择要复制的数据');
                    return false;
                }
                data.ids = ids;
                for (var i = 0; i < array.length; i++) {
                    data[array[i].name] = array[i].value;
                }
                Fast.api.ajax({
                    loading: false,
                    url: url,
                    data: data
                }, function (data, ret) {
                    var _parent = parent;
                    Fast.api.close(data);

                    setTimeout(function () {
                        _parent.$(".btn-refresh").trigger("click");
                    });

                });
            });
        },
        document: function () {
            Table.api.init();
            var lg = $('[name=select_lang]').val();
            // 表格2
            var table = $("#table");
            table.bootstrapTable({
                url: 'ldcms/copy_langs/getDocumentData?mid=' + Config.mid,
                extend: {
                    index_url: '',
                    add_url: '',
                    edit_url: '',
                    del_url: '',
                    multi_url: '',
                    table: '',
                },
                toolbar: '#toolbar',
                sortName: 'id',
                search: false,
                commonSearch: false,
                showColumns: false,
                showToggle: false,
                showExport: false,
                escape: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: 'ID', width: '100px'},
                        {
                            field: 'mid',
                            title: __('模型'),
                            align: 'left',
                            width: '150px',
                            formatter: function (value, row, index) {
                                return Config.mname;
                            }
                        },
                        {field: 'cname', title: __('栏目'), width: '250px', align: 'left'},
                        {field: 'title', title: __('标题'), align: 'left'}
                    ]
                ],
                queryParams: function (params) {
                    //这里可以追加搜索条件
                    var filter = params.filter ? JSON.parse(params.filter) : {};
                    var op = params.op ? JSON.parse(params.op) : {};
                    //这里可以动态赋值，比如从URL中获取admin_id的值，filter.admin_id=Fast.api.query('admin_id');
                    filter.lg = $('[name=select_lang]').val();
                    op.lg = "=";
                    params.filter = JSON.stringify(filter);
                    params.op = JSON.stringify(op);
                    return params;
                }
            });

            $("#c-old_category").data("params", function (obj) {
                //obj为SelectPage对象
                return {custom: {lang: $("select[name=select_lang]").val()}};
            });

            $("#c-new_category").data("params", function (obj) {
                //obj为SelectPage对象
                return {custom: {lang: Config.lang}};
            });

            Table.api.bindevent(table);

            /*选择语言*/
            $('select[name=select_lang]').on('change', function () {
                var options = table.bootstrapTable('getOptions');
                var queryParams = options.queryParams;
                options.queryParams = function (params) {
                    //这一行必须要存在,否则在点击下一页时会丢失搜索栏数据
                    params = queryParams(params);
                    //如果希望追加搜索条件,可使用
                    var filter = params.filter ? JSON.parse(params.filter) : {};
                    var op = params.op ? JSON.parse(params.op) : {};
                    filter.lg = $('[name=select_lang]').val();
                    op.lg = "=";
                    params.filter = JSON.stringify(filter);
                    params.op = JSON.stringify(op);
                    return params;
                };
                table.bootstrapTable('refresh', {});
            });

            $(document).on("change", "#c-old_category", function(){
                var old_cid=$(this).val();
                var options = table.bootstrapTable('getOptions');
                var queryParams = options.queryParams;
                options.queryParams = function (params) {
                    //这一行必须要存在,否则在点击下一页时会丢失搜索栏数据
                    params = queryParams(params);
                    //如果希望追加搜索条件,可使用
                    var filter = params.filter ? JSON.parse(params.filter) : {};
                    var op = params.op ? JSON.parse(params.op) : {};
                    filter.lg = $('[name=select_lang]').val();
                    op.lg = "=";
                    filter.cid=old_cid;
                    op.cid="=";
                    params.filter = JSON.stringify(filter);
                    params.op = JSON.stringify(op);
                    return params;
                };
                table.bootstrapTable('refresh', {});
            });

            // 复制内容数据
            $(document).on("click", ".btn-copy-submit", function () {
                var array = $(this).parents('form').serializeArray();
                var data = {};
                for (var i = 0; i < array.length; i++) {
                    data[array[i].name] = array[i].value;
                }
                if(!data['old_category']){
                    Toastr.error('请先选择原有栏目');
                    return false;
                }
                if(!data['new_category']){
                    Toastr.error('请先选择现有栏目');
                    return false;
                }
                data.mid = Config.mid;
                var ids = Table.api.selectedids(table);
                if (ids.length === 0) {
                    Toastr.error('请先选择要复制的数据');
                    return false;
                }
                data.ids = ids;

                var url = $(this).attr('action');
                Fast.api.ajax({
                    loading: false,
                    url: url,
                    data: data
                }, function (data, ret) {
                    var _parent = parent;
                    Fast.api.close(data);

                    setTimeout(function () {
                        _parent.$(".btn-refresh").trigger("click");
                    });
                });
            });
            Form.api.bindevent($("form[role=form]"));
        }
    };
    return Controller;
});