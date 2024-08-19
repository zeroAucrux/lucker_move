<?php

return [
    'autoload' => false,
    'hooks' => [
        'upgrade' => [
            'ldcms',
        ],
        'app_init' => [
            'ldcms',
        ],
        'config_init' => [
            'ldcms',
            'summernote',
        ],
        'reset_route' => [
            'ldcms',
        ],
    ],
    'route' => [
        '/$' => 'ldcms/index/index',
        '/search' => 'ldcms/search/index',
        '/sitemap.xml' => 'ldcms/sitemap/index',
        '/tag/[:tag]$' => 'ldcms/tag/index',
        '/[:category]$' => 'ldcms/lists/index',
        '/[:category]/[:id]$' => 'ldcms/detail/index',
    ],
    'priority' => [],
    'domain' => '',
];
