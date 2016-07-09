<?php

/**
 * This is the model class for table "country".
 *
 * The followings are the available columns in table 'country':
 * @property integer $country_id
 * @property string $iso
 * @property string $iso3
 * @property integer $iso_numeric
 * @property string $currency_code
 * @property string $currency_name
 * @property string $currrency_symbol
 * @property string $flag
 * @property string $name
 */
class Country extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'country';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iso, iso3, iso_numeric, currency_code, currency_name, currrency_symbol, flag, name', 'required'),
			array('iso_numeric', 'numerical', 'integerOnly'=>true),
			array('iso', 'length', 'max'=>2),
			array('iso3, currency_code, currrency_symbol', 'length', 'max'=>3),
			array('currency_name', 'length', 'max'=>50),
			array('flag', 'length', 'max'=>10),
			array('name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('country_id, iso, iso3, iso_numeric, currency_code, currency_name, currrency_symbol, flag, name', 'safe', 'on'=>'search'),
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
			'country_id' => 'Country',
			'iso' => 'Iso',
			'iso3' => 'Iso3',
			'iso_numeric' => 'Iso Numeric',
			'currency_code' => 'Currency Code',
			'currency_name' => 'Currency Name',
			'currrency_symbol' => 'Currrency Symbol',
			'flag' => 'Flag',
			'name' => 'Name',
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

		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('iso',$this->iso,true);
		$criteria->compare('iso3',$this->iso3,true);
		$criteria->compare('iso_numeric',$this->iso_numeric);
		$criteria->compare('currency_code',$this->currency_code,true);
		$criteria->compare('currency_name',$this->currency_name,true);
		$criteria->compare('currrency_symbol',$this->currrency_symbol,true);
		$criteria->compare('flag',$this->flag,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Country the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
