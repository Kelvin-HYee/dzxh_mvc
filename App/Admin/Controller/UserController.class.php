<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/6
 * Time: 12:34
 */

namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController{
     /*更改个人信息页面*/
    public function AlterPsw(){
        $id=$_SESSION['adminId'];
        $list=D('Admin')->readAdmin_One($id);
        if($list[0]['sex']==1){
            $list[0]['sex']="男";
        }elseif($list[0]['sex']==2){
            $list[0]['sex']="女";
        }else{
            $list[0]['sex']="保密";
        }
        if($list[0]['authority']==1){
            $list[0]['authority']="超级管理员";
        }elseif($list[0]['authority']==2){
            $list[0]['authority']="管理员";
        }else{
            $list[0]['authority']="普通用户";
        }
        $this->assign('list',$list);
        $this->display();
    }
    /*更改用户信息页面*/
    public function editAdmin($id){
        if ($_SESSION['adminTh']==1){
        $list=D('Admin')->readAdmin_One($id);
        if($list[0]['sex']==1){
            $list[0]['sex']="男";
        }elseif($list[0]['sex']==2){
            $list[0]['sex']="女";
        }else{
            $list[0]['sex']="保密";
        }
        if($list[0]['authority']==1){
            $list[0]['authority']="超级管理员";
        }elseif($list[0]['authority']==2){
            $list[0]['authority']="管理员";
        }else{
            $list[0]['authority']="普通用户";
        }
        $this->assign('list',$list);
        $this->display();
        }else{
            $this->error("你没权限",U('Index/main'));
        }
    }
    public function editAdmin_c($id){
        if(!IS_POST) halt('页面错误');
        $data = array(
            'username'=>I('post.username'),
            'password'=>md5(I('post.password')),
            'sex'=>I('post.sex'),
        );
        if (D('Admin')->editAdmin($id,$data)){
            // 更新监控表
            $user = M('Admin') -> where(array('username' => I('post.username'))) -> find();
            $data = array(
                'pid' => $_SESSION['adminId'],//操作者id
                'username'=>$_SESSION['adminName'],//操作者用户名
                'ip' => get_client_ip(),//操作者ip
                'last_time' => date('y-m-d H:i:s'),//时间
                'handle' =>6,//修改资料
                'handle_o'=>'改名为：'.$user['username']//操作对象
            );
            if(M('monitor') -> add($data)){
                $this->success('编辑成功',U('User/DelUser'));
            }
        }else{
            $this->error('编辑失败');
        }
    }
    /*新增用户页面*/
    public function AddUser(){
        if ($_SESSION['adminTh']==1){
        $this->display();
        }else{
            $this->error("你没权限",U('Index/main'));
        }
    }
    public function  AddUser_c(){
        if(!IS_POST) halt('页面错误');
        $data = array(
            'username'=>I('post.username'),
            'password'=>md5(I('post.password')),
            'sex'=>I('post.sex'),
            'authority'=>I('post.authority'),
        );
        if(D('Admin')->addAdmin($data)){
            // 更新监控表
            $user = M('Admin') -> where(array('username' => I('post.username'))) -> find();
            $data = array(
                'pid' => $_SESSION['adminId'],
                'username'=>$_SESSION['adminName'],
                'ip' => get_client_ip(),
                'last_time' => date('y-m-d H:i:s'),
                'handle' =>8,
                'handle_o'=>$user['username']
            );
            if(M('monitor') -> add($data)){
                $this->success("添加成功",U('User/DelUser'));
            }
        }else{
            $this->error("添加失败");
        }
    }
    /*管理用户页面*/
    public function DelUser(){
        if ($_SESSION['adminTh']==1){
        $list = D('Admin')->readAdmin();
        for($i=0;$i<=sizeof($list)-1;$i++){
           if($list[$i]['sex']==1){
               $list[$i]['sex']="男";
           }elseif($list[$i]['sex']==2){
               $list[$i]['sex']="女";
           }else{
               $list[$i]['sex']="保密";
           }
            if($list[$i]['authority']==1){
                $list[$i]['authority']="超级管理员";
            }elseif($list[$i]['authority']==2){
                $list[$i]['authority']="管理员";
            }else{
                $list[$i]['authority']="普通用户";
            }
        }
        $this->assign('list', $list);
        $this->display();
        }else{
            $this->error("你没权限",U('Index/main'));
        }
    }
    public function DelUser_c($id){
        if ($_SESSION['adminTh']==1){
        if($id==1){
            $this->error("超级管理员不可删除");
            exit();
        }
            if($id==2){
                $this->error("超级管理员不可删除");
                exit();
            }
        $user = M('Admin') -> where(array('id' => $id)) -> find();
        $dUser=D('Admin')->delAdmin($id);
        if ($dUser==1){
            // 更新监控表
            $data = array(
                'pid' => $_SESSION['adminId'],
                'username'=>$_SESSION['adminName'],
                'ip' => get_client_ip(),
                'last_time' => date('y-m-d H:i:s'),
                'handle' =>7,
                'handle_o'=>'被删者：'.$user['username']
            );
            if(M('monitor') -> add($data)){
                $this->success("用户删除成功！",U('Index/main'));
            }
        }elseif ($dUser==0){
            $this->error("用户删除失败！");
        }
        }else{
            $this->error("你没权限",U('Index/index'));
        }
    }
}