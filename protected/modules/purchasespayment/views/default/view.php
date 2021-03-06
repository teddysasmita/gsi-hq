<?php
/* @var $this PurchasespaymentsController */
/* @var $model Purchasespayments */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
   'Lihat Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
      array('label'=>'Sejarah', 'url'=>array('history', 'id'=>$model->id)),
      array('label'=>'Data Detil yang dihapus', 
         'url'=>array('/purchasespayment/detailpurchasespayments/deleted', 'id'=>$model->id)),
);
?>

<h1>Pembayaran pada Pemasok</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		array(
	        'label'=>'Nama Pemasok',
	        'value'=>lookup::SupplierNameFromSupplierID($model->idsupplier)
      	),
		array(
		'label'=>'Total',
		'value'=>number_format($model->total)
		),
		array(
				'label'=>'Diskon',
				'value'=>number_format($model->discount)
		),
		array(
            'label'=>'Userlog',
            'value'=>lookup::UserNameFromUserID($model->userlog),
        ),
		'datetimelog',
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailpurchasespayments where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailpurchasespayments where id='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
      'totalItemCount'=>$count,
   ));
   $this->widget('zii.widgets.grid.CGridView', array(
   'dataProvider'=>$dataProvider,
   'columns'=>array(
      array(
             'header'=>'Nomor Nota',
             'name'=>'idpurchasstockeentry',
             'value'=>"lookup::PurchasesStockEntryNumFromID(\$data['idpurchasestockentry'])"
         ),
      array(
         'header'=>'Total',
         'type'=>'number',
         'name'=>'total',
      ),
      array(
         'header'=>'Diskon',
         'type'=>'number',
         'name'=>'discount',
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
      /*array(
         'class'=>'CButtonColumn',
         'buttons'=> array(
            'delete'=>array(
               'visible'=>'false'
             ),
            'update'=>array(
               'visible'=>'false'
            )
         ),
         'viewButtonUrl'=>"Action::decodeViewDetailPurchaseMemoUrl(\$data)",
      )*/      
   )));
 ?>
 
 <?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailpurchasespayments2 where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailpurchasespayments2 where id='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
      'totalItemCount'=>$count,
   ));
   $this->widget('zii.widgets.grid.CGridView', array(
   'dataProvider'=>$dataProvider,
   'columns'=>array(
      array(
             'header'=>'Nomor Retur',
             'name'=>'idpurchaseretur',
             'value'=>"lookup::PurchasesReturInfoFromID(\$data['idpurchaseretur'])"
         ),
      array(
         'header'=>'Total',
         'type'=>'number',
         'name'=>'total',
      ),
      /*array(
         'class'=>'CButtonColumn',
         'buttons'=> array(
            'delete'=>array(
               'visible'=>'false'
             ),
            'update'=>array(
               'visible'=>'false'
            )
         ),
         'viewButtonUrl'=>"Action::decodeViewDetailPurchaseMemoUrl(\$data)",
      )*/      
   )));
 ?>
 
 <?php 
   $count=Yii::app()->db->createCommand("select count(*) from payments where idtransaction='$model->id'")
      ->queryScalar();
   $sql="select * from payments where idtransaction='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
      'totalItemCount'=>$count,
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
      /*array(
         'class'=>'CButtonColumn',
         'buttons'=> array(
            'delete'=>array(
               'visible'=>'false'
             ),
            'update'=>array(
               'visible'=>'false'
            )
         ),
         'viewButtonUrl'=>"Action::decodeViewDetailPurchaseMemoUrl(\$data)",
      )*/      
   )));
 ?>
 
