   <?php
/* @var $this StockexitsController */
/* @var $model Stockexits */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   $transScript=<<<EOS
		$('#Stockexits_transid').change(
		function() {
			$.getJSON('index.php?r=LookUp/getTrans',{ id: $('#Stockexits_transid').val() },
            function(data) {
				if (data[0].id !== 'NA') {
					$('#Stockexits_transname').val(data[0].transname);
					$('#transinfo').html(data[0].transinfo);
            		$('#Stockexits_transinfo').val(data[0].transinfo);
					$('#Stockexits_donum').val(data[0].invnum);
            		$('#command').val('getPO');
					$('#Stockexits_transinfo_em_').prop('style', 'display:none')
					$('#stockexits-form').submit();
				} else {
					$('#Stockexits_transname').val();
					$('#transinfo').html('');
            		$('#Stockexits_transinfo_em_').html('Data tidak ditemukan');
					$('#Stockexits_transinfo_em_').prop('style', 'display:block')
				}
			})
		});
		$('.updateButton').click(
		function(evt) {
			$('#command').val('updateDetail');
			$('#detailcommand').val(this.href);
			$('#stockexits-form').submit();
		}); 
		
		var localstream = null;
		
		navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || 
					navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia;
	 
		if (navigator.getUserMedia) {       
	    	navigator.getUserMedia({video:true}, handleVideo, captureError);
		}
		
		$('#captureButton').click(
			function(evt) {
				captureImage(localstream);
		});
		
				
		function captureImage(stream) {
			var myvideo = document.querySelector("#myvideo");
			var mycanvas = document.querySelector("#mycanvas");
			var myctx = mycanvas.getContext("2d");

			myctx.drawImage(myvideo, 0, 0, 320, 240);
			myimage = document.querySelector("#faceid");
			myimage.src = mycanvas.toDataURL('image/jpeg', 0.8);
			
			$("#Stockexits_faceid").val(myimage.src);
		}
		
		function handleVideo(stream) {
			var myvideo = document.querySelector("#myvideo");
			var mycanvas = document.querySelector("#mycanvas");
			
			myvideo.src = window.URL.createObjectURL(stream);
			localstream = stream;
		}
		
		function captureError() {

		}	
		
EOS;
   Yii::app()->clientScript->registerScript("transScript", $transScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
		'id'=>'stockexits-form',
		'enableAjaxValidation'=>true,
		'action'=>Yii::app()->createUrl("/stockexits/default/create"),
      	'htmlOptions'=>array(
			'enctype'=>'multipart/form-data',
		)	
   ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
		'id'=>'stockexits-form',
		'enableAjaxValidation'=>true,
		'action'=>Yii::app()->createUrl("/stockexits/default/update", array('id'=>$model->id)),
      	'htmlOptions'=>array(
      		'enctype'=>'multipart/form-data',
      	)
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo CHtml::hiddenField('detailcommand', '', array('id'=>'detailcommand'));
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'transname');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
        echo $form->hiddenField($model, 'regnum');
        echo $form->hiddenField($model, 'transinfo');
        echo $form->hiddenField($model, 'donum');
        echo $form->hiddenField($model, 'faceid');
        //echo $form->fileField($model, 'faceid', array('id'=>'Stockexits_faceid', 'style'=>'display:none'));
      ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Stockexits[idatetime]',
                     // additional javascript options for the date picker plugin
                  'options'=>array(
                     'showAnim'=>'fold',
                     'dateFormat'=>'yy/mm/dd',
                     'defaultdate'=>$model->idatetime
                  ),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->idatetime,
               ));
            ?>
		<?php echo $form->error($model,'idatetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idwarehouse'); ?>
         <?php 
			$warehouses = lookup::WarehouseNameFromIpAddr($_SERVER['REMOTE_ADDR']);
         	if (count($warehouses) > 1) {
				$data = CHtml::listData($warehouses, 'id', 'code');
         		echo $form->dropDownList($model, 'idwarehouse', $data, 
					array('empty'=>'Harap Pilih'));
         	} else if (count($warehouses) == 1) {
				echo CHtml::hiddenField('Stockexits[idwarehouse]', $warehouses[0]['id']);
				echo CHtml::label($warehouses[0]['code'],'false', array('class'=>'money')); 
			} else {
				echo CHtml::tag('span', array('class'=>'error'), 'Tidak Terdaftar');
			}
         ?>
	</div>
		
	<div class="row">
		<?php echo $form->labelEx($model,'donum'); ?>
        <?php
			echo CHtml::label($model->donum, false); 
           //echo $form->textField($model, 'donum', array('maxlength'=>50)); 
        ?>
        <?php echo $form->error($model,'donum');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'transid'); ?>
		<?php 
			echo $form->textField($model,'transid', array('maxlength'=>30));
		?>
		<?php echo $form->error($model,'transid'); ?>
	</div>
	
    <div class="row">
		<?php echo CHtml::label('Info', false); ?>
		<?php 
			echo CHtml::label($model->transinfo, false, 
				array('id'=>'transinfo','width'=>'200px'));
		?>
		<?php echo $form->error($model,'transinfo'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
        <?php 
           echo $form->textArea($model, 'remark', array('COLS'=>40, 'ROWS'=>5)); 
        ?>
        <?php echo $form->error($model,'remark');?> 
	</div>
    
    <canvas width="320px" height="240px" id="mycanvas" style="display:none"></canvas>
    <video width="320px" height="240px" id="myvideo" style="display:inline" autoplay></video>    
    <div class="row">
		<?php echo $form->labelEx($model,'faceid'); ?>
        
        <image id="faceid" width="320px" height="240px" src="">
        <?php echo CHtml::button('Capture', array('id'=>'captureButton')); ?>
        <?php echo $form->error($model,'faceid');?> 
	</div>
<?php 
    if (isset(Yii::app()->session['Detailstockexits'])) {
       $rawdata=Yii::app()->session['Detailstockexits'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailstockexits where id='$model->id'")->queryScalar();
       $sql="select * from detailstockexits where id='$model->id'";
       $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata, array(
          'totalItemCount'=>$count,
		  'keyField'=>'iddetail',
    ));
	$boom='biim';
    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider,
            'columns'=>array(
               array(
                   'header'=>'Item Name',
                   'name'=>'iditem',
                   'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
               ),
              array(
                  'header'=>'Nomor Seri',
                  'name'=>'serialnum',
              ),
				array(
					'header'=>'Tersedia',
					'name'=>'avail',
					'value'=>"lookup::StockStatusName(\$data['status'])",
				),
              array(
                  'class'=>'CButtonColumn',
                  'buttons'=> array(
                      'delete'=>array(
                       'visible'=>'false'
                      ),
                     'view'=>array(
                        'visible'=>'false'
                     )
                  ),
				'updateButtonOptions'=>array("class"=>'updateButton'),
                  'updateButtonUrl'=>"Action::decodeUpdateDetailStockExitUrl(\$data, \"$model->idwarehouse\",
					\"$model->transname\", \"$model->transid\")",
              )
          ),
    ));
?>

   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->