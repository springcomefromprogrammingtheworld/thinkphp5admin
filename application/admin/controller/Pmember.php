<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

//个人会员管理
class Pmember extends Controller
{

    //个人会员列表
    public function index()
    {
        return $this->fetch();
    }


    //删除个人会员
    public function del()
    {
        //
    }

    //个人会员详情
    public function details(){
         echo "个人会员详情";
    }
}
