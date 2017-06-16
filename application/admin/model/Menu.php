<?php
namespace app\admin\model;

use think\Model;

class Menu extends Model
{
    public $field = 'id, title, url';

    public function selectAll($field = '', $where = [], $limit = NULL, $order = ['id' => 'asc'])
    {
        $field = !empty($field) ? $field : $this->field;
        $data  = $this->field($field)
            ->where($where)
            ->limit($limit)
            ->order($order)
            ->select();
        return $data;
    }

    public function findOne($field = null, $data = array())
    {
        $data = $this->field($field)
            ->where($data)
            ->find();
        return $data;
    }
}