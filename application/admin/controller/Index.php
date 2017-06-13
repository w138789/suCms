<?php
namespace app\admin\controller;

class Index extends Admin
{
    public function index()
    {
        return '后台首页';
    }

    public function login(){
        return $this->fetch();
    }
}
