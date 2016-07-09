<?php

/**
 * This is the model class for table "credit_details".
 *
 * The followings are the available columns in table 'credit_details':
 * @property integer $cr_d_id
 * @property integer $cr_d_credit_id
 * @property double $cr_d_val
 * @property integer $cr_d_type
 * @property string $cr_d_date
 *
 * The followings are the available model relations:
 * @property CreditCards $crDCredit
 */
class CreditDetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'credit_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cr_d_credit_id, cr_d_date', 'required'),
			array('cr_d_credit_id, cr_d_type', 'numerical', 'integerOnly'=>true),
			array('cr_d_val', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cr_d_id, cr_d_credit_id, cr_d_val, cr_d_type, cr_d_date', 'safe', 'on'=>'search'),
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
			'crDCredit' => array(self::BELONGS_TO, 'CreditCards', 'cr_d_credit_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cr_d_id' => 'Cr D',
			'cr_d_credit_id' => 'Cr D Credit',
			'cr_d_val' => 'Cr D Val',
			'cr_d_type' => 'Cr D Type',
			'cr_d_date' => 'Cr D Date',
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

		$criteria->compare('cr_d_id',$this->cr_d_id);
		$criteria->compare('cr_d_credit_id',$this->cr_d_credit_id);
		$criteria->compare('cr_d_val',$this->cr_d_val);
		$criteria->compare('cr_d_type',$this->cr_d_type);
		$criteria->compare('cr_d_date',$this->cr_d_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CreditDetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
