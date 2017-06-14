<?php
namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    public function login($username, $password)
    {
        $admin = $this->get(['username' => $username]);
        if (empty($admin))
        {
            return false;
        } else
        {
            if (md5($password . $admin['salt']) == $admin['password'])
            {

                return true;
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
            'admin_id' => $admin['admin_id'],
            'username' => $admin['username'],
            'username' => $admin['username'],
        ];
    }
}