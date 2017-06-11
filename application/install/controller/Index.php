<?php
namespace app\install\controller;

use think\Controller;

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
        if (IS_WRITE) {
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
}
