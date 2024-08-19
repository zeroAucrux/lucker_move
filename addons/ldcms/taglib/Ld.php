<?php


namespace addons\ldcms\taglib;


use fast\Random;
use think\template\TagLib;

class Ld extends TagLib
{
    protected $tags = [
        'src'      => ['attr' => '', 'close' => 0],
        'nav'      => ['attr' => '', 'close' => 1],
        'sort'     => ['attr' => 'cid', 'close' => 1],
        'slide'    => ['attr' => 'name', 'close' => 1],
        'list'     => ['attr' => '', 'close' => 1],
        'content'  => ['attr' => 'id', 'close' => 1],
        'prev'     => ['attr' => '', 'close' => 1],
        'next'     => ['attr' => '', 'close' => 1],
        'position' => ['attr' => 'name', 'close' => 1],
        'link'     => ['attr' => '', 'close' => 1],
        'filter'   => ['attr' => '', 'close' => 1],
        'search'   => ['attr' => '', 'close' => 1],
        'tags'     => ['attr' => '', 'close' => 1],
        'pics'     => ['attr' => 'value', 'close' => 1],
        'listrows' => ['attr' => 'cid', 'close' => 0],
        'action'   => ['attr' => 'action', 'close' => 1],
    ];

    /*资源加载*/
    public function tagSrc($tag)
    {
        $str = '__ADDON__/' . $tag['src'] . '?v={$site.version}';
        return $str;
    }

    /*导航*/
    public function tagNav($tag, $content)
    {
        $pid   = isset($tag['pid']) ? $tag['pid'] : 0;
        $limit = isset($tag['limit']) ? $tag['limit'] : '';
        $empty = isset($tag['empty']) ? $tag['empty'] : '';
        $key   = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod   = isset($tag['mod']) ? $tag['mod'] : '2';
        $alias = isset($tag['alias']) ? $tag['alias'] : 'item';
        $var   = Random::alnum(10);
        $parse = '<?php ';
        $parse .= '$__' . $var . '__ =\addons\ldcms\model\Category::instance()->getHomeNav(' . $pid . ',"' . $limit . '");';
        $parse .= '?>';
        $parse .= '{volist name="$__' . $var . '__" id="' . $alias . '" empty="' . $empty . '" key="' . $key . '" mod="' . $mod . '"}';
        $parse .= $content;
        $parse .= '{/volist}';

        return $parse;
    }

    /*当前分类 子分类列表*/
    public function tagSort($tag, $content)
    {
        $cid   = $tag['cid'] ?? 0;
        $pid   = $tag['pid'] ?? 0;
        $limit = isset($tag['limit']) ? $tag['limit'] : '';
        $empty = isset($tag['empty']) ? $tag['empty'] : '';
        $key   = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod   = isset($tag['mod']) ? $tag['mod'] : '2';
        $alias = isset($tag['alias']) ? $tag['alias'] : 'item';
        $var   = Random::alnum(10);
        $parse = '<?php ';
        $parse .= '$__' . $var . '__ =\addons\ldcms\model\Category::instance()->getHomeCategory(' . $cid . ',' . $pid . ',"' . $limit . '");';
        if (!empty($cid)) {
            $parse .= '$__' . $var . '__=[$__' . $var . '__];';
        }
        $parse .= '?>';
        $parse .= '{volist name="$__' . $var . '__" id="' . $alias . '" empty="' . $empty . '" key="' . $key . '" mod="' . $mod . '"}';
        $parse .= $content;
        $parse .= '{/volist}';

        return $parse;
    }

    /*轮播*/
    public function tagSlide($tag, $content)
    {
        $name  = $tag['name'] ?? '';
        $empty = isset($tag['empty']) ? $tag['empty'] : '';
        $key   = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod   = isset($tag['mod']) ? $tag['mod'] : '2';
        $alias = isset($tag['alias']) ? $tag['alias'] : 'item';
        $var   = Random::alnum(10);
        $parse = '<?php ';
        $parse .= '$__' . $var . '__=\addons\ldcms\model\Ad::instance()->getHomeSlide("' . $name . '");';
        $parse .= '?>';
        $parse .= '{volist name="$__' . $var . '__" id="' . $alias . '" empty="' . $empty . '" key="' . $key . '" mod="' . $mod . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

    /*文档列表*/
    public function tagList($tag, $content)
    {
        //传入cid 默认不开启分页
        //不传入cid 默认获取栏目cid,并且开启分页
        //栏目cid不存在，默认为0
        //传page判断最后是否开启page
        $cid       = $tag['cid'] ?? '0'; //栏目ID
        $mid       = $tag['mid'] ?? '0'; //栏目ID
        $limit     = $tag['limit'] ?? '16'; //数量
        $tags      = $tag['tags'] ?? '';
        $condition = $tag['condition'] ?? ''; //条件
        $empty     = $tag['empty'] ?? '';
        $key       = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod       = $tag['mod'] ?? '2';
        $alias     = $tag['alias'] ?? 'item';
        $ext       = $tag['ext'] ?? '';
        $page      = $tag['page'] ?? 'null'; //是否开启分页
        $order     = $tag['order'] ?? ''; //排序
        $simple    = $tag['simple'] ?? 'null'; //简洁分页

        $var = [];
        isset($tag['tags']) ? $var['tags'] = $tag['tags'] : '';
        isset($tag['condition']) ? $var['condition'] = $tag['condition'] : '';
        $params = $this->filterTag($var);

        $parse = '<?php ';
        $parse .= '$cid=' . $this->autoBuildVar($cid) . ';';
        $parse .= '$mid=' . $this->autoBuildVar($mid) . ';';
        $parse .= '$page=' . $page . ';';
        $parse .= '$simple=' . $simple . ';';

        $parse .= <<<eof
            // 不传入cid默认获取栏目cid,并且开启分页
            if( empty(\$cid) && isset(\$category['id']) && !empty(\$category["id"]) ){
                \$cid=\$category["id"];
                is_null(\$page)?\$page=true:'';  //如果没设置分页，那么默认开启分页
            }
            //传入cid,默认关闭分页
            if(!empty(\$cid)){
                is_null(\$page)?\$page=false:''; 
            }
            //判断手机端是否开启简洁分页
            \$addon_config=get_addon_config('ldcms');
            is_null(\$simple)&&\$addon_config['simple']&&request()->isMobile()?\$simple=true:'';
eof;
        /*传入参数*/
        $params[] = "'cid'=>\$cid";
        $params[] = "'mid'=>\$mid";
        $params[] = "'limit'=>'{$limit}'";
        $params[] = "'ext'=>'{$ext}'";
        isset($tag['flag']) ? $params[] = "'flag'=>'{$tag['flag']}'" : '';
        $params[] = "'filterWhere'=>isset(\$filterWhere)?\$filterWhere:[]";
        $params[] = "'_where'=>isset(\$_where)?\$_where:[]";
        $params[] = "'page'=>\$page";
        $params[] = "'simple'=>\$simple";
        $params[] = "'_order'=>'{$order}'";

        $var   = Random::alnum(10);
        $parse .= '$__' . $var . '__=\addons\ldcms\model\Document::instance()->getHomeList([' . implode(',', $params) . ']);';
        $parse .= ' ?>';

        $parse .= '{volist name="$__' . $var . '__" id="' . $alias . '" empty="' . $empty . '" key="' . $key . '" mod="' . $mod . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        $parse .= '<?php ';
        $parse .= '$pages=$page?$__' . $var . '__->render():"";';
        $parse .= '$pages_total=$page&&!$simple?$__' . $var . '__->total():0;'; //数据总数(开启分页并且不是简洁分页的时候)
        $parse .= ' ?>';
        return $parse;
    }


    public function filterTag($tag)
    {
        $params = [];
        foreach ($tag as $k => &$v) {
            $origin   = $v;
            $v        = $this->autoBuildVar($v);
            $v        = $origin == $v ? '"' . $v . '"' : $v;
            $params[] = '"' . $k . '"=>' . $v;
        }

        return $params;
    }

    public function autoBuildVar(&$name)
    {
        //如果是字符串则特殊处理
        if (preg_match("/^('|\")(.*)('|\")\$/i", $name, $matches)) {
            $quote = $matches[1] == '"' ? "'" : '"';
            $name  = $quote . $matches[2] . $quote;

            return $name;
        }
        $flag = substr($name, 0, 1);
        if (':' == $flag) {
            // 以:开头为函数调用，解析前去掉:
            $name = substr($name, 1);
        }

        // 常量不需要解析
        if (defined($name)) {
            return $name;
        }
        $this->tpl->parseVar($name);
        $this->tpl->parseVarFunction($name);
        return $name;
    }

    /*内容*/
    public function tagContent($tag, $content)
    {
        $cid   = $tag['cid'] ?? '0';
        $alias = isset($tag['alias']) ? $tag['alias'] : 'content';
        $parse = '<?php ';
        $parse .= '$' . $alias . '=\addons\ldcms\model\Document::instance()->getHomeInfoByCId(' . $cid . ');';
        $parse .= '?>';
        $parse .= $content;
        $parse .= '<?php unset($' . $alias . ');?>';
        return $parse;
    }

    /*上一页*/
    public function tagPrev($tag, $content)
    {
        $order = $tag['order'] ?? ''; //排序
        $parse = '<?php ';
        $parse .= '$item=\addons\ldcms\model\Document::instance()->getPrevNext($content["id"],$content["cid"],"prev","' . $order . '");';
        $parse .= 'if($item): ?>' . $content . '<?php endif; unset($item);?>';
        return $parse;
    }

    /*下一页*/
    public function tagNext($tag, $content)
    {
        $order = $tag['order'] ?? ''; //排序
        $parse = '<?php ';
        $parse .= '$item=\addons\ldcms\model\Document::instance()->getPrevNext($content["id"],$content["cid"],"next","' . $order . '");';
        $parse .= 'if($item): ?>' . $content . '<?php endif; unset($item); ?>';
        return $parse;
    }


    /*面包屑*/
    public function tagPosition($tag, $content)
    {
        $cid   = $tag['cid'] ?? '0';
        $empty = isset($tag['empty']) ? $tag['empty'] : '';
        $key   = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod   = isset($tag['mod']) ? $tag['mod'] : '2';
        $alias = isset($tag['alias']) ? $tag['alias'] : 'item';
        $var   = Random::alnum(10);
        $parse = '<?php ';
        $parse .= '$__' . $var . '__=\addons\ldcms\model\Category::instance()->getHomePosition(' . $cid . ');';
        $parse .= '?>';
        $parse .= '{volist name="$__' . $var . '__" id="' . $alias . '" empty="' . $empty . '" key="' . $key . '" mod="' . $mod . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

    /*友情链接*/
    public function tagLink($tag, $content)
    {
        $name  = $tag['name'] ?? '';
        $limit = $tag['limit'] ?? '';
        $empty = isset($tag['empty']) ? $tag['empty'] : '';
        $key   = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod   = isset($tag['mod']) ? $tag['mod'] : '2';
        $alias = isset($tag['alias']) ? $tag['alias'] : 'item';
        $var   = Random::alnum(10);
        $parse = '<?php ';
        $parse .= '$__' . $var . '__=\addons\ldcms\model\Links::instance()->getHomeList("' . $name . '","' . $limit . '");';
        $parse .= '?>';
        $parse .= '{volist name="$__' . $var . '__" id="' . $alias . '" empty="' . $empty . '" key="' . $key . '" mod="' . $mod . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

    /*多条件筛选标签*/
    public function tagFilter($tag, $content)
    {
        $mid        = $tag['mid'] ?? '';
        $empty      = isset($tag['empty']) ? $tag['empty'] : '';
        $key        = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod        = isset($tag['mod']) ? $tag['mod'] : '2';
        $alias      = isset($tag['alias']) ? $tag['alias'] : 'item';
        $title_text = isset($tag['title_text']) ? $tag['title_text'] : '全部';
        $var        = Random::alnum(10);
        $parse      = '<?php ';
        $parse      .= '$__' . $var . '__=\addons\ldcms\model\Fields::instance()->getHomeFilter(' . $mid . ',"' . $title_text . '");';
        $parse      .= '?>';
        $parse      .= '{volist name="$__' . $var . '__[\'filterList\']" id="' . $alias . '" empty="' . $empty . '" key="' . $key . '" mod="' . $mod . '"}';
        $parse      .= $content;
        $parse      .= '{/volist}';
        $parse      .= '<?php $filterWhere=$__' . $var . '__["filterWhere"];?>';
        return $parse;
    }

    /*搜索标签*/
    public function tagSearch($tag, $content)
    {
        $limit  = $tag['limit'] ?? '16';
        $empty  = isset($tag['empty']) ? $tag['empty'] : '';
        $key    = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod    = isset($tag['mod']) ? $tag['mod'] : '2';
        $alias  = isset($tag['alias']) ? $tag['alias'] : 'item';
        $params = [
            "'limit'=>{$limit}",
        ];
        $var    = Random::alnum(10);
        $parse  = '<?php ';
        $parse  .= '$__' . $var . '__=\addons\ldcms\model\Document::instance()->getHomeSearch([' . implode(',', $params) . ']);';
        $parse  .= '?>';
        $parse  .= '{volist name="$__' . $var . '__->items()" id="' . $alias . '" empty="' . $empty . '" key="' . $key . '" mod="' . $mod . '"}';
        $parse  .= $content;
        $parse  .= '{/volist}';
        $parse  .= '<?php $pages=$__' . $var . '__->render(); $pages_total=$__' . $var . '__->total(); ?>';
        return $parse;
    }

    /**
     * 调用tags 标签
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagTags($tag, $content)
    {
        $id    = $tag['id'] ?? 0;
        $cid   = $tag['cid'] ?? 0;
        $limit = $tag['limit'] ?? '16';
        $empty = isset($tag['empty']) ? $tag['empty'] : '';
        $key   = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod   = isset($tag['mod']) ? $tag['mod'] : '2';
        $alias = isset($tag['alias']) ? $tag['alias'] : 'item';
        $var   = Random::alnum(10);
        $parse = '<?php ';
        $parse .= '$__' . $var . '__=\addons\ldcms\model\Tags::instance()->getHomeList(' . $cid . ',' . $id . ',' . $limit . ');';
        $parse .= '?>';
        $parse .= '{volist name="$__' . $var . '__[\'data\']" id="' . $alias . '" empty="' . $empty . '" key="' . $key . '" mod="' . $mod . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

    /**
     * 获取多图内容
     * @param $tag
     * @param $content
     * @return string
     * Author: Wusn <958342972@qq.com>
     * DateTime: 2023/2/25 3:15 下午
     */
    public function tagPics($tag, $content)
    {
        $value    = $tag['value'];
        $empty    = isset($tag['empty']) ? $tag['empty'] : '';
        $key      = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod      = isset($tag['mod']) ? $tag['mod'] : '2';
        $alias    = isset($tag['alias']) ? $tag['alias'] : 'item';
        $var      = Random::alnum(10);
        $var_name = '$__' . $var . '__';
        $parse    = '<?php ';
        $parse    .= '$var=' . $value . ';';
        $parse    .= 'if(!empty($var)):';
        $parse    .= $var_name . '=explode(",",$var);';
        $parse    .= '?>';
        $parse    .= '{volist name="' . $var_name . '" id="' . $alias . '" empty="' . $empty . '" key="' . $key . '" mod="' . $mod . '"}';
        $parse    .= $content;
        $parse    .= '{/volist} ';
        $parse    .= "<?php endif;?>";
        return $parse;
    }

    public function tagListrows($tag, $content)
    {
        $cid   = $tag['cid'] ?? 0;
        $parse = '<?php ';
        $parse .= 'echo \addons\ldcms\model\Document::instance()->getListRows(' . $cid . ');';
        $parse .= '?>';
        return $parse;
    }

    public function tagAction($tag, $content)
    {
        $empty    = isset($tag['empty']) ? $tag['empty'] : '';
        $key      = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod      = isset($tag['mod']) ? $tag['mod'] : '2';
        $alias    = isset($tag['alias']) ? $tag['alias'] : 'item';
        $page     = $tag['page'] ?? 'null'; //是否开启分页
        $buildsql = $tag['buildsql'] ?? false;

        $params = [];
        foreach ($tag as $k => &$v) {
            $origin = $v;
            $this->autoBuildVar($v);
            /*判断变量*/
            $flag = substr($v, 0, 1);
            /*判断类调用*/
            $class    = preg_match('/::|\\\\|\(\)|->/', $v);
            $v        = $origin == $v && $flag != '$' && !$class ? '"' . $v . '"' : $v;
            $params[] = '"' . $k . '"=>' . $v;
        }

        if (!isset($tag['action'])) {
            return '';
        }

        $parse = '<?php ';
        $parse .= '$page=' . $page . ';';
        $var   = Random::alnum(10);
        $parse .= '$__' . $var . '__=\addons\ldcms\TagAction::instance()->exec([' . implode(',', $params) . ']);';
        if ($buildsql) {
            $parse .= 'echo $__' . $var . '__; ?>';
            $parse .= $content;
            return $parse;
        }
        $parse .= '?>';
        $parse .= '{volist name="$__' . $var . '__" id="' . $alias . '" empty="' . $empty . '" key="' . $key . '" mod="' . $mod . '"}';
        $parse .= $content;
        $parse .= '{/volist} ';
        $parse .= '<?php ';
        $parse .= '$pages=$page?$__' . $var . '__->render():"";';
        $parse .= '$pages_total=$page&&!$simple?$__' . $var . '__->total():0;'; //数据总数(开启分页并且不是简洁分页的时候)
        $parse .= ' ?>';
        return $parse;
    }
}