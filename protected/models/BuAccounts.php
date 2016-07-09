<?php

/**
 * This is the model class for table "bu_accounts".
 *
 * The followings are the available columns in table 'bu_accounts':
 * @property integer $accid
 * @property integer $special_deal_id
 * @property integer $feature_id
 * @property string $fname
 * @property string $lname
 * @property integer $country_id
 * @property string $gender
 * @property string $photo
 * @property string $address
 * @property string $city
 * @property string $mobile
 * @property string $tel
 * @property string $email
 * @property integer $bu_acc_TypeID
 * @property integer $has_group
 * @property string $start_date
 * @property integer $status
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Bills[] $bills
 * @property BuAccountTypes $buAccType
 * @property Country $country
 * @property BusinessUnit[] $businessUnits
 * @property CreditCards[] $creditCards
 * @property CreditOwner[] $creditOwners
 */
class BuAccounts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bu_accounts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('special_deal_id, country_id, bu_acc_TypeID, status', 'numerical', 'integerOnly'=>true),
			array('fname, lname, address, city, email', 'length', 'max'=>45),
			array('gender', 'length', 'max'=>10),
			//array('photo', 'length', 'max'=>200),
			array('mobile, tel', 'length', 'max'=>25),
			array('start_date, created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('accid, special_deal_id, fname, lname, country_id, gender, photo, address, city, mobile, tel, email, bu_acc_TypeID, start_date, status, created', 'safe', 'on'=>'search'),
			array('photo', 'file','types'=>'jpg, gif, png,jpeg', 'allowEmpty'=>true, 'on'=>'insert,update'),
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
			'bills' => array(self::HAS_MANY, 'Bills', 'bill_owner_id'),
			'buAccType' => array(self::BELONGS_TO, 'BuAccountTypes', 'bu_acc_TypeID'),
			'country' => array(self::BELONGS_TO, 'Country', 'country_id'),
			'feature' => array(self::BELONGS_TO, 'Features', 'feature_id'),
			'businessUnits' => array(self::HAS_MANY, 'BusinessUnit', 'accid'),
			'creditCards' => array(self::HAS_MANY, 'CreditCards', 'cr_card_owner_id'),
			'creditOwners' => array(self::HAS_MANY, 'CreditOwner', 'cr_owner_ownerid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
			
		$langFile = Yii::app()->session['Language']['LangFile'];
		// print_r($langFile);return;
		return array(
			'accid' => 'Account ID',
			'special_deal_id' => 'Special Deal', // Yii::t($langFile, 'BuAccount_pkg_id')
			'fname' => 'First Name',
			'lname' => 'Last Name',
			'country_id' => 'Country',
			'gender' => 'Gender',
			'photo' => 'Photo',
			'address' => 'Address',
			'city' => 'City',
			'mobile' => 'Mobile',
			'tel' => 'Tel',
			'email' => 'Email',
			'bu_acc_TypeID' => 'Account Type',
			'start_date' => 'Start Date',
			'status' => 'Status',
			'created' => 'Created',
			'title' => 'Package Name',
			// 'duration' => 'Billing Cycle Duration',
			'name' => 'Country'
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

		$criteria->compare('accid',$this->accid);
		$criteria->compare('special_deal_id',$this->special_deal_id);
		$criteria->compare('fname',$this->fname,true);
		$criteria->compare('lname',$this->lname,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('bu_acc_TypeID',$this->bu_acc_TypeID);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created',$this->created,true);
		
		
		//$criteria->together = true; 
        //$criteria->compare('t.pkgid',$this->pkg_id,true);
        //$criteria->with = array('pkg');
        //$criteria->compare('title',$this->pkg,true,"OR");
		
		
		//$criteria->together = true; 
        //$criteria->compare('t.bcid',$this->bill_cycle_id,true);
        //$criteria->with = array('billCycle');
        //$criteria->compare('duration',$this->billCycle,true,"OR");

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BuAccounts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
