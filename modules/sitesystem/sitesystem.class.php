<?php
require_once ROOT.'/include/tb.class.php';
class site_system extends tb
{	
	public function __construct()
    {  
		$this->table='site_system';
		$this->fields=array('user_id','name','address','tel','fax','icp','tongji','logo','yuming','banquan','code','rexian','status','qq');
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
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];		
		$pages = ceil($total / $epage);		
		if($page>$pages) $page=$pages;		
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $this->mysql->get_all(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $limit), $sql));
		
		$pager->page=$page;
		$pager->epage=$epage;
		$pager->total=$total;
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $pager->show()           
        );
	}
	
	
}
?>