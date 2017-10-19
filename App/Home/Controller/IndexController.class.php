<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
		$photo=M('dzhb')->select();
		$new_article=M('article')->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->order('id desc')->where('pid<>1')->limit(3)->select();
		$photo_activity=M('article')->field('id,photo')->order('id desc')->where('cId=4')->limit(4)->select();
		$p_activity=M('article')->field('id,photo')->order('zan desc')->where('cId=4')->limit(4)->select();
		$blog=M('article')->field('id,title,content,author,zan')->order('id desc')->where('cId=5')->limit(2)->select();
		$movie=M('article')->field('id,title,content,dateline')->order('id desc')->where('cId=2')->limit(4)->select();
		$most=M('Article')->field('id,title,dateline')->order('clicktimes desc')->where('cId!=1')->limit(7)->select();
		$qq=M('qq')->where('id=1')->select();
		$inform=M('Article')->field('id,title,author')->order('id desc')->where('cId=1')->limit(4)->select();
		$icpp=M('admin_mes')->field('icp')->where('id=1')->select();
		$icp=$icpp[0]['icp'];
		$this->assign('icp',$icp);
		$this->assign('inform',$inform);
		$this->assign('qq',$qq);
		$this->assign('most',$most);
		$this->assign('movie',$movie);
		$this->assign('blog',$blog);
		$this->assign('act',$p_activity);
		$this->assign('activity',$photo_activity);
		$this->assign('new_a',$new_article);
		$this->assign('photo',$photo);
		$this->display();
    }

	/*RSS界面*/
	public function rss(){
		$rss=new FeedController();
		$result=$rss->index();
		$this->assign('result',$result);
		$this->display();
	}

	/*关于我们*/
	public function about(){
		$list = M('member')->where("id='1'")->limit(1)->select();
		$icpp=M('admin_mes')->field('icp')->where('id=1')->select();
		$icp=$icpp[0]['icp'];
		$this->assign('icp',$icp);
		$this->assign('list',$list);
		$this->display();
	}
	
	/*处理非通知类文章的查看*/
	public function singlePage($id=null){
		if($id==null){
			$this->error('你丫在逗我吧~id都不给我还想来看我~~',$this->site_url,8);
		}else{
			$list=D('Index')->readArticle_One($id);
			if($list[0]['photo']==null){
				$list[0]['photo']='Public/images/a1.jpg';
			}
			$data['clicktimes'] = $list[0]['clicktimes']+1;
			$Click=M('Article')->where('id='.$id)->save($data);
			$Article=M('Article')->order('zan desc')->field('id,title')->where('cId<>1')->limit(7)->select();//喜欢程度（赞）最多
			$icpp=M('admin_mes')->field('icp')->where('id=1')->select();
			$icp=$icpp[0]['icp'];
			$this->assign('icp',$icp);
			$this->assign('article',$Article);
			$this->assign('list',$list);
			$this->display();
		}
	}
	/*点赞处理器*/
	public function zan(){
		$data['id']=isset($_POST['id'])?intval(trim($_POST['id'])):0;
		$obj = M("Article");

		if(!isset($_COOKIE[$_POST['id']+10000])&&$obj->where($data)->setInc('zan')){
			$cookiename = $_POST['id']+10000;
			setcookie($cookiename,40,time()+60,'/');

			$data['info'] = "ok";
			$data['status'] = 1;
			$this->ajaxReturn($data);

			exit();
		}else{
			$data['info'] = "fail";
			$data['status'] = 0;
			$this->ajaxReturn($data);
			exit();
		}

	}
	/*订阅邮件*/
	public function mail(){
		if(!IS_POST) halt('页面错误');
		$verify = I('post.ver');
		$mail=I('post.mail');
		$check=M('mail')->select();
		if(!check_verify($verify)){
			$this->error("亲，验证码输错了哦！",$this->site_url,5);
		}else {
			foreach ($check as $ch) {
			if (array_search($mail, $ch)) {
				$this->error("亲，你的邮箱已经订阅了哦！", $this->site_url, 5);
		    	}
			}
				$data = array(
					'mail' => $mail
				);
				M('mail')->add($data);
				$this->success("谢谢订阅我们！你的点点支持会化成我们无穷的动力！", $this->site_url, 5);
		}
	}

	/*联系我们*/
	public function contact(){
		$qq=M('qq')->where('id=1')->select();
		$icpp=M('admin_mes')->field('icp')->where('id=1')->select();
		$icp=$icpp[0]['icp'];
		$this->assign('icp',$icp);
		$this->assign('qq',$qq);
		$this->display();
	}
	
	/*验证码生成*/
	public function verify_c(){
		$Verify = new \Think\Verify();
		$Verify->fontSize = 18;
		$Verify->length   = 4;
		$Verify->useNoise = false;
		$Verify->codeSet = '0123456789';
		//$Verify->expire = 600;
		$Verify->entry();
	}
	
	/*搜索处理程序*/
	public function search($where = null,$order = 'id'){
		$keywords = I('post.keyword');
		if ($keywords == null) {
			if($where!=null){
				//进行了搜索后的分页与查询
				$Article = M('Article'); // 实例化Article对象
				$count = $Article->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->where($where)->count();
				$Pagecount = 7;
				$Page = new \Think\Page($count, $Pagecount);// 实例化分页类 传入总记录数和每页显示的记录数(25)
				$Page->rollPage = 7;
				$Page->setConfig('first', '首页');
				$Page->setConfig('prev', '上一页');
				$Page->setConfig('next', '下一页');
				$Page->setConfig('last', '尾页');
				$Page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 ' . I('p', 1) . ' 页/共 %TOTAL_PAGE% 页 ( ' . $Pagecount . ' 条/页 共 %TOTAL_ROW% 条)');
				$show = $Page->show();// 分页显示输出
				// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
				$list = $Article->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->order($order.' desc')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->select();
			}else{
				$this->error('你又迷路了,快打电话找警察叔叔吧~',$this->site_url,1000);
			}
		} else {
			$order = 'id desc';
			//进行了搜索后的分页与查询
			$Article = M('Article'); // 实例化Article对象
			$keywords = addslashes($keywords);
			$where = $keywords ? " content like '%{$keywords}%' or title like '%{$keywords}%'" : null;
			$count = $Article->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->where($where)->count();
			$Pagecount = 7;
			$Page = new \Think\Page($count, $Pagecount);// 实例化分页类 传入总记录数和每页显示的记录数(25)
			$Page->rollPage = 7;
			$Page->setConfig('first', '首页');
			$Page->setConfig('prev', '上一页');
			$Page->setConfig('next', '下一页');
			$Page->setConfig('last', '尾页');
			$Page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 ' . I('p', 1) . ' 页/共 %TOTAL_PAGE% 页 ( ' . $Pagecount . ' 条/页 共 %TOTAL_ROW% 条)');
			$show = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$list = $Article->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->where($where)->order($order)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		}
		$qq=M('qq')->where('id=1')->select();
		$icpp=M('admin_mes')->field('icp')->where('id=1')->select();
		$icp=$icpp[0]['icp'];
		$this->assign('icp',$icp);
		$this->assign('qq',$qq);
		$this->assign('list', $list);// 赋值数据集
		$this->assign('page', $show);// 赋值分页输出
		$this->display();
	}
}