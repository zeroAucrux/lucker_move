<?php


namespace addons\ldcms\controller;


use addons\ldcms\model\Category;
use addons\ldcms\model\Document;
use think\Response;

class Sitemap extends Base
{
    protected $noNeedLogin = ['*'];
    protected $options = [
        'item_key'  => '',
        'root_node' => 'urlset',
        'item_node' => 'url',
        'root_attr' => 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/"'
    ];

    public function index()
    {
        $list=[];
        $list[]= $this->makeNode('', date('Y-m-d'), '1.00'); // 根目录
        $category=Category::instance()->getHomeCategoryData();
        $document=Document::instance();
        foreach ($category['data'] as $item){
            $list[]=$this->makeNode($item['url'],date('Y-m-d'),'0.80');
            $doclist=$document->getHomeList(['cid'=>$item['id']]);
            foreach ($doclist as $doc){
                $list[]=$this->makeNode($doc['url'],date('Y-m-d'));
            }
        }
        return Response::create($list, 'xml', 200, [],$this->options);
    }

    // 生成结点信息
    private function makeNode($link, $date, $priority = 0.60)
    {
        return [
            'loc'      =>$this->domainurl($link),
            'priority' => $priority,
            'lastmod'=>$date,
            'changefreq'=>'Always'
        ];
    }

    protected function domainurl($url)
    {
        if(!$url) return $url;
        $regex = "/^(?:[a-z]+:)\/\//i";
        $domain=$this->request->domain();
        if (stripos($url, '/') === 0) {
            $url = preg_match($regex, $url) || ($domain && stripos($url, $domain) === 0) ? $url : $domain . $url;
        }
        return $url;
    }
}