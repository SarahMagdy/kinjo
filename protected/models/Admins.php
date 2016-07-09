<?php

/**
 * This is the model class for table "admins".
 *
 * The followings are the available columns in table 'admins':
 * @property integer $adid
 * @property string $fname
 * @property string $lname
 * @property string $username
 * @property string $password
 * @property string $photo
 * @property integer $status
 * @property integer $level
 * @property string $email
 * @property string $created
 */
class Admins extends CActiveRecord
{
	public $confirmpassword;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'admins';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email', 'required'),
			array('status, level', 'numerical', 'integerOnly'=>true),
			array('fname, lname, username, email', 'length', 'max'=>50),
			array('password, photo', 'length', 'max'=>200),
			array('created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('adid, fname, lname, username, password, photo, status, level, email, created', 'safe', 'on'=>'search'),
			array('email' , 'ChkEmail'),
			array('confirmpassword', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match"),
			array('email', 'unique', 'attributeName' => 'email', 'message'=>'This Email is already in use'),
			array('username', 'unique', 'attributeName' => 'username', 'message'=>'This Username is already in use'),
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
			'adid' => 'Admin ID',
			'fname' => 'First Name',
			'lname' => 'Last Name',
			'username' => 'Username',
			'password' => 'Password',
			'confirmpassword' => 'Confirm Password',
			'photo' => 'Photo',
			'status' => 'Status',
			'level' => 'Level',
			'email' => 'Email',
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

		$criteria->compare('adid',$this->adid);
		$criteria->compare('fname',$this->fname,true);
		$criteria->compare('lname',$this->lname,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('level',$this->level);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Admins the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function ChkEmail($attribute,$params)
	{
	 	$pattern = '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-z0-9-]+)*(\.[a-zA-Z]{2,4})$/';
	 	if(!preg_match($pattern, $this->$attribute))
	 	{
	      $this->addError($attribute, 'Please Enter a Valid E-mail!');
		}
	}
}
