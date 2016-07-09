<?php

/**
 * This is the model class for table "prod_colors".
 *
 * The followings are the available columns in table 'prod_colors':
 * @property integer $color_id
 * @property integer $color_pid
 * @property string $color_code
 * @property string $color_name
 *
 * The followings are the available model relations:
 * @property Products $colorP
 */
class ProdColors extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'prod_colors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('color_pid', 'numerical', 'integerOnly'=>true),
			array('color_code', 'length', 'max'=>6),
			array('color_name', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('color_id, color_pid, color_code, color_name', 'safe', 'on'=>'search'),
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
			'colorP' => array(self::BELONGS_TO, 'Products', 'color_pid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'color_id' => 'Color',
			'color_pid' => 'Color Pid',
			'color_code' => 'Color Code',
			'color_name' => 'Color Name',
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

		$criteria->compare('color_id',$this->color_id);
		$criteria->compare('color_pid',$this->color_pid);
		$criteria->compare('color_code',$this->color_code,true);
		$criteria->compare('color_name',$this->color_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProdColors the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
