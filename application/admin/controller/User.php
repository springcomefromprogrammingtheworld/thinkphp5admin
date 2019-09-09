<?php
namespace app\admin\controller;
use think\Db;
use think\Cookie;
class User extends  Base
{
    //列表
    public function index()
    {
        $param = $this->request->param();//参数
        $where = '';
        if(array_key_exists('start', $param) && array_key_exists('end', $param)){
            if($param['start'] != '' && $param['end'] != ''){
                $where['u.create_time'] = array(array('gt',strtotime($param['start'])),array('lt',strtotime($param['start']) + 86399));
            }
        }
        if(array_key_exists('account', $param)){
            if($param['account'] != ''){
                $where['u.account'] = $param['account'];
            }
        }

        $list = Db::name('user')->alias('u')
            ->where($where)
            ->join('user_group ug ', 'u.roleid = ug.roleid', 'left')
            ->field('u.uid,u.create_time,u.account,u.status,u.roleid,ug.roleid,ug.name')
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
            if($param['pass'] == $param['repass']){
                $result = Db::name('user')
                    ->where('account' , $param['account'])
                    ->find();
                if(empty($result)){
                    $data = [
                        'account' => $param['account'],
                        'password' => md5($param['pass']),
                        'roleid' => $param['roleid'],
                        'create_time' => time(),
                        'status' => $param['status'],
                        'sort'=>$param['sort'],
                        'isshow' => 1
                    ];

                    Db::name('user')->insert($data);
                    $userId = Db::name('user')->getLastInsID();

                    return ['status'=>1 , 'msg'=>'管理员添加成功'];
                }else{
                    return ['status'=>0 , 'msg'=>'管理员名称已经存在，请重新输入'];
                }
            }else{
                return ['status'=>0 , 'msg'=>'两次密码不一致'];
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
            if($param['pass'] != '' && $param['repass'] != ''){
                if($param['pass'] == $param['repass']) {
                    $data = [
                        'uid'=> $param['id'],
                        'account' => $param['account'],
                        'password' => md5($param['pass']),
                        'roleid' => $param['roleid'],
                        'update_time' => time(),
                        'status' => $param['status'],
                        'sort' => $param['sort']
                    ];
                    $result = Db::name('user')->update($data);
                    if($result){
                        return ['status'=>1 , 'msg'=>'更新管理员成功'];
                    }else{
                        return ['status'=>0 , 'msg'=>'更新管理员失败'];
                    }
                }else{
                    return ['status'=>0 , 'msg'=>'两次密码不一致'];
                }
            }else{
                $data = [
                    'uid'=> $param['id'],
                    'account' => $param['account'],
                    'roleid' => $param['roleid'],
                    'update_time' => time(),
                    'status' => $param['status'],
                    'sort'=>$param['sort']
                ];
                $result = Db::name('user')->update($data);
                if($result){
                    return ['status'=>1 , 'msg'=>'更新管理员成功'];
                }else{
                    return ['status'=>0 , 'msg'=>'更新管理员失败'];
                }
            }
        }else{
            return ['status'=>0 , 'msg'=>'参数错误'];
        }
    }

    //编辑
    public function edit(){
        $param  = $this->request->param();//参数
        $list = Db::name('user_group')->select();
        $this->assign('list',$list);
        if(array_key_exists('id', $param)){
            $result = Db::name('user')->where('uid',$param['id'])->find();
            $this->assign('user',$result);
            $this->assign('id',$param['id']);
            return $this->fetch('edit');
        }else{
            return $this->fetch('add');
        }
    }

    //删除管理员
    public function del(){
        $method  = $this->request->method();//获取提交方式
        if($method == "POST") {
            $param = $this->request->param();//参数
            if(is_array($param['id'])){
                $result = Db::name('user')->where('uid','in',$param['id'])->delete();
            }else{
                $result = Db::name('user')->where('uid',$param['id'])->delete();
            }

            if($result){
                return ['status'=>1 , 'msg'=>'删除管理员成功'];
            }else{
                return ['status'=>0 , 'msg'=>'删除管理员失败'];
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
            $result = Db::name('user')->where('uid',$param['id'])->update($data);
            if($result){
                return ['status'=>1 , 'msg'=>'修改状态成功'];
            }else{
                return ['status'=>0 , 'msg'=>'修改状态失败'];
            }
        }else{
            return ['status'=>0 , 'msg'=>'参数错误'];
        }
    }


    //修改当前管理员密码
    public function edit_pass(){
        $uid=Cookie::get('uid');
        if($this->request->method()=="POST"){
            $param=$this->request->param();
            if(md5($param['npass'])!=md5($param['qpass'])){
                return ['status'=>0 , 'msg'=>'两次输入密码不一致','data'=>$param,'uid'=>$uid];
            }else{
                $rs = Db::name('user')->where('uid',$uid)->update(['password'=>md5($param['npass'])]);
                if($rs){
                    return ['status'=>1 , 'msg'=>'修改密码成功'];
                }else{
                    return ['status'=>0 , 'msg'=>'修改密码失败'];
                }
            }
        }else{
            return $this->fetch();
        }
    }
}
