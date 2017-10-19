<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/6
 * Time: 21:31
 */
namespace Admin\Model;
use Think\Model;
/*该类是处理表为xh_admin的数据操作*/
class AdminModel extends  Model{
    public function addAdmin($data){
        //添加Admin管理员
        if(M('Admin')->add($data)){
            return 1;
        }else{
            return 0;
        }
    }
    public function editAdmin($id,$data){
        //编辑Admin管理员信息
        if(M('Admin')->where('id='.$id)->save($data)){
            return 1;
        }else{
            return 0;
        }
    }
    public function readAdmin(){
        //读取Admin表中所有信息
        $list=M('Admin')->select();
        if ($list==false){
            redirect('../Index/main',5,'数据库查询失败!页面跳转中...');
        }elseif ($list==null){
            redirect('../Index/main',5,'数据库信息为空！页面跳转中...');
        }else{
            return $list;
        }
    }
    public function readAdmin_One($id){
        //读取表中所有信息
        $list=M('Admin')->where('id='.$id)->select();
        if ($list==false){
            redirect('../Index/main',5,'数据库查询失败!页面跳转中...');
        }elseif ($list==null){
            redirect('../Index/main',5,'数据库信息为空！页面跳转中...');
        }else{
            return $list;
        }
    }
    public function delAdmin($id){
        //删除表中数据库信息
        if (M('Admin')->where('id='.$id)->delete()){
            return 1;
            //$this->success("用户删除成功！",U('Index/index'));  Model没有此方法
        }else{
            return 0;
            //$this->error("用户删除失败！");
        }
    }
}