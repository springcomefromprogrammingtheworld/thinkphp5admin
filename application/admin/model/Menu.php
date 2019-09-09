<?php

namespace app\admin\model;

use think\Model;
use think\Db;
class Menu extends Model
{
    //获取顶级菜单
    public function get_top_menu(){
        $top_menu = Menu::all(['parentid' => '0'])->toArray();
        return $top_menu;
    }

    //获取所有菜单操作统计量
    public function get_all_action(){
        $action_total=Db::name('menu')->where('parentid!=0')->count();
          return $action_total;
    }


    /**
     * 添加操作菜单
     * @param $data array 添加菜单数据
     * @return int|string
     */
    public function add_action_menu($data){
        $insert_data['menu_name']=$data['menuname'];
        $insert_data['menu_link']=$data['menulink'];
        $insert_data['parentid']=$data['menulevel'];
        $insert_data['menu_sort']=$data['menusort'];
        $insert_data['createt_time']=time();
        $insert_data['menu_status']=$data['mstatus'];
        $insert_data['remarks']=$data['remarks'];
        $insert_data['menu_display']=$data['mdisplay'];
        $rs=Db::name('menu')->insert($insert_data);
        return $rs;
    }

    /**
     * 更新操作菜单
     * @param $data array 更新操作菜单数据
     * @return int|string
     */
    public function update_action_menu($data){
        $update_data['menu_name']=$data['menuname'];
        $update_data['menu_link']=$data['menulink'];
        $update_data['parentid']=$data['menulevel'];
        $update_data['menu_sort']=$data['menusort'];
        $update_data['update_time']=time();
        $update_data['menu_status']=$data['mstatus'];
        $update_data['remarks']=$data['remarks'];
        $update_data['menu_display']=$data['mdisplay'];
        $rs=Db::name('menu')->where('menuid',$data['menuid'])->update($update_data);
        return $rs;
    }



    //处理菜单列表数据
    public function handle_menu($menu_data){
        foreach($menu_data as $key => $value){
            if($value['parentid']==0){
                $menu_data[$key]['ismenu']="顶级菜单";
            }else{
                $menu_data[$key]['ismenu']=$this->is_menu($value['parentid']);
            }
        }
        return $menu_data;
    }


    /**
     * 获取子菜单所属父级菜单
     * @param $parentid string 当前菜单父级id
     * @return string
     */
    public function is_menu($parentid){
        $rs=Db::name('menu')->where('menuid='.$parentid)->find();
        return $rs['menu_name'];
    }

    /**
     * 获取指定操作菜单
     * @param $mid string 菜单id
     * @param array
     */
    public function get_assign_menu($mid){
        $rs=Db::name('menu')->where('menuid='.$mid)->find();
        return $rs;
    }
}
