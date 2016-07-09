<?php

/**
 * This is the model class for table "bills".
 *
 * The followings are the available columns in table 'bills':
 * @property integer $bill_id
 * @property integer $bill_owner_id
 * @property string $bill_due_date
 * @property string $bill_pay_date
 * @property double $bill_amount
 * @property double $bill_disc
 * @property integer $bill_currency_id
 *
 * The followings are the available model relations:
 * @property BuAccounts $billOwner
 */
class Bills extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bills';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bill_owner_id, bill_due_date, bill_pay_date, bill_currency_id', 'required'),
			array('bill_owner_id, bill_currency_id', 'numerical', 'integerOnly'=>true),
			array('bill_amount, bill_disc', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bill_id, bill_owner_id, bill_due_date, bill_pay_date, bill_amount, bill_disc, bill_currency_id', 'safe', 'on'=>'search'),
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
			'billOwner' => array(self::BELONGS_TO, 'BuAccounts', 'bill_owner_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bill_id' => 'Bill',
			'bill_owner_id' => 'Bill Owner',
			'bill_due_date' => 'Bill Due Date',
			'bill_pay_date' => 'Bill Pay Date',
			'bill_amount' => 'Bill Amount',
			'bill_disc' => 'Bill Disc',
			'bill_currency_id' => 'Bill Currency',
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

		$criteria->compare('bill_id',$this->bill_id);
		$criteria->compare('bill_owner_id',$this->bill_owner_id);
		$criteria->compare('bill_due_date',$this->bill_due_date,true);
		$criteria->compare('bill_pay_date',$this->bill_pay_date,true);
		$criteria->compare('bill_amount',$this->bill_amount);
		$criteria->compare('bill_disc',$this->bill_disc);
		$criteria->compare('bill_currency_id',$this->bill_currency_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Bills the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
