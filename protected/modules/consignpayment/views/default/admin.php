<?php
/* @var $this ConsignpaymentsController */
/* @var $model Consignpayments */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Pencarian Data',
);

$this->menu=array(
	array('label'=>'Daftar', 'url'=>array('index')),
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#stockentries-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Pembayaran Konsinyasi</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'consignpayments-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		'regnum',
		'idatetime',
		array(
			'name'=>'idsupplier',
			'value'=>"lookup::SupplierNameFromSupplierID(\$data['idsupplier'])"
		),
		array(
			'name'=>'total',
			'type'=>'number'
		),
		array(
			'name'=>'discount',
			'type'=>'number'
		),
		array(
			'name'=>'labelcost',
			'type'=>'number'
		),
		'status',
		array(
			'name'=>'userlog',
			'value'=>"lookup::UserNameFromUserID(\$data['userlog'])",
		),
		'datetimelog',
         /*   'idpurchaseorder',
		
		'discount',
		
		'remark',
		'userlog',
		'datetimelog',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
