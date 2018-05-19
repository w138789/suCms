<?php
namespace app\admin\controller;


class GoodsAttr extends Admin
{
    public $model;

    public function _initialize()
    {
        parent::_initialize();
        //$this->getContentMenu();
        $this->model = model('GoodsAttr');
        $this->tree  = new \com\Tree();
    }

    public function index()
    {
        $name = input('keyword', '', 'trim');
        $map  = [];
        $data = [];
        if ($name)
        {
            $map['title'] = ['like', '%' . $name . '%'];
        }
        $list = $this->model->where($map)->order('attr_id DESC')->paginate(10);
        foreach ($list as $k => $v)
        {
            $lists[$k]['attr_id'] = $v['attr_id'];
            $lists[$k]['title']   = $v['title'];
            $lists[$k]['pid']     = $v['pid'];
            $lists[$k]['value']   = $v['value'];
        }
        if (!empty($lists))
        {
            $lists = $this->tree->toFormatTree($lists, 'title', 'attr_id');
            $data  = [
                'keyword' => $name,
                'page'    => $list->render(),
                'list'    => $lists
            ];
        }

        $this->setMeta('规格列表');
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
            $this->model->where(['attr_id' => $pk])->setField($name, $value);
        }
    }

    public function add($pid = 0)
    {
        if (IS_POST)
        {
            $data = input('post.');
            $id   = $this->model->save($data);
            if ($id)
            {
                //session('admin_menu_list', null);
                //记录行为
                action_log('update_goods_attr', 'GoodsAttr', $id, session('admin_auth.admin_id'));
                return $this->success('新增成功', 'index');
            } else
            {
                return $this->error('新增失败');
            }
        } else
        {
            if ($pid)
            {
                /* 获取上级分类信息 */
                $cate = $this->model->get($pid);
                if (!($cate))
                {
                    return $this->error('指定的上级分类不存在或被禁用！');
                }
                $data['parent'] = $cate;
            } else
            {
                $data['parent'] = null;
            }
            $data['info'] = null;
            $this->assign($data);
            $this->setMeta('新增商品');
            return $this->fetch();
        }
    }

    public function edit($id = 0)
    {
        if (IS_POST)
        {
            $data = input('post.');
            //print_r($data); exit;
            if ($this->model->save($data, ['attr_id' => $data['id']]) !== false)
            {
                //session('admin_menu_list', null);
                //记录行为
                action_log('update_goods_attr', 'GoodsAttr', $data['id'], session('admin_auth.admin_id'));
                return $this->success('更新成功', 'index');
            } else
            {
                return $this->error('更新失败');
            }
        } else
        {
            $info = $this->model->field(true)->find($id);
            $this->assign('info', $info);
            $this->setMeta('更新商品');
            return $this->fetch();
        }
    }

    public function del()
    {
        $id = $this->getArrayParam('id');
        if (empty($id))
        {
            return $this->error('请选择要操作的数据');
        }
        $map = ['goods_id' => ['in', $id]];
        if ($this->model->where($map)->delete())
        {
            //session('admin_menu_list', null);
            //记录行为
            action_log('update_goods_attr', 'GoodsAttr', $id, session('admin_auth.admin_id'));
            return $this->success('删除成功');
        } else
        {
            return $this->error('删除失败！');
        }
    }

    public function specAdd()
    {
        $attr = $this->model->where('pid', 0)->select();
        foreach ($attr as $k => $v)
        {
            $attrs[$k]['attr_id'] = $v['attr_id'];
            $attrs[$k]['title']   = $v['title'];
        }
        //print_r($attrs);
        $this->assign('attr', $attrs);
        $this->assign('info', null);
        $this->setMeta('规格增加');
        return $this->fetch();
    }

    public function ajaxAdd($attr_id)
    {

        $attr  = $this->model->where('attr_id', $attr_id)->value('value');
        $attrs = explode("\r\n", $attr);
        //print_r($attrs);
        return $attrs;
    }

    public function ajaxChild($pid)
    {

        $attr  = $this->model->where('pid', $pid)->select();
        foreach($attr as $k => $v){
            $attrs[$k]['attr_id']= $v['attr_id'];
            $attrs[$k]['title']= $v['title'];
        }
        return $attrs;
    }
}