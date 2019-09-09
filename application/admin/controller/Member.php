<?php
namespace app\admin\controller;
use think\Controller;

class Member extends  Base
{
    //列表
    public function index()
    {
        return $this->fetch();
    }

    //添加
    public function add(){
        return $this->fetch();
    }

    //编辑
    public function edit(){
        return $this->fetch();
    }

    //删除
    public function del(){
        return $this->fetch();
    }

    //修改密码
    public function password(){
        return $this->fetch();
    }
}
