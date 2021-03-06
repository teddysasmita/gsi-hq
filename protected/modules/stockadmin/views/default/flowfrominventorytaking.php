<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
		'Proses'=>array('/site/proses'),
		'Daftar'=>array('default/index'),
		'Kartu Stok Berdasar Hasil Opname'
);
?>

<h1><?php echo "Masukkan Kriteria" ?></h1>

<div class="form">
<?php 
	echo CHtml::beginForm("index.php?r=stockadmin/default/flowfrominventorytaking", 'get');	
?>
	
<div class="row">
<?php
echo CHtml::label('ID Barang','iditem');
$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
		'name'=>'iditem',
		'sourceUrl'=>Yii::app()->createUrl('LookUp/getItem3'),
		'htmlOptions'=>array('size'=>50),
		'value'=>$iditem,
));
?>
</div>

<div class="row">
<?php
echo CHtml::label('Gudang', 'whcode');
$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
		'name'=>'whcode',
		'sourceUrl'=>Yii::app()->createUrl('LookUp/getWarehouse'),
		'htmlOptions'=>array('size'=>50),
		'value'=>$whcode
));
?>
</div>

<div class="row">
<?php
echo CHtml::label('Per Tanggal', 'idatetime');
//echo $form->dateField($model,'idatetime',array('size'=>19,'maxlength'=>19)); 
$this->widget('zii.widgets.jui.CJuiDatePicker',array(
	'name'=>'idatetime',
                  // additional javascript options for the date picker plugin
	'options'=>array(
		'showAnim'=>'fold',
		'dateFormat'=>'yy/mm/dd',
		'defaultdate'=>idmaker::getDateTime()
	),
	'htmlOptions'=>array(
		'style'=>'height:20px;',
	),
	'value'=>$idatetime,
));
?> 
</div>

<div class="row">
<?php
echo CHtml::label('Stok Opname', 'idinventorytaking');
//echo $form->dateField($model,'idatetime',array('size'=>19,'maxlength'=>19)); 
$so = Yii::app()->db->createCommand()
	->select('id, operationlabel')->from('inventorytakings')
	->queryAll();
$data = CHtml::listData($so, 'id', 'operationlabel');
echo CHtml::dropDownList('idinventorytaking', $idinventorytaking, $data, array('empty'=>'Harap Pilih'));
?> 
</div>


<div class="row">
<?php 
	echo CHtml::submitButton('Kerjakan',array('name'=>'go'));
?>
</div>

<?php 
	echo CHtml::endForm();
?>
</div> <!-- form -->


<h2><?php echo lookup::ItemNameFromItemID($iditem). " - $iditem" ?></h2>
<h2><?php echo 'Per Tanggal: '. $idatetime; ?></h2>
<h2><?php echo 'Stok Awal: '. number_format($startamount); ?></h2>
	
<?php 

if (isset($alldata)) {
	$mydp = new CArrayDataProvider($alldata, array(
			'keyField'=>'iddetail',
			'pagination'=>array(
				'pageSize'=>20
			),
	));
	$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'quantity-grid',
			'dataProvider'=>$mydp,
			'columns'=>array(
					//'id',
					array(
							'header'=>'Tanggal',
							'name'=>'idatetime',
					),
					array(
							'header'=>'Nomor Urut',
							'name'=>'regnum',
					),
					array(
						'header'=>'Transaksi',
						'class'=>'CLinkColumn',
						'labelExpression'=>"\$data['transid']",
						'urlExpression'=>"lookup::getTrans(\$data)",
					),
					array(
							'header'=>'Total',
							'name'=>'total',
					),
					array(
							'header'=>'Gudang',
							'name'=>'code',
							'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])"
					),
					array(
							'header'=>'Nomor Seri',
							'name'=>'serialnums',
							'type'=>'ntext',
					),
			),
	));
}

?>

<h2>
<?php 
	$mytotal = $startamount;
	foreach($alldata as $data) {
		$mytotal += $data['total'];
	};
	
	echo "Jumlah = ".$mytotal." unit."; 
?>
</h2>
