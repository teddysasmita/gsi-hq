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
 * @property double $itemnamex
 * @property double $itemnamey
 * @property double $itemnamefz
 * @property string $itemnameft
 * @property double $itemnamew
 * @property double $itemnameh
 * @property double $pricex
 * @property double $pricey
 * @property double $pricew
 * @property double $priceh
 * @property string $priceft
 * @property double $pricefz
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
			array('id, idatetime, regnum, papersize, labelwidth, labelheight, itemnamex, itemnamey, itemnamefz, itemnameft, itemnamew, itemnameh, pricex, pricey, pricew, priceh, priceft, pricefz, userlog, datetimelog', 'required'),
			array('paperwidth, paperheight, labelwidth, labelheight, itemnamex, itemnamey, itemnamefz, itemnamew, itemnameh, pricex, pricey, pricew, priceh, pricefz', 'numerical'),
			array('id, userlog', 'length', 'max'=>21),
			array('idatetime, datetimelog', 'length', 'max'=>19),
			array('regnum', 'length', 'max'=>12),
			array('papersize', 'length', 'max'=>20),
			array('itemnameft, priceft', 'length', 'max'=>50),
			array('bkjpg', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idatetime, regnum, papersize, paperwidth, paperheight, labelwidth, labelheight, itemnamex, itemnamey, itemnamefz, itemnameft, itemnamew, itemnameh, pricex, pricey, pricew, priceh, priceft, pricefz, bkjpg, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'papersize' => 'Jenis Kertas',
			'paperwidth' => 'Lebar Kertas (cm)',
			'paperheight' => 'Tinggi Kertas (cm)',
			'labelwidth' => 'Lebar Label (cm)',
			'labelheight' => 'Tinggi Label (cm)',
			'itemnamex' => 'Posisi x Nama Barang (mm)',
			'itemnamey' => 'Posisi y Nama Barang (mm)',
			'itemnamefz' => 'Ukuran Huruf Nama Barang',
			'itemnameft' => 'Jenis Huruf Nama Barang',
			'itemnamew' => 'Lebar Ruang Nama Barang (mm)',
			'itemnameh' => 'Tinggi Ruang Nama Barang (mm)',
			'pricex' => 'Posisi x Harga (mm)',
			'pricey' => 'Posisi y Harga (mm)',
			'pricew' => 'Lebar Ruang Harga (mm)',
			'priceh' => 'Tinggi Ruang Harga (mm)',
			'priceft' => 'Jenis Huruf Harga',
			'pricefz' => 'Ukuran Huruf Harga',
			'bkjpg' => 'Bkjpg',
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
		$criteria->compare('itemnamex',$this->itemnamex);
		$criteria->compare('itemnamey',$this->itemnamey);
		$criteria->compare('itemnamefz',$this->itemnamefz);
		$criteria->compare('itemnameft',$this->itemnameft,true);
		$criteria->compare('itemnamew',$this->itemnamew);
		$criteria->compare('itemnameh',$this->itemnameh);
		$criteria->compare('pricex',$this->pricex);
		$criteria->compare('pricey',$this->pricey);
		$criteria->compare('pricew',$this->pricew);
		$criteria->compare('priceh',$this->priceh);
		$criteria->compare('priceft',$this->priceft,true);
		$criteria->compare('pricefz',$this->pricefz);
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
