<?php
namespace app\admin\controller;


class Menu extends Admin
{
    public $model;
    public $tree;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Menu');
        $this->tree  = new \com\Tree();
    }

    public function index()
    {
        $list = $this->model->field(true)->order('sort asc, id asc')->column('*', 'id');
        int_to_string($list, ['hide' => [1 => '是', 0 => '否'], 'is_dev' => [1 => '是', 0 => '否']]);
        if (!empty($list))
        {
            $list = $this->tree->toFormatTree($list);
        }
        //记录当前列表面的cookie
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->setMeta('菜单列表');
        $this->assign('list', $list);
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
                session('admin_menu_list', null);
                //记录行为
                action_log('update_menu', 'Menu', $id, session('admin_auth.admin_id'));
                return $this->success('新增成功', Cookie('__forward__'));
            } else
            {
                return $this->error('新增失败');
            }
        } else
        {
            $this->assign('info', ['pid' => input('pid')]);
            $list = $this->model->field(true)->order('sort asc, id asc')->column('*', 'id');
            $list = $this->tree->toFormatTree($list);
            if (!empty($list))
            {
                $list = array_merge([0 => ['id' => 0, 'title_show' => '顶级菜单']], $list);
            } else
            {
                $list = [0 => ['id' => 0, 'title_show' => '顶级菜单']];
            }
            $this->assign('list', $list);
            $this->setMeta('新增菜单');
            return $this->fetch('edit');
        }
    }

    public function edit($id = 0)
    {
        if (IS_POST)
        {
            $data = input('post.');
            if ($this->model->save($data, ['id' => $data['id']]) !== false)
            {
                session('admin_menu_list', null);
                //记录行为
                action_log('update_menu', 'Menu', $data['id'], session('admin_auth.admin_id'));
                return $this->success('更新成功', Cookie('__forward__'));
            } else
            {
                return $this->error('更新失败');
            }
        } else
        {
            $info = $this->model->field(true)->find($id);
            $list = $this->model->field(true)->order('sort asc, id asc')->column('*', 'id');
            $list = $this->tree->toFormatTree($list);
            $list = array_merge([0 => ['id' => 0, 'title_show' => '顶级菜单']], $list);
            $this->assign('list', $list);
            $this->assign('info', $info);
            $this->setMeta('新增菜单');
            return $this->fetch('edit');
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
            session('admin_menu_list', null);
            //记录行为
            action_log('update_menu', 'Menu', $id, session('admin_auth.admin_id'));
            return $this->success('删除成功');
        } else
        {
            return $this->error('删除失败！');
        }
    }

    public function import()
    {
        if (IS_POST)
        {
            $tree  = input('post.tree');
            $lists = explode(PHP_EOL, $tree);
            if ($lists == [])
            {
                return $this->error('请按格式填写批量导入的菜单，至少一个菜单');
            } else
            {
                $pid = input('post.pid');
                foreach ($lists as $k => $v)
                {
                    $record = explode('|', $v);
                    if (count($record) == 4)
                    {
                        $this->model->save([
                            'title'  => $record[0],
                            'url'    => $record[1],
                            'pid'    => $record[2],
                            'sort'   => 0,
                            'hide'   => 0,
                            'tip'    => '',
                            'is_dev' => 0,
                            'group'  => $record[3],
                        ]);
                    }
                    session('admin_menu_list', null);
                    return $this->success('导入成功', 'index?pid='.$pid);
                }
            }
        } else
        {
            $this->setMeta('批量导入后台菜单');
            $pid = (int)input('get.pid');
            $this->assign('pid', $pid);
            $data = $this->model->where(['id' => $pid])->field(true)->find();
            $this->assign('data', $data);
            return $this->fetch();
        }
    }

}