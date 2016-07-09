<?php

/**
 * This is the model class for table "pd_conf_v".
 *
 * The followings are the available columns in table 'pd_conf_v':
 * @property integer $pdconfv_id
 * @property integer $pdconfv_pid
 * @property integer $pdconfv_confid
 * @property double $pdconfv_value
 *
 * The followings are the available model relations:
 * @property PdConfig $pdconfvConf
 * @property Products $pdconfvP
 */
class PdConfV extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pd_conf_v';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pdconfv_pid, pdconfv_confid', 'numerical', 'integerOnly'=>true),
			array('pdconfv_value', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pdconfv_id, pdconfv_pid, pdconfv_confid, pdconfv_value', 'safe', 'on'=>'search'),
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
			'pdconfvConf' => array(self::BELONGS_TO, 'PdConfig', 'pdconfv_confid'),
			'pdconfvP' => array(self::BELONGS_TO, 'Products', 'pdconfv_pid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pdconfv_id' => 'Pdconfv',
			'pdconfv_pid' => 'Pdconfv Pid',
			'pdconfv_confid' => 'Pdconfv Confid',
			'pdconfv_value' => 'Pdconfv Value',
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

		$criteria->compare('pdconfv_id',$this->pdconfv_id);
		$criteria->compare('pdconfv_pid',$this->pdconfv_pid);
		$criteria->compare('pdconfv_confid',$this->pdconfv_confid);
		$criteria->compare('pdconfv_value',$this->pdconfv_value);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PdConfV the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
