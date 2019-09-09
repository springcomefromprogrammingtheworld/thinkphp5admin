<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
//执行mysql语句控制器(仅限超级管理员拥有)
class UseMysql extends Base
{
    //sql语句输入视图
    public function index()
    {
        if(isset($_POST['sql'])){
            $sql=$_POST['sql'];
            $res=Db::execute($sql);
            dump($res);
        }
        return $this->fetch();
    }
}
