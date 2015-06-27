<?php 

class DetailitemsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
                  //'postOnly + delete', // we only allow deletion via POST request
		);
	}
     
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($iddetail)
	{
         $model=$this->loadModel($iddetail);
         if(($model==NULL)&&isset(Yii::app()->session['Detailitems'])) {
            $model=new Detailitems;
            $model->attributes=$this->loadSession($iddetail);
         }   
         $this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id)
	{
		$model=new Detailitems;
            $master=Yii::app()->session['master'];
            
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
            
            $model->id=$id;  
            $idmaker=new idmaker();
            $model->iddetail=$idmaker->getCurrentID2();
            
            $this->onInsert($model);
            
            if(isset($_POST['Detailitems']))
		{
                  $temp=Yii::app()->session['Detailitems'];
                  $model->attributes=$_POST['Detailitems'];
                  $temp[]=$_POST['Detailitems'];
                  Yii::app()->session['Detailitems']=$temp;
			/*$model->attributes=$_POST['Detailitems'];
			if($model->save())*/
                  if ($master=='create')
                     $this->redirect(array('default/createdetail'));
                  else if($master=='update')
                     $this->redirect(array('default/updatedetail'));
		}
            $this->render('create',array(
			'model'=>$model,'master'=>$master
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($iddetail)
	{
		$model=$this->loadModel($iddetail);
            $master=Yii::app()->session['master'];
            
            if(($model==NULL)&&isset(Yii::app()->session['Detailitems'])) {
               $model=new Detailitems;
               $model->attributes=$this->loadSession($iddetail);
            }

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Detailitems']))
		{
			$temp=Yii::app()->session['Detailitems'];
                  $model->attributes=$_POST['Detailitems'];
                  foreach ($temp as $tk=>$tv) {
                     if($tv['iddetail']==$_POST['Detailitems']['iddetail']) {
                        $temp[$tk]=$_POST['Detailitems'];
                        break;
                     }
                  }
                  Yii::app()->session['Detailitems']=$temp;
			
                  //if($model->save())
                  if ($master=='create')
                     $this->redirect(array('default/createdetail'));
                  else if($master=='update')
                     $this->redirect(array('default/updatedetail'));
		}

		$this->render('update',array(
			'model'=>$model,'master'=>$master
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($iddetail)
	{
		//$this->loadModel($iddetail)->delete();
            $details=Yii::app()->session['Detailitems'];
            foreach ($details as $ik => $iv) {
               if($iv['iddetail']==$iddetail) {
                  if(isset(Yii::app()->session['Deletedetailitems']))
                     $deletelist=Yii::app()->session['Deletedetailitems'];
                  $deletelist[]=$iv;
                  Yii::app()->session['Deletedetailitems']=$deletelist;
                  unset($details[$ik]);
                  break;
               }
            }
            Yii::app()->session['Detailitems']=$details;
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax'])) 
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('items/update', 'id'=>$iv['id']));
       }
             

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Detailitems');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Detailitems('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Detailitems']))
			$model->attributes=$_GET['Detailitems'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Detailitems the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Detailitems::model()->findByPk($id);
		/*
             if($model===null)
               throw new CHttpException(404,'The requested page does not exist.');
		*/
            return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Detailitems $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='detailitems-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
      
      protected function onInsert(& $model)
      {
         $model->iditem='-';
      }
      
      protected function loadSession($iddetail)
      {
         $details=Yii::app()->session['Detailitems'];
         foreach ($details as $row) {
            if($row['iddetail']==$iddetail)
               return $row;
         }
         throw new CHttpException(404,'The requested page does not exist.');
         return NULL;
      }
}
