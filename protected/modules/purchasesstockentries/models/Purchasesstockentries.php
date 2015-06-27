<?php

/**
 * This is the model class for table "purchasesstockentries".
 *
 * The followings are the available columns in table 'purchasesstockentries':
 * @property string $id
 * @property string $idatetime
 * @property string $regnum
 * @property string $ponum
 * @property string $sjnum
 * @property string $idsupplier
 * @property string $remark
 * @property string $userlog
 * @property string $datetimelog
 */
class Purchasesstockentries extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchasesstockentries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, idatetime, regnum,ponum, sjnum, idsupplier, userlog, datetimelog', 'required'),
			array('id, idsupplier, userlog', 'length', 'max'=>21),
			array('idatetime, datetimelog', 'length', 'max'=>19),
			array('regnum', 'length', 'max'=>30),
			array('ponum, sjnum', 'length', 'max'=>30),
			array('remark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idatetime, regnum, ponum, sjnum, idsupplier, userlog, datetimelog', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'regnum'=>'Nomor LPB',	
			'idatetime' => 'Tanggal',
			'ponum' => 'Nomor PO',
			'sjnum' => 'Nomor SJ',
			'idsupplier' => 'Pemasok',
			'remark' => 'Catatan',
			'userlog' => 'Userlog',
			'datetimelog' => 'Datetimelog',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('idatetime',$this->idatetime,true);
		$criteria->compare('ponum',$this->ponum,true);
		$criteria->compare('sjnum',$this->sjnum,true);
		$criteria->compare('regnum',$this->regnum,true);
		$criteria->compare('idsupplier',$this->idsupplier,true);
		$criteria->compare('userlog',$this->userlog,true);
		$criteria->compare('datetimelog',$this->datetimelog,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Purchasesstockentries the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
