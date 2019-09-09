<?php
namespace app\admin\controller;

use think\Controller;
use think\Loader;
use think\Db;
use think\Request;

class Menu extends Base
{


    //操作菜单列表
    public function index()
    {
        $this->assign('action_total', Loader::model('Menu')->get_all_action());
        $list = Db::name('menu')->paginate(20, false, ['query' => ['menu_name' => "菜单管理"]]);
        // 获取分页显示
        $page = $list->render();
        $this->assign('list', Loader::model('Menu')->handle_menu($list->toArray()['data']));
        $this->assign('page', $page);
        return $this->fetch();
    }


    //添加操作菜单
    public function add()
    {
        if ($this->request->method() == "POST") {
            $rs = Loader::model('Menu')->add_action_menu($this->request->param());
            if ($rs) {
                return ['status' => 1, 'msg' => '添加菜单完成！'];
            } else {
                return ['status' => 0, 'msg' => '添加菜单失败！'];
            }
        } else {
            $this->assign('topmenu', Loader::model('Menu')->get_top_menu());
            return $this->fetch();
        }
    }

    //菜单可用性开关
    public function menu_status_on()
    {
        if ($this->request->method() == "POST") {
            $param = $this->request->param();
            $rs = Db::name('menu')->where('menuid', $param['mid'])->update(['menu_status' => $param['mstatus']]);
            if ($rs) {
                return ['status' => 1, 'msg' => $param['mstatus'] ? "启用" . $param['mname'] . "菜单" : "禁用" . $param['mname'] . "菜单"];
            } else {
                return ['status' => 0, 'msg' => $param['mstatus'] ? "启用" . $param['mname'] . "菜单失败" : "禁用" . $param['mname'] . "菜单失败"];
            }
        }
    }

    //菜单是否显示开关
    public function menu_display_on()
    {
        if ($this->request->method() == "POST") {
            $param = $this->request->param();
            $rs = Db::name('menu')->where('menuid', $param['mid'])->update(['menu_display' => $param['mdisplay']]);
            if ($rs) {
                return ['status' => 1, 'msg' => $param['mdisplay'] ? "显示" . $param['mname'] . "菜单" : "隐藏" . $param['mname'] . "菜单"];
            } else {
                return ['status' => 0, 'msg' => $param['mdisplay'] ? "显示" . $param['mname'] . "菜单失败" : "隐藏" . $param['mname'] . "菜单失败"];
            }
        }
    }


    //编辑操作菜单
    public function edit()
    {
        if ($this->request->method() == "POST") {
            $rs = Loader::model('Menu')->update_action_menu($this->request->param());
            if ($rs) {
                return ['status' => 1, 'msg' => '更新菜单成功!'];
            } else {
                return ['status' => 0, 'msg' => '更新菜单失败!'];
            }
        } else {
            $this->assign('topmenu', Loader::model('Menu')->get_top_menu());
            $this->assign('emenu', Loader::model('Menu')->get_assign_menu($this->request->param()['mid']));
            return $this->fetch();
        }
    }

    //删除菜单
    public function del()
    {
        if ($this->request->method() == "POST") {
            $param = $this->request->param();
            if ($param['pid'] == 0) {
                return ['status' => 0, 'msg' => '顶级菜单不可删除!'];
            } else {
                $rs = Db::name('menu')->delete($param['mid']);
                if ($rs) {
                    $this->update_user_group_menu($param['mid']);
                    return ['status' => 1, 'msg' => '删除菜单成功!'];
                } else {
                    return ['status' => 0, 'msg' => '删除菜单失败!'];
                }
            }
        }
    }

    /**
     * 更新角色操作权限
     * @param $menu_id string 被删除的菜单id
     */
    private function update_user_group_menu($menu_id=null)
    {
        if(empty($menu_id)){
            return;
        }else{
            $rs = Db::name('user_group')->select();
            foreach ($rs as $key => $value) {
                $user_group_id = $value['roleid'];
                $user_group_action_value = $value['group_menu'];
                $ids = array();//保存用户组的所有权限规则id
                $ids = array_merge($ids, explode(',', trim($user_group_action_value, ',')));
                if (in_array($menu_id, $ids)) {
                    $index = array_keys($ids, $menu_id, false);
                    unset($ids[$index[0]]);
                    $uugrs = join(',', array_values($ids));//更新后的用户组权限结果
                    db('user_group')->where('roleid', $user_group_id)->update(['group_menu'  => $uugrs]);
                }
            }
        }
    }
}
