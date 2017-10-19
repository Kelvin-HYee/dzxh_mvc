<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/22
 * Time: 0:03
 */

namespace Home\Controller;
use Think\Controller;
class InformController extends Controller {
    public function index(){
        $qq=M('qq')->where('id=1')->select();
        $most=M('Article')->field('id,title,dateline')->order('clicktimes desc')->where('cId!=1')->limit(3)->select();

        /*查找通知*/
        $count = M('article')->where('cId=1')->count();
        $Pagecount = 9;
        $Page  = new \Think\Page($count,$Pagecount);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->rollPage = 9;
        $Page->setConfig('first','首页');
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('last','尾页');
        //尾页不起作用
        //$this->lastSuffix && $this->config['last'] = $this->totalPages;
        //在源码里面（Page.class.php）将上面那句话注释掉就可以了
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$Pagecount.' 条/页 共 %TOTAL_ROW% 条)');
        $show  = $Page->show();// 分页显示输出
        $list = M('article')->field('id,title,dateline,author')->order('id desc')->where('cId=1')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出

        $icpp=M('admin_mes')->field('icp')->where('id=1')->select();
        $icp=$icpp[0]['icp'];
        $this->assign('icp',$icp);
        $this->assign('most',$most);
        $this->assign('qq',$qq);
        $this->display();
    }
}