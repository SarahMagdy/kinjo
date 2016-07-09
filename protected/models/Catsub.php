<?php

/**
 * This is the model class for table "catsub".
 *
 * The followings are the available columns in table 'catsub':
 * @property integer $csid
 * @property integer $parent_id
 * @property integer $catsub_buid
 * @property string $title
 * @property string $desription
 * @property string $img_thumb
 * @property string $img_url
 * @property string $created
 *
 * The followings are the available model relations:
 * @property BusinessUnit $catsubBu
 * @property Catsub $parent
 * @property Catsub[] $catsubs
 * @property Products[] $products
 * @property Subscriptions[] $subscriptions
 */
class Catsub extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catsub';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_id, catsub_buid', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>45),
			array('desription', 'length', 'max'=>500),
			array('img_thumb, img_url', 'length', 'max'=>200),
			array('created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('csid, parent_id, catsub_buid, title, desription, img_thumb, img_url, created', 'safe', 'on'=>'search'),
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
			'catsubBu' => array(self::BELONGS_TO, 'BusinessUnit', 'catsub_buid'),
			'parent' => array(self::BELONGS_TO, 'Catsub', 'parent_id'),
			'catsubs' => array(self::HAS_MANY, 'Catsub', 'parent_id'),
			'products' => array(self::HAS_MANY, 'Products', 'csid'),
			'subscriptions' => array(self::HAS_MANY, 'Subscriptions', 'csid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'csid' => 'Category /subcategory ID',
			'parent_id' => 'Parent',
			'catsub_buid' => 'Catsub Buid',
			'title' => 'Title',
			'desription' => 'Desription',
			'img_thumb' => 'Img Thumb',
			'img_url' => 'Image Url',
			'created' => 'Created',
			'catsub_title'=>'Category',
			'img'=>'Image',
			'business_unit'=>'Business Unit'
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

		$criteria->compare('csid',$this->csid);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('catsub_buid',$this->catsub_buid);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('desription',$this->desription,true);
		$criteria->compare('img_thumb',$this->img_thumb,true);
		$criteria->compare('img_url',$this->img_url,true);
		$criteria->compare('created',$this->created,true);
		$criteria->addSearchCondition('catsub_buid', Yii::app()->session['User']['UserBuid']);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Catsub the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
