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
                        url: "ldcms/config/delcfg",
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