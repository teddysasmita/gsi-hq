<?php
/* @var $this DisplayentriesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar',
);

$this->menu=array(
   array('label'=>'Tambah Data', 'url'=>array('create')),
   array('label'=>'Pencarian Data', 'url'=>array('admin')),
   array('label'=>'Data yang telah dihapus', 'url'=>array('deleted')),
);
?>

<h1>Barang Masuk Display</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
