<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/21
 * Time: 21:34
 */

namespace Home\Model;
use Think\Model;
class IndexModel{
    /*获取非通知类的文章数据数据*/
        public function readArticle_One($id){
            $list=M('Article')->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->order('id desc')->where('id='.$id)->select();
            if($list==false){
                $this->error('数据库查询失败！页面跳转中...');
            }elseif($list==null){
                $this->error('数据库信息为空！页面跳转中...');
            }else{
                return $list;
            }
        }
    
}