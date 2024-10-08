<?php

return [
    [
        'group' => '基础',
        'type' => 'bool',
        'name' => 'pc_site',
        'title' => 'PC站点是否开启',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '1',
        'rule' => '',
        'content' => '{"1":"开启","0":"关闭"}',
        'tip' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '基础',
        'type' => 'bool',
        'name' => 'wap_site',
        'title' => '是否开启手机版',
        'rule' => '',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '0',
        'content' => '{"1":"开启","0":"关闭"}',
        'tip' => '响应式模板无需开启（系统默认模板无需开启）',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '基础',
        'type' => 'bool',
        'name' => 'simple',
        'title' => '开启手机端简洁分页',
        'rule' => '',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '1',
        'content' => '{"1":"开启","0":"关闭"}',
        'tip' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '基础',
        'type' => 'bool',
        'name' => 'api_site',
        'title' => 'API是否开启',
        'rule' => '',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '1',
        'content' => '{"1":"开启","0":"关闭"}',
        'tip' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '基础',
        'type' => 'bool',
        'name' => 'api_auth',
        'title' => 'API强制认证',
        'rule' => '',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '1',
        'content' => '{"1":"开启","0":"关闭"}',
        'tip' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '基础',
        'type' => 'string',
        'name' => 'api_appid',
        'title' => 'API Appid',
        'rule' => '',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '',
        'content' => '',
        'tip' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '基础',
        'type' => 'string',
        'name' => 'api_secret',
        'title' => 'Api Secret',
        'rule' => '',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '',
        'content' => '',
        'tip' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '基础',
        'type' => 'string',
        'name' => 'main_domain',
        'title' => '网站主域名',
        'rule' => '',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => 'lucker.app',
        'content' => '',
        'tip' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '基础',
        'type' => 'number',
        'name' => 'content_url_num',
        'title' => '相同的内链替换次数',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '1',
        'content' => '',
        'tip' => '',
        'rule' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '基础',
        'type' => 'bool',
        'name' => 'baidupush',
        'title' => '内容主动推送到百度',
        'rule' => '',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '1',
        'content' => '{"1":"开启","0":"关闭"}',
        'tip' => '如果开启百度主动推送链接，将在文章发布时自动进行推送，请务必配置下方token',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '基础',
        'name' => 'baidupushzz_token',
        'title' => '百度普通收录token',
        'type' => 'string',
        'content' => [],
        'value' => '',
        'rule' => '',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
    [
        'group' => '基础',
        'name' => 'baidupushks_token',
        'title' => '百度快速收录token',
        'type' => 'string',
        'content' => [],
        'value' => '',
        'rule' => '',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
    [
        'group' => '基础',
        'type' => 'bool',
        'name' => 'is_form_email',
        'title' => '表单发送邮件',
        'rule' => '',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '0',
        'content' => '{"1":"开启","0":"关闭"}',
        'tip' => '如果配置表单发送邮件，必须先至<code>常规管理>系统配置>邮件配置</code>中配置邮件服务器',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '基础',
        'type' => 'string',
        'name' => 'to_email',
        'title' => '接收人邮箱',
        'rule' => '',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '',
        'content' => '',
        'tip' => '',
        'visible' => '',
        'extend' => ' placeholder="请输入接收人邮箱"',
    ],
    [
        'group' => '伪静态',
        'name' => 'domain',
        'title' => '绑定二级域名前缀',
        'type' => 'string',
        'content' => [],
        'value' => '',
        'rule' => '',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
    [
        'group' => '伪静态',
        'name' => 'rewrite',
        'title' => '伪静态',
        'type' => 'array',
        'content' => [],
        'value' => [
            'index/index' => '/$',
            'search/index' => '/search',
            'sitemap/index' => '/sitemap.xml',
            'tag/index' => '/tag/[:tag]$',
            'lists/index' => '/[:category]$',
            'detail/index' => '/[:category]/[:id]$',
        ],
        'rule' => 'required',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
    [
        'group' => '伪静态',
        'type' => 'radio',
        'name' => 'rewrite_mode',
        'title' => '多语言路由模式',
        'rule' => '',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '0',
        'content' => [
            '变量模式',
            '目录模式',
            '子域名模式',
        ],
        'tip' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '字典',
        'type' => 'array',
        'name' => 'flags',
        'title' => '标志字典',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => [
            'top' => '置顶',
            'hot' => '热门',
            'new' => '最新',
        ],
        'content' => '{"value1":"title1","value2":"title2"}',
        'tip' => '',
        'rule' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '字典',
        'type' => 'bool',
        'name' => 'sensitive_check',
        'title' => '是否开启文章内容敏感词检测',
        'rule' => '',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '1',
        'content' => '{"1":"开启","0":"关闭"}',
        'tip' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '字典',
        'type' => 'text',
        'name' => 'sensitive',
        'title' => '敏感词字典',
        'setting' => [
            'table' => '',
            'conditions' => '',
            'key' => '',
            'value' => '',
        ],
        'value' => '',
        'content' => '{"value1":"title1","value2":"title2"}',
        'tip' => '',
        'rule' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => 'SEO页面标题',
        'type' => 'custom',
        'name' => 'shuoming',
        'title' => '',
        'setting' => [],
        'value' => '',
        'content' => '{"value1":"title1","value2":"title2"}',
        'tip' => '',
        'rule' => '',
        'visible' => '',
        'extend' => '<p class="alert alert-info-light">'."\n"
            .'<b>自定义页面标题</b> （支持使用标签或文字）<br>'."\n"
            .'全局标签：{$ld.sitetitle}站点标题 <br>'."\n"
            .'栏目页标签：{$category.seo_title}栏目SEO名称、{$category.name}栏目标题<br>'."\n"
            .'详情页标签：{$content.title}内容标题<br>'."\n"
            .'搜索页标签：{$search}搜索关键字<br>'."\n"
            .'以下配置参数不设置时将使用系统默认规则。</p>',
    ],
    [
        'group' => 'SEO页面标题',
        'type' => 'string',
        'name' => 'index_title',
        'title' => '首页标题',
        'setting' => [],
        'value' => '{$ld.sitetitle}-generator',
        'content' => '{"value1":"title1","value2":"title2"}',
        'tip' => '示例：{$ld.sitetitle}-LDCMS',
        'rule' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => 'SEO页面标题',
        'type' => 'string',
        'name' => 'list_title',
        'title' => '栏目页标题',
        'setting' => [],
        'value' => '{$category.seo_title}-{$ld.sitetitle}',
        'content' => '{"value1":"title1","value2":"title2"}',
        'tip' => '示例：{$category.seo_title}-{$ld.sitetitle}',
        'rule' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => 'SEO页面标题',
        'type' => 'string',
        'name' => 'detail_title',
        'title' => '详情页标题',
        'setting' => [],
        'value' => '{$content.title}-{$category.seo_title}-{$ld.sitetitle}',
        'content' => '{"value1":"title1","value2":"title2"}',
        'tip' => '示例：{$content.title}-{$category.seo_title}-{$ld.sitetitle}',
        'rule' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '伪静态',
        'type' => 'custom',
        'name' => 'rewrite_mode_tip',
        'title' => '',
        'setting' => [],
        'value' => '',
        'content' => '{"value1":"title1","value2":"title2"}',
        'tip' => '',
        'rule' => '',
        'visible' => '',
        'extend' => '<p class="alert alert-info-light">'."\n"
            .'<b>多语言路由模式说明</b><br>'."\n"
            .'变量模式（http://example.com?lg=zh-cn）、目录模式（http://example.com/zh-cn/）、子域名模式（http://zh-cn.example.com） <br>'."\n"
            .'使用子域名模式需要在多语言列表中绑定域名。<br></p>',
    ],
    [
        'group' => '其它',
        'type' => 'image',
        'name' => 'document_noimage',
        'title' => '文档默认缩略图',
        'setting' => [],
        'value' => '/assets/addons/ldcms/noimage.png',
        'content' => '',
        'tip' => '',
        'rule' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '其它',
        'type' => 'image',
        'name' => 'category_noimage',
        'title' => '栏目默认缩略图',
        'setting' => [],
        'value' => '/assets/addons/ldcms/noimage.png',
        'content' => '',
        'tip' => '',
        'rule' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '其它',
        'type' => 'image',
        'name' => 'slider_noimage',
        'title' => '轮播图默认图片',
        'setting' => [],
        'value' => '/assets/addons/ldcms/noimage.png',
        'content' => '',
        'tip' => '',
        'rule' => '',
        'visible' => '',
        'extend' => '',
    ],
    [
        'group' => '翻译api',
        'name' => 'open_translate',
        'title' => '开启翻译',
        'type' => 'bool',
        'content' => '{"1":"开启","0":"关闭"}',
        'value' => '1',
        'rule' => '',
        'msg' => '',
        'tip' => '是否开启翻译功能',
        'ok' => '',
        'extend' => '',
    ],
    [
        'group' => '翻译api',
        'name' => 'youdao_appKey',
        'title' => '应用ID',
        'type' => 'string',
        'content' => [],
        'value' => '',
        'rule' => '',
        'msg' => '',
        'tip' => '请输入有道翻译API的应用ID',
        'ok' => '',
        'extend' => '',
    ],
    [
        'group' => '翻译api',
        'name' => 'youdao_appSecret',
        'title' => '应用秘钥',
        'type' => 'string',
        'content' => [],
        'value' => '',
        'rule' => '',
        'msg' => '',
        'tip' => '请输入有道翻译API的应用秘钥',
        'ok' => '',
        'extend' => '',
    ],
    [
        'group' => '翻译api',
        'name' => 'youdao_lang_code',
        'title' => '支持语言对应代码',
        'type' => 'array',
        'content' => [],
        'value' => [
            'zh-CHS' => 'zh-cn',
            'zh-CHT' => 'zh-tw',
            'en' => 'en',
            'de' => 'de',
            'ar' => 'ar',
            'es' => 'es',
            'fr' => 'fr',
            'id' => 'id',
            'it' => 'it',
            'ja' => 'ja',
            'ko' => 'ko',
            'nl' => 'nl',
            'ru' => 'ru',
            'th' => 'th',
            'vi' => 'vi',
        ],
        'rule' => '',
        'msg' => '',
        'tip' => '有道翻译支持的语言代码，键名为有道的code(不可修改)，value为当前网站语言的code',
        'ok' => '',
        'extend' => '',
    ],
];
