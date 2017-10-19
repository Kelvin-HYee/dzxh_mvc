<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/14
 * Time: 11:07
 */

namespace Admin\Model;
use Think\Model;

class ArticleModel extends Model{
    public function addArticle($data){
        //添加文章
        if(M('Article')->add($data)){
            return 1;
        }else{
            return 0;
        }
    }
    public function editArticle($id,$data){
        if(M('Article')->where('id='.$id)->save($data)){
            return 1;
        }else{
            return 0;
        }
    }
    public function delArticle($id){
        if (M('Article')->where('id='.$id)->delete()){
            return 1;
        }else{
            return 0;
        }
    }

    public function readArticle_One($id){
        $list=M('Article')->where('id='.$id)->select();
        if($list==false){
            redirect('../Index/main',5,'数据库查询失败！页面跳转中...');
        }elseif($list==null){
            redirect('../Index/main',5,'数据库信息为空！页面跳转中...');
        }else{
            return $list;
        }
    }
}