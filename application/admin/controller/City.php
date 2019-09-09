<?php
namespace app\admin\controller;
use think\Controller;

class City extends  Base
{
    //列表
    public function index()
    {
        return $this->fetch();
    }
}
