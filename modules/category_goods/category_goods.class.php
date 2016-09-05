<?php
require_once ROOT.'/include/tb.class.php';
class category_goods extends tb
{	
	public function __construct()
    {  
		$this->table='category_goods';
		$this->fields=array('cate_id','goods_id');
		parent::__construct();
    }
	
	function getlist($data = array())
	{
		global $pager;
		$_select="g.*";	
		$_order=isset($data['order'])?' order by '.$data['order']:'';
		$where=isset($data['where'])?" where ".$data['where']:'';	
			
		$sql = "select SELECT from `{category_goods}` cg left join `{goods}` g on cg.goods_id=g.goods_id   
		{$where} ORDER LIMIT";

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
	
	
	
	
}
?>