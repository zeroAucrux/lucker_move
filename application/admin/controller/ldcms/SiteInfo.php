<?php


namespace app\admin\controller\ldcms;


use addons\ldcms\utils\Dir;
use addons\ldcms\utils\ThemeService;
use app\common\model\Config as ConfigModel;
use think\addons\Service;
use think\Exception;

class SiteInfo extends Base
{
    protected $cfgName = 'ldcms';
    protected $noDel = [
        'template',
        'sitetitle',
        'seo_keywords',
        'seo_description'
    ];
    protected $noNeedRight = ['check', 'getGroups'];
    public function index()
    {
        $pinyin = new \Overtrue\Pinyin\Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');
        $lang = $this->lang;
        $config = ld_get_site_fullconfig($lang,true);
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a", [], 'trim');
            if ($params) {
                foreach ($config as $k => &$v) {
                    if (isset($params[$v['name']])) {
                        if ($v['type'] == 'array') {
                            $params[$v['name']] = is_array($params[$v['name']]) ? $params[$v['name']] : (array) json_decode($params[$v['name']], true);
                            $value = $params[$v['name']];
                        } else {
                            $value = is_array($params[$v['name']]) ? implode(',', $params[$v['name']]) : $params[$v['name']];
                        }
                        $v['value'] = $value;
                    }
                }
                try {
                    //更新配置文件
                    ld_set_site_fullconfig($config, $lang);
                    $this->success();
                } catch (Exception $e) {
                    $this->error(__($e->getMessage()));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $tips = [];
        $siteList = [];
        $group_default_title = '基础';
        $group_default_name = $pinyin->permalink($group_default_title, '');
        if (is_array($config)) {
            foreach ($config as $index => $item) {
                $item['extend_html'] = $item['extend'];
                $item['content'] = empty($item['content']) ?: json_decode($item['content'], true);
                if ($item['name'] == 'template') { /*设置模板选项*/
                    $item['content'] =array_column(ThemeService::getInstallThemeList(),'name','name');
                }
                if ($item['type'] == 'array') { /*如果是数组，将值赋给content*/
                    $item['content'] = $item['value'];
                }
                if ($item['name'] == '__tips__') {
                    $tips = $item;
                    unset($config[$index]);
                }

                if (isset($item['group'])) {
                    $group_title = $item['group'];
                    $group_name = $pinyin->permalink($group_title, '');
                } else {
                    $group_title = $group_default_title;
                    $group_name = $group_default_name;
                }
                $siteList[$group_name]['name'] = $group_name;
                $siteList[$group_name]['title'] = $group_title;
                $siteList[$group_name]['list'][] = $item;
            }
        }
        $index = 0;
        foreach ($siteList as $k => &$v) {
            $v['active'] = !$index ? true : false;
            $index++;
        }
        $this->view->assign('siteList', $siteList);
        $this->view->assign('noDel', $this->noDel);
        $typeList = $this->getTypeList();
        unset($typeList['selectpage']);
        unset($typeList['selectpages']);
        $this->view->assign('typeList', $typeList);
        $this->view->assign('ruleList', ConfigModel::getRegexList());

        $configFile = ADDON_PATH . $this->cfgName . DS . 'site_info.html';
        $viewFile = is_file($configFile) ? $configFile : '';
        return $this->view->fetch($viewFile);
    }

    private function getTypeList()
    {
        $typeList = [
            'string' => __('String'),
            'password' => __('Password'),
            'text' => __('Text'),
            'editor' => __('Editor'),
            'number' => __('Number'),
            'date' => __('Date'),
            'time' => __('Time'),
            'datetime' => __('Datetime'),
            'datetimerange' => __('Datetimerange'),
            'select' => __('Select'),
            'selects' => __('Selects'),
            'image' => __('Image'),
            'images' => __('Images'),
            'file' => __('File'),
            'files' => __('Files'),
            'switch' => __('Switch'),
            'checkbox' => __('Checkbox'),
            'radio' => __('Radio'),
            'array' => __('Array'),
            'custom' => __('Custom'),
        ];
        return $typeList;
    }

    public function getGroups()
    {
        $word = (array) $this->request->request("q_word/a");
        $keyValue = $this->request->request('keyValue');
        if ($keyValue) {
            $list[] = ['id' => $keyValue, 'title' => $keyValue];
        } else {
            $config = ld_get_site_fullconfig($this->lang);
            $groups = [];
            foreach ($config as $item) {
                if (isset($item['group'])) {
                    $groups[] = $item['group'];
                }
            }
            $groups = array_merge(array_unique($groups));
            $list = [];
            if (empty($groups)) {
                $list[] = ['id' => '基础', 'title' => '基础'];
            }

            foreach ($groups as $group) {
                $list[] = ['id' => $group, 'title' => $group];
            }
            if (array_filter($word)) {
                foreach ($word as $k => $v) {
                    $list[] = ['id' => $v, 'title' => $v];
                }
            }
        }
        return ['list' => $list, 'total' => count($list)];
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a", [], 'trim');
            if ($params) {
                foreach ($params as $k => &$v) {
                    $v = is_array($v) && $k !== 'setting' ? implode(',', $v) : $v;
                }
                if (in_array($params['type'], ['select', 'selects', 'checkbox', 'radio', 'array'])) {
                    $params['content'] = json_encode(ConfigModel::decode($params['content']), JSON_UNESCAPED_UNICODE);
                } else {
                    $params['content'] = '';
                }

                try {
                    $config = ld_get_site_fullconfig($this->lang,true);
                    $config[] = $params;
                    ld_set_site_fullconfig($config, $this->lang);
                    $this->success();
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 删除配置项
     * @param $name
     */
    public function delcfg($name = null)
    {
        $config = ld_get_site_fullconfig($this->lang);
        if (in_array($name, $this->noDel)) {
            $this->error($name . '必须配置，禁止删除');
        }
        foreach ($config as $index => $item) {
            if ($item['name'] == $name) {
                unset($config[$index]);
            }
        }
        ld_set_site_fullconfig($config, $this->lang);
        $this->success();
    }

    public function check()
    {
        $params = $this->request->post("row/a");
        $config = ld_get_site_fullconfig($this->lang,true);
        $keys = [];
        foreach ($config as $item) {
            $keys[] = $item['name'];
        }
        if ($params) {
            if (in_array($params['name'], $keys)) {
                $this->error(__('Name already exist'));
            } else {
                $this->success();
            }
        } else {
            $this->error(__('Invalid parameters'));
        }
    }
}