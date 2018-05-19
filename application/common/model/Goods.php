<?php
namespace app\common\model;

use think\Model;

class Goods extends Model
{
    public $fields = 'id, title, url';
    public $keyList;

    public function initialize()
    {
        parent::initialize();

        $this->keyList = array(
            array('name' => 'id', 'title' => 'ID', 'type' => 'hidden', 'help' => '', 'option' => ''),
            array('name' => 'goods_name', 'title' => '商品名称', 'type' => 'text', 'help' => '', 'option' => ''),
            array('name' => 'category_id', 'title' => '商品分类', 'type' => 'select', 'help' => '', 'option' => $this->category()),
            array('name' => 'brand_id', 'title' => '品牌分类', 'type' => 'select', 'help' => '', 'option' => $this->brand()),
            array('name' => 'goods_sn', 'title' => '商品货号', 'type' => 'text', 'help' => '', 'option' => ''),
            array('name' => 'goods_number', 'title' => '商品数量', 'type' => 'num', 'help' => '', 'option' => ''),
            array('name' => 'market_price', 'title' => '市场售价', 'type' => 'decimal', 'help' => '', 'option' => ''),
            array('name' => 'shop_price', 'title' => '本店售价', 'type' => 'decimal', 'help' => '', 'option' => ''),
            array('name' => 'keywords', 'title' => '关键字', 'type' => 'text', 'help' => '', 'option' => ''),
            array('name' => 'goods_thumb', 'title' => '商品图片', 'type' => 'image', 'help' => '', 'option' => ''),
            array('name' => 'images', 'title' => '多图上传', 'type' => 'images', 'help' => '', 'option' => ''),
            array('name' => 'goods_desc', 'title' => '商品详情', 'type' => 'editor', 'help' => '', 'option' => ''),
            array('name' => 'status', 'title' => '是否上架', 'type' => 'radio', 'help' => '', 'option' => ['0'=>'下架','1'=>'上架']),
            array('name' => 'is_recommend', 'title' => '是否推荐', 'type' => 'radio', 'help' => '', 'option' => ['0'=>'否','1'=>'是']),
        );
    }

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

    public function category()
    {
        $tree = new \com\Tree();
        $category = db('Category')->field('id,pid,title')->select();
        //print_r($category); exit;
        $ss = $tree->toFormatTree($category);
        //print_r($ss);
        foreach ($ss as $k => $v)
        {
            if ($v['pid'] == 0)
            {
                $sss[$v['id']] = $v['title'];
            } else
            {
                $sss[$v['id']] = $v['title_show'];
            }
        }
        return $sss;
    }

    public function brand()
    {
        $tree = new \com\Tree();
        $category = db('Category')->field('id,pid,title')->select();
        //print_r($category); exit;
        $ss = $tree->toFormatTree($category);
        //print_r($ss);
        foreach ($ss as $k => $v)
        {
            if ($v['pid'] == 0)
            {
                $sss[$v['id']] = $v['title'];
            } else
            {
                $sss[$v['id']] = $v['title_show'];
            }
        }
        return $sss;
    }

    public function getStatusTextAttr($value, $data) {
        $status = array(
            0 => '禁用',
            1 => '启用',
        );
        return $status[$data['status']];
    }

    public function getIsRecommendTextAttr($value, $data) {
        $status = array(
            0 => '否',
            1 => '是',
        );
        return $status[$data['is_recommend']];
    }
}