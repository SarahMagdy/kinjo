<?php

/**
 * This is the model class for table "billing_cycle".
 *
 * The followings are the available columns in table 'billing_cycle':
 * @property integer $bcid
 * @property $bc_duration
 * @property integer $bc_type
 *
 * The followings are the available model relations:
 * @property SpecialDeals[] $specialDeals
 */
class BillingCycle extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'billing_cycle';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bc_duration , bc_type', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bcid, bc_duration, bc_type', 'safe', 'on'=>'search'),
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
			'specialDeals' => array(self::HAS_MANY, 'SpecialDeals', 'sp_d_bill_cycle_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bcid' => 'Bill Cycle ID', // 'Bcid',
			'bc_duration' =>'Duration',
			'bc_type' => 'Bc Type',
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

		$criteria->compare('bcid',$this->bcid);
		$criteria->compare('bc_duration',$this->bc_duration);
		$criteria->compare('bc_type',$this->bc_type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BillingCycle the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
