<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC39';
	public $tracker;
	public $state;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			$this->render('view',array(
				'model'=>$this->loadModel($id),
			));
		} else {
        	throw new CHttpException(404,'You have no authorization for this operation.');
        };	
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
             if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
                    Yii::app()->user->id))  {   
                $this->state='c';
                $this->trackActivity('c');    
                    
                $model=new Pricetagprints;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Pricetagprints'])) {
                   Yii::app()->session['Pricetagprints']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Pricetagprints'];
                }
                
               // Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);

                if (isset($_POST)){
                	if(isset($_POST['yt0'])) {
                      //The user pressed the button;
                      $model->attributes=$_POST['Pricetagprints'];
                       
                      $this->beforePost($model);
                      $respond=true;
                      // && $this->checkSerialNum(Yii::app()->session['Detailpricetagprints']);
                      if ($respond) {
                		$data = $this->getImage();
                		if ($data !== FALSE) {
                			$model->bkjpg = $data;	
                		}
                      	$respond=$model->save();
                         if(!$respond) {
                             throw new CHttpException(404,'There is an error in master posting'. ' '. $model->errors);
                         }

                         if(isset(Yii::app()->session['Detailpricetagprints']) ) {
                           $details=Yii::app()->session['Detailpricetagprints'];
                           $respond=$respond&&$this->saveNewDetails($details);
                         } 

                         if($respond) {
                            $this->afterPost($model);
                            Yii::app()->session->remove('Pricetagprints');
                            Yii::app()->session->remove('Detailpricetagprints');
                            $this->redirect(array('view','id'=>$model->id));
                         } 
                         
                      } else {
                        throw new CHttpException(404,'Nomor Serial telah terdaftar.');
                     }     
                   } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Pricetagprints'];
                         Yii::app()->session['Pricetagprints']=$_POST['Pricetagprints'];
                         $this->redirect(array('detailpricetagprints/create',
                            'id'=>$model->id));
                      } 
                   } 
                }

                $this->render('create',array(
                    'model'=>$model,
                ));
                
             } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
             }
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
          if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                 Yii::app()->user->id))  {

             $this->state='u';
             $this->trackActivity('u');

             $model=$this->loadModel($id);
             $this->afterEdit($model);
             
             Yii::app()->session['master']='update';

             if(!isset(Yii::app()->session['Pricetagprints']))
                Yii::app()->session['Pricetagprints']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Pricetagprints'];

             if(!isset(Yii::app()->session['Detailpricetagprints'])) 
               Yii::app()->session['Detailpricetagprints']=$this->loadDetails($id);
             
             // Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
                     $model->attributes=$_POST['Pricetagprints'];
                     $data = $this->getImage();
                     if ($data !== FALSE) {
                     	$model->bkjpg = $data;
                     }
                     $this->beforePost($model);
                     $this->tracker->modify('pricetagprints', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                     	throw new CHttpException(404,'There is an error in master posting ');
                     }

                     if(isset(Yii::app()->session['Detailpricetagprints'])) {
                         $details=Yii::app()->session['Detailpricetagprints'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     
                     if(isset(Yii::app()->session['DeleteDetailpricetagprints'])) {
                         $deletedetails=Yii::app()->session['DeleteDetailpricetagprints'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                    
                     if($respond) {
                         Yii::app()->session->remove('Pricetagprints');
                         Yii::app()->session->remove('Detailpricetagprints');
                         Yii::app()->session->remove('DeleteDetailpricetagprints');
                         $this->redirect(array('view','id'=>$model->id));
                     }
                 }
             }

             $this->render('update',array(
                     'model'=>$model,
             ));
         }  else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
         if(Yii::app()->authManager->checkAccess($this->formid.'-Delete', 
                 Yii::app()->user->id))  {

            $model=$this->loadModel($id);
            $this->trackActivity('d');
            $this->beforeDelete($model);
            $this->tracker->delete('pricetagprints', $id);

            $detailmodels=Detailpricetagprints::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailpricetagprints', array('iddetail'=>$dm->iddetail));
               $dm->delete();
            }

            $model->delete();
            $this->afterDelete($model);

         // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
         if(!isset($_GET['ajax']))
               $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
     }

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List', 
			Yii::app()->user->id)) {
			$this->trackActivity('l');
			
			$filenames = glob(Yii::app()->assetManager->basePath.'/pricetagprint*');
			foreach($filenames as $f)
				unlink($f);
               
               Yii::app()->session->remove('Pricetagprints');
               Yii::app()->session->remove('Detailpricetagprints');
               Yii::app()->session->remove('DeleteDetailpricetagprints');
               $dataProvider=new CActiveDataProvider('Pricetagprints',
                  array(
                     'criteria'=>array(
                        'order'=>'id desc'
                     )
                  )
               );
               $this->render('index',array(
                     'dataProvider'=>$dataProvider,
               ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
            if(Yii::app()->authManager->checkAccess($this->formid.'-List', 
                Yii::app()->user->id)) {
                $this->trackActivity('s');
               
                $model=new Pricetagprints('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Pricetagprints']))
			$model->attributes=$_GET['Pricetagprints'];

		$this->render('admin',array(
			'model'=>$model,
		));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
	}

         public function actionHistory($id)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $model=$this->loadModel($id);
                $this->render('history', array(
                   'model'=>$model,
                    
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }   
        }
        
        public function actionDeleted()
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $this->render('deleted', array(
         
                    
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }   
        }
        
        public function actionRestore($idtrack)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $this->trackActivity('r');
                $this->tracker->restore('pricetagprints', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Pricetagprints');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }
        
        public function actionRestoreDeleted($idtrack)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $this->trackActivity('n');
                $this->tracker->restoreDeleted('pricetagprints', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Pricetagprints');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }
        
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Pricetagprints the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Pricetagprints::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Pricetagprints $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='pricetagprints-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
      public function actionCreateDetail()
      {
      //this action continues the process from the detail page
         if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
                 Yii::app()->user->id))  {
             $model=new Pricetagprints;
             $model->attributes=Yii::app()->session['Pricetagprints'];

             $details=Yii::app()->session['Detailpricetagprints'];
             $this->afterInsertDetail($model, $details);

             $this->render('create',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         } 
      }
      
      public function actionUpdateDetail()
      {
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                 Yii::app()->user->id))  {

             $model=new Pricetagprints;
             $model->attributes=Yii::app()->session['Pricetagprints'];

             $details=Yii::app()->session['Detailpricetagprints'];
             $this->afterUpdateDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      
      public function actionDeleteDetail()
      {
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                 Yii::app()->user->id))  {


             $model=new Pricetagprints;
             $model->attributes=Yii::app()->session['Pricetagprints'];

             $details=Yii::app()->session['Detailpricetagprints'];
             $this->afterDeleteDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      

     protected function saveNewDetails(array $details)
     {                  
         foreach ($details as $row) {
             $detailmodel=new Detailpricetagprints;
             $detailmodel->attributes=$row;
             $respond=$detailmodel->insert();
             if (!$respond) {
                break;
             }
         }
         return $respond;
     }
     

     protected function saveDetails(array $details)
     {
         $idmaker=new idmaker();

         $respond=true;
         foreach ($details as $row) {
             $detailmodel=Detailpricetagprints::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailpricetagprints;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailpricetagprints', array('iddetail'=>$detailmodel->iddetail));
                 }    
             }
             $detailmodel->attributes=$row;
             $detailmodel->userlog=Yii::app()->user->id;
             $detailmodel->datetimelog=$idmaker->getDateTime();
             $respond=$detailmodel->save();
             if (!$respond) {
               break;
             }
          }
          return $respond;
     }
      
     protected function deleteDetails(array $details)
     {
         $respond=true;
         foreach ($details as $row) {
             $detailmodel=Detailpricetagprints::model()->findByPk($row['iddetail']);
             if($detailmodel) {
                 $this->tracker->init();
                 $this->trackActivity('d', $this->__DETAILFORMID);
                 $this->tracker->delete('detailpricetagprints', $detailmodel->id);
                 $respond=$detailmodel->delete();
                 if (!$respond) {
                   break;
                 }
             }
         }
         return $respond;
     }


     protected function loadDetails($id)
     {
      $sql="select * from detailpricetagprints where id='$id'";
      $details=Yii::app()->db->createCommand($sql)->queryAll();

      return $details;
     }


     protected function afterInsert(& $model)
     {
         $idmaker=new idmaker();
         $model->id=$idmaker->getCurrentID2();
         $model->idatetime=$idmaker->getDateTime();
         $model->regnum=$idmaker->getRegNum($this->formid);
         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         $model->papersize = 'A4';
         $model->paperwidth = 21;
         $model->paperheight = 29.7;
     }

     protected function afterPost(& $model)
     {
        if ($this->state == 'c') { 
     		$idmaker=new idmaker();
         	$idmaker->saveRegNum($this->formid, $model->regnum);
        }
     }

     protected function beforePost(& $model)
     {
         $idmaker=new idmaker();

         if ($this->state == 'c') {
         	$model->userlog=Yii::app()->user->id;
         	$model->datetimelog=$idmaker->getDateTime();
         	$model->regnum=$idmaker->getRegNum($this->formid);
         }
     }

     protected function beforeDelete(& $model)
     {

     }

     protected function afterDelete(& $model)
     {

     }

     protected function afterEdit(& $model)
     {

     }

     protected function afterInsertDetail(& $model, $details)
     {

     }


     protected function afterUpdateDetail(& $model, $details)
     {

     }

     protected function afterDeleteDetail(& $model, $details)
     {
     }


     protected function trackActivity($action)
     {
         $this->tracker=new Tracker();
         $this->tracker->init();
         $this->tracker->logActivity($this->formid, $action);
     }
     
      private function checkSerialNum(array $details ) 
      {
         $respond=true;
         
         foreach($details as $detail) {
            if ($detail['serialnum'] !== 'Belum Diterima') {
               $count=Yii::app()->db->createCommand()
                  ->select('count(*)')
                  ->from('detailpricetagprints')
                  ->where("serialnum = :serialnum", array(':serialnum'=>$detail['serialnum']))
                  ->queryScalar();
               $respond=$count==0;
               if(!$respond)
                  break;
            };
         }   
         return $respond;
      }
     
	public function actionPrintPricetag($id) 
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
			$master = $this->loadModel($id);
			$detail = $this->loadDetails($id);
			
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			$mypdf=new Pricetagprintpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, 
					array($master->paperwidth*10, $master->paperheight*10), true, 'UTF-8', false);
			$mypdf->init();
			$mypdf->LoadData($master->attributes,  
					$detail);
			$mypdf->display();
			$mypdf->output('Cetak Pricetag'.'-'.date('Ymd').'.pdf', 'D');
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}	
	}
	
	private function getImage()
	{
		if ($_FILES['Pricetagprints']['error']['bkjpg'] == 0) {
			if (file_exists( $_FILES['Pricetagprints']['tmp_name']['bkjpg'])) {
				$imagefile = fopen($_FILES['Pricetagprints']['tmp_name']['bkjpg'], 'r');
				$imagedata = fread($imagefile, $_FILES['Pricetagprints']['size']['bkjpg']);
				return $imagedata;
			}
		} else
			return false;
	}
}
