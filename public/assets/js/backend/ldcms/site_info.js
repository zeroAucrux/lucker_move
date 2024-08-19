define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function () {
            //不可见的元素不验证
            $("form#add-form").data("validator-options", {
                ignore: ':hidden',
                rules: {
                    content: function () {
                        return ['radio', 'checkbox', 'select', 'selects'].indexOf($("#add-form select[name='row[type]']").val()) > -1;
                    },
                    extend: function () {
                        return $("#add-form select[name='row[type]']").val() == 'custom';
                    }
                }
            });

            $(document).on("keyup", "#c-group_text", function () {
                $("#c-group").val($(this).val());
            });

            Form.api.bindevent($("form[role=form]"), function (res) {
                setTimeout(function () {
                    location.reload();
                }, 1500);
            });
            //渲染关联显示字段和存储字段
            var renderselect = function (id, data, defaultvalue) {
                var html = [];
                for (var i = 0; i < data.length; i++) {
                    html.push("<option value='" + data[i].name + "' " + (defaultvalue == data[i].name ? "selected" : "") + " data-subtext='" + data[i].title + "'>" + data[i].name + "</option>");
                }
                var select = $(id);
                $(select).html(html.join(""));
                select.trigger("change");
                if (select.data("selectpicker")) {
                    select.selectpicker('refresh');
                }
            };
            //关联表切换
            $(document).on('change', "#c-selectpage-table", function (e, first) {
                var that = this;
                Fast.api.ajax({
                    url: "general/config/get_fields_list",
                    data: {table: $(that).val()},
                }, function (data, ret) {
                    renderselect("#c-selectpage-primarykey", data.fieldList, first ? $("#c-selectpage-primarykey").data("value") : '');
                    renderselect("#c-selectpage-field", data.fieldList, first ? $("#c-selectpage-field").data("value") : '');
                    return false;
                });
                return false;
            });
            //如果编辑模式则渲染已知数据
            if (['selectpage', 'selectpages'].indexOf($("#c-type").val()) > -1) {
                $("#c-selectpage-table").trigger("change", true);
            }

            //切换类型时
            $(document).on("change", "#c-type", function () {
                var value = $(this).val();
                $(".tf").addClass("hidden");
                $(".tf.tf-" + value).removeClass("hidden");
                if (["selectpage", "selectpages"].indexOf(value) > -1 && $("#c-selectpage-table option").length == 1) {
                    //异步加载表列表
                    Fast.api.ajax({
                        url: "general/config/get_table_list",
                    }, function (data, ret) {
                        renderselect("#c-selectpage-table", data.tableList);
                        return false;
                    });
                }
            });

            //切换显示隐藏变量字典列表
            $(document).on("change", "form#add-form select[name='row[type]']", function (e) {
                $("#add-content-container").toggleClass("hide", ['select', 'selects', 'checkbox', 'radio'].indexOf($(this).val()) > -1 ? false : true);
            });

            //删除配置
            $(document).on("click", ".btn-delcfg", function () {
                var that = this;
                Layer.confirm(__('Are you sure you want to delete this item?'), {
                    icon: 3,
                    title: '提示'
                }, function (index) {
                    Backend.api.ajax({
                        url: "ldcms/site_info/delcfg",
                        data: {name: $(that).data("name")}
                    }, function () {
                        $(that).closest("tr").remove();
                        Layer.close(index);
                    },function (){
                        Layer.close(index);
                    });
                    location.reload();
                });

            });
        }
    };
    return Controller;
});