<?php

/**
 * This is the model class for table "purchasespayments".
 *
 * The followings are the available columns in table 'purchasespayments':
 * @property string $id
 * @property string $regnum
 * @property string $idatetime
 * @property string $idsupplier
 * @property double $total
 * @property double $discount
 * @property double $labelcost
 * @property string $status
 * @property string $remark
 * @property string $userlog
 * @property string $datetimelog
 */
class Purchasespayments extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchasespayments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, regnum, idatetime, idsupplier, labelcost, discount, status, userlog, datetimelog', 'required'),
			array('total, discount, labelcost', 'numerical'),
			array('id, idsupplier, userlog', 'length', 'max'=>21),
			array('regnum', 'length', 'max'=>12),
			array('idatetime, datetimelog', 'length', 'max'=>19),
			array('status', 'length', 'max'=>10),
			array('remark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('regnum, idatetime, idsupplier, labelcost, discount, status, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'regnum' => 'Nomor Urut',
			'idatetime' => 'Tanggal',
			'idsupplier' => 'Pemasok',
			'total' => 'Total',
			'discount' => 'Diskon/Promo',
			'labelcost' => 'Biaya Label',
			'status' => 'Status',
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

		//$criteria->compare('id',$this->id,true);
		$criteria->compare('regnum',$this->regnum,true);
		$criteria->compare('idatetime',$this->idatetime,true);
		//$criteria->compare('idsupplier',$this->idsupplier,true);
		$criteria->compare('idsupplier',lookup::SupplierIDFromFirstName($this->idsupplier),true);
		$criteria->compare('idsupplier',lookup::SupplierIDFromLastName($this->idsupplier),true, 'or' );
		$criteria->compare('total',$this->total);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('labelcost',$this->labelcost);
		$criteria->compare('status',$this->status,true);
		//$criteria->compare('remark',$this->remark,true);
		//$criteria->compare('userlog',lookup::UserIDFromName($this->userlog),true);
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
	 * @return Purchasespayments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
