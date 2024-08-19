require.config({
    paths: {
        'ldcms-jquery-tagsinput': '../addons/ldcms/admin/libs/jquery.tagsinput',
        'ldcms-jquery-autocomplete': '../addons/ldcms/admin/libs/jquery.autocomplete',
        'ldcms-editable': '../libs/bootstrap-table/dist/extensions/editable/bootstrap-table-editable.min',
        'x-editable': '../addons/ldcms/admin/libs/bootstrap-editable.min',
    },
    shim: {
        'ldcms-jquery-autocomplete': {
            deps: ['jquery'],
            exports: '$.fn.extend'
        },
        'ldcms-jquery-tagsinput': {
            deps: ['jquery', 'ldcms-jquery-autocomplete', 'css!../addons/ldcms/admin/libs/jquery.tagsinput.min.css'],
            exports: '$.fn.extend'
        },
        'ldcms-editable': {
            deps: ['x-editable', 'bootstrap-table']
        },
        "x-editable": {
            deps: ["css!../addons/ldcms/admin/libs/bootstrap-editable.css"],
        }
    }
});
require(['jquery','template'], function ($,template) {
    if(Config.ldcmslang){
        var tpl="<div style=\"position: absolute;top: 15px;right: 15px;width: 100px;text-align: center; background: #444c69;border-radius: 10px;\">\n" +
            "        <a href=\"javascript:;\" class=\"dropdown-toggle\" style=\"color: #fff;\" data-toggle=\"dropdown\" aria-expanded=\"false\"><i class=\"fa fa-language\"></i> <%=langs[language] %> <i class=\"fa fa-angle-down\"></i></a>\n" +
            "        <ul class=\"dropdown-menu\" style=\"left: auto;right:0;min-width: 100px\">\n" +
            "     <% for(var i  in langs ){%> <li <% if(i==language){%> class=\"active\"<% } %> >\n" +
            "                <a href=\"?ref=addtabs&ldcms_lang=<%=i %>\"><%=langs[i] %></a>\n" +
            "            </li>\n <% } %>" +
            "        </ul>\n" +
            "    </div>";
        var html = template.render(tpl, {
            langs:Config.ldcms.langs,
            language:Config.ldcmslang
        });
        $('.ldcmslang').html(html);
        /*父框架刷新*/
        $('.ldcmslang .dropdown-menu li a').on('click',function (){
            window.parent.location.href=$(this).attr('href');
            return false;
        });
    }

});