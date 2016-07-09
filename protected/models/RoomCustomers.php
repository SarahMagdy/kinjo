<?php

/**
 * This is the model class for table "room_customers".
 *
 * The followings are the available columns in table 'room_customers':
 * @property integer $id
 * @property integer $Cust_id
 * @property integer $business_id
 * @property string $checkin_date
 * @property string $checkout_date
 *
 * The followings are the available model relations:
 * @property Rooms $id0
 */
class RoomCustomers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'room_customers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Cust_id, business_id, checkin_date, checkout_date', 'required'),
			array('Cust_id, business_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, Cust_id, business_id, checkin_date, checkout_date', 'safe', 'on'=>'search'),
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
			'id0' => array(self::BELONGS_TO, 'Rooms', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'Cust_id' => 'Cust',
			'business_id' => 'Business',
			'checkin_date' => 'Checkin Date',
			'checkout_date' => 'Checkout Date',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('Cust_id',$this->Cust_id);
		$criteria->compare('business_id',$this->business_id);
		$criteria->compare('checkin_date',$this->checkin_date,true);
		$criteria->compare('checkout_date',$this->checkout_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RoomCustomers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
