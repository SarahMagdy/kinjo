<?php

/**
 * This is the model class for table "customers".
 *
 * The followings are the available columns in table 'customers':
 * @property integer $cid
 * @property string $fname
 * @property string $lname
 * @property string $email
 * @property string $password
 * @property integer $gender
 * @property string $birthdate
 * @property integer $country_id
 * @property string $social_id
 * @property string $google_id
 * @property string $fav_id
 * @property integer $status
 * @property string $created
  * @property string $phone

 * The followings are the available model relations:
 * @property BuRating[] $buRatings
 * @property Country $country
 * @property Favorites[] $favorites
 * @property GrCustActivity[] $grCustActivities
 * @property MessagesLog[] $messagesLogs
 * @property Orders[] $orders
 * @property ProductRating[] $productRatings
 * @property PushNotifications[] $pushNotifications
 * @property Subscriptions[] $subscriptions
 */
class Customers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('password, birthdate', 'required'),
			array('gender, country_id, status', 'numerical', 'integerOnly'=>true),
			array('fname, lname,  social_id, google_id, fav_id', 'length', 'max'=>45),
			array('password', 'length', 'max'=>50),
			array('created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cid, fname, lname, email, password, gender, birthdate, country_id,phone, social_id, google_id, fav_id, status, created', 'safe', 'on'=>'search'),
		        array('phone', 'length', 'max'=>20),
			//array('email', 'match', 'pattern' => '[a,c]', 'message' => 'Please enter a Valid E-mail', "on"=>array('update', 'create')),
			array('email' , 'myValidation')
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
			'buRatings' => array(self::HAS_MANY, 'BuRating', 'cid'),
                        'country' => array(self::BELONGS_TO, 'Country', 'country_id'),
			'favorites' => array(self::HAS_MANY, 'Favorites', 'cid'),
			'grCustActivities' => array(self::HAS_MANY, 'GrCustActivity', 'cid'),
			'messagesLogs' => array(self::HAS_MANY, 'MessagesLog', 'cid'),
			'orders' => array(self::HAS_MANY, 'Orders', 'cid'),
			'productRatings' => array(self::HAS_MANY, 'ProductRating', 'cid'),
			'pushNotifications' => array(self::HAS_MANY, 'PushNotifications', 'cid'),
			'subscriptions' => array(self::HAS_MANY, 'Subscriptions', 'cid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cid' => 'Cid',//'Cid',
			'fname' => 'Fname',
			'lname' => 'Lname',
			'email' => 'Email',
			'password' => 'Password',
			'gender' => 'Gender',
                        'phone' => 'Phone',
			'birthdate' => 'Birthdate',
			'country_id' => 'Country',
                        'phone' => 'Phone',
			'social_id' => 'Social',
			'google_id' => 'Google',
			'fav_id' => 'Fav',
			'status' => 'Status',
                        'hash' => 'Hash',
			'q_code' => 'Q Code',
			'notify_enable' => 'Notify Enable',
			'lat' => 'Lat',
			'long' => 'Long',
			'block_groups' => 'Block Groups',
			'created' => 'Created',
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

		$criteria->compare('cid',$this->cid);
		$criteria->compare('fname',$this->fname,true);
		$criteria->compare('lname',$this->lname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('birthdate',$this->birthdate,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('social_id',$this->social_id,true);
		$criteria->compare('google_id',$this->google_id,true);
		$criteria->compare('fav_id',$this->fav_id,true);
		$criteria->compare('status',$this->status);
                $criteria->compare('phone',$this->phone,true);
                $criteria->compare('hash',$this->hash,true);
		$criteria->compare('q_code',$this->q_code,true);
		$criteria->compare('notify_enable',$this->notify_enable,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('long',$this->long,true);
		$criteria->compare('block_groups',$this->block_groups);

		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Customers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function myValidation($attribute,$params)
	{
	    // if ($params['strength'] === self::WEAK)
	        // $pattern = '/^(?=.*[a-zA-Z0-9]).{5,}$/';  
	    // elseif ($params['strength'] === self::STRONG)
	        // $pattern = '/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z](?=.*[a-zA-Z])).{5,}$/';  
	 	//$pattern = '/[a-z]*[.,_][a-z]*[@][a-z]*[.com]/';
	 	$pattern = '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-z0-9-]+)*(\.[a-zA-Z]{2,4})$/';
	 	if(!preg_match($pattern, $this->$attribute))
	 	{
	      $this->addError($attribute, 'Please Enter a Valid E-mail!');
		}
	}
	
	
}
