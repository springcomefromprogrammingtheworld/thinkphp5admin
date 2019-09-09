<?php

namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Cookie;
class Log extends Controller
{

    //日志列表
    public function index()
    {
        if(empty(Cookie::get('uid'))){
            $this->success("请登录急客救援后台管理系统再进行操作!", 'login/login');
        }
        $request = Request::instance();
        $auth=new Auth();
        $auth->check_auth(Cookie::get('uid'),$request);//验证权限状态
        $auth->check_action_status();//验证可操作性状态
        $list = Db::name('log')->paginate(20, false, ['query' => ['menu_name' => "日志记录"]]);
        // 获取分页显示
        $page = $list->render();
        $this->assign('list', $list->toArray()['data']);
        $this->assign('page', $page);
        return $this->fetch();
    }

    //记录日志
    public function add($data)
    {
        Db::name('log')->insert($data);
    }
}
