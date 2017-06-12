<?php
namespace app\install\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    protected $status;

    public function _initialize()
    {
        $this->status = [
            'index'    => 'info',
            'check'    => 'info',
            'config'   => 'info',
            'sql'      => 'info',
            'complete' => 'info',
        ];
        if (request()->action() != 'complete' && is_file(APP_PATH . 'database.php') && is_file(APP_PATH . 'install.lock'))
        {
            return $this->redirect('index/index/index');
        }
    }

    public function index()
    {
        $this->status['index'] = 'primary';
        $this->assign('status', $this->status);
        return $this->fetch();
    }

    public function check()
    {
        session('error', false);

        //环境检测
        $env = check_env();

        //目录文件读写检测
        if (IS_WRITE)
        {
            $dirfile = check_dirfile();
            $this->assign('dirfile', $dirfile);
        }

        //函数检测
        $func = check_func();

        session('step', 1);

        $this->assign('env', $env);
        $this->assign('func', $func);
        $this->status['index'] = 'success';
        $this->status['check'] = 'primary';
        $this->assign('status', $this->status);
        return $this->fetch();
    }

    public function config($db = null, $admin = null)
    {
        if (request()->isPost())
        {
            //检测管理员信息
            if (!is_array($admin) || empty($admin[0]) || empty($admin[1]) || empty($admin[2]))
            {
                return $this->error('请填写管理员信息!');
            } elseif ($admin[1] != $admin[2])
            {
                return $this->error('确认密码和密码不一致');
            } else
            {
                $info = [];
                list($info['username'], $info['password'], $info['repassword'], $info['email']) = $admin;
                //缓存管理员信息
                session('admin_info', $info);
            }

            //检测数据库配置
            if (!is_array($db) || empty($db[0]) || empty($db[1]) || empty($db[2]) || empty($db[3]))
            {
                return $this->error('请填写完整的数据库配置');
            } else
            {
                $DB = [];
                list($DB['type'], $DB['hostname'], $DB['database'], $DB['username'], $DB['password'], $DB['hostport'], $DB['prefix']) = $db;
                //缓存数据库配置
                session('db_config', $DB);

                //创建数据库
                $dbname = $DB['database'];
                unset($DB['database']);
                $db  = Db::connect($DB);
                $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";
                if (!$db->execute($sql))
                {
                    return $this->error($db->getError());
                } else
                {
                    return $this->redirect('install/index/sql');
                }

            }
            return json_encode($admin);
        } else
        {
            $this->status['index']  = 'success';
            $this->status['check']  = 'success';
            $this->status['config'] = 'primary';
            $this->assign('status', $this->status);
            return $this->fetch();
        }

    }

    public function sql()
    {
        session('error', false);
        $this->status['index']  = 'success';
        $this->status['check']  = 'success';
        $this->status['config'] = 'success';
        $this->status['sql']    = 'primary';
        $this->assign('status', $this->status);
        echo $this->fetch();

        //连接数据库
        $dbconfig = session('db_config');
        $db       = Db::connect($dbconfig);
        //创建数据表
        create_tables($db, $dbconfig['prefix']);
        //注册创始人帐号
        $admin = session('admin_info');
        register_administrator($db, $dbconfig['prefix'], $admin);

        //创建配置文件
        $conf = write_config($dbconfig);
        session('config_file', $conf);

        if (session('error'))
        {
            show_msg('失败');
        } else
        {
            echo '<script type="text/javascript">location.href = "' . url('install/index/complete') . '";</script>';
        }


    }

    public function complete()
    {
        $this->status['index']    = 'success';
        $this->status['check']    = 'success';
        $this->status['config']   = 'success';
        $this->status['sql']      = 'success';
        $this->status['complete'] = 'success';
        $this->assign('status', $this->status);
        return $this->fetch();
    }
}
