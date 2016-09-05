<?php

if (!defined('ROOT'))  die('不能访问');//防止直接访问
global $moban;
global $u_userid;
$temlate_dir = "themes/".$moban;
$magic->template_dir = $temlate_dir;
$magic->compile_dir='./data/compile/'.$moban;
$magic->assign("tpldir",$temlate_dir);


require_once ROOT.'/modules/site_news/site_news.class.php';
$site_news=new site_news();

require_once ROOT.'/modules/sitecat/sitecat.class.php';
$sitecat=new sitecat();

require_once ROOT.'/modules/site_advt/site_advt.class.php';
$site_advt=new site_advt();


if($_G['query_sort']=='main')
{


	//文章
$riqi=date('Y-m-d H:i:s');
/*if($moban=='default')
{*/
$advt=$site_advt->get_one(array("where"=>"user_id='$u_userid' and  status=1 and  addtime<'$riqi' and endtime>'$riqi' and typeid=2 ","order "=>"id desc "));
/*}*/
/*if($moban=='shopcom')
{
$advt=$site_advt->get_one(array("where"=>"user_id='$u_userid' and  status=1 and  addtime<'$riqi' and endtime>'$riqi' and typeid=4 and code='$moban' ","order "=>"id desc "));
}*/
$_G['advt']=$advt;
if($_G['advt']['image'])
$_G['advt']['image']=image_path.$_G['advt']['image'];
	
	
	$id=$article_id;
	$_G['sid']=$id;
	if(!empty($id))
	{
		
		$categoryid=$_G['lei']['id'];
	 	if($categoryid==11)//公司产品
	   	{
			require_once ROOT.'/modules/category_goods/category_goods.class.php';
			$category_goods=new category_goods();
			require_once ROOT.'/modules/gcategory/gcategory.class.php';
			$gcategory=new gcategory();
			$_G['row']=$gcategory->get_one(array("where"=>"cate_id=$id and store_id=$u_userid "));
			if($_G['row']['parent_id']!=0)//子类
			{
				$arr=array(
				'order'=> 'g.goods_id desc',
				'page'=>(int)$_REQUEST['page'],
				'epage'=>8,
				'where'=>' g.closed = 0 AND g.if_show = 1 and cg.cate_id='.$id .' and g.store_id='.$_G['u_userid'],
				);
			}
			else//父类
			{
				$res=$gcategory->get_all(array("where"=>"parent_id=$id and store_id=$u_userid "));
				$str='';
				foreach($res as $key=>$var)
				{
					$str.=' or cg.cate_id='.$var['cate_id'];
				}
				$arr=array(
				'order'=> 'g.goods_id desc',
				'page'=>(int)$_REQUEST['page'],
				'epage'=>8,
				'where'=>' g.closed = 0 AND g.if_show = 1 and ( cg.cate_id='.$id .$str.') and g.store_id='.$_G['u_userid'],
				);
			}
			
			$result=$category_goods->getList($arr);
			$_G['goods']=$result['list'];
			foreach($result['list'] as $key=>$var)
			{
				$_G['goods'][$key]['goods_name1']=substr($var['goods_name'],0,52);
				if($var['default_image'])
				{
					if(strpos(strtolower($_G['goods'][$key]['default_image']),'http://') ===false)
							$_G['goods'][$key]['default_image']=image_path.$var['default_image'];
				}
				if($key==0)
					$_G['catename']=$var['cate_name'];
			}
			$_G['page']=$result['page'];
			
	   	}
		else
		{
			$arr=array(
			'where'=>'status=1 and id='.$id,
			);
			$resul=$site_news->con($arr);
			
			$_G['solu_con']=$resul['con'];
			$categoryid=(int)$_G['solu_con']['categoryid'];
			
			if($_G['solu_con']['image'])
			$_G['solu_con']['image']=image_path.$_G['solu_con']['image'];
			$_G['solu_con']['content']=str_replace('/data/files/store_',image_path.'data/files/store_',$_G['solu_con']['content']);
			
			$pre=array(
			'where'=>' status=1 and id<'.$id .' and categoryid='.$categoryid .' and user_id='.$_G['u_userid'],
			);
			$next=array(
			'where'=>' status=1 and id>'.$id .' and categoryid='.$categoryid .' and user_id='.$_G['u_userid'],
			);
			
			$prec=$site_news->con($pre);
			$_G['precon']=$prec['con'];
			$nextc=$site_news->con($next);
			$_G['nextcon']=$nextc['con'];
		}
	}
	else
	{
		$categoryid=$_G['lei']['id'];
		if(empty($categoryid))
		{
			$categoryid=1;
		}

		if($categoryid==6 || $categoryid==5 || $categoryid==11)//企业风采 新闻中心
		{
			if($categoryid==11)
			{
				require_once ROOT.'/modules/goods/goods.class.php';
				$goods=new goods();
				$arr=array(
				'order'=> 'goods_id desc',
				'page'=>(int)$_REQUEST['page'],
				'epage'=>8,
				'where'=>' store_id='.$u_userid . ' and  if_show=1 and closed=0' ,
				);
				$result=$goods->getList($arr);
				$_G['goods']=$result['list'];
				foreach($_G['goods'] as $key=>$var)
				{
					$_G['goods'][$key]['goods_name1']=substr($var['goods_name'],0,52);
					if($var['default_image'])
					{
						if(strpos(strtolower($var['default_image']),'http://') ===false)
							$_G['goods'][$key]['default_image']=image_path.$var['default_image'];
					}
				}
				$_G['page']=$result['page'];
			}
			else
			{
				$arr=array(
				'order'=> 'id desc',
				'page'=>(int)$_REQUEST['page'],
				'epage'=>8,
				'where'=>' status=1 and categoryid='.$categoryid .' and user_id='.$_G['u_userid'],
				);
				$result=$site_news->getList($arr);
				$_G['list']=$result['list'];
				foreach($_G['list'] as $key=>$var)
				{
					$_G['list'][$key]['image']=image_path.$var['image'];
					
					if(strpos(strtolower($_G['list'][$key]['image']),'http://') ===false)
							$_G['list'][$key]['image']=image_path.$var['image'];
					
					$_G['list'][$key]['title1']=substr($var['title'],0,52);
				}
				$_G['page']=$result['page'];
			}
		}
		else
		{
			$zrr=array(
			'where'=>' categoryid='.$categoryid.' and status=1 and user_id='.$_G['u_userid'],
			'order'=>' id desc '
			);
			
			$res=$site_news->con($zrr);
			$_G['solu']=$res['con'];
			$_G['solu']['content']=str_replace('/data/files/store_',image_path.'/data/files/store_',$_G['solu']['content']);
		}
	}

	//$_G['cate']=$sitecat->get_one(array("where"=>"id=$_G['lei']['id'] and status=1 "));
	$temp=$_G['lei']['content_tpl'];
	$_G['categoryid']=$categoryid;
	
	
	$template=$temp;
	
}


$magic->assign('_G',$_G);
$magic->display($template);