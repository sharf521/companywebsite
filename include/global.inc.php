<?php

function checkPost($data)
{
	if(!get_magic_quotes_gpc()) {
		return is_array($data)?array_map('rAddSlashes',$data):addslashes($data);
	} else {
		Return $data;
	}
}
/**
 * 获取IP地址
 */
function ip() 
{
	if(!empty($_SERVER["HTTP_CLIENT_IP"])) {
		$ip_address = $_SERVER["HTTP_CLIENT_IP"];
	}else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		$ip_address = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
	}else if(!empty($_SERVER["REMOTE_ADDR"])){
		$ip_address = $_SERVER["REMOTE_ADDR"];
	}else{
		$ip_address = '';
	}
	return $ip_address;
}

function sock_open($url,$data=array())
{	
	$row = parse_url($url);
	$host = $row['host'];
	$port = isset($row['port']) ? $row['port']:80;
	
	$post='';//要提交的内容.
	foreach($data as $k=>$v)
	{
		//$post.=$k.'='.$v.'&';
		$post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
	}
	$fp = fsockopen($host, $port, $errno, $errstr, 30); 
	if (!$fp)
	{ 
		echo "$errstr ($errno)<br />\n"; 
	} 
	else 
	{
		$header = "POST $url HTTP/1.1\r\n"; 
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "User-Agent: MSIE\r\n";
		$header .= "Host: $host\r\n"; 
		$header .= "Content-Length: ".strlen($post)."\r\n";
		$header .= "Connection: Close\r\n\r\n"; 
		$header .= $post."\r\n\r\n";		
		fputs($fp, $header); 
		//$status = stream_get_meta_data($fp);
		
		while (!feof($fp)) 
		{
			$tmp .= fgets($fp, 128);
		}
		fclose($fp);
		$tmp = explode("\r\n\r\n",$tmp);
		unset($tmp[0]);
		$tmp= implode("",$tmp);
		
		/*while (!feof($fp)) 
		{
		 if(($header = fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
			break;
		 }
		}
		$tmp = ""; 
		while (!feof($fp))
		{ 
			$tmp .= fgets($fp, 128); 
		}
		fclose($fp); */
	}
	return $tmp;
}

// 分页函数
function page($num, $perpage, $curpage, $mpurl) {
	$multipage = '';
	//$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
	if(strpos($mpurl,'?')===false)
		$mpurl .='?';
	else
		$mpurl .='&amp;';
	if($num > $perpage) {
		$page = 10;
		$offset = 5;
		$pages = @ceil($num / $perpage);
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

function getextension($filename) 
{ 
    return substr(strrchr($filename, "."), 1); 
}

function href_rel_nofollow( $text ) {
	//$text = stripslashes($text);
	$text = preg_replace_callback('|<a (.+?)>|i', 'rel_nofollow_callback', $text);
	return $text;
}

function rel_nofollow_callback( $matches )
{
	$text = $matches[1];
	$text = str_replace(array(' rel="nofollow"', " rel='nofollow'",'rel=\"nofollow\"'), '', $text);
	if(strpos($text,$_SERVER['SERVER_NAME'])===false)		
		return "<a $text rel=\"nofollow\">";	
	else
		return 	"<a $text >";
}


function htmltojs($str)
{
  $re='';
  $str= split("\r\n",$str);
  foreach($str as $v)
  {
	  $re.="document.writeln('".trim($v)."');\r\n";
  }  
  return $re;
}

function del_file($dir) { 
	if (is_dir($dir)) { 
		$dh=opendir($dir);//打开目录 //列出目录中的所有文件并去掉 . 和 .. 
		while (false !== ( $file = readdir ($dh))) { 
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file; 
				if(!is_dir($fullpath)) { 
					unlink($fullpath);
				} else { 
					del_file($fullpath); 
				} 
			}
		}
		closedir($dh); 
	} 
} 


//DeCode(密文,'D',密钥); 解密
//DeCode(明文,'E',密钥); 加密
function DeCode($string,$operation,$key='OcgqhcZ+QY')
{
	$key=md5($key);
	$key_length=strlen($key);
	$string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;

	$string_length=strlen($string);
	$rndkey=$box=array();
	$result='';
	for($i=0;$i<=255;$i++)
	{
		$rndkey[$i]=ord($key[$i%$key_length]);
		$box[$i]=$i;
	}
	for($j=$i=0;$i<256;$i++)
	{
		$j=($j+$box[$i]+$rndkey[$i])%256;
		$tmp=$box[$i];
		$box[$i]=$box[$j];
		$box[$j]=$tmp;
	}
	for($a=$j=$i=0;$i<$string_length;$i++)
	{
		$a=($a+1)%256;
		$j=($j+$box[$a])%256;
		$tmp=$box[$a];
		$box[$a]=$box[$j];
		$box[$j]=$tmp;
		$result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
	}
	if($operation=='D')
	{
		if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8))
		{
			return substr($result,8);
		}
		else
		{
			return'';
		}
	}
	else
	{
		return str_replace('=','',base64_encode($result));
	}
}