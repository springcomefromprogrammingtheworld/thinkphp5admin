<?php
namespace app\admin\controller;
use think\Db;

class UserGroup extends  Base
{
    //列表
    public function index()
    {
        $param = $this->request->param();//参数
        $where = '';
        if(array_key_exists('start', $param) && array_key_exists('end', $param)){
            if($param['start'] != '' && $param['end'] != ''){
                $where['create_time'] = array(array('gt',strtotime($param['start'])),array('lt',strtotime($param['start']) + 86399));
            }
        }
        if(array_key_exists('name', $param)){
            if($param['name'] != ''){
                $where['name'] = $param['name'];
            }
        }

        $list = Db::name('user_group')
            ->where($where)
            ->paginate(10);
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('count',count($list));
        $this->assign('page',$page);
        return $this->fetch();
    }

    //添加
    public function add(){
        $method  = $this->request->method();//获取提交方式
        if($method == "POST"){
            $param  = $this->request->param();//参数
            $result = Db::name('user_group')
                ->where('name' , $param['name'])
                ->find();
            if(empty($result)) {
                $data = [
                    'name' => $param['name'],
                    'group_menu' => join(',', array_values($param['menuid'])),
                    'sort' => $param['sort'],
                    'create_time' => time(),
                    'status' => $param['status'],
                    'remarks' => $param['remarks']
                ];

                Db::name('user_group')->insert($data);
                $userId = Db::name('user_group')->getLastInsID();

                return ['status'=>1 , 'msg'=>'添加角色成功'];
            }else{
                return ['status'=>0 , 'msg'=>'角色名称已经存在，请重新输入'];
            }
        }else{
            return ['status'=>0 , 'msg'=>'参数错误'];
        }
    }

    //更新信息
    public function update(){
        $method  = $this->request->method();//获取提交方式
        if($method == "POST") {
            $param = $this->request->param();//参数

            $data = [
                'roleid' => $param['id'],
                'name' => $param['name'],
                'group_menu' => join(',', array_values($param['menuid'])),
                'sort' => $param['sort'],
                'update_time' => time(),
                'status' => $param['status'],
                'remarks' => $param['remarks']
            ];
            $result = Db::name('user_group')->update($data);
            if($result){
                return ['status'=>1 , 'msg'=>'更新角色信息成功'];
            }else{
                return ['status'=>0 , 'msg'=>'更新角色信息失败'];
            }
        }else{
            return ['status'=>0 , 'msg'=>'参数错误'];
        }
    }

    //编辑
    public function edit(){
        $param  = $this->request->param();//参数
        $list = Db::name('menu')
            ->where(['menu_status'=>1 , 'parentid'=>0])
            ->field('menuid,parentid,menu_name')
            ->select();

        foreach ($list as $key=>$value){
            $list[$key]['view'] = Db::name('menu')
                ->where(['menu_status'=>1 , 'parentid'=>$value['menuid']])
                ->field('menuid,parentid,menu_name')
                ->select();
        }

        $this->assign('list',$list);
        if(array_key_exists('id', $param)){
            $result = Db::name('user_group')->where('roleid',$param['id'])->find();
            $this->assign('usergroup',$result);
            $this->assign('id',$param['id']);
            return $this->fetch('edit');
        }else{
            return $this->fetch('add');
        }
    }

    //删除角色
    public function del(){
        $method  = $this->request->method();//获取提交方式
        if($method == "POST") {
            $param = $this->request->param();//参数
            if(is_array($param['id'])){
                $result = Db::name('user_group')->where('roleid','in',$param['id'])->delete();
            }else{
                $result = Db::name('user_group')->where('roleid',$param['id'])->delete();
            }

            if($result){
                return ['status'=>1 , 'msg'=>'删除角色成功'];
            }else{
                return ['status'=>0 , 'msg'=>'删除角色失败'];
            }
        }else{
            return ['status'=>0 , 'msg'=>'参数错误'];
        }
    }

    //修改管理员状态
    public function off(){
        $method  = $this->request->method();//获取提交方式
        if($method == "POST") {
            $param = $this->request->param();//参数

            $data = [
                'status'=>$param['type'],
                'update_time'=>time()
            ];
            $result = Db::name('user_group')->where('roleid',$param['id'])->update($data);
            if($result){
                return ['status'=>1 , 'msg'=>'修改状态成功'];
            }else{
                return ['status'=>0 , 'msg'=>'修改状态失败'];
            }
        }else{
            return ['status'=>0 , 'msg'=>'参数错误'];
        }
    }
}
