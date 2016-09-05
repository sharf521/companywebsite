<?php
require_once ROOT.'/include/tb.class.php';
class site_news extends tb
{	
	public function __construct()
    {  
		$this->table='site_news';
		$this->fields=array('id','user_id','title','categoryid','image','content','createdate','showtimes','status','deltime');
		parent::__construct();
    }
	
	
	function getlist($data = array())
	{
		global $pager;
		$_select=implode(',',$this->fields);		
		$_order=isset($data['order'])?' order by '.$data['order']:'';
		$where=isset($data['where'])?" where ".$data['where']:'';	
			
		$sql = "select SELECT from `".$this->table."` {$where} ORDER LIMIT";
		//змЬѕЪ§	
		$row=$this->mysql->get_one(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		$total = $row['num'];
		
		$epage = empty($data['epage'])?10:$data['epage'];	
		$page=$data['page'];
		if(!empty($page))
		{
			$index = $epage * ($page - 1);	
		}
		else
		{
			$index=0;$page=1;
		}		
		if($index>$total){$index=0;$page=1;}
		$limit = " limit {$index}, {$epage}";
		$list = $this->mysql->get_all(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $limit), $sql));		
		global $pager;
		$pager->page=$page;
		$pager->epage=$epage;
		$pager->total=$total;
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $pager->show()           
        );
	}
	
	function con($data = array())
	{
		
		$_select=" * ";			
		
		$where=isset($data['where'])?" where ".$data['where']:'';
		
		$_order=isset($data['order'])?' order by '.$data['order']:'order by id desc';
		
		$sql = "select SELECT from `{site_news}` {$where} ORDER LIMIT";
		
		$con = $this->mysql->get_one(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $limit), $sql));
		
		return array(
		'con'=>$con,
		);
	}
	function conlist($data = array())
	{
		
		$_select=" * ";			
		
		$where=isset($data['where'])?" where ".$data['where']:'';
		
		$_order=isset($data['order'])?' order by '.$data['order']:'order by id desc';
		
		$sql = "select SELECT from `{site_news}` {$where} ORDER LIMIT";
		
		$conlist = $this->mysql->get_all(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $limit), $sql));
		
		return array(
		'con'=>$conlist,
		);
	}		
}
?>