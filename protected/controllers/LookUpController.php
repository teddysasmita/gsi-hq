<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LookUpController
 *
 * @author teddy
 */
class LookUpController extends Controller {
   //put your code here
   
	public function actionGetModel($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()->selectDistinct('modelname')->from('itemmodels')
			->where('modelname like :p_model', array(':p_model'=>'%'.$term.'%'))
			->order('modelname')
			->queryColumn();
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$data[$key]=$value;
				}
			} else
				$data[0]='NA';
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
        };
		
	}	
	
	public function actionGetBrand($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()->selectDistinct('brandname')->from('itembrands')
			->where('brandname like :p_brand', array(':p_brand'=>'%'.$term.'%'))
			->order('brandname')
			->queryColumn();
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$data[$key]=$value;
				}
			} else
				$data[0]='NA';
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};	
	}
	
	public function actionGetObjects($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()->selectDistinct('objectname')->from('itemobjects')
				->where('objectname like :p_objects', array(':p_objects'=>'%'.$term.'%'))
				->order('objectname')
				->queryColumn();
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$data[$key]=$value;
				}
			} else
				$data[0]='NA';
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
        };
	}
	
	public function actionGetItemName($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()->selectDistinct('name')->from('items')
			->where('name like :p_name', array(':p_name'=>'%'.$term.'%'))
			->order('name')
			->limit(12)
			->queryColumn();
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$data[$key]=$value;
				}
			} else
				$data[0]='NA';
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetItemName2($id)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()->selectDistinct('name')->from('items')
			->where('id = :p_id', array(':p_id'=>$id))
			->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetSalesName($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->select('concat(firstname, \' \', lastname) as nama')->from('salespersons')
				->where('firstname like :p_firstname or lastname like :p_lastname', 
						array(':p_firstname'=>'%'.$term.'%', ':p_lastname'=>'%'.$term.'%'))
				->order('nama')
				->queryColumn();
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$data[$key]=$value;
				}
			} else
				$data[0]='NA';
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetSalesID($name)
	{
		if (!Yii::app()->user->isGuest) {
			$name=rawurldecode($name);
			//list($firstname, $lastname) = explode(' ', $name);
			//print_r($firstname); echo "boom";
			//print_r($lastname);
			$data=Yii::app()->db->createCommand()->select('id')->from('salespersons')
			->where("concat(`firstname`, ' ', `lastname`) = :p_name", 
				array(':p_name'=>$name))
			->order('id')
			->queryScalar();
			echo $data;
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	
	public function actionGetWareHouse($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()->select('code')->from('warehouses')
			->where('code like :p_code', array(':p_code'=>'%'.$term.'%'))
			->order('code')
			->queryColumn();
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$data[$key]=$value;
				}
			} else
				$data[0]='NA';
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
   public function actionGetItem($name)
   {
		if (!Yii::app()->user->isGuest) {
	   		$data=Yii::app()->db->createCommand()->selectDistinct('name')->from('items')
	              ->where('name like :itemname', array(':itemname'=>'%'.$name.'%'))
	              ->order('name')
	              ->queryColumn();
	      
	      	if(count($data)) { 
	         	foreach($data as $key=>$value) {
	            	$data[$key]=rawurlencode($value);
	         	}
	      	} else {
	         $data[0]='NA';
	      	}
	      	echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
   }
   
   public function actionGetItem4($name)
   {
   	if (!Yii::app()->user->isGuest) {
   		$data=Yii::app()->db->createCommand()->select('concat(name, \'->\',code)')->from('items')
   		->where('name like :itemname', array(':itemname'=>'%'.$name.'%'))
   		->order('name')
   		->queryColumn();
   		 
   		if(count($data)) {
   			foreach($data as $key=>$value) {
   				$data[$key]=rawurlencode($value);
   			}
   		} else {
   			$data[0]='NA';
   		}
   		echo json_encode($data);
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetItem2($term)
   {
   	if (!Yii::app()->user->isGuest) {
   		$data=Yii::app()->db->createCommand()
   		->select('name as label, id as value')
   		->from('items')
   		->where('name like :p_name',
   				array(':p_name'=>"%$term%"))
   		->limit(10)
   				->queryAll();
   		/*echo Yii::app()->db->createCommand()->select('a.donum, b.id')->from('stockentries a')
   		 ->leftJoin('purchasesreceipts b','b.donum = a.donum' )
   		->where("a.idsupplier = :idsupplier and b.id = NULL", array(':idsupplier'=>$idsupplier))->text;
   		*/
   		echo json_encode($data);
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetItem3($term)
   {
   	if (!Yii::app()->user->isGuest) {
   		$data=Yii::app()->db->createCommand()
   		->select("concat(name, ' - ', id) as label, id as value")
   		->from('items')
   		->where('name like :p_name',
   				array(':p_name'=>"%$term%"))
   				->limit(10)
   				->queryAll();
   		/*echo Yii::app()->db->createCommand()->select('a.donum, b.id')->from('stockentries a')
   		 ->leftJoin('purchasesreceipts b','b.donum = a.donum' )
   		->where("a.idsupplier = :idsupplier and b.id = NULL", array(':idsupplier'=>$idsupplier))->text;
   		*/
   		echo json_encode($data);
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
	public function actionGetItemPrice($iditem)
   	{
		if (!Yii::app()->user->isGuest) {
	   		$data=Yii::app()->db->createCommand()->select('normalprice')->from('sellingprices')
	              ->where('iditem = :p_iditem', array(':p_iditem'=>$iditem))
	              ->order('idatetime desc')
	              ->queryScalar();
	      
	      	if($data==FALSE) { 
	           $data=-1;
	      	}
	      	echo $data;
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
   
   public function actionGetItemID($name)
   {
		if (!Yii::app()->user->isGuest) {  	
			//print_r($name);	
      		$name=rawurldecode($name);
      		$data=Yii::app()->db->createCommand()->select('id')->from('items')
              ->where("name = :p_name", array(':p_name'=>$name))
              ->order('id')
              ->queryScalar();
      		echo $data; 
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
   }
   
   public function actionGetItemID2($namecode)
   {
   	if (!Yii::app()->user->isGuest) {
   		//print_r($name);
   		$namecode=rawurldecode($namecode);
   		$temp=explode('->', $namecode);
   		$data=Yii::app()->db->createCommand()->select('id')->from('items')
   		->where("name = :p_name", array(':p_name'=>$temp[0]))
   		->order('id')
   		->queryScalar();
   		echo $data;
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetWareHouseID($name)
   {
   	if (!Yii::app()->user->isGuest) {
   		//print_r($name);
   		$name=rawurldecode($name);
   		$data=Yii::app()->db->createCommand()->select('id')->from('warehouses')
   		->where('code = :p_code', array(':p_code'=>$name))
   		->order('id')
   		->queryScalar();
   		echo json_encode($data);
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetUndonePO($idsupplier)
   {
		if (!Yii::app()->user->isGuest) {
      		$idsupplier=rawurldecode($idsupplier);
      		$data=Yii::app()->db->createCommand()->select('id, regnum')->from('purchasesorders')
         		->where("status <> '2' and idsupplier = :p_idsupplier", array(':p_idsupplier'=>$idsupplier))
         		->queryAll();
      		echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
   }
   
   public function actionGetUnsettledPO($idsupplier)
   {
   		if (!Yii::app()->user->isGuest) {
   			$idsupplier=rawurldecode($idsupplier);
      		$data=Yii::app()->db->createCommand()->select('id, regnum')->from('purchasesorders')
         		->where("paystatus <> '2' and idsupplier = :idsupplier", array(':idsupplier'=>$idsupplier))
         		->queryAll();
      		echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
   }        
   
   public function actionGetUndoneDO($idsupplier)
   {
   		if (!Yii::app()->user->isGuest) {	   	
	   		$idsupplier=rawurldecode($idsupplier);
		   	/*echo Yii::app()->db->createCommand()->select('a.donum, b.id')->from('stockentries a')
		   	->leftJoin('purchasesreceipts b','b.donum = a.donum' )
		   	->where("a.idsupplier = :idsupplier and b.id = NULL", array(':idsupplier'=>$idsupplier))->text;
		   	*/			
		   	$data=Yii::app()->db->createCommand()->select('a.donum, c.id')
		   		->from('stockentries a')
		   		->join('purchasesorders b', 'b.regnum = a.transid')
		   		->leftJoin('purchasesreceipts c','c.donum = a.donum' )
		   		->where("b.idsupplier = :p_idsupplier and c.id is NULL",
      				array(':p_idsupplier' => $idsupplier))
		   		->queryAll();
		   	
		   	echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
   }
   
	public function actionGetUserOperation($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->authdb->createCommand()
				->select('description as label, name as value')
				->from('AuthItem')
				->where('type=:p_type and description like :p_desc', 
     				array(':p_type'=>'0', ':p_desc'=>"%$term%"))
				->queryAll();
   		/*echo Yii::app()->db->createCommand()->select('a.donum, b.id')->from('stockentries a')
   		 ->leftJoin('purchasesreceipts b','b.donum = a.donum' )
   		->where("a.idsupplier = :idsupplier and b.id = NULL", array(':idsupplier'=>$idsupplier))->text;
   		*/   		   
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
   		};
	}
   
	public function actionGetUserTask($term)
 	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->authdb->createCommand()
   				->select('description as label, name as value')
   				->from('AuthItem')
   				->where('type=:p_type and description like :p_desc',
   					array(':p_type'=>'1', ':p_desc'=>"%$term%"))
   				->queryAll();
		echo json_encode($data);
   		} else {
   			throw new CHttpException(404,'You have no authorization for this operation.');
   		};
	}
 
	public function actionGetUserRole($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->authdb->createCommand()
			->select('description as label, name as value')
			->from('AuthItem')
			->where('type=:p_type and description like :p_desc',
					array(':p_type'=>'2', ':p_desc'=>"%$term%"))
					->queryAll();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetTrans($id)
	{
		if (!Yii::app()->user->isGuest) {
			$prefix = substr($id, 0, 2);
			
			if ($prefix == 'RD') {
				$sql=<<<EOS
				select a.id, a.regnum, 'NA' as invnum,
				concat( 'Penukaran Pengiriman Barang - ',a.deliverynum, ' - ', a.receivername, ' - ', a.idatetime) as transinfo,
				'AC34' as transname
				from deliveryreplaces a
				where regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else if ($prefix == 'SJ') {
				$sql=<<<EOS
				select a.id, a.regnum, a.invnum,
				concat( 'Pengiriman Barang - ', a.invnum, ' - ', a.receivername, ' - ', a.idatetime) as transinfo,
				'AC13' as transname
				from deliveryorders a
				where regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'MD') {
				$sql=<<<EOS
				select a.id, a.regnum, 'NA' as invnum,
				concat( 'Permintaan Barang Display - NA - ', concat(b.firstname, ' ', b.lastname), 
					' - ', a.idatetime) as transinfo,
				'AC16' as transname
				from requestdisplays a
				join salespersons b on b.id = a.idsales
				where regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'PB') {
				$sql=<<<EOS
				select a.id, a.regnum, a.invnum,
				concat( 'Pengambilan Barang Pembeli - ',a.invnum,' - ', a.receivername, ' - ', a.idatetime) as transinfo,
				'AC19' as transname
				from orderretrievals a 
				where regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'PR') {
				$sql=<<<EOS
				select a.id, a.regnum, '-' as invnum,
				concat( 'Pengembalian Barang ke Pemasok - ', a.regnum,' - ', concat(c.firstname, ' ', c.lastname), ' - ', a.idatetime) as transinfo,
				'AC50' as transname
				from returstocks a
				join detailreturstocks b on b.id = a.id
				join suppliers c on c.id = a.idsupplier
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'TB') {
				$sql=<<<EOS
				select a.id, a.regnum, 'NA' as invnum,
				concat( 'Pemindahan Barang - NA - ', b.code, ' - ', a.idatetime) as transinfo,
				'AC18' as transname
				from itemtransfers a
				join warehouses b on b.id = a.idwhsource
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'SM') {
				$sql=<<<EOS
				select a.id, a.regnum, 'NA' as invnum,
				concat( 'Pengiriman Barang Tanpa Transaksi - ',a.receivername,' - ', a.idatetime) as transinfo,
				'AC14' as transname
				from deliveryordersnt a
				join detaildeliveryordersnt b on b.id = a.id
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'FB') {
				$sql=<<<EOS
				select a.id, a.regnum, a.invnum,
				concat( 'Pembatalan Faktur - ',a.invnum,' - ', a.idatetime) as transinfo,
				'AC23' as transname
				from salescancel a
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'FG') {
				$sql=<<<EOS
				select a.id, a.regnum, a.invnum,
				concat( 'Ganti Barang Faktur - ',a.invnum,' - ', a.idatetime) as transinfo,
				'AC24' as transname
				from salesreplace a
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'RE') {
				$sql=<<<EOS
				select a.id, a.regnum, a.retrievalnum, '-' as invnum, 
				concat( 'Penukaran Pengambilan Barang - ',a.retrievalnum,' - ', a.idatetime) as transinfo,
				'AC29' as transname
				from retrievalreplaces a
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'KS') {
				$sql=<<<EOS
				select a.id, a.regnum, '-' as invnum,
				concat( 'Pengiriman Barang utk Perbaikan - ',a.regnum,' - ', a.idatetime) as transinfo,
				'AC25' as transname
				from sendrepairs a
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'KR') {
				$sql=<<<EOS
				select a.id, a.regnum, '-' as invnum,
				concat( 'Penerimaan Barang dari Perbaikan - ',a.regnum,' - ', a.idatetime) as transinfo,
				'AC33' as transname
				from receiverepairs a
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else if ($prefix == 'PO') {
				$sql=<<<EOS
				select a.id, a.regnum, '-' as invnum,
				concat( 'Pemesanan Barang ke Pemasokb- ',a.regnum,' - ', a.idatetime) as transinfo,
				'AC2' as transname
				from purchasesorders a
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			}
			else {
				$sql=<<<EOS
				select a.id, a.regnum,
				concat( 'Penerimaan Barang - ', b.firstname, ' ', b.lastname, ' - ', a.idatetime) as transinfo,
				'AC12' as transname
				from purchasesstockentries a
				join suppliers b on b.id = a.idsupplier
				where regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			}
			
			if ($data !== FALSE)
				echo json_encode($data);
			else 
				echo json_encode(array(array('id'=>'NA')));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetBankName($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
			->select('name')
			->from('salesposbanks')
			->where('name like :p_name',
					array(':p_name'=>"%$term%"))
					->queryColumn();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
		
	}
	
	public function actionGetBankID($name)
	{
		$name=rawurldecode($name);
		
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
			->select('id')
			->from('salesposbanks')
			->where('name = :p_name',
					array(':p_name'=>$name))
					->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionGetSCAddress($id)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
			->select('address')
			->from('servicecenters')
			->where('id = :p_id',
					array(':p_id'=>$id))
					->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionCheckItemSerial($iditem, $serialnum, $idwh, $avail = '1')
	{
		//$iditem=rawurldecode($iditem);
		//$serialnum=rawurldecode($serialnum);
		
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->select('count(*) as total')
				->from('stockentries a')
				->join('detailstockentries b', 'b.id = a.id')
				->where('b.iditem = :p_iditem and b.serialnum = :p_serialnum and a.idwarehouse = :p_idwh',
					array(':p_iditem'=>$iditem, ':p_serialnum'=>$serialnum, ':p_idwh'=>$idwh))
				/*->where('b.iditem = :p_iditem and b.serialnum = :p_serialnum',
					array(':p_iditem'=>$iditem, ':p_serialnum'=>$serialnum))*/
				->queryScalar();
			if ($data == FALSE) {
				$data=Yii::app()->db->createCommand()
					->select('count(*) as total')
					->from('wh'.$idwh.' a')
					->where('a.iditem = :p_iditem and a.serialnum = :p_serialnum and a.avail = :p_avail',
						array(':p_iditem'=>$iditem, ':p_serialnum'=>$serialnum, ':p_avail'=>$avail))
					/*->where('a.iditem = :p_iditem and a.serialnum = :p_serialnum',
				 		array(':p_iditem'=>$iditem, ':p_serialnum'=>$serialnum))*/
						->queryScalar();
			} 
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionCheckSerial($serialnum)
	{
		$idwh=rawurldecode($idwh);
		$serialnum=rawurldecode($serialnum);
		$result = 1;
		
		if (!Yii::app()->user->isGuest) {
			$idwarehouses = Yii::app()->db->createCommand()
				->select('id')->from('warehouses')
				->queryColumn();
			foreach ($idwarehouses as $idwh) {
				$data=Yii::app()->db->createCommand()
					->select('iditem, avail, status')
					->from('wh'.$idwh.' a')
					->where('a.serialnum = :p_serialnum',
							array(':p_serialnum'=>$serialnum))
				->queryRow();
				if ($data == false) 
					$result = 1;
				else if ($data['avail'] == '0'){
					if ($data['status'] == '1')
						$result = 2;
					else if ($data['status'] == '0')
						$result = 3;
				} else if ($data['avail'] == '1'){
					if ($data['status'] == '1')
						$result = 4;
					else if ($data['status'] == '0')
						$result = 5;
				}
				if ($result > 1)
					break;
			}	
			echo json_encode($result);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionCheckSerial2($serialnum, $iditem)
	{
		//$idwh=rawurldecode($idwh);
		$serialnum=rawurldecode($serialnum);
		$result = 1;
		
		if (!Yii::app()->user->isGuest) {
			$idwarehouses = Yii::app()->db->createCommand()
				->select('id')->from('warehouses')
				->queryColumn();
			foreach ($idwarehouses as $idwh) {
				$data=Yii::app()->db->createCommand()
					->select('iditem, avail, status')
					->from('wh'.$idwh.' a')
					->where('a.serialnum = :p_serialnum',
							array(':p_serialnum'=>$serialnum))
					->queryRow();
				if ($data == false)
					$result = 1;
				else if ($data['iditem'] == $iditem ) {
					if ($data['avail'] == '0')
						$result = 2;
					else if ($data['avail'] == '1' ) 
						$result = 3;
				} else if ($data['iditem'] !== $iditem ) {
					if ($data['avail'] == '0')
						$result = 4;
					else if ($data['avail'] == '1' ) 
						$result = 5;
				}
				if ($result > 1)
					break;
			}
			echo json_encode($result);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionGetExitedItemFromSerial($serialnum, $retrievalnum)
	{
		//$invnum = rawurldecode($invnum);
		$serialnum = rawurldecode($serialnum);
		
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->select('b.iditem, c.name')
				->from('stockexits a')
				->join('detailstockexits b', 'b.id = a.id')
				->join('items c', 'c.id = b.iditem')
				->where('b.serialnum = :p_serialnum and a.transid = :p_transid',
						array(':p_serialnum'=>$serialnum, ':p_transid'=>$retrievalnum))
				->queryRow();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionCheckItemQty($iditem, $idwh)
	{
		//$invnum = rawurldecode($invnum);
		//$serialnum = rawurldecode($serialnum);
	
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->select('count(*)')
				->from('wh'.$idwh)
				->where('iditem = :p_iditem and avail = :p_avail and status = :p_status',
					array(':p_iditem'=>$iditem, 'p_avail'=>'1', 'p_status'=>'1'))
				->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionCheckItemSerial2($iditem, $serialnum)
	{
		//$idwh=rawurldecode($idwh);
		$serialnum=rawurldecode($serialnum);
	
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->select('count(*)')
				->from('detailstockentries')
				->where('serialnum = :p_serialnum and iditem <> :p_iditem',
					array(':p_serialnum'=>$serialnum, ':p_iditem'=>$iditem))
					/*->where('a.iditem = :p_iditem and a.serialnum = :p_serialnum',
					 array(':p_iditem'=>$iditem, ':p_serialnum'=>$serialnum))*/
				->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionGetCashboxes($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
			->selectDistinct("concat(a.accountnum, ' -> ', a.name) as label, a.id as value")
			->from('cashboxes a')
			->where('a.name like :p_name', array(':p_name'=>$term.'%'))
			->order('a.name')
			->queryAll();
	
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetExpenseName($id)
	{
		$name=rawurldecode($id);
	
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
			->select("concat(accountnum, ' -> ', name)")
			->from('expenses')
			->where('id = :p_id',
					array(':p_id'=>$id))
					->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionGetExpenses($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
			->selectDistinct("concat(a.accountnum, ' -> ', a.name) as label, a.id as value")
			->from('expenses a')
			->where('a.name like :p_name', array(':p_name'=>$term.'%'))
			->order('a.name')
			->queryAll();
	
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetCashboxName($id)
	{
		$name=rawurldecode($id);
	
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
			->select("concat(accountnum, ' -> ', name)")
			->from('cashboxes')
			->where('id = :p_id',
					array(':p_id'=>$id))
					->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	
}
