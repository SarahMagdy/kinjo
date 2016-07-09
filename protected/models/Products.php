<?php

/**
 * This is the model class for table "products".
 *
 * The followings are the available columns in table 'products':
 * @property integer $pid
 * @property integer $buid
 * @property integer $csid
 * @property string $sku
 * @property string $title
 * @property string $discription
 * @property string $price
 * @property integer $instock
 * @property string $qrcode
 * @property string $barcode
 * @property string $nfc
 * @property string $hash
 * @property integer $bookable
 * @property string $barcode
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Favorites[] $favorites
 * @property Offers[] $offers
 * @property OrdersDetails[] $ordersDetails
 * @property PdConfV[] $pdConfVs
 * @property ProductRating[] $productRatings
 * @property BusinessUnit $bu
 * @property Catsub $cs
 * @property ProductsImgs[] $productsImgs
 */
class Products extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('buid, csid, instock, bookable', 'numerical', 'integerOnly'=>true),
			array('sku, title', 'length', 'max'=>45),
			array('discription', 'length', 'max'=>500),
			array('price', 'length', 'max'=>15),
			array('qrcode, barcode, nfc, hash', 'length', 'max'=>250),
			array('rating', 'length', 'max'=>5),
			array('created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pid, buid, csid, sku, title, discription, price, instock, qrcode,barcode, nfc, hash, bookable, created', 'safe', 'on'=>'search'),
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
			'favorites' => array(self::HAS_MANY, 'Favorites', 'pid'),
			'offers' => array(self::HAS_MANY, 'Offers', 'pid'),
			'ordersDetails' => array(self::HAS_MANY, 'OrdersDetails', 'pid'),
			'pdConfVs' => array(self::HAS_MANY, 'PdConfV', 'pdconfv_pid'),
			'productRatings' => array(self::HAS_MANY, 'ProductRating', 'pid'),
			'bu' => array(self::BELONGS_TO, 'BusinessUnit', 'buid'),
			'cs' => array(self::BELONGS_TO, 'Catsub', 'csid'),
			'productsImgs' => array(self::HAS_MANY, 'ProductsImgs', 'pid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pid' => 'Product ID',
			'buid' => 'Business Unit ID',
			'csid' => 'Category ID',
			'sku' => 'SKU',
			'title' => 'Product Name',
			'discription' => 'Description',
			'price' => 'Price',
			'instock' => 'Instock',
			'qrcode' => 'QR-Code',
			'barcode' => 'Barcode',
			'nfc' => 'NFC',
			'hash' => 'Hash',
			'bookable' => 'Bookable',
			'rating' => 'Rating',
			'created' => 'Created',
			'catsub_title'=>'Category',
			'business_unit_title'=>'Business',
			'name'=>'Name',
			'value'=>'Value',
			'img'=>'Images',
			'conf'=>'Configration',
			
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

		$criteria->compare('pid',$this->pid);
		$criteria->compare('buid',$this->buid);
		$criteria->compare('csid',$this->csid);
		$criteria->compare('sku',$this->sku,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('discription',$this->discription,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('instock',$this->instock);
		$criteria->compare('qrcode',$this->qrcode,true);
		$criteria->compare('barcode',$this->barcode,true);
		$criteria->compare('nfc',$this->nfc,true);
		$criteria->compare('hash',$this->hash,true);
		$criteria->compare('bookable',$this->bookable);
		$criteria->compare('rating',$this->rating,true);
		$criteria->compare('created',$this->created,true);
		
		
		// $criteria->together = true; 
        // $criteria->compare('t.buid',$this->buid,true);
        // $criteria->with = array('bu');
        // $criteria->compare('title',$this->bu,true,"OR");
		$criteria->addSearchCondition('buid', Yii::app()->session['User']['UserBuid']);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Products the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
