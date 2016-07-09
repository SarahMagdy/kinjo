<?php

/**
 * This is the model class for table "orders_details".
 *
 * The followings are the available columns in table 'orders_details':
 * @property integer $ord_det_id
 * @property integer $ord_id
 * @property integer $ord_buid
 * @property integer $reserved_bu
 * @property integer $pid
 * @property string $item
 * @property integer $qnt
 * @property double $disc
 * @property double $price
 * @property double $fees
 * @property double $final_price
 * @property double $convert_price
 * @property double $dollor_price
 * @property integer $pay_type
 * @property integer $cust_billingAddr
 * @property integer $cust_shipAddr
 * @property integer $app_source
 * @property string $close_date
 * @property string $created
 *
 * The followings are the available model relations:
 * @property OrdersDetailConf[] $ordersDetailConfs
 * @property BusinessUnit $ordBu
 * @property Orders $ord
 * @property Products $p
 */
class OrdersDetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orders_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item, created', 'required'),
			array('ord_id, ord_buid, reserved_bu, pid, qnt, pay_type, cust_billingAddr, cust_shipAddr, app_source', 'numerical', 'integerOnly'=>true),
			array('disc, price, fees, final_price, convert_price, dollor_price', 'numerical'),
			array('item', 'length', 'max'=>100),
			array('close_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ord_det_id, ord_id, ord_buid, reserved_bu, pid, item, qnt, disc, price, fees, final_price, convert_price, dollor_price, pay_type, cust_billingAddr, cust_shipAddr, app_source, close_date, created', 'safe', 'on'=>'search'),
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
			'ordersDetailConfs' => array(self::HAS_MANY, 'OrdersDetailConf', 'ord_de_conf_de_id'),
			'ordBu' => array(self::BELONGS_TO, 'BusinessUnit', 'ord_buid'),
			'ord' => array(self::BELONGS_TO, 'Orders', 'ord_id'),
			'p' => array(self::BELONGS_TO, 'Products', 'pid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ord_det_id' => 'Ord Det',
			'ord_id' => 'Ord',
			'ord_buid' => 'Ord Buid',
			'reserved_bu' => 'Reserved Bu',
			'pid' => 'Pid',
			'item' => 'Item',
			'qnt' => 'Qnt',
			'disc' => 'Disc',
			'price' => 'Price',
			'fees' => 'Fees',
			'final_price' => 'Final Price',
			'convert_price' => 'Convert Price',
			'dollor_price' => 'Dollor Price',
			'pay_type' => 'Pay Type',
			'cust_billingAddr' => 'Cust Billing Addr',
			'cust_shipAddr' => 'Cust Ship Addr',
			'app_source' => 'App Source',
			'close_date' => 'Close Date',
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

		$criteria->compare('ord_det_id',$this->ord_det_id);
		$criteria->compare('ord_id',$this->ord_id);
		$criteria->compare('ord_buid',$this->ord_buid);
		$criteria->compare('reserved_bu',$this->reserved_bu);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('item',$this->item,true);
		$criteria->compare('qnt',$this->qnt);
		$criteria->compare('disc',$this->disc);
		$criteria->compare('price',$this->price);
		$criteria->compare('fees',$this->fees);
		$criteria->compare('final_price',$this->final_price);
		$criteria->compare('convert_price',$this->convert_price);
		$criteria->compare('dollor_price',$this->dollor_price);
		$criteria->compare('pay_type',$this->pay_type);
		$criteria->compare('cust_billingAddr',$this->cust_billingAddr);
		$criteria->compare('cust_shipAddr',$this->cust_shipAddr);
		$criteria->compare('app_source',$this->app_source);
		$criteria->compare('close_date',$this->close_date,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrdersDetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
