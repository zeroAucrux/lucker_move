<?php
/**
 * 多语言路由模式
 */

namespace addons\ldcms\utils;


use think\Cache;
use think\Config;
use think\Route;

trait LdRoute
{
    protected function getLang($mode)
    {
        $url     = request()->url();
        $urlData = parse_url($url);
        $result     = explode('/', isset($urlData['path']) ? $urlData['path'] : '');
        $spare      = substr($urlData['path'], 1, strpos($urlData['path'], '.') - 1);
        $lang_list=config('ldcms.lang_list');
        $lang=config('ldcms.default_lang');
        $index=0;
        /*目录模式*/
        if($mode==1){
            foreach($lang_list as $k=>$v){
                //判断语言在url中的位置
                $index=strpos($url, '/' . $k) === 0?1:(strpos($url,'/ldcms/' . $k) === 0?2:0);
                if(!$index){
                    continue;
                }
                if (isset($result[$index]) && $result[$index] === $k || $spare === $k) {
                    $lang = $k;
                    ld_set_home_lang($lang);
                    break;
                }
            }
            /*如果没有语言 则设为默认语言*/
            if(!$index){
                Cache::tag('category')->clear();
                ld_set_home_lang($lang);
            }
        }
        /*子域名模式*/
        if($mode==2){
            $host=request()->host();
            $domains=array_column($lang_list,'domain');
            /*如果域名在语言列表中 则进行遍历，如果不在则使用默认语言*/
            if(in_array($host,$domains)){
                foreach ($lang_list as $k=>$v){
                    if($v['domain']==$host){
                        $lang = $k;
                        ld_set_home_lang($lang);
                        break;
                    }
                }
            }else{
                Cache::tag('category')->clear();
                ld_set_home_lang($lang);
            }
        }

        return $lang;
    }

    /**
     * 多语言路由
     */
    protected function routeLang($config)
    {
        if ($this->getName() == 'ldcms') {
            $url     = request()->url();
            $urlData = parse_url($url);
            $mode    = $config['rewrite_mode'];   //路由模式
            $lang    = $this->getLang($mode);  //语言
            $isDirRoute=0;  //是否是目录模式

            //判断url中是否含有lang目录
            $pattern = "/\/(" . preg_quote($lang, '/') . "|" . preg_quote('/ldcms/'.$lang, '/') . ")(\/|$)/";
            if (preg_match($pattern, $url, $matches)) {
                $isDirRoute=1;
            }

            $execute = "\\think\\addons\\Route@execute?addon=%s&controller=%s&action=%s";
            $rewrite=$config['rewrite'];
            /*目录模式*/
            if ($mode == 1 && !empty($urlData['path']) && $isDirRoute) {
                $rewrite=$this->filterRewrite($rewrite,$url,$lang);
                $rules = array_map(function ($value) {
                    return "ldcms/{$value}";
                }, array_flip($rewrite));
                $rules=$this->buildDirRoute($rules,$execute,$lang);
                Route::rules([]);
                Route::rule($rules);
                $this->resetRoute();
            }
            /*子域名模式*/
            if($mode==2){
                $rules = array_map(function ($value) {
                    return "ldcms/{$value}";
                }, array_flip($rewrite));
                $rules=$this->buildDomainRoute($rules,$execute,$lang);
                Route::domain($rules);
            }
        }
    }

    /*生成目录路由*/
    protected function buildDirRoute($rule,$execute,$lg)
    {
        $rules=[];
        foreach ($rule as $k => $v) {
            $urlArr = explode('/', $v);
            if (count($urlArr) < 3) {
                continue;
            }
            list($addon, $controller, $action) = $urlArr;
            $rules[$k] = sprintf($execute . '&lg=%s', $addon, $controller, $action,$lg);
        }
        return $rules;
    }

    /*生成子域名路由*/
    protected function buildDomainRoute($rule,$execute,$lg){
        $rules=[];$drules = [];
        foreach ($rule as $k => $v) {
            $urlArr = explode('/', $v);
            if (count($urlArr) < 3) {
                continue;
            }
            list($addon, $controller, $action) = $urlArr;
//            $drules[$k] = sprintf($execute . '&indomain=1&lg=%s', $addon, $controller, $action,$lg);
            $drules[$k] = sprintf($execute , $addon, $controller, $action,$lg);
            $rules[$lg] = $drules ?: [];
//            $rules[$lg][':controller/[:action]'] = sprintf($execute . '&indomain=1&lg=%s', $addon, ":controller", ":action",$lg);
            $rules[$lg][':controller/[:action]'] = sprintf($execute, $addon, ":controller", ":action",$lg);
        }
        return $rules;
    }

    /*格式化路由*/
    private function filterRewrite($rewrite,$url,$lang){
        foreach ( $rewrite as &$r) {
            $flag   = strpos($r, '/') === 0;
            $r=explode('/',$r);
            /*判断url中是否含有ldcms 没有插入到第一个，有就插入到第二个*/
            if(strpos($url, '/' . $lang) === 0){
                array_splice($r,1,0,$lang);
            }
            if(strpos($url,'/ldcms/' . $lang) === 0){
                array_splice($r,2,0,$lang);
            }

            $r=implode('/',$r);
            $r      = ($flag ? '/' : '')  . ltrim($r, '/');
        }
        /*更新配置中的伪静态，但不更新文件*/
        set_addon_config('ldcms', ['rewrite' => $rewrite], false);
        return $rewrite;
    }

    /*重新注册路由*/
    public function resetRoute()
    {
        //注册路由
        $routeArr = (array)Config::get('addons.route');
        $domains = [];
        $rules = [];
        $execute = "\\think\\addons\\Route@execute?addon=%s&controller=%s&action=%s";
        foreach ($routeArr as $k => $v) {
            if (is_array($v)) {
                $addon = $v['addon'];
                $domain = $v['domain'];
                $drules = [];
                foreach ($v['rule'] as $m => $n) {
                    $urlArr = explode('/', $n);
                    if (count($urlArr) < 3) {
                        continue;
                    }
                    list($addon, $controller, $action) = $urlArr;
                    $drules[$m] = sprintf($execute . '&indomain=1', $addon, $controller, $action);
                }
                //$domains[$domain] = $drules ? $drules : "\\addons\\{$k}\\controller";
                $domains[$domain] = $drules ? $drules : [];
                $domains[$domain][':controller/[:action]'] = sprintf($execute . '&indomain=1', $addon, ":controller", ":action");
            }
            else {
                if (!$v) {
                    continue;
                }
                $urlArr = explode('/', $v);
                if (count($urlArr) < 3) {
                    continue;
                }
                list($addon, $controller, $action) = $urlArr;
                $rules[$k] = sprintf($execute, $addon, $controller, $action);
            }
        }
        Route::rule($rules);
        if ($domains) {
            Route::domain($domains);
        }
    }
}