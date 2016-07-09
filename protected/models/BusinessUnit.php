<?php

/**
 * This is the model class for table "business_unit".
 *
 * The followings are the available columns in table 'business_unit':
 * @property integer $buid
 * @property integer $accid
 * @property integer $pkg_id
 * @property string $title
 * @property string $long
 * @property string $lat
 * @property integer $membership
 * @property string $logo
 * @property string $cpanel_logo
 * @property string $urlid
 * @property string $description
 * @property integer $type
 * @property string $site
 * @property integer $statid
 * @property string $apiKey
 * @property string $rating
 * @property string $currency_code
 * @property integer $active
 * @property string $created
 *
 * The followings are the available model relations:
 * @property BuContacts[] $buContacts
 * @property BuLangSetting[] $buLangSettings
 * @property BuRating[] $buRatings
 * @property BuSetting[] $buSettings
 * @property BuTables[] $buTables
 * @property BuAccounts $acc
 * @property BusinessUnitLang[] $businessUnitLangs
 * @property Catsub[] $catsubs
 * @property Messages[] $messages
 * @property OrdersDetails[] $ordersDetails
 * @property PdConfig[] $pdConfigs
 * @property Products[] $products
 * @property Subscriptions[] $subscriptions
 */
class BusinessUnit extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'business_unit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('currency_code', 'required'),
			array('accid, pkg_id,membership, type, statid ,active', 'numerical', 'integerOnly'=>true),
			array('title, long, lat', 'length', 'max'=>45),
			array('site, apiKey', 'length', 'max'=>200),
			//array('urlid', 'length', 'max'=>100),
			array('description', 'length', 'max'=>500),
			array('rating', 'length', 'max'=>5),
			array('currency_code', 'length', 'max'=>3),
			array('created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('buid, accid, title, long, lat, membership, logo,  cpanel_logo,urlid, description, type, site, statid, apiKey, rating, created', 'safe', 'on'=>'search'),
			array('logo', 'file','types'=>'jpg , jpeg , gif, png', 'allowEmpty' => true,'on'=>'insert,update'),
			array('urlid','file','types'=>'png', 'allowEmpty' => true,'on'=>'insert,update'),
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
			'buContacts' => array(self::HAS_MANY, 'BuContacts', 'bu_contact_bu_id'),
			'buLangSettings' => array(self::HAS_MANY, 'BuLangSetting', 'bu_lang_bu_id'),
			'buRatings' => array(self::HAS_MANY, 'BuRating', 'buid'),
			'buSettings' => array(self::HAS_MANY, 'BuSetting', 'bu_setting_bu_id'),
			'buTables' => array(self::HAS_MANY, 'BuTables', 'bu_table_buid'),
			'acc' => array(self::BELONGS_TO, 'BuAccounts', 'accid'),
			'type0' => array(self::BELONGS_TO, 'Types', 'type'),
			'businessUnitLangs' => array(self::HAS_MANY, 'BusinessUnitLang', 'bu_lang_bu_id'),
			'catsubs' => array(self::HAS_MANY, 'Catsub', 'catsub_buid'),
			'messages' => array(self::HAS_MANY, 'Messages', 'buid'),
			'ordersDetails' => array(self::HAS_MANY, 'OrdersDetails', 'ord_buid'),
			'pdConfigs' => array(self::HAS_MANY, 'PdConfig', 'conf_buid'),
			'products' => array(self::HAS_MANY, 'Products', 'buid'),
			'subscriptions' => array(self::HAS_MANY, 'Subscriptions', 'buid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'buid' => 'Business ID',
			'accid' => 'Account',
			'title' => 'Business Name',
			'long' => 'Longitude',
			'lat' => 'Latitude',
			'membership' => 'Membership',
			'logo' => 'Logo',
			'cpanel_logo' => 'Cpanel Logo',
			'urlid' => 'Icon Marker',
			'description' => 'Description',
			'type' => 'Type',
			'site' => 'Site',
			'statid' => 'Statid',
			'apiKey' => 'Api Key',
			'rating' => 'Rating',
			'currency_code' => 'Currency',
			'active' => 'Active Or Not Active',
			'created' => 'Created',
			'loc'=>'Location',
			'pkg_id'=>'Package',
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

		$criteria->compare('buid',$this->buid);
		$criteria->compare('accid',$this->accid);
		$criteria->compare('pkg_id',$this->pkg_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('long',$this->long,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('membership',$this->membership);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('cpanel_logo',$this->cpanel_logo,true);
		$criteria->compare('urlid',$this->urlid,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('site',$this->site,true);
		$criteria->compare('statid',$this->statid);
		$criteria->compare('apiKey',$this->apiKey,true);
		$criteria->compare('rating',$this->rating,true);
		$criteria->compare('currency_code',$this->currency_code,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('active',$this->active);
		
	   //$criteria->together = true;
       //$criteria->compare('t.accid',$this->accid,true);
       //$criteria->with = array('acc');
      // $criteria->compare('title',$this->acc,true,"OR");
      if(Yii::app()->session['User']['UserType']!= 'admin'){
		$criteria->addSearchCondition('accid', Yii::app()->session['User']['UserOwnerID']);
	  }
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BusinessUnit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
