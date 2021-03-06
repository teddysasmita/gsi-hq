<?php
/* @var $this PurchasesstockentriesController */
/* @var $model Purchasesstockentries */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>21,'maxlength'=>21)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'regnum'); ?>
		<?php echo $form->textField($model,'regnum',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'idatetime'); ?>
		<?php echo $form->textField($model,'idatetime',array('size'=>19,'maxlength'=>19)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'idsupplier'); ?>
		<?php 
			//echo $form->textField($model,'idsupplier',array('size'=>21,'maxlength'=>21)); 
			$suppliersdata = Yii::app()->db
				->createCommand("select id, concat(firstname, ' ', lastname) as sname from suppliers order by firstname, lastname")
				->queryAll();
			
			$suppliersdata = CHtml::listData($suppliersdata, 'id', 'sname');
			echo $form->dropDownList($model, 'idsupplier', $suppliersdata, 
				array('empty'=>'Harap Pilih'));	
		?>
	</div>
   
      <div class="row">
		<?php echo $form->label($model,'ponum'); ?>
		<?php echo $form->textField($model,'ponum',array('size'=>12,'maxlength'=>12)); ?>
	</div>
   
     <div class="row">
		<?php echo $form->label($model,'sjnum'); ?>
		<?php echo $form->textField($model,'sjnum',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ppn'); ?>
		<?php 
		echo $form->dropDownList($model, 'ppn', array('0'=>'Tidak', '1'=>'Ya'),
         	array('empty'=>'Harap Pilih'));
		?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'userlog'); ?>
		<?php echo $form->textField($model,'userlog',array('size'=>21,'maxlength'=>21)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'datetimelog'); ?>
		<?php echo $form->textField($model,'datetimelog',array('size'=>19,'maxlength'=>19)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->