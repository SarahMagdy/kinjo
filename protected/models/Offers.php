<?php

/**
 * This is the model class for table "offers".
 *
 * The followings are the available columns in table 'offers':
 * @property integer $ofid
 * @property integer $pid
 * @property string $title
 * @property string $text
 * @property string $discount
 * @property integer $active
 * @property string $from
 * @property string $to
 * @property integer $scheduled
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Products $p
 */
class Offers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'offers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pid, active, scheduled', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>45),
			array('text', 'length', 'max'=>200),
			array('discount', 'length', 'max'=>6),
			array('from, to, created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ofid, pid, title, text, discount, active, from, to, scheduled, created', 'safe', 'on'=>'search'),
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
			'p' => array(self::BELONGS_TO, 'Products', 'pid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ofid' => 'Offer ID',
			'pid' => 'Pid',
			'title' => 'Title',
			'text' => 'Text',
			'discount' => 'Discount',
			'active' => 'Active',
			'from' => 'From',
			'to' => 'To',
			'scheduled' => 'Scheduled',
			'created' => 'Created',
			'pro_name' => 'Product Name',
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

		$criteria->compare('ofid',$this->ofid);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('discount',$this->discount,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('`from`',' <='.$this->from,true);
		$criteria->compare('`to`',' >='.$this->to,true);
		$criteria->compare('scheduled',$this->scheduled);
		$criteria->compare('created',$this->created,true);
		
		$criteria->with = 'p';
		$criteria->addSearchCondition('buid', Yii::app()->session['User']['UserBuid']);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Offers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
