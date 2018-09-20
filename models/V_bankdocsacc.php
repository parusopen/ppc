<?php

namespace app\models;

use yii\db\Query;

class V_bankdocsacc
{
    public static function getTree($pid = 0)
    {
		
		$tree = null;	
		$sql = '';
		
		if($pid == 0){
			$sql = "select * from v_acatalog where signs=0 and haschildren > 0 and unitcode in ('BankDocuments') order by rn asc";
			$ch_li = '';
		}elseif((int)$pid){
			$sql = "select * from v_acatalog where crn = ".$pid;
			$ch_li = ' style="display:none;" ';
		}
		
		$catalog = \Yii::$app->db->createCommand($sql)->queryAll();	

		if($catalog){
			
			$tree = '<ul>';
			
			foreach($catalog as $kv){
				
				$rec_ = new V_bankdocsacc();
				$tree_rec = $rec_->getTree($kv['rn']);
				
				if($tree_rec){
					$open_ico = '<i class="icon-plus-sign"></i>';
				}else{
					$open_ico = '<i></i>';
				}	
				
				$tree.='<li'.$ch_li.'><span data-id="'.$kv['rn'].'">'.$open_ico.$kv['NAME'].'</span>'.$rec_->getTree($kv['rn']).'</li>';
			}
			
			$tree.='</ul>';
		}
			
        return $tree;
    }
	
	public static function getTreeRelationItems($id)
    {
		$items = \Yii::$app->db->createCommand('select * from v_bankdocsacc where crn = '.$id)->queryAll();
		return json_encode($items);
	}

	public static function getTreeRelationItemsParams($id)
    {
		$items = \Yii::$app->db->createCommand('select * from V_BANKDOCSPEC where nprn = '.$id)->queryAll();	
		return json_encode($items);
	}

	public static function modifyObjects($data)
    {
		if(!isset($data['nb'])){
			$data['nb'] = 0;
		}
		if(!isset($data['nsc'])){
			$data['nsc'] = 0;
		}
		if(!isset($data['nsd'])){
			$data['nsd'] = 0;
		}
		
		if($data['type']==1){
			$object = 'p_doctypes';
		}else{
			$object = 'p_docparams';
		}
		
		if($data['action']==1){
			$action = 'insert';
			if($data['type']==1){
				$func_params = "(".$data['nc'].", ".$data['pid'].", '".$data['dc']."', '".$data['dn']."', '".$data['sc']."', null) as nrn";
			}else{
				$func_params = "(".$data['nc'].", ".$data['pid'].", '".$data['dn']."', '".$data['dc']."', ".$data['nb'].", ".$data['nsc'].", ".$data['nsd'].") as nrn";	
			}	
		}else if($data['action']==2){
			$action = 'update';
			if($data['type']==1){
				$func_params = "(".$data['nc'].", ".$data['id'].", '".$data['dc']."', '".$data['dn']."', '".$data['sc']."')";
			}else{
				$func_params = "(".$data['nc'].", ".$data['pid'].", ".$data['id'].", '".$data['dn']."', ".$data['nb'].", ".$data['nsc'].", ".$data['nsd'].")";
			}	
		}else{
			$action = 'delete';
			if($data['type']==1){
				$func_params = "(".$data['nc'].", ".$data['id'].")";
			}else{
				$func_params = "(".$data['nc'].", ".$data['pid'].", ".$data['id'].")";
			}	
		}
		
		$items = array();
		
		$items = \Yii::$app->db->createCommand("select ".$object."_".$action." ".$func_params."")->queryOne();
		
		$data['success'] = true;
		
		$items = array_merge($items, $data);
		
		return json_encode($items);
	}
	
}  


