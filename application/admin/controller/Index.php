<?php
namespace app\admin\controller;


class Index extends Admin
{

    public function index()
    {
        $this->setMeta('后台首页');
        return $this->fetch();
    }

    public function login($username = '', $password = '', $verify = '')
    {
        if (IS_POST)
        {
            if (!$username || !$password)
            {
                return $this->error('用户名或密码不能为空');
            }
            $captcha['captcha'] = $verify;
            $verifyRuturn       = $this->validate($captcha, [
                'captcha|验证码' => 'require|captcha'
            ]);

            if ($verifyRuturn != 'true')
            {
                return $this->error($verifyRuturn);
            }
            $result = $this->logins($username, $password);
            if ($result == 1)
            {
                return $this->success('登录成功', 'admin/index/index');
            } else
            {
                switch ($result)
                {
                    case -1:
                        $error = '密码错误';
                        break;
                    case -2:
                        $error = '账号错误';
                        break;
                }
                return $this->error($error);
            }

        } else
        {
            return $this->fetch();
        }
    }

    public function clear()
    {
        if (IS_POST)
        {
            $clear = input('post.clear/a', []);
            foreach ($clear as $k => $v)
            {
                if ($v == 'cache')
                {
                    \think\Cache::clear(); // 清空缓存数据
                } elseif ($v == 'log')
                {
                    \think\Log::clear();
                }
            }
            return $this->success('更新成功', 'admin/index/index');
        } else
        {
            $keylist = [
                ['name'   => 'clear', 'title' => '更新缓存', 'type' => 'checkbox', 'help' => '',
                 'option' => ['cache' => '缓存数据', 'log' => '日志数据']
                ]
            ];
            $data    = ['keyList' => $keylist];

            $this->assign($data);
            $this->setMeta('更新缓存');
            return $this->fetch('public/edit');
        }

    }

    public function logout()
    {
        session('admin_auth', null);
        session('admin_auth_sign', null);
        $this->redirect('admin/index/login');
    }
}

