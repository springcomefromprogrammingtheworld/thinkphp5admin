<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Cookie;
class Login extends Controller
{
    public $log;
    public $request;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->log=new Log();
        $this->request=Request::instance();
    }

    //登录
    public function login()
    {
        $request = Request::instance();
        $method = $request->method();//获取提交方式
        if($method=="POST"){
            $login_param=$request->param();//登陆参数
            $user_info=Db::name('user')->where(array('account'=>$login_param['uname'],'password'=>md5($login_param['pw'])))->find();
            if(empty($user_info)){
                $this->error("账号或者密码错误", 'login/login');
            }else{
                if($user_info['uid']!=1&&$user_info['status']==0){
                    $this->error("该管理被禁用请联系超级管理启用该管理员账号", 'login/login');
                }
                 $login_data['last_time']=time();
                 $login_data['login_ip']=$this->request->ip();
                 $login_data['login_count']=$user_info['login_count']+1;
                 Db::name('user')->where('uid',$user_info['uid'])->update($login_data);
                 Cookie::set('uid',$user_info['uid'],86400*7);
                 Cookie::set('uname',$user_info['account'],86400*7);;
                $this->log->add(array('login_name'=>$user_info['account'],'req_url'=>'login/login','ip'=>$this->request->ip(),'log_contents'=>'登陆后台','log_time'=>date('Y-m-d H:i:s')));
                $this->success("登录成功", 'index/index');
            }
        }else{
            return $this->fetch();
        }
    }

    //退出登录
    public function logout()
    {
        $uid = Cookie::get('uid');
        if(!empty($uid)){
            $this->log->add(array('login_name'=>Cookie::get('uname'),'req_url'=>'login/logout','ip'=>$this->request->ip(),'log_contents'=>'退出登陆','log_time'=>date('Y-m-d H:i:s')));
            Cookie::delete('uid');//注销管理员id
            Cookie::delete('uname');//注销管理员账号
            Cookie::delete('thismenu');//注销当前后台管理员菜单(赋值到前端的值)
            Cookie::delete('usermenu');//注销当前后台管理员菜单
            $this->error('登出成功', 'login/login');
        }else{
            $this->error('已经登出了', 'login/login');
        }

    }
}
