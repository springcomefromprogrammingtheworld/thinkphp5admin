<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use think\Cookie;

class Auth extends Controller
{
    //默认配置
    protected $_config = array(
        'AUTH_ON' => true,             // 认证开关
        'AUTH_USER_GROUP' => 'user_group',    // 用户-用户组关系表
        'AUTH_MENU' => 'menu',         // 权限菜单表
        'AUTH_USER' => 'user'         // 用户信息表
    );
    //请求对象实例
    public $request;
    //不需要验证的操作权限
    public $not_check = array('login/login', 'login/logout', 'index/index', 'index/welcome','log/add');

    public function _initialize()
    {
        $this->request = Request::instance();
    }


    /**
     * 操作权限验证
     * @param $uid string 当前管理员id
     * @param Request $request
     * @return bool
     */
    public function check_auth($uid, Request $request)
    {
        if (!$this->_config['AUTH_ON']) {
            return true;
        } else {
            $authList = Cookie::get('usermenu');
            if (empty($authList)) {
                $authList = $this->get_auth_menu($uid);
            }
        }


        //如果是超级管理员,拥有所有的操作权限
        if (Cookie::get('uid') == 1) {
            return true;
        }

        //不需要验证的操作权限
        if (in_array(strtolower($request->controller() . "/" . $request->action()), $this->not_check)) {
            return true;
        }

        //对比当前操作是否在权限菜单
        if (in_array(strtolower($request->controller() . "/" . $request->action()), $authList)) {
            return true;
        } else {
            if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {        //如果是ajax请求方式
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('status' => 0, 'msg' => "没有" . strtolower($request->controller() . "/" . $request->action()) . "操作权限!")));
            } else {
                $this->error("没有" . strtolower($request->controller() . "/" . $request->action()) . "操作权限!", 'login/login');
            }
        }
    }

    /**
     * 获取当前管理员用户所有权限菜单
     * @param $uid string 用户id
     */
    public function get_auth_menu($uid)
    {
        //获取管理员用户信息 s
        $user_info = Db::name('user')->where('uid', $uid)->find();
        $user_group_info = Db::name('user_group')->where('roleid', $user_info['roleid'])->find();

        $ids = array();//保存用户所属用户组设置的所有权限规则id
        $ids = array_merge($ids, explode(',', trim($user_group_info['group_menu'], ',')));
        $ids = array_unique($ids);

        //获取当前管理员用户所有权限菜单
        $map = array(
            'menuid' => array('in', $ids),
            'menu_status' => 1,
        );
        //读取用户组所有权限规则
        $menu = Db::name($this->_config['AUTH_MENU'])->where($map)->field('menu_link')->select();

        $return_menu = array();
        foreach ($menu as $value) {
            if ($value['menu_link'] != null && $value['menu_link'] != '')
                array_push($return_menu, $value['menu_link']);
        }
        Cookie::set('usermenu', $return_menu, 86400 * 7);
        return $return_menu;
    }


    //检查操作可用状态
    public function check_action_status()
    {
        //如果是超级管理员,跳过检查状态
        if (Cookie::get('uid') == 1) {
            return true;
        }

        //不需要验证的操作权限
        if (in_array(strtolower($this->request->controller() . "/" . $this->request->action()), $this->not_check)) {
            return true;
        }

        $map = array(
            'menu_link' => strtolower($this->request->controller() . "/" . $this->request->action()),
            'menu_status' => 1,
        );
        $rs = Db::name('menu')->where($map)->find();

        $action_status_rs = array();
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            // ajax 请求的处理方式
            if (empty($rs)) {
                $action_status_rs = ['status' => 0, 'msg' => "当前操作" . strtolower($this->request->controller() . "/" . $this->request->action()) . "被禁用,请设置为可用状态", 'method' => 'ajax'];
            } else {
                $action_status_rs = ['status' => 1, 'method' => 'ajax'];
            }
        } else {
            // 正常请求的处理方式
            if (empty($rs)) {
                $action_status_rs = ['status' => 0, 'msg' => "当前操作" . strtolower($this->request->controller() . "/" . $this->request->action()) . "被禁用,请设置为可用状态", 'method' => 'jump'];
            } else {
                $action_status_rs = ['status' => 1, 'method' => 'jump'];
            }
        };
        if ($action_status_rs['method'] == 'ajax' && $action_status_rs['status'] == 0) {
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode($action_status_rs));
        }
        if ($action_status_rs['method'] == 'jump' && $action_status_rs['status'] == 0) {
            $this->action_status_rs = $action_status_rs;
            $this->error($action_status_rs);
        }
    }



}
