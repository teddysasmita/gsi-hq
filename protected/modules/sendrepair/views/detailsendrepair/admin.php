<?php
/* @var $this DetailsendrepairsController */
/* @var $model Detailsendrepairs */

$this->breadcrumbs=array(
		'Proses'=>array('/site/proses'),
		'Daftar'=>array('default/index'),
		'Pencarian Data Detil'
);

$this->menu=array(
	array('label'=>'List Detailsendrepairs', 'url'=>array('index')),
	array('label'=>'Create Detailsendrepairs', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#detailsendrepairs-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Detil Pengiriman Barang untuk Perbaikan</h1>

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

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailsendrepairs-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'iddetail',
		'id',
		'iditem',
		'qty',
		'buyprice',
		'sellprice',
		/*
		'price',
		'userlog',
		'datetimelog',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
