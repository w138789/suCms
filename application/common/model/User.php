<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\common\model;

/**
 * 用户模型
 */
class User extends Base{

    protected $name = "Member";

    protected $type = array(
        'id'  => 'integer'
    );
    protected $insert = array('salt', 'password', 'status');
    protected $update = array();

    public $editfield = array(
        array('name'=>'id','type'=>'hidden'),
        array('name'=>'username','title'=>'用户名','type'=>'readonly','help'=>''),
        array('name'=>'nickname','title'=>'昵称','type'=>'text','help'=>''),
        array('name'=>'password','title'=>'密码','type'=>'password','help'=>'为空时则不修改'),
        array('name'=>'sex','title'=>'性别','type'=>'select','option'=>array('0'=>'保密','1'=>'男','2'=>'女'),'help'=>''),
        array('name'=>'email','title'=>'邮箱','type'=>'text','help'=>'用户邮箱，用于找回密码等安全操作'),
        array('name'=>'qq','title'=>'QQ','type'=>'text','help'=>''),
        array('name'=>'score','title'=>'用户积分','type'=>'text','help'=>''),
        array('name'=>'signature','title'=>'用户签名','type'=>'textarea','help'=>''),
        array('name'=>'status','title'=>'状态','type'=>'select','option'=>array('0'=>'禁用','1'=>'启用'),'help'=>''),
    );

    public $addfield = array(
        array('name'=>'username','title'=>'用户名','type'=>'text','help'=>'用户名会作为默认的昵称'),
        array('name'=>'password','title'=>'密码','type'=>'password','help'=>'用户密码不能少于6位'),
        array('name'=>'repassword','title'=>'确认密码','type'=>'password','help'=>'确认密码'),
        array('name'=>'email','title'=>'邮箱','type'=>'text','help'=>'用户邮箱，用于找回密码等安全操作'),
    );

    public $useredit = array(
        array('name'=>'id','type'=>'hidden'),
        array('name'=>'nickname','title'=>'昵称','type'=>'text','help'=>''),
        array('name'=>'sex','title'=>'性别','type'=>'select','option'=>array('0'=>'保密','1'=>'男','2'=>'女'),'help'=>''),
        array('name'=>'email','title'=>'邮箱','type'=>'text','help'=>'用户邮箱，用于找回密码等安全操作'),
        array('name'=>'mobile','title'=>'联系电话','type'=>'text','help'=>''),
        array('name'=>'qq','title'=>'QQ','type'=>'text','help'=>''),
        array('name'=>'signature','title'=>'用户签名','type'=>'textarea','help'=>''),
    );

    public $userextend = array(
        array('name'=>'company','title'=>'单位名称','type'=>'text','help'=>''),
        array('name'=>'company_addr','title'=>'单位地址','type'=>'text','help'=>''),
        array('name'=>'company_contact','title'=>'单位联系人','type'=>'text','help'=>''),
        array('name'=>'company_zip','title'=>'单位邮编','type'=>'text','help'=>''),
        array('name'=>'company_depart','title'=>'所属部门','type'=>'text','help'=>''),
        array('name'=>'company_post','title'=>'所属职务','type'=>'text','help'=>''),
        array('name'=>'company_type','title'=>'单位类型','type'=>'select', 'option'=>'', 'help'=>''),
    );

    protected function setStatusAttr($value){
        return 1;
    }

    protected function setPasswordAttr($value, $data){
        return md5($value.$data['salt']);
    }

    protected function getGroupListAttr($value, $data){
        $sql = db('AuthGroupAccess')->where('id', $data['id'])->fetchSql(true)->column('group_id');
        return db('AuthGroup')->where('id in ('.$sql.')')->column('title', 'id');
    }

    /**
     * 用户登录模型
     */
    public function login($username = '', $password = '', $type = 1){
        $map = array();
        if (\think\Validate::is($username,'email')) {
            $type = 2;
        }elseif (preg_match("/^1[34578]{1}\d{9}$/",$username)) {
            $type = 3;
        }
        switch ($type) {
            case 1:
                $map['username'] = $username;
                break;
            case 2:
                $map['email'] = $username;
                break;
            case 3:
                $map['mobile'] = $username;
                break;
            case 4:
                $map['id'] = $username;
                break;
            case 5:
                $map['id'] = $username;
                break;
            default:
                return 0; //参数错误
        }

        $user = $this->where($map)->find();
        if(isset($user['status']) && $user['status']){
            /* 验证用户密码 */
            if(md5($password.$user['salt']) === $user['password']){
                $this->autoLogin($user); //更新用户登录信息
                return $user['id']; //登录成功，返回用户ID
            } else {
                return -2; //密码错误
            }
        } else {
            return -1; //用户不存在或被禁用
        }
    }

    /**
     * 用户注册
     * @param  integer $user 用户信息数组
     */
    function register($username, $password, $email, $isautologin = true){
        $data['username'] = $username;
        $data['salt'] = rand_string(6);
        $data['password'] = $password;
        $data['email'] = $email;
        $result = $this->validate(true)->save($data);
        if ($result) {
            return $result;
        }else{
            if (!$this->getError()) {
                $this->error = "注册失败！";
            }
            return false;
        }
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $data = array(
            'id'             => $user['id'],
            'login'           => array('exp', '`login`+1'),
            'last_login_time' => time(),
            'last_login_ip'   => get_client_ip(1),
        );
        $this->where(array('id'=>$user['id']))->update($data);
        $user = $this->where(array('id'=>$user['id']))->find();

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'id'             => $user['id'],
            'username'        => $user['username'],
            'last_login_time' => $user['last_login_time'],
        );

        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));
    }

    public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
    }

    public function getInfo($id){
        $data = $this->where(array('id'=>$id))->find();
        return $data;
    }

    /**
     * 修改用户资料
     */
    public function editUser($data, $ischangepwd = false){
        if ($data['id']) {
            if (!$ischangepwd || ($ischangepwd && $data['password'] == '')) {
                unset($data['salt']);
                unset($data['password']);
            }else{
                $data['salt'] = rand_string(6);
            }
            $result = $this->validate('member.edit')->save($data, array('id'=>$data['id']));
            if ($result) {
                return true;
            }else{
                return false;
            }
        }else{
            $this->error = "非法操作！";
            return false;
        }
    }

    public function editpw($data, $is_reset = false){
        $id = $is_reset ? $data['id'] : session('admin_auth.admin_id');
        if (!$is_reset) {
            //后台修改用户时可修改用户密码时设置为true
            $this->checkPassword($id,$data['oldpassword']);

            $validate = $this->validate('member.password');
            if (false === $validate) {
                return false;
            }
        }

        $data['salt'] = rand_string(6);

        return $this->save($data, array('id'=>$id));
    }

    protected function checkPassword($id,$password){
        if (!$id || !$password) {
            $this->error = '原始用户id和密码不能为空';
            return false;
        }

        $user = $this->where(array('id'=>$id))->find();
        if (md5($password.$user['salt']) === $user['password']) {
            return true;
        }else{
            $this->error = '原始密码错误！';
            return false;
        }
    }
}