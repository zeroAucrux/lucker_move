<?php


namespace app\admin\controller\ldcms;


use addons\ldcms\utils\builder\Form;
use app\common\model\Config as ConfigModel;
use think\addons\Service;
use think\Cache;
use think\Exception;

class Config extends Base
{
    protected $cfgName = 'ldcms';
    protected $noNeedRight = ['check', 'getGroups'];
    protected $noDel = [
        'template',
        'sitetitle',
        'seo_keywords',
        'seo_description',
        'default_editor',
        'pc_site',
        'api_site',
        'flags',
        'content_url_num'
    ];
    public function index()
    {
        $info = get_addon_info($this->cfgName);
        $config = get_addon_fullconfig($this->cfgName);
        if (!$info) {
            $this->error(__('No Results were found'));
        }
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
                    set_addon_fullconfig($this->cfgName, $config);
                    Service::refresh();
                    //清除栏目缓存
                    Cache::tag('category')->clear();
                    $this->success();
                } catch (Exception $e) {
                    $this->error(__($e->getMessage()));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $tips = [];
        $groupList = [];
        foreach ($config as $index => &$item) {
            if($item['group']=='其它'){
                continue;
            }
            //如果有设置分组
            if (isset($item['group']) && $item['group']) {
                if (!in_array($item['group'], $groupList)) {
                    $groupList["custom" . (count($groupList) + 1)] = $item['group'];
                }
            }
            if ($item['name'] == '__tips__') {
                $tips = $item;
                unset($config[$index]);
            }
        }
        $groupList['other'] = '其它';

        $this->view->assign('noDel', $this->noDel);
        $this->view->assign('typeList', ConfigModel::getTypeList());
        $this->view->assign('ruleList', ConfigModel::getRegexList());
        $this->view->assign("groupList", $groupList);
        $this->view->assign("addon", ['info' => $info, 'config' => $config, 'tips' => $tips]);
        $configFile = ADDON_PATH . $this->cfgName . DS . 'config.html';
        $viewFile = is_file($configFile) ? $configFile : 'addon/config';
        return $this->view->fetch($viewFile);
    }

    public function getGroups()
    {
        $word = (array) $this->request->request("q_word/a");
        $keyValue = $this->request->request('keyValue');
        if ($keyValue) {
            $list[] = ['id' => $keyValue, 'title' => $keyValue];
        } else {
            $config = get_addon_fullconfig($this->cfgName);
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
                $params['lang'] = ld_get_lang();
                foreach ($params as $k => &$v) {
                    $v = is_array($v) && $k !== 'setting' ? implode(',', $v) : $v;
                }
                if (in_array($params['type'], ['select', 'selects', 'checkbox', 'radio', 'array'])) {
                    $params['content'] = json_encode(ConfigModel::decode($params['content']), JSON_UNESCAPED_UNICODE);
                } else {
                    $params['content'] = '';
                }

                try {
                    $config = get_addon_fullconfig($this->cfgName);
                    $config[] = $params;
                    set_addon_fullconfig($this->cfgName, $config);

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
        $config = get_addon_fullconfig($this->cfgName);
        if (in_array($name, $this->noDel)) {
            $this->error($name . '必须配置，禁止删除');
        }
        foreach ($config as $index => $item) {
            if ($item['name'] == $name && $item['lang'] == ld_get_lang()) {
                unset($config[$index]);
            }
        }
        set_addon_fullconfig($this->cfgName, $config);
        $this->success();
    }

    public function check()
    {
        $params = $this->request->post("row/a");
        $config = get_addon_fullconfig($this->cfgName);
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