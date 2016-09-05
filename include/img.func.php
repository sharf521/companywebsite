<?php
function resize($srcFile,$dstW,$dstH,$path='',$dstFile='',$rate=100,$markwords=null,$markimage=null) 
{ 
	$file= explode('?', $srcFile);
    $file= explode('/', $file[0]);
    $filename= $file[count($file)-1];  //图片名	
    if(empty($dstFile))
		$dstFile=$path.$filename;//生成后图片
	else 
		$dstFile=$path.$dstFile;

	$data = GetImageSize($srcFile); 
	$im=create($srcFile,$data[2]);	
	if(!$im) return False; 
	$srcW=ImageSX($im); 
	$srcH=ImageSY($im); 
	$dstX=0; 
	$dstY=0; 
	if ($srcW*$dstH>$srcH*$dstW) 
	{ 
		$fdstH = round($srcH*$dstW/$srcW); 
		$dstY = floor(($dstH-$fdstH)/2); 
		$fdstW = $dstW; 
	} 
	else 
	{ 
		$fdstW = round($srcW*$dstH/$srcH); 
		$dstX = floor(($dstW-$fdstW)/2); 
		$fdstH = $dstH; 
	} 
	$ni=ImageCreateTrueColor($dstW,$dstH); 
	$dstX=($dstX<0)?0:$dstX; 
	$dstY=($dstX<0)?0:$dstY; 
	$dstX=($dstX>($dstW/2))?floor($dstW/2):$dstX; 
	$dstY=($dstY>($dstH/2))?floor($dstH/s):$dstY; 
	$white = ImageColorAllocate($ni,255,255,255); 
	$black = ImageColorAllocate($ni,0,0,0); 
	imagefilledrectangle($ni,0,0,$dstW,$dstH,$white);// 填充背景色 
	ImageCopyResized($ni,$im,$dstX,$dstY,0,0,$fdstW,$fdstH,$srcW,$srcH); 
	if($markwords!=null) 
	{ 		
		ImageTTFText($ni,20,30,100,100,$black,ROOT."/include/font/simhei.ttf",$markwords); //写入文字水印 
		//参数依次为，文字大小|偏转度|横坐标|纵坐标|文字颜色|文字类型|文字内容 
	} 
	elseif($markimage!=null) 
	{ 
		$wimage_data = GetImageSize($markimage); 
		$wimage=create($markimage,$wimage_data[2]);
		imagecopy($ni,$wimage,500,560,0,0,88,31); //写入图片水印,水印图片大小默认为88*31 
		imagedestroy($wimage); 
	} 
	ImageJpeg($ni,$dstFile,$rate); 
	//ImageJpeg($ni,$srcFile,$rate); 
	imagedestroy($im); 
	imagedestroy($ni); 
} 
function create($src,$imagetype)
{
	switch($imagetype)
	{
		case 1 :
		$im = imagecreatefromgif($src);		break;
		case 2 :
		$im = imagecreatefromjpeg($src);	break;
		case 3 :
		$im = imagecreatefrompng($src);		break;
		case 4:
		$im=  imagecreatefromgd($src);
	}
	return $im;
}
?>