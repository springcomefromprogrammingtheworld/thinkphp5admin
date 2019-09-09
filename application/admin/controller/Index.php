<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function welcome(){
        $this->assign('user_total',Db::name('user')->count());
        $this->assign('menu_category',Db::name('menu')->where('parentid=0')->count());
        $this->assign('menu_total',Db::name('menu')->where('parentid!=0')->count());
        $this->assign('ip',$this->request->ip());
        $this->assign('os',PHP_OS);
        $this->assign('servers',$_SERVER ['SERVER_SOFTWARE']);
        $this->assign('php_v',PHP_VERSION);
        $this->assign('mod',php_sapi_name());
        $this->assign('mysql_v',Db::query("select VERSION()")[0]['VERSION()']);
        $this->assign('upload_max_filesize',ini_get('upload_max_filesize'));
        $this->assign('max_execution_time',ini_get('max_execution_time'));
        $this->assign('memory_limit',ini_get('memory_limit'));
        return $this->fetch();

    }
}
