<?php
namespace app\admin\controller;
use think\Controller;

class Order extends  Base
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
}
