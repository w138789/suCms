<?php
namespace app\common\model;


class Admin extends Model
{
    public $fields = 'id,username,salt,password';

    public function selectAll($field = '', $where = [], $limit = NULL, $order = ['id' => 'asc'])
    {
        $field = !empty($field) ? $field : $this->fields;
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