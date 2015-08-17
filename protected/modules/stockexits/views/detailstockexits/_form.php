<?php
/* @var $this DetailstockexitsController */
/* @var $model Detailstockexits */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
   
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailstockexits-form',
	'enableAjaxValidation'=>true,
   ));

$supplierScript=<<<EOS
	$('input,select').keypress(function(event) { return event.keyCode != 13; });

      $('#isAccepted').click(function() {
   		if ($('#isAccepted').prop('checked')) {
   			$('#Detailstockexits_serialnum').val('Belum Diterima');
   		}
      });
   		
	$('#Detailstockexits_serialnum').change(function() {
   		var myserialnum = $('#Detailstockexits_serialnum').val();
   		if (myserialnum !== 'Belum Diterima') {
   			$('#isAccepted').prop('checked', false);
   			$.getJSON('index.php?r=LookUp/checkSerial', 
   				{ serialnum: escape($('#Detailstockexits_serialnum').val())},
   				function(data) {
   				if ((data == 1)) {
   					$('#status').removeClass('money');
   					$('#status').addClass('error');
   					$('#status').html('Nomor seri belum terdaftar');
   					$('#Detailstockexits_status').val('');
   				} if ((data.avail == 2) || (data.avail == 3)) {
   					$('#status').removeClass('money');
   					$('#status').addClass('error');
   					$('#status').html('Barang sudah keluar');
   					$('#Detailstockexits_status').val('');
   				} else if (data == 4) {
   					$('#Detailstockexits_status').val("1");
   					$('#status').removeClass('error');
   					$('#status').addClass('money');
   					$('#status').html('Bagus');
   				} else if (data == 5) {
   					$('#Detailstockexits_status').val("0");
   					$('#status').removeClass('error');
   					$('#status').addClass('money');
   					$('#status').html('Rusak');
   				}				
   			});
		};
	});
	
   	$('#myButton').click(
   		function(evt) {
   			var myserialnum = $('#Detailstockexits_serialnum').val();
   			if (myserialnum !== 'Belum Diterima') {
	   			$.getJSON('index.php?r=LookUp/checkSerial', 
   				{ serialnum: escape($('#Detailstockexits_serialnum').val())},
   				function(data) {
   				if ((data == 1)) {
   					$('#status').removeClass('money');
   					$('#status').addClass('error');
   					$('#status').html('Nomor seri belum terdaftar');
   					$('#Detailstockexits_status').val('');
   				} if ((data.avail == 2) || (data.avail == 3)) {
   					$('#status').removeClass('money');
   					$('#status').addClass('error');
   					$('#status').html('Barang sudah keluar');
   					$('#Detailstockexits_status').val('');
   				} else if (data == 4) {
   					$('#Detailstockexits_status').val("1");
   					$('#status').removeClass('error');
   					$('#status').addClass('money');
   					$('#status').html('Bagus');
   				} else if (data == 5) {
   					$('#Detailstockexits_status').val("0");
   					$('#status').removeClass('error');
   					$('#status').addClass('money');
   					$('#status').html('Rusak');
   				}				
   			});
   			} else {
   				$('#Detailstockexits_serialnum_em_').html('');
				$('#Detailstockexits_serialnum_em_').prop('style', 'display:none');
   				$('#detailstockexits-form').submit();
   			}
   	});
   		
EOS;
   Yii::app()->clientScript->registerScript("supplierScript", $supplierScript, CClientscript::POS_READY);
   
 ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php 
	echo $form->errorSummary($model); ?>
        
        <?php 
         echo $form->hiddenField($model,'iddetail');
         echo $form->hiddenField($model,'id');
         echo $form->hiddenField($model,'userlog');
         echo $form->hiddenField($model,'datetimelog');
         echo $form->hiddenField($model,'iditem');
         echo $form->hiddenField($model,'status');
         echo CHtml::hiddenField('idwh',$idwh);
        ?>

	<div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
		<?php 
			echo CHtml::tag('span', array('id'=>'itemname'), lookup::ItemNameFromItemID($model->iditem), true);
        ?>
		<?php echo $form->error($model,'iditem'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'serialnum'); ?>
		<?php echo $form->textField($model,'serialnum'); ?>
		<?php echo $form->error($model,'serialnum'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('Belum Diterima', false); ?>
		<?php 
			echo CHtml::checkBox('isAccepted', $model->serialnum == 'Belum Diterima'); 
		?>
	</div>
        
    <div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
			echo CHtml::tag('span', array('id'=>'status', 'class'=>'error'), $error);
		?>		
		<?php echo $form->error($model,'status'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::Button($mode, array('id'=>'myButton')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->