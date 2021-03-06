<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC6';
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
                    
                $model=new Purchasesmemos;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Purchasesmemos'])) {
                   Yii::app()->session['Purchasesmemos']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Purchasesmemos'];
                }
                
               // Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);

                if (isset($_POST)){
                   if(isset($_POST['yt1'])) {
                      //The user pressed the button;
                      $model->attributes=$_POST['Purchasesmemos'];
                      
                      
                      $this->beforePost($model);
                      $respond=$model->save();
                      if(!$respond) {
                          throw new CHttpException(404,'There is an error in master posting');
                      }

                      if(isset(Yii::app()->session['Detailpurchasesmemos']) ) {
                        $details=Yii::app()->session['Detailpurchasesmemos'];
                        $respond=$respond&&$this->saveNewDetails($details);
                      } 

                      if($respond) {
                         $this->afterPost($model);
                         Yii::app()->session->remove('Purchasesmemos');
                         Yii::app()->session->remove('Detailpurchasesmemos');
                         $this->redirect(array('view','id'=>$model->id));
                      } 
                           
                   } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Purchasesmemos'];
                         Yii::app()->session['Purchasesmemos']=$_POST['Purchasesmemos'];
                         $this->redirect(array('detailpurchasesmemos/create',
                            'id'=>$model->id));
                      } else if ($_POST['command']=='setPO') {
                         $model->attributes=$_POST['Purchasesmemos'];
                         Yii::app()->session['Purchasesmemos']=$_POST['Purchasesmemos'];
                         $this->loadPO($model->idpurchaseorder, $model->id);
                         $details=Yii::app()->session['Detailpurchasesmemos'];
                         $this->afterInsertDetail($model, $details);
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

             if(!isset(Yii::app()->session['Purchasesmemos']))
                Yii::app()->session['Purchasesmemos']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Purchasesmemos'];

             if(!isset(Yii::app()->session['Detailpurchasesmemos'])) 
               Yii::app()->session['Detailpurchasesmemos']=$this->loadDetails($id);
             
             // Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
                     $model->attributes=$_POST['Purchasesmemos'];
                     $this->beforePost($model);
                     $this->tracker->modify('purchasesmemos', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                       throw new CHttpException(404,'There is an error in master posting');
                     }

                     if(isset(Yii::app()->session['Detailpurchasesmemos'])) {
                         $details=Yii::app()->session['Detailpurchasesmemos'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     
                     if(isset(Yii::app()->session['DeleteDetailpurchasesmemos'])) {
                         $deletedetails=Yii::app()->session['DeleteDetailpurchasesmemos'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                    
                     if($respond) {
                         Yii::app()->session->remove('Purchasesmemos');
                         Yii::app()->session->remove('Detailpurchasesmemos');
                         Yii::app()->session->remove('DeleteDetailpurchasesmemos');
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
            $this->tracker->delete('purchasesmemos', $id);

            $detailmodels=Detailpurchasesmemos::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailpurchasesmemos', array('iddetail'=>$dm->iddetail));
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

         Yii::app()->session->remove('Purchasesmemos');
         Yii::app()->session->remove('Detailpurchasesmemos');
         Yii::app()->session->remove('DeleteDetailpurchasesmemos');
         $dataProvider=new CActiveDataProvider('Purchasesmemos',
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
         $model=new Purchasesmemos('search');
         $model->unsetAttributes();  // clear any default values
         if(isset($_GET['Purchasesmemos']))
            $model->attributes=$_GET['Purchasesmemos'];

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
                $this->tracker->restore('purchasesmemos', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Purchasesmemos');
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
                $this->tracker->restoreDeleted('purchasesmemos', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Purchasesmemos');
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
	 * @return Purchasesmemos the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Purchasesmemos::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Purchasesmemos $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='purchasesmemos-form')
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
             $model=new Purchasesmemos;
             $model->attributes=Yii::app()->session['Purchasesmemos'];

             $details=Yii::app()->session['Detailpurchasesmemos'];
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

             $model=new Purchasesmemos;
             $model->attributes=Yii::app()->session['Purchasesmemos'];

             $details=Yii::app()->session['Detailpurchasesmemos'];
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


             $model=new Purchasesmemos;
             $model->attributes=Yii::app()->session['Purchasesmemos'];

             $details=Yii::app()->session['Detailpurchasesmemos'];
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
             $detailmodel=new Detailpurchasesmemos;
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
             $detailmodel=Detailpurchasesmemos::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailpurchasesmemos;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailpurchasesmemos', array('iddetail'=>$detailmodel->iddetail));
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
             $detailmodel=Detailpurchasesmemos::model()->findByPk($row['iddetail']);
             if($detailmodel) {
                 $this->tracker->init();
                 $this->trackActivity('d', $this->__DETAILFORMID);
                 $this->tracker->delete('detailpurchasesmemos', $detailmodel->id);
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
      $sql="select * from detailpurchasesmemos where id='$id'";
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
         $model->total=0;
         $model->discount=0;
     }

     protected function afterPost(& $model)
     {
         $idmaker=new idmaker();
         $idmaker->saveRegNum($this->formid, $model->regnum);
         
     }

     protected function beforePost(& $model)
     {
         $idmaker=new idmaker();

         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         $model->regnum=$idmaker->getRegNum($this->formid);
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
     	$total=0;
     	$totaldisc=0;
     	foreach ($details as $row) {
     		$total=$total+(($row['price']+$row['cost1']+$row['cost2'])*$row['qty']);
     		$totaldisc=$totaldisc+$row['discount']*$row['qty'];
     	}
     	$model->attributes=Yii::app()->session['Purchasesmemos'];
     	$model->total=$total;
     	$model->discount=$totaldisc;
     }


     protected function afterUpdateDetail(& $model, $details)
     {
     	$total=0;
     	$totaldisc=0;
     	foreach ($details as $row) {
     		$total=$total+(($row['price']+$row['cost1']+$row['cost2'])*$row['qty']);
     		$totaldisc=$totaldisc+$row['discount']*$row['qty'];
     	}
     	$model->attributes=Yii::app()->session['Purchasesmemos'];
     	$model->total=$total;
     	$model->discount=$totaldisc;
     }

     protected function afterDeleteDetail(& $model, $details)
     {
     	$total=0;
     	$totaldisc=0;
     	foreach ($details as $row) {
     		$total=$total+(($row['price']+$row['cost1']+$row['cost2'])*$row['qty']);
     		$totaldisc=$totaldisc+$row['discount']*$row['qty'];
     	}
     	$model->attributes=Yii::app()->session['Purchasesmemos'];
     	$model->total=$total;
     	$model->discount=$totaldisc;
     }


     protected function trackActivity($action)
     {
         $this->tracker=new Tracker();
         $this->tracker->init();
         $this->tracker->logActivity($this->formid, $action);
     }
     
      private function loadPO($idpo, $id)
      {
        $details=array();

        $dataMemo=Yii::app()->db->createCommand()
           ->select()
           ->from('purchasesmemos')
           ->where('idpurchaseorder=:idpo', array('idpo'=>$idpo))
           ->order(array('id desc'))
           ->queryAll();
        if(count($dataMemo)) {
           $lastMemoID=$dataMemo[0]['id'];
           $dataPO=Yii::app()->db->createCommand()
              ->select()
              ->from('detailpurchasesmemos')
              ->where('id = :id', array(':id'=>$lastMemoID))
              ->queryAll();
        } else {
           $dataPO=Yii::app()->db->createCommand()
              ->select()
              ->from('detailpurchasesorders')
              ->where('id = :id', array(':id'=>$idpo))
              ->queryAll();
        }
        Yii::app()->session->remove('Detailpurchasesmemos');
         foreach($dataPO as $row) {
            $detail['iddetail']=idmaker::getCurrentID2();
            $detail['id']=$id;
            $detail['iditem']=$row['iditem'];
            $detail['userlog']=Yii::app()->user->id;
            $detail['datetimelog']=idmaker::getDateTime();
            $detail['qty']=$row['qty'];
            $detail['prevprice']=$row['price'];
            $detail['price']=$row['price'];
            $detail['discount']=$row['discount'];
            $detail['prevcost1']=$row['cost1'];
            $detail['cost1']=$row['cost1'];
            $detail['prevcost2']=$row['cost2'];
            $detail['cost2']=$row['cost2'];
            $details[]=$detail; 
        }
        Yii::app()->session['Detailpurchasesmemos']=$details;
      }
     
      
}