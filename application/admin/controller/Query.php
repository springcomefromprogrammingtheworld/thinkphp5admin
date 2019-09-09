<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
//开发人员工具----查询数据库数据
class Query extends Base
{

    public function index()
    {

        if(isset($_POST['sub'])){
            $sql="select ".$_POST['field'].' from '.$_POST['table'].' where '.$_POST['where'];
            $res= Db::query($sql);
            dump($sql);
            dump($res);
            die;
        }
        if(isset($_POST['url'])){
            $get_content = file_get_contents($_POST['url']);
            dump($get_content);
            die;
        }
        if(isset($_POST['datasheet'])){
            $sql="show columns from ".$_POST['datasheet'];
            $res= Db::query($sql);
            dump($res);
            die;
        }
        return $this->fetch();
    }
}
