<?php
/* @var $this DetailstockdamageController */
/* @var $model Detailstockdamage */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Tambah Data'=>array('default/create','id'=>$model->id),
      'Tambah Detil'); 
else if ($master=='update')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Ubah Data'=>array('default/update','id'=>$model->id),
      'Tambah Detil');

?>

<h1>Detil Barang Rusak</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'idwh'=>$idwh, 'mode'=>'Create')); ?>