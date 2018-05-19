<?php
namespace app\common\model;

use think\Model;

class GoodsAttr extends Model
{
    public $fields = 'attr_id, title, url';
    public $keyList;

    public function initialize()
    {
        parent::initialize();

        $this->tree = new \com\Tree();

        $this->keyList = array(
            array('name' => 'attr_id', 'title' => 'ID', 'type' => 'hidden', 'help' => '', 'option' => ''),
            array('name' => 'title', 'title' => '规格名称', 'type' => 'text', 'help' => '', 'option' => ''),
            array('name' => 'pid', 'title' => '上级规格', 'type' => 'select', 'help' => '', 'option' => $this->attr()),
            array('name' => 'value', 'title' => '规格参数', 'type' => 'textarea', 'help' => '', 'option' => ''),
        );
    }

    public function attr()
    {
        $category = db('GoodsAttr')->field('attr_id,title,pid')->select();
        //print_r($category);
        if ($category)
        {
            $ss = $this->tree->toFormatTree($category, 'title', 'attr_id');
            //print_r($ss);
            foreach ($ss as $k => $v)
            {
                if ($v['pid'] == 0)
                {
                    $sss[$v['attr_id']] = $v['title'];
                } else
                {
                    $sss[$v['attr_id']] = $v['title_show'];
                }
            }
            return $sss;
        }
    }

    public function getPidTitleAttr($value, $data)
    {
        return $this->where('attr_id',$data['pid'])->value('title');
    }

}