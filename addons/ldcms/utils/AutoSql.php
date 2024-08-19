<?php
/**
 * 依据字段信息生成sql
 */

namespace addons\ldcms\utils;

use Exception;
use think\Config;
use think\Db;
use think\exception\DbException;
use think\exception\PDOException;

class AutoSql
{
    protected static $instance = null;
    protected $data = [
        'table'     => '',  //表名称
        'field'     => '',  //新字段
        'old_field' => '',  //旧字段
        'type'      => 'VARCHAR',
        'length'    => '255',  //长度
        'comment'   => '', //注释
        'after'     => '', //插入某列
        'default'   => 'NULL'  //默认值
    ];

    public function __construct($data)
    {
        $this->data = array_merge($this->data, $data);
    }

    public static function instance($data = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($data);
        }

        return self::$instance;
    }

    /**
     * 创建表
     * @param string $table 表名称
     * @param string $title 标题
     * @param string $primary_key 主键
     * @param string $default_fields 默认字段
     * @param bool $is_auto_increment 主键自增
     */
    public function createTable($table, $title, $primary_key = 'id', $default_fields = '', $is_auto_increment = true)
    {
        $auto_increment = $is_auto_increment ? 'AUTO_INCREMENT' : '';
        $prefix         = Config::get('database.prefix');
        $sql            = "CREATE TABLE `{$prefix}{$table}` (`{$primary_key}` int(10) NOT NULL {$auto_increment},{$default_fields} PRIMARY KEY (`{$primary_key}`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='{$title}';";
        try {
            db()->execute($sql);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        return true;
    }

    /**
     * 删除表
     * @param $table
     * @return bool
     * @throws \think\db\exception\BindParamException
     */
    public function deleteTable($table)
    {
        $prefix = Config::get('database.prefix');
        $sql    = "DROP TABLE `{$prefix}{$table}`;";
        try {
            db()->execute($sql);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        return true;
    }

    public function setTable($table)
    {
        $prefix = Config::get('database.prefix');
        $res    = $this->checkTable($table);
        if (!$res) {
            throw new \Exception($table . '表不存在');
        }
        $this->data['table'] = $prefix . $table;
        return $this;
    }

    /**
     * 检查当前表是否存在
     * @param $table_name 表名称
     * @return bool
     */
    public function checkTable($table_name)
    {
        $prefix     = Config::get('database.prefix');
        $table_name = $prefix . $table_name;
        $res        = Db::query("SHOW TABLES LIKE '" . $table_name . "'");
        return count($res);
    }

    public function setType($type)
    {
        switch ($type) {
            case 'date':
            case 'time':
            case 'datetime':
                $this->data['type'] = strtoupper($type);
                break;
            case 'editor':
            case 'array':
            case 'text':
                $this->data['type'] = 'TEXT';
                break;
            case 'number':
                $this->data['type'] = 'INT';
                break;
            case 'string':
            default:
                $this->data['type'] = 'VARCHAR';
                break;
        }
        return $this;
    }

    public function setOldField($old_field)
    {
        $this->data['old_field'] = $old_field;
        return $this;
    }

    public function setField($field)
    {
        $this->data['field'] = $field;
        return $this;
    }

    public function setLength($length)
    {
        $this->data['length'] = $length;
        return $this;
    }

    public function setComment($comment)
    {
        $this->data['comment'] = $comment;
        return $this;
    }

    public function setDefault($default = 'NULL')
    {
        $this->data['default'] = $default;
        return $this;
    }

    public function setDecimals($decimals)
    {
        $this->data['decimals'] = $decimals;
        if ($decimals > 0) {
            $this->data['type']   = 'DECIMAL';
            $this->data['length'] = "{$this->data['length']},{$this->data['decimals']}";
        }
        return $this;
    }

    public function getSql($type)
    {
        $this->buildBefore();
        if ($type == 'add')
            return $this->addFieldSql();
        if ($type == 'edit')
            return $this->editFieldSql();
        if ($type == 'delete')
            return $this->delFieldSql();
    }

    protected function buildBefore()
    {
        if (empty($this->data['table'])) {
            throw new \Exception('表名称不能为空');
        }
        if (empty($this->data['field'])) {
            throw new \Exception('字段不能为空');
        }
        if (in_array($this->data['type'], ['INT','DECIMAL'])) {
            $this->data['length'] = "({$this->data['length']})";
            $this->data['default'] = $this->data['default'] == '' ? 'NULL' : $this->data['default'];
        } elseif (in_array($this->data['type'], ['SET', 'ENUM'])) {
            $content               = \app\common\model\Config::decode($this->data['content']);
            $this->data['length']  = "('" . implode("','", array_keys($content)) . "')";
            $this->data['default'] = in_array($this->data['default'], array_keys($content)) ? $this->data['default'] : ($this->data['type'] == 'ENUM' ? key($content) : '');
        } elseif (in_array($this->data['type'], ['DATE', 'TIME', 'DATETIME'])) {
            $this->data['length']  = '';
            $this->data['default'] = "NULL";
        } elseif (in_array($this->data['type'], ['TEXT'])) {
            $this->data['length']  = "(0)";
            $this->data['default'] = "NULL";
        } else {
            $this->data['length'] = "({$this->data['length']})";
        }
        $this->data['default'] = strtoupper($this->data['default']) === 'NULL' ? "NULL" : "'{$this->data['default']}'";
    }

    /**
     * 获取添加字段的SQL
     * @return string
     */
    public function addFieldSql()
    {
        $sql = "ALTER TABLE `{$this->data['table']}` "
            . "ADD `{$this->data['field']}` {$this->data['type']} {$this->data['length']} "
            . "DEFAULT {$this->data['default']} "
            . "COMMENT '{$this->data['comment']}' "
            . ($this->data['after'] ? "AFTER `{$this->data['after']}`" : '');

        return $sql;
    }

    public function editFieldSql()
    {
        $sql = "ALTER TABLE `{$this->data['table']}` "
            . ($this->data['old_field'] ? 'CHANGE' : 'MODIFY') . " COLUMN " . ($this->data['old_field'] ? "`{$this->data['old_field']}`" : '') . " `{$this->data['field']}` {$this->data['type']} {$this->data['length']} "
            . "DEFAULT {$this->data['default']} "
            . "COMMENT '{$this->data['comment']}' "
            . ($this->data['after'] ? "AFTER `{$this->data['after']}`" : '');
        return $sql;
    }

    /**
     * 获取删除字段的SQL
     * @return string
     */
    public function delFieldSql()
    {
        $sql = "ALTER TABLE `{$this->data['table']}` "
            . "DROP `{$this->data['field']}`";
        return $sql;
    }

    /**
     * 根据类型转换值的类型
     */
    public function getTypeToValue($type,$value)
    {
        $this->setType($type);
        switch ($this->data['type']){
            case 'BIGINT':
                $value=strtotime($value);
                break;
            default:;
        }
        return $value;
    }
}