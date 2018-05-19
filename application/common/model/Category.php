<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\common\model;

/**
 * 设置模型
 */
class Category extends Base{

    protected $name = "Category";
    protected $auto = array('icon'=>1, 'status'=>1);

    protected $type = array(
        'icon'  => 'integer',
    );

    public function change(){
        $data = input('post.');
        if ($data['id']) {
            $result = $this->save($data,['id'=>$data['id']]);
        }else{
            unset($data['id']);
            $result = $this->save($data);
        }
        if (false !== $result) {
            return true;
        }else{
            $this->error = "失败！";
            return false;
        }
    }

    public function info($id, $field = true){
        return $this->db()->where(array('id'=>$id))->field($field)->find();
    }

    /**
     * 组合分类
     * @param $arr
     * @param $id
     * @return array
     */
    public function stree($arr, $id)
    {

        $subs = array(); // 子孙数组
        $ks = 0;
        foreach ($arr as $v)
        {
            if ($v['pid'] == $id)
            {
                $ks++;
                $subs[] = $v;
                $subs[$ks-1]['arr'] = $this->stree($arr, $v['id']);
            }
        }
        return $subs;
    }
}