<?php


namespace addons\ldcms\utils\builder;

use think\Config;
use think\Exception;
use think\View;
use addons\ldcms\utils\Arr;
class Form
{
    /**
     * @var array
     *  'type'=>'',     //必填
        'name'=>'',     //必填
        'title'=>'',    //必填
        'tip'=>'',
        'rule'=>'',
     *  'value'=>'',
        'extend_html'=>'',
        'maximum'=>''
     */
    protected $items=[];
    protected static $instance=null;
    protected $view=null;
    protected $is_form=true; //是否含有表单
    protected $html_count=0;
    protected $default_template="../../../addons/ldcms/utils/builder/view/fields";
    protected $groups=[];
    protected $group_curr=0;
    protected $items_group=[];
    protected $field_show=false;
    protected $name_prefix='row';

    protected function __construct()
    {
        $this->view = View::instance(Config::get('template'), Config::get('view_replace_str'));
    }

    /**
     * 是否开启form标签
     * 如果不想开启form标签，再调用该方法，方法默认值是false,也就是说关闭form标签。
     * @param false $isForm
     * @return $this
     */
    public function isForm($isForm=false)
    {
        $this->is_form=$isForm;
        return $this;
    }

    /**
     * 设置html
     * 支持传入html 或 闭包函数
     * @param $html
     * @return $this
     * @throws Exception
     */
    public function setHtml($html)
    {
        if(is_string($html)){
            $fun=function ($data) use ($html){
                $data['extend_html'] = $html;
                return $data;
            };
        }

        if(is_callable($html)){
            $fun=$html;
        }
        $this->setFormItem('html'.$this->html_count,'','custom','',$fun);
        $this->html_count++;
        return $this;
    }

    /*是否显示字段*/
    public function fieldShow($show=true){
        $this->field_show=$show;
        return $this;
    }

    /**
     * 设置表单分组
     * 在需要分组的元素位置插入此方法，填写中文名称即可
     * @param $title
     * @return $this
     */
    public function setGroup($title)
    {
        $this->groups[]=$title;
        $this->group_curr=count($this->groups)-1;
        return $this;
    }

    /*设置表单name 前缀*/
    public function setNamePrefix($perfix)
    {
        $this->name_prefix=$perfix;
    }

    /**
     * 设置表单项
     * @param $field    字段
     * @param $title    标题
     * @param $type     类型
     * @param string $rule     验证规则
     * @param string $arr_fcn  数组|回调函数
     */
    public function setFormItem($field,$title,$type,$rule='',$arr_fcn='')
    {
        $item=[
            'type'=>$type,     //必填
            'field'=>$field,     //必填
            'title'=>$title,    //必填
            'field_name'=>$this->toFormName($field),
            'item_id'=>str_replace('.','-',$field),
            'tip'=>'',
            'rule'=>$rule,
            'value'=>'',
            'extend_html'=>'',
            'content_list'=>[],
            'class'=>'',
            'placeholder'=>'',   //占位符
            'visible'=>'' ,       //显示条件
            'setting'=>[
                'primarykey'=>'id',
                'field'=>'id'
            ],
            'maximum'=>1
        ];
        if($type=='hidden'){
            $item['class']='hidden';
        }
        if($type=='images' || $type=='files'){
            $item['maximum']=0;
        }
        /*判断是回调函数或数组*/
        if(is_callable($arr_fcn)){
            $item=call_user_func($arr_fcn,$item);
        }

        if(is_array($arr_fcn)){
            $item=array_merge($item,$arr_fcn);
        }
        if(empty($item)){
            throw new Exception('表单项不能为空');
        }
        $this->items[$field]=$item;
        $this->items_group[$this->group_curr][]=$field;
        return $this;
    }

    /**
     * 设置表单隐藏字段
     * @param $field
     * @throws Exception
     */
    public function setFormItemHidden($field)
    {
        $arr=['class'=>'hidden'];
        $this->setFormItem($field,$field,'string','',$arr);
        return $this;
    }

    public function toFormName($name)
    {
        $arr=explode('.',$name);
        $name='';
        foreach ($arr as $item){
            $name.='['.$item.']';
        }

        return $this->name_prefix.$name;
    }

    /**
     * 批量设置表单项
     * @var array
    'type'=>'',     //必填
    'name'=>'',     //必填
    'title'=>'',    //必填
    'tip'=>'',
    'rule'=>'',
    'value'=>'',
    'extend_html'=>'',
    'maximum'=>''
     */
    public function setFormItems($items)
    {
        $fields=[];
        $nitems=[];
        foreach ($items as $key=> $item){
            $fields[]=$item['field'];
            $item['value']=$item['value']??'';
            $item['field_name']=$this->toFormName($item['field']);
            $item['item_id']=str_replace('.','-',$item['field']);
            $nitems[$item['field']]=$item;
        }
        $this->items_group[$this->group_curr]=array_merge($this->items_group[$this->group_curr]??[],$fields);
        $this->items=array_merge($this->items,$nitems);
        return $this;
    }

    public function assign($name, $value = '')
    {
        $this->view->assign($name,$value);
        return $this;
    }

    //设置表单值
    public function values($row)
    {
        foreach ($this->items as $k=>&$v){
            $v['value']=Arr::get($row->toArray(),$k,'');
        }
        unset($v);
        return $this;
    }

    public function fetch($template = '', $vars = [], $replace = [], $config = [], $renderContent = false){
        foreach ($this->items as &$item){
            foreach ($this->items_group as $k=>$group){
                if(in_array($item['field'],$group)){
                    $item['item_group']='tab'.$k;
                }
            }
        }
        unset($item);

        $this->view->assign('isForm',$this->is_form);
        $this->view->assign('fields',$this->items);
        $this->view->assign('tab_group',$this->groups);
        $this->view->assign('item_group',$this->items_group);
        $this->view->assign('field_show',$this->field_show);
        empty($template)?$template=$this->default_template:'';
        return $this->view->fetch($template, $vars = [], $replace = [], $config = [], $renderContent = false);
    }

    //初始化
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}