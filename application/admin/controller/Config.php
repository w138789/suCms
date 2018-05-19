<?php
namespace app\admin\controller;

use think\Cookie;

class Config extends Admin
{
    public $model;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Config');
    }

    public function index()
    {
        $group = input('group', 0, 'trim');
        $name  = input('name', 0, 'trim');
        $map   = ['status' => 1];
        if ($group)
        {
            $map['group'] = $group;
        }
        if ($name)
        {
            $map['name'] = ['like', '%' . $name . '%'];
        }
        $list = $this->model->where($map)->order('id DESC')->paginate(25);
        //记录当前列页的cookie
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $data = [
            'group'       => config('config_group_list'),
            'config_type' => config('config_config_list'),
            'page'        => $list->render(),
            'group_id'    => $group,
            'list'        => $list
        ];
        $this->assign($data);
        $this->setMeta('配置管理');
        return $this->fetch();

    }

    public function group($id = 1)
    {
        if (IS_POST)
        {
            $config = input('config/a');
            foreach ($config as $k => $v)
            {
                $this->model->where(['name' => $k])->setField('value', $v);
            }
            cache('db_config_data', null);
            return $this->success('更新成功!');
        } else
        {
            $type  = config('config_group_list');
            $where = ['status' => 1, 'group' => $id];
            $order = 'sort';
            $list  = $this->model->selectAll('', $where, $order);
            $this->assign('list', $list);
            $this->assign('id', $id);
            $this->setMeta($type[$id] . '设置');
            return $this->fetch();
        }

    }

    /**
     * 新增配置
     */
    public function add()
    {
        if (IS_POST)
        {
            $data = input();
            if (!empty($data))
            {
                $id = $this->model->validate(true)->save($data);
                if ($id)
                {
                    cache('db_config_data', null);
                    //记录行为
                    action_log('update_config', 'config', $id, session('admin_auth.admin_id'));
                    return $this->success('新增成功');
                } else
                {
                    return $this->error('新增失败');
                }
            }
        } else
        {
            $this->setMeta('新增配置');
            $this->assign('info', null);
            return $this->fetch('edit');
        }
    }

    public function edit($id = 0)
    {
        if (IS_POST)
        {
            $data = input();
            if ($data)
            {
                $result = $this->model->validate(true)->save($data, ['id' => $data['id']]);
                if ($result !== false)
                {
                    cache('db_config_data', null);
                    //记录行为
                    action_log('update_config', 'config', $data['id'], session('admin_auth.admin_id'));
                    return $this->success('更新成功', Cookie('__forward'));
                } else
                {
                    return $this->error($this->model->getError());
                }
            } else
            {
                return $this->error($this->model->getError());
            }
        } else
        {
            $info = $this->model->field(true)->find($id);
            if ($info === false)
            {
                return $this->error('获取配置信息错误');
            }
            $this->assign('info', $info);
            $this->setMeta('编辑配置');
            return $this->fetch();
        }
    }

    public function del()
    {
        $id = array_unique((array)input('id', 0));
        if (empty($id))
        {
            return $this->error('请选择要操作的数据');
        }
        $map = ['id' => ['in', $id]];
        if ($this->model->where($map)->delete())
        {
            cache('db_config_data', null);
            //记录行为
            action_log('update_config', 'config', $id, session('admin_auth.admin_id'));
            return $this->success('删除成功');
        } else
        {
            $this->error('删除失败');
        }
    }

    public function themes()
    {
        $list   = $this->model->getThemesList();
        $pc     = config('pc_themes');
        $mobile = config('mobile_themes');
        $data   = [
            'pc'     => $pc,
            'mobile' => $mobile,
            'list'   => $list,
        ];

        $this->assign($data);
        $this->setMeta('主题设置');
        return $this->fetch();
    }

    /**
     * 设置主题
     * @return json
     */
    public function setthemes($name, $id)
    {
        $result = $this->model->where('name', $name . '_themes')->setField('value', $id);
        if (false !== $result)
        {
            \think\Cache::clear();
            return $this->success('设置成功！');
        } else
        {
            return $this->error('设置失败！');
        }
    }
}