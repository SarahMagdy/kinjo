<?php

class CustomersController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function init() {
        parent::init();
        Yii::app()->language = Yii::app()->session['Language']['UserLang'];
    }

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete','RoomCustomers','SetupRooms','UpdateRoomQrcode', 'AjaxRoomassign', 'AjaxRoomnumber', 'sendNotify', 'ajaxSendNotify', 'ResetPassword', 'ajaxSaveNewMess', 'AssignRooms'),
                'users' => array('*'),
            ),
            /*
              array('allow', // allow authenticated user to perform 'create' and 'update' actions
              'actions'=>array('create','update'),
              'users'=>array('@'),
              ),
              array('allow', // allow admin user to perform 'admin' and 'delete' actions
              'actions'=>array('admin','delete'),
              'users'=>array('admin'),
              ), */
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        Login::UserAuth('Customers', 'View');
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        Login::UserAuth('Customers', 'Create');
        $model = new Customers;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Customers'])) {
            $_POST['Customers'] = CI_Security::ChkPost($_POST['Customers']);
            $model->attributes = $_POST['Customers'];
            $model->password = md5($model->password);
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->cid));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionAssignRooms() {
        Login::UserAuth('Customers', 'AssignRooms');
        $s3=array();
        $_POST = CI_Security::ChkPost($_POST);
        $BuID = isset(Yii::app()->session['User']['UserBuid']) ? Yii::app()->session['User']['UserBuid'] : 0;


        if ($_SERVER["REQUEST_METHOD"] == "POST" && $BuID > 0) {

            if (isset($_POST['customer']) && isset($_POST['startdate'])&& isset($_POST['enddate']) && isset($_POST['customer'])&& isset($_POST['number'])) {
               
              
                $startdate = $_POST['startdate'];               
                $enddate = $_POST['enddate'];
                if($enddate>$startdate){
                $customer=$_POST['customer'];            
                $query="SELECT id FROM rooms where room_number=".$_POST['number']."";
              
                $id=Yii::app()->db->createCommand($query)->queryRow();
                var_dump($id);
                
                
                $roomCustomerid=Yii::app()->db->createCommand("SELECT id FROM room_customers where id=".$id['id']."")->queryRow();
                if($roomCustomerid){
                  $sql=  Yii::app()->db->createCommand("update room_customers "
                      . " set Cust_id=".$customer.",business_id=".$BuID.",checkin_date='".$startdate."',checkout_date= '".$enddate."' where id=".$id['id']."")->execute();     
              
            }
            else{
                 $sql2=  Yii::app()->db->createCommand("insert into room_customers (id,Cust_id,business_id,checkin_date,checkout_date)"
                         . " values( ".$id['id'].",".$customer.",".$BuID.",'".$startdate."','".$enddate."')")->execute();
            }
          
        }
        
        else{
            echo "end date must be greater than start date";
        }
        
    }
    
    $this->redirect(array('/Customers/RoomCustomers'));
        }
    $this->render('AssignRooms', array('s3' => $s3));
    RoomCustomers();
    }
    
   public function actionRoomCustomers()
{
    $model=new RoomCustomers;

    // uncomment the following code to enable ajax-based validation
    /*
    if(isset($_POST['ajax']) && $_POST['ajax']==='room-customers-RoomCustomers-form')
    {
        echo CActiveForm::validate($model);
        Yii::app()->end();
    }
    */

    if(isset($_POST['RoomCustomers']))
    {
        $model->attributes=$_POST['RoomCustomers'];
        if($model->validate())
        {
            // form inputs are valid, do something here
            return;
        }
    }
    $this->render('RoomCustomers',array('model'=>$model));
}

    public function actionAjaxRoomassign() {
        $s3 = array();
        if (isset($_POST['startdate'])) {
            $roomtype = Yii::app()->db->createCommand("select * from roomtype")->queryAll();
            foreach ($roomtype AS $roomtypekey => $roomtypeRow) {
                array_push($s3, array('room_id' => $roomtypeRow['room_id'],
                    'roomprice' => $roomtypeRow['room_price'],
                    'roomtype' => $roomtypeRow['room_type']));
            }
        }
        echo json_encode($s3);
    }

    public function actionAjaxRoomnumber() {
       
        if (isset($_POST['roomid'])) {

             $roomNo=array();
            $roomnumber = Yii::app()->db->createCommand("SELECT id,room_number FROM rooms JOIN "
                            . "roomtype ON rooms.roomtype = roomtype.room_id AND roomtype.room_id=" . $_POST['roomid'] . " ")->queryAll();
            

            foreach ($roomnumber AS $roomNokey => $roomNoRow) {
                array_push($roomNo, array(
                    'id'=>$roomNoRow['id'],
                    'room_number' => $roomNoRow['room_number']));
            }
        }
         echo json_encode($roomNo);
    }
    
    
    public function actionSetupRooms()
    {
        Login::ChkAuthType('Customers', 'SetupRooms');
        Login::ChkBuSess();
        $_POST = CI_Security::ChkPost($_POST);
        $BuID = isset(Yii::app()->session['User']['UserBuid']) ? Yii::app()->session['User']['UserBuid'] : 0;
        $Data = array();
             if ($_SERVER["REQUEST_METHOD"] == "POST" && $BuID > 0) {
            Yii::app()->db->createCommand(" Delete FROM room_customers WHERE business_id = " . $BuID)->execute();      
            Yii::app()->db->createCommand(" Delete FROM rooms WHERE business_id = " . $BuID)->execute();
         
            $RoomsSetArr = $_POST;
        
            $Num = 1;
            $InsRooms = " INSERT INTO rooms (room_number,business_id,roomtype) VALUES ";
            foreach ($RoomsSetArr as $Key => $Val) {
                if ($Val > 0) {
                    $Roomtype = ltrim($Key, 'room');
                    
                    $Roomtype = rtrim($Roomtype, 'type');
              
                    for ($i = 0; $i < $Val; $i ++) {

                        $InsRooms .= " (" . $Num . "," . $BuID . "," . $Roomtype . "),";
                        $Num++;
                    }
                }
            }
            $InsRooms = rtrim($InsRooms, ',');
            echo Yii::app()->db->createCommand($InsRooms)->execute();
            $this->UpdateRoomQrcode();
        }

        $TablesSQL = " SELECT 
        IFNULL((SELECT COUNT(id)FROM rooms WHERE business_id = " . $BuID . " AND roomtype = 1),'')  AS typeGarden,
        IFNULL((SELECT COUNT(id)FROM rooms WHERE business_id = " . $BuID . " AND roomtype = 2),'')  AS typeStreet,
        IFNULL((SELECT COUNT(id)FROM rooms WHERE business_id = " . $BuID . " AND roomtype = 3),'')  AS typeOcean	
	FROM rooms WHERE business_id = " . $BuID . " LIMIT 0,1";
        $roomsRow = Yii::app()->db->createCommand($TablesSQL)->queryRow();
        $Data['typeGarden'] = $roomsRow['typeGarden'];
        $Data['typeStreet'] = $roomsRow['typeStreet'];
        $Data['typeOcean'] = $roomsRow['typeOcean'];      
        $this->render('SetupRooms', array('Data' => $Data));
        
    }
    
    private function UpdateRoomQrcode() {
        $BuID = isset(Yii::app()->session['User']['UserBuid']) ? Yii::app()->session['User']['UserBuid'] : 0;

        if ($BuID > 0) {

            $RoomsSQl = " SELECT * FROM rooms WHERE business_id = " . $BuID;
            $RoomsData = Yii::app()->db->createCommand($RoomsSQl)->queryAll();

            $Cipher = new Cipher('secret');

            $UpSQl = " UPDATE rooms SET room_QR_code = CASE ";
            $roomIDs = " ";

            foreach ($RoomsData as $Key => $Row) {

                $EncryptedTxt = $Row['business_id'] . '-' . $BuID . '-' .$Row['roomtype'].'-type'. date(time());
                $EncryptedQr = $Cipher->encrypt($EncryptedTxt);

                $UpSQl .= " WHEN business_id = " . $Row['business_id'] . " THEN '" . $EncryptedQr . "' ";
                $roomIDs .= $Row['id'] . ',';
            }
            $roomIDs = rtrim($roomIDs, ',');
            $UpSQl .= " END WHERE business_id = " . $BuID . " AND id IN (" . $roomIDs . ")";
            Yii::app()->db->createCommand($UpSQl)->execute();
        }
    }
    

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        Login::UserAuth('Customers', 'Update');
        $model = $this->loadModel($id);

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Customers'])) {
            $_POST['Customers'] = CI_Security::ChkPost($_POST['Customers']);
            $model->attributes = $_POST['Customers'];
            $model->password = md5($model->password);
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->cid));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        Login::UserAuth('Customers', 'Delete');
        $this->loadModel($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
// $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            if (isset($_POST['returnUrl'])) {
// $_POST['returnUrl'];
                $_POST['returnUrl'] = CI_Security::ChkPost($_POST['returnUrl']);
            } else {
                $this->redirect(array('admin'));
            }
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        Login::UserAuth('Customers', 'Index');
        $dataProvider = new CActiveDataProvider('Customers');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        Login::UserAuth('Customers', 'Admin');
        $model = new Customers('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Customers'])) {
            $_GET['Customers'] = CI_Security::ChkPost($_GET['Customers']);
            $model->attributes = $_GET['Customers'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Customers the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Customers::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionSendNotify() {
        Login::UserAuth('Customers', 'SendNotify');
        $model = new Customers;

        $Buid = isset(Yii::app()->session['User']['UserBuid']) ? Yii::app()->session['User']['UserBuid'] : 0;

        $BuLoc = Yii::app()->db->createCommand("SELECT * FROM business_unit WHERE buid =" . $Buid)->queryRow();
        $BuSetting = Yii::app()->db->createCommand("SELECT * FROM bu_setting WHERE (bu_setting_name = 'general_notify' OR bu_setting_name = 'diameter')AND bu_setting_bu_id =" . $Buid)->queryAll();

        $BUType = '0';
        $Dia = '0';
        if (empty($BuSetting)) {

            $BUType = '0';
        } else {

            foreach ($BuSetting as $key => $row) {

                if ($row['bu_setting_name'] == 'general_notify') {
                    $BUType = $row['bu_setting_val'];
                }
                if ($row['bu_setting_name'] == 'diameter') {
                    $Dia = $row['bu_setting_val'];
                }
            }
        }
        $CustSQLO = " SELECT cid FROM orders LEFT JOIN orders_details ON orders.ord_id = orders_details.ord_id WHERE ord_buid = " . $Buid;

        $CustSQLS = " SELECT cid FROM subscriptions WHERE buid = " . $Buid;

        $CustSQLL = " SELECT cid FROM customers
WHERE lat  BETWEEN ('" . $BuLoc['lat'] . "' - (1.0 / 111.045)) AND (" . $BuLoc['lat'] . " + (" . $Dia . " / 111.045))
AND `long` BETWEEN (" . $BuLoc['long'] . " - (1.0 / (111.045 * COS(RADIANS(" . $BuLoc['lat'] . "))))) 
AND (" . $BuLoc['long'] . " + (" . $Dia . " / (111.045 * COS(RADIANS(" . $BuLoc['lat'] . ")))))";

        $CustWhr = " WHERE ";

        if ($BUType == '0') {
            $CustWhr .= " (cid IN (" . $CustSQLO . ") OR cid IN (" . $CustSQLS . ")) ";
        }
        if ($BUType == '1') {
            $CustWhr .= "  (cid IN (" . $CustSQLO . ") OR cid IN (" . $CustSQLS . "))  AND cid IN (" . $CustSQLL . ")";
        }
        if ($BUType == '2') {
            $CustWhr .= " cid IN (" . $CustSQLL . ") ";
        }
        $CustSQL = " SELECT * FROM customers " . $CustWhr;

        $CustData = Yii::app()->db->createCommand($CustSQL)->queryAll();

        $Buid = isset(Yii::app()->session['User']['UserBuid']) ? Yii::app()->session['User']['UserBuid'] : 0;
        $messages = Yii::app()->db->createCommand(' SELECT * FROM messages WHERE buid = ' . $Buid)->queryAll();

        $this->render('sendnotify', array(
            'CustData' => $CustData,
            'messages' => $messages,
            'model' => $model
        ));
    }

    public function actionAjaxSendNotify() {
        Login::UserAuth('Customers', 'SendNotify');
        $RegsArr = array();
        $SQl = " ";


        $_POST = CI_Security::ChkPost($_POST);
// print_r($_POST);
// return;

        if ($_POST['NotifyType'] == 0) {

            $CustID = isset($_POST['CustID']) ? $_POST['CustID'] > 0 ? $_POST['CustID'] : 0 : 0;
            $SQl = " SELECT puid,cid,gcm_regid FROM push_notifications 
WHERE puid = (SELECT puid FROM push_notifications WHERE cid = " . $CustID . " ORDER BY count_dev DESC LIMIT 0,1)";
        } elseif ($_POST['NotifyType'] == 1) {

//$SQl = "SELECT gcm_regid FROM push_notifications WHERE cid in (".implode(',',$_POST['CustIDs']).")" ;
            $SQl = " SELECT puid,cid,gcm_regid FROM 
(SELECT puid,cid,gcm_regid FROM push_notifications WHERE cid in (" . implode(',', $_POST['CustIDs']) . ") 
ORDER BY count_dev DESC )AS T_Push GROUP BY cid ";
        } elseif ($_POST['NotifyType'] == 2) {

            $Buid = isset(Yii::app()->session['User']['UserBuid']) ? Yii::app()->session['User']['UserBuid'] : 0;

            $BuLoc = Yii::app()->db->createCommand("SELECT * FROM business_unit WHERE buid =" . $Buid)->queryRow();
            $BuSetting = Yii::app()->db->createCommand("SELECT * FROM bu_setting WHERE (bu_setting_name = 'general_notify' OR bu_setting_name = 'diameter')AND bu_setting_bu_id =" . $Buid)->queryAll();

            $BUType = '0';
            $Dia = '0';
            if (empty($BuSetting)) {

                $BUType = '0';
            } else {
                foreach ($BuSetting as $key => $row) {

                    if ($row['bu_setting_name'] == 'general_notify') {
                        $BUType = $row['bu_setting_val'];
                    }
                    if ($row['bu_setting_name'] == 'diameter') {
                        $Dia = $row['bu_setting_val'];
                    }
                }
            }

            $CustSQLO = "SELECT cid FROM orders LEFT JOIN orders_details ON orders.ord_id = orders_details.ord_id WHERE ord_buid = " . $Buid;

            $CustSQLS = "SELECT cid FROM subscriptions WHERE buid = " . $Buid;

            $CustSQLL = " SELECT cid FROM customers
WHERE lat  BETWEEN ('" . $BuLoc['lat'] . "' - (1.0 / 111.045)) AND (" . $BuLoc['lat'] . " + (" . $Dia . " / 111.045))
AND `long` BETWEEN (" . $BuLoc['long'] . " - (1.0 / (111.045 * COS(RADIANS(" . $BuLoc['lat'] . "))))) 
AND (" . $BuLoc['long'] . " + (" . $Dia . " / (111.045 * COS(RADIANS(" . $BuLoc['lat'] . ")))))";

            $S_Where = " WHERE SUBSTRING(notify_enable, 1, 1 ) = 0 ";

            if ($BUType == '0') {
                $S_Where .= " AND (push_notifications.cid IN (" . $CustSQLO . ") OR push_notifications.cid IN (" . $CustSQLS . ")) ";
            }
            if ($BUType == '1') {
                $S_Where .= " AND (push_notifications.cid IN (" . $CustSQLO . ") OR push_notifications.cid IN (" . $CustSQLS . "))  AND push_notifications.cid IN (" . $CustSQLL . ")";
            }
            if ($BUType == '2') {
                $S_Where .= " AND  push_notifications.cid IN (" . $CustSQLL . ") ";
            }



            if (isset($_POST['Filters'])) {

                if (isset($_POST['Filters']['age'])) {

                    $S_Where .= " AND TIMESTAMPDIFF(YEAR,birthdate,CURDATE()) " . $_POST['Filters']['age'];
                }
                if (isset($_POST['Filters']['gender'])) {

                    $S_Where .= " AND gender = " . $_POST['Filters']['gender'];
                }
            }

            $SQl = " SELECT puid,gcm_regid,cid FROM 
(SELECT puid,gcm_regid,push_notifications.cid 
FROM push_notifications 
LEFT JOIN customers ON customers.cid = push_notifications.cid " . $S_Where . " 
ORDER BY count_dev DESC )AS T_Push GROUP BY cid ";
        }

        $CustRegs = Yii::app()->db->createCommand($SQl)->queryAll();

        if (count($CustRegs) > 0) {

            $SQLMess = " INSERT INTO messages_log (mid,cid,puid,is_group) VALUES ";

            foreach ($CustRegs as $key => $row) {

                array_push($RegsArr, $row['gcm_regid']);

                $SQLMess .= " (" . $_POST['MessID'] . "," . $row['cid'] . "," . $row['puid'] . ", 0),";
            }

            $SQLMess = substr($SQLMess, 0, -1);

            Yii::app()->db->createCommand($SQLMess)->execute();

            $ResArr = array();
            $ResArr['Type'] = 'General';
            $ResArr['Mess'] = trim($_POST['MessTXT']);
            $ResArr['Data'] = array();
//echo GCM::SendNotification($RegsArr, $_POST['MessTXT']);
            echo GCM::SendNotification($RegsArr, json_encode($ResArr));
        }
    }

    public function actionResetPassword() {

        $Q = Yii::app()->getRequest()->getQuery('q');
        $Mess = '';
        $CustID = 0;

        if ($Q != '') {

            $CustSql = " SELECT cid FROM customers WHERE q_code = '" . $Q . "'";
            $CustRes = Yii::app()->db->createCommand($CustSql)->queryRow();
            $CustID = $CustRes['cid'];
        } else {

// $CustID = isset($_POST['CustID'])?$_POST['CustID']:0;
            $CustID = 0;
            if (isset($_POST['CustID'])) {
                $CustID = CI_Security::ChkPost($_POST['CustID']);
            }


            if ($CustID > 0) {

                $UpCustSql = " UPDATE customers SET password = '" . md5($_POST['password']) . "' , q_code = '' WHERE cid = " . $CustID;
                $CustRes = Yii::app()->db->createCommand($UpCustSql)->execute();

                if ($CustRes >= 0) {

                    $Mess = 'Password Reset Succeded';
                };
            } else {

                $Mess = 'Request Reset Password again';
            }
        }
        $this->renderPartial('reset_pass', array('CustID' => $CustID, 'Mess' => $Mess));
    }

    public function actionAjaxSaveNewMess() {
        Login::UserAuth('Customers', 'SendNotify');
        $Buid = isset(Yii::app()->session['User']['UserBuid']) ? Yii::app()->session['User']['UserBuid'] : 0;

        $_POST = CI_Security::ChkPost($_POST);
        Yii::app()->db->createCommand("INSERT INTO messages(buid,message) VALUES (" . $Buid . ",'" . $_POST['MessTXT'] . "')")->execute();
        echo Yii::app()->db->getLastInsertID();
    }

    /**
     * Performs the AJAX validation.
     * @param Customers $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'customers-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
