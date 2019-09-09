<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Cookie;
use think\Db;
class Base extends Controller
{
    //菜单
    public $menu;
    //请求对象实例
    public $request;
    //权限验证类
    public $auth;

    public function _initialize(){
        header("Content-Type:text/html; charset=utf-8");
        $this->request = Request::instance();
        $this->check_login();//验证登陆状态
        $this->auth=new Auth();
        $this->auth->check_auth(Cookie::get('uid'),$this->request);//验证权限状态
        $this->auth->check_action_status();//验证可操作性状态

        if(empty(Cookie::get('thismenu'))){
            $this->get_menu(Cookie::get('uid'));
        }
        $this->assign('menu',Cookie::get('thismenu'));
        $this->assign('uname',Cookie::get('uname'));
    }

    //检测后台登陆状态
    public function check_login(){
        if(empty(Cookie::get('uid'))){
            $this->success("请登录急客救援后台管理系统再进行操作!", 'login/login');
        }
    }


    /**
     * 获取当前用户菜单操作权限
     * @param $uid string 管理员id
     * @return array 返回当前管理员操作菜单
     */
    public function get_menu($uid){
        $user_info=Db::name('user')->where('uid',$uid)->find();
        $user_group_info=Db::name('user_group')->where('roleid',$user_info['roleid'])->find();
        $ids = array();//保存用户所属用户组设置的所有权限规则id
        $ids = array_merge($ids, explode(',', trim($user_group_info['group_menu'], ',')));
        $ids = array_unique($ids);
        //获取当前管理员用户所有权限菜单
        $map=array(
            'menuid'=>array('in',$ids),
            'menu_status'=>1,
            'menu_display'=>1,
        );
        $menu = Db::name('menu')->where($map)->select();
        $this->menu=$menu;
        $this->get_final_menu();
        Cookie::set('thismenu',$this->menu,86400*7);
    }

    //获取最终菜单
    public function get_final_menu(){
        $array=array();
        foreach($this->menu as $key => $value){
            if($value['parentid']==0){ //顶级菜单
                $this->menu[$key]['submenu']=$this->make_menu($value['menuid'],$key);
                array_push($array,$this->menu[$key]);
            }
        }
        $this->menu=$array;
    }




    /**
     * 生成后台菜单数组
     * @param $menuid string 当前顶级菜单id
     * @param $start int 当前开始查找顶级菜单所属子菜单开始位置
     * @return array
     */
    public function make_menu($menuid,$start){
        $arr = array();
        $start_count=$start;
        for($i=0;$i<count($this->menu);$i++){
            ++$start_count;
            if($start_count>=count($this->menu)){
                break;
            }else{
                if($this->menu[$start_count]['parentid']==$menuid){
                    array_push($arr,$this->menu[$start_count]);
                }
            }
        }
        return $arr;
    }

}
