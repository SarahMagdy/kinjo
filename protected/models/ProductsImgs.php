<?php

/**
 * This is the model class for table "products_imgs".
 *
 * The followings are the available columns in table 'products_imgs':
 * @property integer $pimgid
 * @property integer $pid
 * @property string $pimg_url
 * @property string $pimg_thumb
 *
 * The followings are the available model relations:
 * @property Products $p
 */
class ProductsImgs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'products_imgs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pid, pimg_url, pimg_thumb', 'required'),
			array('pid', 'numerical', 'integerOnly'=>true),
			array('pimg_url, pimg_thumb', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pimgid, pid, pimg_url, pimg_thumb', 'safe', 'on'=>'search'),
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
			'pimgid' => 'Pimgid',
			'pid' => 'Pid',
			'pimg_url' => 'Pimg Url',
			'pimg_thumb' => 'Pimg Thumb',
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

		$criteria->compare('pimgid',$this->pimgid);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('pimg_url',$this->pimg_url,true);
		$criteria->compare('pimg_thumb',$this->pimg_thumb,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductsImgs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
