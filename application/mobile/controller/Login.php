<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\mobile\controller;

use app\common\controller\Fornt;
use think\Session;

class Login extends Common
{
    public function index()
    {
        return $this->fetch();
    }

    public function login()
    {
        if (IS_POST)
        {

            if (empty(input('name')) || empty(input('password')))
            {
                return [
                    'status' => '2',
                    'msg'    => '登录失败'
                ];
            }
            $user = model('User')->field('id, mobile, password, salt')->where("mobile = '" . input('name') . "'")->find();
            if (empty($user))
            {
                return [
                    'status' => '2',
                    'msg'    => '登录用户为空'
                ];
            }
            $password  = $user['password'];
            $passwords = md5(input('password').$user['salt']);
            if ($password == $passwords)
            {
                session('user.userId',$user['id']);
                session('user.name',$user['mobile']);
                return [
                    'status' => '1',
                    'msg'    => '登录成功'
                ];
            } else
            {
                return [
                    'status' => '2',
                    'msg'    => '登录失败'
                ];
            }
        }
    }

    public function logout()
    {
        Session::delete('user');
        $this->success("退出成功", url('login/index'));
    }
}
