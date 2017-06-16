<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\Base;

class Admin extends Base
{
    private $menu;

    public function _initialize()
    {
        parent::_initialize();

        $this->menu = model('Menu');

        if (!is_login() && !in_array($this->url, ['admin/index/login', 'admin/index/logout', 'admin/index/verify']))
        {
            $this->redirect('admin/index/login');
        }
        if (!in_array($this->url, ['admin/index/login', 'admin/index/logout', 'admin/index/verify']))
        {
            define('IS_ROOT', is_administrator());
            if (!IS_ROOT && config('admin_allow_ip'))
            {
                if (!in_array(get_client_ip(1), explode(',', config('admin_allow_ip'))))
                {
                    $this->error('403:禁止访问');
                }
            }

            $this->setMenu();
        }
    }

    protected function setMenu()
    {
        $hover_url     = MODULE_NAME . '/' . CONTROLLER_NAME;
        $controller    = $this->url;
        $menu          = ['main' => [], 'child' => []];
        $where['pid']  = 0;
        $where['hide'] = 0;
        $where['type'] = 'admin';
        if (get_config('develop_mode') == 0)
        {
            //开发者模式没开启
            $where['is_dev'] = 0;
        }
        $field = 'id,title,url,icon,group,pid,"" as style';
        $row   = $this->menu->selectAll($field, $where);
        foreach ($row as $k => $v)
        {
            if ($controller == $v['url'])
            {
                $v['style'] = "active";
            }
            $menu['main'][$v['id']] = $v;
        }

        // 查找当前子菜单
        $pid = db('menu')->where("pid !=0 AND url like '%{$hover_url}%'")->value('pid');
        $id  = db('menu')->where("pid = 0 AND url like '%{$hover_url}%'")->value('id');
        $pid = $pid ? $pid : $id;
        if ($pid)
        {
            $map['pid']  = $pid;
            $map['hide'] = 0;
            $map['type'] = 'admin';
            $field       = 'id,title,url,icon,group,pid,"" as style';
            $row         = $this->menu->selectAll($field, $map);
            foreach ($row as $k => $v)
            {
                if ($controller == $v['url'])
                {
                    $menu['main'][$v['pid']]['style'] = 'active';
                    $v['style']                       = 'active';
                }
                $menu['child'][$v['group']][] = $v;
            }
        }
        $this->assign('__menu__', $menu);
    }

    final protected function checkRule($rule, $type = AuthRuler::rule_url)
    {

    }

    protected function setMeta($title = '')
    {
        $this->assign('meta_title', $title);
    }
}

