<?php

/**
 * This is the model class for table "special_deals".
 *
 * The followings are the available columns in table 'special_deals':
 * @property integer $sp_d_id
 * @property integer $sp_d_bill_cycle_id
 * @property string $sp_d_title
 * @property double $sp_d_amount
 * @property string $sp_d_currency
 * @property string $sp_d_description
 *
 * The followings are the available model relations:
 * @property BuAccounts[] $buAccounts
 * @property BillingCycle $spDBillCycle
 */
class SpecialDeals extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'special_deals';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sp_d_currency', 'required'),
			array('sp_d_bill_cycle_id', 'numerical', 'integerOnly'=>true),
			array('sp_d_amount', 'numerical'),
			array('sp_d_title', 'length', 'max'=>50),
			array('sp_d_currency', 'length', 'max'=>3),
			array('sp_d_description', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sp_d_id, sp_d_bill_cycle_id, sp_d_title, sp_d_amount, sp_d_currency, sp_d_description', 'safe', 'on'=>'search'),
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
			'buAccounts' => array(self::HAS_MANY, 'BuAccounts', 'special_deal_id'),
			'spDBillCycle' => array(self::BELONGS_TO, 'BillingCycle', 'sp_d_bill_cycle_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'sp_d_id' => 'Deal ID', // 'Sp D',
			'sp_d_bill_cycle_id' => 'Deal Cycle', // 'Sp D Bill Cycle',
			'sp_d_title' => 'Deal' , // 'Sp D Title',
			'sp_d_amount' => 'Deal Fees' , // 'Sp D Amount',
			'sp_d_currency' => 'Currency', // 'Sp D Currency',
			'sp_d_description' => 'Description', // 'Sp D Description',
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

		$criteria->compare('sp_d_id',$this->sp_d_id);
		$criteria->compare('sp_d_bill_cycle_id',$this->sp_d_bill_cycle_id);
		$criteria->compare('sp_d_title',$this->sp_d_title,true);
		$criteria->compare('sp_d_amount',$this->sp_d_amount);
		$criteria->compare('sp_d_currency',$this->sp_d_currency,true);
		$criteria->compare('sp_d_description',$this->sp_d_description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SpecialDeals the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
