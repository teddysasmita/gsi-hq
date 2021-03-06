   <?php
/* @var $this PurchasespaymentsController */
/* @var $model Purchasespayments */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
	$suppliers=Yii::app()->db->createCommand()
		->select('id, firstname, lastname')
		->from('suppliers')
		->order('firstname, lastname')
		->queryAll();
	foreach($suppliers as $row) {
		$supplierids[]=$row['id'];
		$suppliernames[]=$row['firstname'].' '.$row['lastname'];
	}
	$supplierids=CJSON::encode($supplierids);
	$suppliernames=CJSON::encode($suppliernames);
	$returlist = CJSON::encode(Yii::app()->session['Detailpurchasespayments2']);
	
	$supplierScript=<<<EOS
      var supplierids=$supplierids;
      var suppliernames=$suppliernames;
      $('#Purchasespayments_suppliername').change(function() {
         var activename=$('#Purchasespayments_suppliername').val();
         $('#Purchasespayments_idsupplier').val(
            supplierids[suppliernames.indexOf(activename)]);
      });
   	
      $('#searchUnsettledPO').click(
         function(event) {
            $('#command').val('setSupplier');
            mainform=$('#purchasespayments-form');
            mainform.submit();
            event.preventDefault();
         }
      );   
			
		$(".updateDetail").click(
			function(event) {
			$("#command").val("adddetail");
            mainform=$('#purchasespayments-form');
            mainform.submit();
		});
	
		$(".updateDetail2").click(
			function(event) {
			$("#command").val("adddetail2");
            mainform=$('#purchasespayments-form');
            mainform.submit();
		});
	
	$("#Purchasespayments_discount").change(function() {
		var disc = $("#Purchasespayments_discount").val();
		var labelcost = $("#Purchasespayments_labelcost").val();
		var total = $("#total").val();
		if ( disc < 0 ) {
			
			disc = - disc * total / 100;
			$("#Purchasespayments_discount").val(disc);
			$("#Purchasespayments_total").val(total - disc - labelcost);
		}
		$("#Purchasespayments_total").val(total - disc - labelcost);
		$("#labeltotal").html(total - disc - labelcost);
		$("#labeltotal").addClass("money");
	});
	
	$("#Purchasespayments_labelcost").change(function() {
		var disc = $("#Purchasespayments_discount").val();
		var labelcost = $("#Purchasespayments_labelcost").val();
		var total = $("#total").val();
		if ( disc < 0 ) {
			disc = - disc * total / 100;
			$("#Purchasespayments_discount").val(disc);
			$("#Purchasespayments_total").val(total - disc - labelcost);
		}
		$("#Purchasespayments_total").val(total - disc - labelcost);
		$("#labeltotal").html(total - disc - labelcost);
		$("#labeltotal").addClass("money");
	});
EOS;
   Yii::app()->clientScript->registerScript("supplierScript", $supplierScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'purchasespayments-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/purchasespayment/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'purchasespayments-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/purchasespayment/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo CHtml::hiddenField('commandinfo', '', array('id'=>'commandinfo'));
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'idsupplier');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
        echo $form->hiddenField($model, 'status');
        echo $form->hiddenField($model, 'regnum');
      ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Purchasespayments[idatetime]',
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
		<?php echo $form->labelEx($model,'idsupplier'); ?>
		<?php 
         $suppliers=Yii::app()->db->createCommand()
            ->select("id,firstname,lastname")
            ->from("suppliers")
            ->order("firstname, lastname")   
            ->queryAll();
         foreach($suppliers as $row) {
            $suppliername[]=$row['firstname'].' '.$row['lastname'];
         }
         $this->widget("zii.widgets.jui.CJuiAutoComplete", array(
             'name'=>'Purchasespayments_suppliername',
             'source'=>$suppliername,
           'value'=>lookup::SupplierNameFromSupplierID($model->idsupplier)
         ));
         echo CHtml::button('Cari Nota & Retur', array( 'id'=>'searchUnsettledPO'));   
      ?>
		<?php echo $form->error($model,'idsupplier'); ?>
	</div>
   
   <div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
         <?php 
            echo $form->textArea($model, 'remark', array('rows'=>6, 'cols'=>50));
         ?>
         <?php echo $form->error($model,'remark'); ?>
	</div>
	
	 <div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
 		<?php echo CHtml::label(lookup::paymentStatus($model->status), false);?>
         <?php echo $form->error($model,'status'); ?>
	</div>
      
<?php 
    if (isset(Yii::app()->session['Detailpurchasespayments'])) {
       $rawdata=Yii::app()->session['Detailpurchasespayments'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailpurchasespayments where id='$model->id'")
            ->queryScalar();
       $sql="select * from detailpurchasespayments where id='$model->id'";
       $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata, array(
          'totalItemCount'=>$count,
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider,
            'columns'=>array(
            	array(
					'header'=>'Nomor LPB',
					'name'=>'idpurchasestockentry',
					'value'=>"lookup::PurchasesStockEntryNumFromID(\$data['idpurchasestockentry'])"
				),
				array(
					'header'=>'Catatan',
					'type'=>'ntext',
					'name'=>'remark',
				),
				array(
					'header'=>'Total',
            		'type'=>'number',
            		'name'=>'total',
            	),
				array(
					'header'=>'Terbayar',
					'type'=>'number',
					'name'=>'paid',	
				),
				array(
					'header'=>'Dibayar',
					'type'=>'number',
					'name'=>'amount',
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
                  'updateButtonUrl'=>"Action::decodeUpdateDetailPurchasesPaymentUrl(\$data)",
               	'updateButtonOptions'=>array('class'=>"updateDetail"),
               )
          ),
    ));
    
?>

<?php 
    if (isset(Yii::app()->session['Detailpurchasespayments2'])) {
       $rawdata2=Yii::app()->session['Detailpurchasespayments2'];
       $count=count($rawdata2);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailpurchasespayments2 where id='$model->id'")
            ->queryScalar();
       $sql="select * from detailpurchasespayments2 where id='$model->id'";
       $rawdata2=Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata2, array(
          'totalItemCount'=>$count,
    	'pagination'=>false,
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider,
            'columns'=>array(
               	array(
					'header'=>'No. Retur',
					'name'=>'idpurchaseretur',
					'value'=>"lookup::PurchasesReturInfoFromID(\$data['idpurchaseretur'])"
				),
				array(
					'header'=>'Total',
					'type'=>'number',
					'name'=>'total',
				),
               array(
                  	'class'=>'CCheckBoxColumn',
					'header'=>'Pilih',
					'selectableRows'=>2,
					'headerTemplate'=>'<span> Pilih {item}</span>',
					'value'=>"\$data['iddetail']",
					'checked'=>"lookup::RepairCheck(\$data)",
               		'htmlOptions'=>array('class'=>'updateDetail2'),
				),
          ),
    ));
    
?>
	<div class="row">
      <?php echo CHtml::label('SubTotal', 'false'); ?>
      <?php 
         echo CHtml::label(number_format($model->total + $model->discount),'false', 
            array('class'=>'money'));
         echo CHtml::hiddenField('total', $model->total + $model->discount,
         	array('id'=>'total')); 
      ?>
   </div>
   
   <div class="row">
      <?php echo $form->labelEx($model,'discount'); ?>
      <?php echo $form->textField($model, 'discount'); ?>
      <?php echo $form->error($model,'discount'); ?>
   </div>
   	   
	<div class="row">
      <?php echo $form->labelEx($model,'total'); ?>
      <?php 
         echo CHtml::label(number_format($model->total),'false', 
            array('class'=>'money', 'id'=>'labeltotal')); 
         echo $form->hiddenfield($model, 'total');
      ?>
   </div>
	
   
<?php 
    if (isset(Yii::app()->session['Detailpurchasespayments3'])) {
       $rawdata3=Yii::app()->session['Detailpurchasespayments3'];
       $count=count($rawdata3);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from payments where idtransaction='$model->id'")
            ->queryScalar();
       $sql="select * from payments where idtransaction='$model->id'";
       $rawdata3 = Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata3, array(
          'totalItemCount'=>$count,
    	'pagination'=>false,
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider,
            'columns'=>array(
               	array(
					'header'=>'Metode',
					'name'=>'method',
               		'value'=>"lookup::getMethod(\$data['method'])"
				),
				array(
					'header'=>'Jumlah',
					'type'=>'number',
					'name'=>'amount',
				),
            	array(
            		'class'=>'CButtonColumn',
            		'buttons'=> array(
            			'view'=>array(
            				'visible'=>'false'
            			)
            		),
            		'updateButtonUrl'=>"Action::decodeUpdateDetailPurchasesPayment3Url(\$data)",
            		'deleteButtonUrl'=>"Action::decodeDeleteDetailPurchasesPayment3Url(\$data)",
            	)
          ),
    ));
    
?>
   
   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->