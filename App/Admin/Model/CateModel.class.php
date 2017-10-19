<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/15
 * Time: 10:39
 */

namespace Admin\Model;
use Think\Model;

class CateModel extends Model{
    public function addCate($data){
        //添加分类
        if(M('Cate')->add($data)){
            return 1;
        }else{
            return 0;
        }
    }
    public function editCate($id,$data){
        //修改分类
        if(M('Cate')->where('pid='.$id)->save($data)){
            return 1;
        }else{
            return 0;
        }
    }
    public function readCate(){
        $list=M('Cate')->select();
        if($list==false){
            redirect('../Index/main',5,'数据库查询失败');
        }elseif($list==null){
            redirect('../Index/main',5,'数据库查询为空,请添加分类');
        }else{
            return $list;
        }
    }
}