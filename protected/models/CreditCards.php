<?php

/**
 * This is the model class for table "credit_cards".
 *
 * The followings are the available columns in table 'credit_cards':
 * @property integer $cr_card_id
 * @property integer $cr_card_owner_id
 * @property string $cr_card_namecard
 * @property string $cr_card_credit
 * @property string $cr_card_cvv
 * @property string $cr_card_expirationDate
 * @property integer $cr_card_rank
 *
 * The followings are the available model relations:
 * @property BuAccounts $crCardOwner
 * @property CreditDetails[] $creditDetails
 */
class CreditCards extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'credit_cards';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cr_card_owner_id, cr_card_namecard, cr_card_credit, cr_card_cvv, cr_card_expirationDate', 'required'),
			array('cr_card_owner_id, cr_card_rank', 'numerical', 'integerOnly'=>true),
			array('cr_card_namecard', 'length', 'max'=>100),
			array('cr_card_credit, cr_card_cvv', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cr_card_id, cr_card_owner_id, cr_card_namecard, cr_card_credit, cr_card_cvv, cr_card_expirationDate, cr_card_rank', 'safe', 'on'=>'search'),
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
			'crCardOwner' => array(self::BELONGS_TO, 'BuAccounts', 'cr_card_owner_id'),
			'creditDetails' => array(self::HAS_MANY, 'CreditDetails', 'cr_d_credit_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cr_card_id' => 'Credit Card ID', // 'Cr Card',
			'cr_card_owner_id' => 'Card Owner', // 'Cr Card Owner',
			'cr_card_credit' => 'Credit Card No', // 'Cr Card Credit',
			'cr_card_namecard' => 'Name ON Card',
			'cr_card_cvv' => 'CVV', // 'Cr Card Cvv',
			'cr_card_expirationDate' => 'Expiration Date',// 'Cr Card Expiration Date',
			'cr_card_rank' => 'Rank',// 'Cr Card Rank',
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

		$criteria->compare('cr_card_id',$this->cr_card_id);
		$criteria->compare('cr_card_owner_id',$this->cr_card_owner_id);
		$criteria->compare('cr_card_namecard',$this->cr_card_namecard,true);
		$criteria->compare('cr_card_credit',$this->cr_card_credit,true);
		$criteria->compare('cr_card_cvv',$this->cr_card_cvv,true);
		$criteria->compare('cr_card_expirationDate',$this->cr_card_expirationDate,true);
		$criteria->compare('cr_card_rank',$this->cr_card_rank);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CreditCards the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
