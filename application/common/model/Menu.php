<?php
namespace app\common\model;


class Menu extends Model
{
    public $fields = 'id, title, url';

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
    public function getAuthNodes($type = 'admin'){
        $map['type'] = $type;
        $rows = $this->db()->field('id,pid,group,title,url')->where($map)->order('id asc')->select();
        foreach ($rows as $key => $value) {
            $data[$value['id']] = $value;
        }
        foreach ($data as $key => $value) {
            if ($value['pid'] > 0) {
                $value['group'] = $data[$value['pid']]['title'] . '管理';
                $list[] = $value;
            }
        }
        return $list;
    }
}