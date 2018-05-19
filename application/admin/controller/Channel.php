<?php
namespace app\admin\controller;


class Channel extends Admin
{
    public $model;
    public $tree;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Channel');
        $this->tree  = new \com\Tree();
    }

    public function index($type = 0)
    {
        /* 获取频道列表 */
        $map = ['status' => ['gt', -1]];
        if ($type)
        {
            $map['type'] = $type;
        }
        $list = $this->model->where($map)->order('sort asc,id asc')->column('*', 'id');
        int_to_string($list);
        if (!empty($list))
        {
            $list = $this->tree->toFormatTree($list);
        }

        config('_sys_get_channel_tree_', true);

        $data = [
            'tree' => $list,
            'type' => $type
        ];
        $this->assign($data);
        $this->setMeta('导航管理');
        return $this->fetch();
    }

    public function editable($name = null, $value = null, $pk = null)
    {
        if ($name && ($value != null || $value != '') && $pk)
        {
            $this->model->where(['id' => $pk])->setField($name, $value);
        }
    }

    public function add()
    {
        if (IS_POST)
        {
            $data = $this->request->post();
            if ($data)
            {
                $id = $this->model->save($data);
                if ($id)
                {
                    return $this->success('新增成功', url('index'));
                    //记录行为
                    action_log('update_channel', 'Channel', $id, session('admin_auth.admin_id'));
                } else
                {
                    return $this->error('新增失败');
                }
            } else
            {
                $this->error($this->model->getError());
            }
        } else
        {
            $pid = input('id', 0);
            //获取父导航
            if (!empty($pid))
            {
                $parent = $this->model->where(['id' => $pid])->field('title')->find();
                $this->assign('parent', $parent);
            }
            $pnav = $this->model->where(['pid' => 0])->select();
            $this->assign('pnav', $pnav);
            $this->assign('pid', $pid);
            $this->assign('info', null);
            $this->setMeta('新增导航');
            return $this->fetch('edit');
        }
    }

    public function edit($id = 0)
    {
        if (IS_POST)
        {
            $data = $this->request->post();
            if ($data)
            {
                if (false !== $this->model->save($data, array('id' => $data['id'])))
                {
                    //记录行为
                    action_log('update_channel', 'Channel', $data['id'], session('admin_auth.admin_id'));
                    return $this->success('编辑成功', url('index'));
                } else
                {
                    return $this->error('编辑失败');
                }
            } else
            {
                return $this->error($this->model->getError());
            }
        } else
        {
            $info = $this->model->find($id);

            if (false === $info)
            {
                return $this->error('获取配置信息错误');
            }

            $pid = input('pid', 0);
            //获取父导航
            if (!empty($pid))
            {
                $parent = $this->model->where(['id' => $pid])->field('title')->find();
                $this->assign('parent', $parent);
            }

            $pnav = $this->model->where(['pid' => '0'])->select();
            $this->assign('pnav', $pnav);
            $this->assign('pid', $pid);
            $this->assign('info', $info);
            $this->setMeta('编辑导航');
            return $this->fetch();
        }
    }

    public function del()
    {
        $id = $this->getArrayParam('id');
        if (empty($id))
        {
            return $this->error('请选择要操作的数据!');
        }

        $map = ['id' => ['in', $id]];
        if ($this->model->where($map)->delete())
        {
            //记录行为
            action_log('update_channel', 'Channel', $id, session('admin_auth.admin_id'));
            return $this->success('删除成功');
        } else
        {
            return $this->error('删除失败');
        }
    }

    public function setStatus()
    {
        $id     = array_unique((array)input('ids', 0));
        $status = input('status', '0', 'trim');

        if (empty($id))
        {
            return $this->error('请选择要操作的数据');
        }

        $map    = ['id' => ['in', $id]];
        $result = $this->model->where($map)->update(['status' => $status]);
        if ($result)
        {
            return $this->success("操作成功");
        } else
        {
            return $this->error("操作失败");
        }
    }

    public function sort()
    {
        if (IS_GET)
        {
            $ids = input('ids');
            $pid = input('pid');
            //获取排序的数据
            $map = ['status' => ['gt', -1]];
            if (!empty($ids))
            {
                $map['id'] = ['in', $ids];
            } else
            {
                if ($pid !== '')
                {
                    $map['pid'] = $pid;
                }
            }
            $list = $this->model->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->setMeta('导航排序');
            return $this->fetch();
        } elseif (IS_POST)
        {
            $ids = input('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key => $value)
            {
                $res = $this->model->where(['id' => $value])->setField('sort', $key + 1);
            }
            if ($res !== false)
            {
                return $this->success('排序成功', 'admin/channel/index');
            } else
            {
                return $this->error('排序失败');
            }
        } else
        {
            return $this->error('非法请求');
        }
    }
}