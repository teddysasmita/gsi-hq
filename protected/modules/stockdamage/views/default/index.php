<?php
/* @var $this StockdamageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar',
);

$this->menu=array(
   array('label'=>'Tambah Data', 'url'=>array('create')),
   array('label'=>'Pencarian Data', 'url'=>array('admin')),
   array('label'=>'Data yang telah dihapus', 'url'=>array('deleted')),
	array('label'=>'Cetak Laporan', 'url'=>array('stockdamagereport')),
	array('label'=>'Nomor Seri Barang yang Keluar', 'url'=>array('serial')),
		
);
?>

<h1>Barang Rusak</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
