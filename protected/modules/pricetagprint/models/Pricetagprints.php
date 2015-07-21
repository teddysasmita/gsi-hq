<?php

/**
 * This is the model class for table "pricetagprints".
 *
 * The followings are the available columns in table 'pricetagprints':
 * @property string $id
 * @property string $idatetime
 * @property string $regnum
 * @property string $papersize
 * @property double $paperwidth
 * @property double $paperheight
 * @property double $labelwidth
 * @property double $labelheight
 * @property double $infoposx
 * @property double $infoposy
 * @property double $infofontsize
 * @property string $infofonttype
 * @property string $bkjpg
 * @property string $userlog
 * @property string $datetimelog
 */
class Pricetagprints extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pricetagprints';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, idatetime, regnum, papersize, labelwidth, labelheight, infoposx, infoposy, infofontsize, infofonttype, userlog, datetimelog', 'required'),
			array('paperwidth, paperheight, labelwidth, labelheight, infoposx, infoposy, infofontsize', 'numerical'),
			array('id, userlog', 'length', 'max'=>21),
			array('idatetime, datetimelog', 'length', 'max'=>19),
			array('regnum', 'length', 'max'=>12),
			array('papersize', 'length', 'max'=>20),
			array('infofonttype', 'length', 'max'=>50),
			array('bkjpg', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idatetime, regnum, papersize, paperwidth, paperheight, labelwidth, labelheight, infoposx, infoposy, infofontsize, infofonttype, bkjpg, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'idatetime' => 'Tanggal',
			'regnum' => 'Nomor Urut',
			'papersize' => 'Ukuran Kertas',
			'paperwidth' => 'Lebar Kertas',
			'paperheight' => 'Tinggi Kertas',
			'labelwidth' => 'Lebar Label',
			'labelheight' => 'Tinggi Label',
			'infoposx' => 'Posisi Harga - X',
			'infoposy' => 'Posisi Harga - Y',
			'infofontsize' => 'Ukuran Huruf',
			'infofonttype' => 'Jenis Huruf',
			'bkjpg' => 'Latar JPEG',
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
		$criteria->compare('regnum',$this->regnum,true);
		$criteria->compare('papersize',$this->papersize,true);
		$criteria->compare('paperwidth',$this->paperwidth);
		$criteria->compare('paperheight',$this->paperheight);
		$criteria->compare('labelwidth',$this->labelwidth);
		$criteria->compare('labelheight',$this->labelheight);
		$criteria->compare('infoposx',$this->infoposx);
		$criteria->compare('infoposy',$this->infoposy);
		$criteria->compare('infofontsize',$this->infofontsize);
		$criteria->compare('infofonttype',$this->infofonttype,true);
		$criteria->compare('bkjpg',$this->bkjpg,true);
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
	 * @return Pricetagprints the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
