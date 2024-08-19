define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ldcms/diyform_data/index/diyform_id/' +Fast.api.query("diyform_id")+ location.search,
                    del_url: 'ldcms/diyform_data/del/diyform_id/'+Fast.api.query("diyform_id"),
                    edit_url: 'ldcms/diyform_data/edit/diyform_id/'+Fast.api.query("diyform_id"),
                    multi_url: 'ldcms/diyform_data/multi',
                    import_url: 'ldcms/diyform_data/import',
                    table: '',
                }
            });

            var table = $("#table");
            var columns = [
                { checkbox: true },
                { field: 'id', title: __('Id'), width: 50, operate: false},
            ];
            //动态追加字段
            $.each(Config.fields, function (i, j) {
                var data = { field: j.field, title: j.title, table: table, operate: (j.type === 'number' ? '=' : 'like') };
                //如果是图片,加上formatter
                if (j.type == 'image') {
                    data.events = Table.api.events.image;
                    data.formatter = Table.api.formatter.image;
                } else if (j.type == 'images') {
                    data.events = Table.api.events.image;
                    data.formatter = Table.api.formatter.images;
                } else if (j.type == 'radio' || j.type == 'checkbox' || j.type == 'select' || j.type == 'selects') {
                    data.formatter = Controller.api.formatter.content;
                    data.extend = j.content;
                    data.searchList = j.content;
                } else {
                    data.formatter = Table.api.formatter.content;
                }

                columns.push(data);
            });

            var rightColumn = [
                { field: 'user_ip', width: 100, title: __('IP'), operate: 'like'},
                { field: 'user_os', width: 100, title: __('系统'), operate: 'like'},
                { field: 'user_bs', width: 100, title: __('浏览器'), operate: 'like'},
                { field: 'create_time',width: 180, title: __('提交时间'),operate: 'RANGE', addclass: 'datetimerange'},
                { field: 'update_time',width: 180, title: __('更新时间'),operate: 'RANGE', addclass: 'datetimerange'},
                {field: 'operate', title: __('Operate'), clickToSelect: false, table: table, width: 100, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
            ];
            //追加操作字段
            columns = columns.concat(rightColumn);
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns:columns,
                searchFormVisible: true,
                search:false,
                fixedColumns:true,
                fixedNumber:1,
                fixedRightNumber:1,
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        edit:function (){
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
