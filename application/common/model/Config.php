<?php
namespace app\common\model;

use think\Model;

class Config extends Model
{
    public $field = 'id, name, type, title, group, extra, remark, icon, create_time, update_time, status, value, sort';

    protected function getAll($field = '', $where = [], $limit = '', $order = ['id' => 'asc'])
    {
        $field = !empty($field) ? $field : $this->field;
        $this->field($field)
            ->where($where)
            ->limit($limit)
            ->order($order)
            ->select();
    }
    public function lists(){
        $map    = array('status' => 1);
        $data   = $this->db()->where($map)->field('type,name,value')->select();

        $config = array();
        if($data && is_array($data)){
            foreach ($data as $value) {
                $config[$value['name']] = $this->parse($value['type'], $value['value']);
            }
        }
        return $config;
    }
}


