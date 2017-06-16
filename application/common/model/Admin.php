<?php
namespace app\common\model;

use think\Model;

class Admin extends Model
{
    public function login($username, $password)
    {
        $admin = $this->get(['username' => $username]);
        if (empty($admin))
        {
            //账号为空
            return -2;
        } else
        {
            if (md5($password . $admin['salt']) == $admin['password'])
            {
                $this->autoLogin($admin);
                //密码正确
                return 1;
            } else
            {
                //密码错误
                return -1;
            }
        }
    }

    /**
     * 自动登录后台用户
     * @param $user 后台用户信息数组
     */
    public function autoLogin($admin)
    {
        //更新用户信息

        //更新登录信息
        $data = [
            'admin_id'        => $admin['admin_id'],
            'username'        => $admin['username'],
            'last_login_ip'   => get_client_ip(1),
            'last_login_time' => time_format(),
        ];
        $this->save($data, ['admin' => $admin['admin_id']]);
        session('admin_auth', $data);
        session('admin_auth_sign', data_auth_sign($data));
    }
}