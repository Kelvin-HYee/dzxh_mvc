<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/22
 * Time: 0:15
 */

namespace Home\Controller;
use Think\Controller;
class ActivityController extends Controller {
    public function index(){
        $jin=M('article')->where('cId=4')->order('id desc')->limit(3)->select();
        $inform=M('Article')->field('id,title,author')->order('id desc')->where('cId=1')->limit(4)->select();
        $most=M('Article')->field('id,title,dateline')->order('clicktimes desc')->where('cId!=1')->limit(3)->select();
        $photo=M('article')->field('id,photo')->where('cId=4')->order('id desc')->limit(4)->select();
        $sort = array(
            'direction' => 'SORT_ASC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field'     => 'id',       //排序字段
        );
        $arrSort = array();
        foreach($photo AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $photo);
        }
        $va=$arrSort[photo];
        $icpp=M('admin_mes')->field('icp')->where('id=1')->select();
        $icp=$icpp[0]['icp'];
        $this->assign('icp',$icp);
        $this->assign('photo',$photo);
        $this->assign('va',$va);
        $this->assign('most',$most);
        $this->assign('inform',$inform);
        $this->assign('jin',$jin);
        $this->display();
    }
}