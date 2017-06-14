<?php
namespace app\admin\controller;

class Index extends Admin
{
    public function index()
    {
        return '后台首页';
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
            $result = $this->login($username, $password);
            if($result){
                //return
            }

        } else
        {
            return $this->fetch();
        }
    }
}

