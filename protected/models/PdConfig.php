<?php

/**
 * This is the model class for table "pd_config".
 *
 * The followings are the available columns in table 'pd_config':
 * @property integer $cfg_id
 * @property integer $parent_id
 * @property integer $conf_buid
 * @property string $name
 * @property string $value
 * @property integer $conf_chkrad
 *
 * The followings are the available model relations:
 * @property PdConfV[] $pdConfVs
 * @property BusinessUnit $confBu
 * @property PdConfig $parent
 * @property PdConfig[] $pdConfigs
 * @property PdConfigLang[] $pdConfigLangs
 */
class PdConfig extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pd_config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_id, conf_buid, conf_chkrad', 'numerical', 'integerOnly'=>true),
			array('name, value', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cfg_id, parent_id, conf_buid, name, value, conf_chkrad', 'safe', 'on'=>'search'),
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
			'pdConfVs' => array(self::HAS_MANY, 'PdConfV', 'pdconfv_confid'),
			'confBu' => array(self::BELONGS_TO, 'BusinessUnit', 'conf_buid'),
			'parent' => array(self::BELONGS_TO, 'PdConfig', 'parent_id'),
			'pdConfigs' => array(self::HAS_MANY, 'PdConfig', 'parent_id'),
			'pdConfigLangs' => array(self::HAS_MANY, 'PdConfigLang', 'conf_lang_conf_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cfg_id' => 'Config ID',
			'parent_id' => 'Parent',
			'conf_buid' => 'Conf Buid',
			'name' => 'Name',
			'value' => 'Value',
			'conf_chkrad' => 'Checkable OR Radio',
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

		$criteria->compare('cfg_id',$this->cfg_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('conf_buid',$this->conf_buid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('conf_chkrad',$this->conf_chkrad);
		$criteria->addSearchCondition('conf_buid', Yii::app()->session['User']['UserBuid']);
		
		
		// $criteria->together = true; 
        // $criteria->compare('t.parent_id',$this->cfg_id,true);
        // $criteria->with = array('parent');
        // $criteria->compare('$this->parent->name',$this->parent,true,"OR");
		
		
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PdConfig the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
