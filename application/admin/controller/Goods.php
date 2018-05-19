<?php

namespace app\admin\controller;


class Goods extends Admin
{
    public $model;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Goods');
    }

    public function index()
    {
        $name = input('keyword', '', 'trim');
        $map  = [];
        //$map['status'] = 1;
        if ($name)
        {
            $map['goods_name'] = ['like', '%' . $name . '%'];
        }
        $list = $this->model->where($map)->order('id DESC')->paginate(10);
        $data = [
            'keyword' => $name,
            'page'    => $list->render(),
            'list'    => $list
        ];
        $this->setMeta('商品列表');
        $this->assign($data);
        return $this->fetch();
    }

    /**
     * 单字段编辑
     * @param null $name 更新字段
     * @param null $value 更新的值
     * @param null $pk 更新条件
     */
    public function editable($name = null, $value = null, $pk = null)
    {
        if ($name && ($value != null || $value != ''))
        {
            $this->model->where(['id' => $pk])->setField($name, $value);
        }
    }

    public function add()
    {
        if (IS_POST)
        {
            $data = input('post.');
            $id   = $this->model->save($data);
            if ($id)
            {
                //session('admin_menu_list', null);
                //记录行为
                action_log('update_goods', 'Goods', $id, session('admin_auth.admin_id'));
                return $this->success('新增成功', 'index');
            } else
            {
                return $this->error('新增失败');
            }
        } else
        {
            $data = array(
                'keyList' => $this->model->keyList,
            );
            $this->assign($data);
            $this->setMeta('新增商品');
            return $this->fetch('public/edit');
        }
    }

    public function edit($id = 0)
    {
        if (IS_POST)
        {
            $data = input('post.');
            if ($this->model->save($data, ['id' => $data['id']]) !== false)
            {
                //session('admin_menu_list', null);
                //记录行为
                action_log('update_goods', 'Goods', $data['id'], session('admin_auth.admin_id'));
                return $this->success('更新成功', 'index');
            } else
            {
                return $this->error('更新失败');
            }
        } else
        {
            $info = $this->model->field(true)->find($id);
            $data = array(
                'keyList' => $this->model->keyList,
            );
            $this->assign($data);
            $this->assign('info', $info);
            $this->setMeta('更新商品');
            return $this->fetch('public/edit');
        }
    }

    public function del()
    {
        $id = $this->getArrayParam('id');
        if (empty($id))
        {
            return $this->error('请选择要操作的数据');
        }
        $map = ['id' => ['in', $id]];
        if ($this->model->where($map)->delete())
        {
            //session('admin_menu_list', null);
            //记录行为
            action_log('update_goods', 'Goods', $id, session('admin_auth.admin_id'));
            return $this->success('删除成功');
        } else
        {
            return $this->error('删除失败！');
        }
    }
}