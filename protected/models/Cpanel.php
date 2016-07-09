<?php

/**
 * This is the model class for table "cpanel".
 *
 * The followings are the available columns in table 'cpanel':
 * @property integer $cp_id
 * @property integer $buid
 * @property string $username
 * @property string $password
 * @property integer $role_id
 * @property string $photo
 * @property string $email
 * @property string $fname
 * @property string $lname
 * @property integer $level
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Roles $role
 */
class Cpanel extends CActiveRecord
{
	
	public $confirmpassword;
	
	
	public $old_password;
    public $new_password;
    public $repeat_password;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cpanel';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('photo', 'required'),
			array('buid,role_id, level', 'numerical', 'integerOnly'=>true),
			array('username, email, fname, lname', 'length', 'max'=>45),
			array('password, photo', 'length', 'max'=>200),
			array('created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cp_id, buid, username, password, role_id, photo, email, fname, lname, level, created', 'safe', 'on'=>'search'),
			
			array('email' , 'ChkEmail'),
			array('confirmpassword', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match"),
			array('email', 'unique', 'attributeName' => 'email', 'message'=>'This Email is already in use'),
			array('username', 'unique', 'attributeName' => 'username', 'message'=>'This Username is already in use'),
			
			
			array('old_password, new_password, repeat_password', 'required', 'on' => 'changePwd'),
	        array('old_password', 'findPasswords' , 'on' => 'changePwd'),
	        array('repeat_password', 'compare', 'compareAttribute'=>'new_password', 'on'=>'changePwd'),
			
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
			 'role' => array(self::BELONGS_TO, 'Roles', 'role_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cp_id' => 'Cp',
			'buid' => 'Buid',
			'username' => 'Username',
			'password' => 'Password',
			'role_id' => 'Role',
			'photo' => 'Photo',
			'email' => 'Email',
			'fname' => 'Fname',
			'lname' => 'Lname',
			'level' => 'Level',
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

		$criteria->compare('cp_id',$this->cp_id);
		$criteria->compare('buid',$this->buid);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('fname',$this->fname,true);
		$criteria->compare('lname',$this->lname,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('created',$this->created,true);
		if(Yii::app()->session['User']['UserRoleID']=='1'){
			$criteria->addCondition('level = 0');
		}
		
		if(Yii::app()->session['User']['UserRoleID']=='2'){
			$criteria->addCondition('level = 1');
		
		
			// "SELECT * FROM `cpanel`  WHERE `buid` IN (SELECT buid FROM business_unit WHERE accid = 1)";
			$criteria->addCondition('buid IN (SELECT buid FROM business_unit WHERE accid = '.Yii::app()->session['User']['UserOwnerID'].')');
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cpanel the static model class
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


	public function findPasswords($attribute, $params)
    {
        // $OwnerAccount = OwnerAccount::model()->findByPk(Yii::app()->session['User']['UserID']);
        
         // var_dump($this->cp_id);
		 // return;
        
		$sql = "SELECT *
				FROM cpanel WHERE cp_id = ".$this->cp_id ;//Yii::app()->session['User']['UserID'];
			
		$command = Yii::app()->db->createCommand($sql)->queryRow();
		
		if(isset($command)){
			// var_dump($this->$attribute);
			// var_dump($this->new_password);
			// return;
			// if ($OwnerAccount->ow_acc_pass != md5($this->old_password))
            // $this->addError($attribute, 'Old password is incorrect.');

            
            if($command['password'] != md5($this->old_password)){
            	$this->addError($attribute, 'Old password is incorrect.');
            }
		}
        
    }
	
	
	
}
