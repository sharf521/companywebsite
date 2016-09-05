<?php

class Page {
	var $page;
	var $epage;
	var $total;
	var $url;
	var $style;
	public function __construct()
    {	
		$this->style=1;
    }
	function show()
	{
		if($this->style==1)
		{			
			return $this->page($this->total,$this->epage,$this->page,$this->url);
		}
		else
		{
			
		}
	}
	
	// 分页函数
	function page($num, $perpage, $curpage, $mpurl='')
	{
		$mpurl=$_SERVER['REQUEST_URI'];
		$mpurl=str_replace(array("&page=$curpage","?page=$curpage"),'',$mpurl);
		//$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
		if(strpos($mpurl,'?')===false)
			$mpurl .='?';
		else
			$mpurl .='&amp;';	
		
		$multipage = '';
		if($num > $perpage) {
			$page = 10;
			$offset = 5;
			$pages = ceil($num / $perpage);
			if($page > $pages) {
				$from = 1;
				$to = $pages;
			} else {
				$from = $curpage - $offset;
				$to = $curpage + $page - $offset - 1;
				if($from < 1) {
					$to = $curpage + 1 - $from;
					$from = 1;
					if(($to - $from) < $page && ($to - $from) < $pages) {
						$to = $page;
					}
				} elseif($to > $pages) {
					$from = $curpage - $pages + $to;
					$to = $pages;
					if(($to - $from) < $page && ($to - $from) < $pages) {
						$from = $pages - $page + 1;
					}
				}
			}
			$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'page=1" class="p_redirect">&laquo;</a>' : '').($curpage > 1 ? '<a href="'.$mpurl.'page='.($curpage - 1).'" class="p_redirect">&#8249;</a>' : '');
			for($i = $from; $i <= $to; $i++) {
				$multipage .= $i == $curpage ? '<span class="p_curpage">'.$i.'</span>' : '<a href="'.$mpurl.'page='.$i.'" class="p_num">'.$i.'</a>';
			}
			$multipage .= ($curpage < $pages ? '<a href="'.$mpurl.'page='.($curpage + 1).'" class="p_redirect">&#8250;</a>' : '').($to < $pages ? '<a href="'.$mpurl.'page='.$pages.'" class="p_redirect">&raquo;</a>' : '');
			$multipage = $multipage ? '<div class="p_bar"><span class="p_info">总记录:'.$num.'</span>'.$multipage.'</div>' : '';
		}		
		return $multipage;
	}
		// 分页函数
	function page2($num, $perpage, $curpage, $mpurl='')
	{
		$mpurl="/".$_SERVER['QUERY_STRING'];			
		$_uri=explode('?',$_SERVER['REQUEST_URI']);
		$_uri='index.html?'.$_uri[1];		
		$multipage = '';
		if($num > $perpage) {
			$page = 10;
			$offset = 5;
			$pages = ceil($num / $perpage);
			if($page > $pages) {
				$from = 1;
				$to = $pages;
			} else {
				$from = $curpage - $offset;
				$to = $curpage + $page - $offset - 1;
				if($from < 1) {
					$to = $curpage + 1 - $from;
					$from = 1;
					if(($to - $from) < $page && ($to - $from) < $pages) {
						$to = $page;
					}
				} elseif($to > $pages) {
					$from = $curpage - $pages + $to;
					$to = $pages;
					if(($to - $from) < $page && ($to - $from) < $pages) {
						$from = $pages - $page + 1;
					}
				}
			}
			
			$mpurl="/".$_SERVER['QUERY_STRING'];		
		$_uri=explode('?',$_SERVER['REQUEST_URI']);
		$_uri='index.html?'.$_uri[1];	
			
			$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'page=1" class="p_redirect">首页</a>' : '').($curpage > 1 ? '<a href="'.$mpurl.'page='.($curpage - 1).'" class="p_redirect">上一页</a>' : '');
			for($i = $from; $i <= $to; $i++) {
				$multipage .= $i == $curpage ? '<span class="p_curpage">'.$i.'</span>' : '<a href="'.$mpurl.'page='.$i.'" class="p_num">'.$i.'</a>';
			}
			$multipage .= ($curpage < $pages ? '<a href="'.$mpurl.'page='.($curpage + 1).'" class="p_redirect">下一页</a>' : '').($to < $pages ? '<a href="'.$mpurl.'page='.$pages.'" class="p_redirect">末页</a>' : '');
			$multipage = $multipage ? '<div class="p_bar"><span class="p_info">总记录:'.$num.'</span>'.$multipage.'</div>' : '';
		}
		
		return $multipage;
	}

}

