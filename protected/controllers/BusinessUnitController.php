<?php

class BusinessUnitController extends Controller {

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
                'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'getStoresLangs',
                    'ContactUS', 'ajaxContactUS', 'AjaxDeletetable', 'ajaxDelContactUS', 'assignTables', 'totalTables', 'CheckTables',
                    'EditSerial', 'ReCreateQrCode', 'DownLoadQrCode', 'DeleteTable', 'AddTable', 'GetAvUnitsByD'),
                'users' => array('*'),
            ),
            /* 		array('allow', // allow authenticated user to perform 'create' and 'update' actions
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
        Login::UserAuth('BusinessUnit', 'View');
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        Login::UserAuth('BusinessUnit', 'Create');

        $model = new BusinessUnit;
        $AccData = BuAccounts::model()->findAll(array("select" => "accid,fname,lname,special_deal_id"));
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['BusinessUnit'])) {
            $_POST['BusinessUnit'] = CI_Security::ChkPost($_POST['BusinessUnit']);
            $model->attributes = $_POST['BusinessUnit'];
            $rnd = $random = date(time());
            //$uploadedLogo = CUploadedFile::getInstance($model, 'logo');
            //----------Logo
            $uploadedLogo = new upload($_FILES['logo']);
            $LogoName = "";
            //if($uploadedLogo != null){
            if ($uploadedLogo->file_src_name != null) {
                //$LogoName = "{$rnd}-{$uploadedLogo}";
                $LogoName = "{$rnd}-$uploadedLogo->file_src_name_body";
                $LogoName = md5($LogoName);
                $model->logo = $LogoName . '.' . $uploadedLogo->file_src_name_ext;
            } else {
                $LogoName = 'default.jpg';
                $model->logo = $LogoName;
            }
            //-----------Icon Marker
            $uploadedIcon = new upload($_FILES['urlid']);
            $IconName = "";
            if ($uploadedIcon->file_src_name != null) {
                $IconName = "{$rnd}-$uploadedIcon->file_src_name_body";
                $IconName = md5($IconName . 'Icon');
                $model->urlid = $IconName . '.' . $uploadedIcon->file_src_name_ext;
            } else {
                $IconName = 'icon.png';
                $model->urlid = $IconName;
            }
            //-----------Cpanel Logo
            $uploadedCpLogo = new upload($_FILES['cpanel_logo']);
            $CpLogoName = "";
            if ($uploadedCpLogo->file_src_name != null) {
                $CpLogoName = "{$rnd}-$uploadedCpLogo->file_src_name_body";
                $CpLogoName = md5($CpLogoName . 'cplogo');
                $model->cpanel_logo = $CpLogoName . '.' . $uploadedCpLogo->file_src_name_ext;
            } else {
                $CpLogoName = 'logo.png';
                $model->cpanel_logo = $CpLogoName;
            }


            $model->membership = 1;

            $model->site = 0;
            $model->statid = 0;
            $model->rating = 0;
            $model->apiKey = md5(uniqid(mt_rand(), true)); // $better_token = md5(uniqid(mt_rand(),true));

            if ($model->save()) {

                if ($uploadedLogo != null) {

                    //$RealPath = realpath($_SERVER['SERVER_NAME'].'/images/upload/business_unit');

                    $RealArr = Globals::ReturnGlobals();
                    $RealPath = $RealArr['ImgPath'] . 'business_unit/';
                    /*
                      $uploadedLogo->saveAs($RealPath.$LogoName,false);
                      $image = new EasyImage($RealPath.$LogoName);
                      $image->resize(100, 100);
                      $image->save($RealPath.'thumbnails/'.$LogoName); */
                    // ---- save resized image -------------
                    $uploadedLogo->file_new_name_body = $LogoName;
                    $uploadedLogo->image_resize = true;
                    $uploadedLogo->image_ratio = true;
                    $uploadedLogo->image_x = strstr($_POST['BusinessUnit']['Dimensions'], 'x', true);
                    $uploadedLogo->image_y = substr($_POST['BusinessUnit']['Dimensions'], strpos($_POST['BusinessUnit']['Dimensions'], "x") + 1);
                    $uploadedLogo->process($RealPath);
                    // ---- save thumbnail image -----------
                    $uploadedLogo->file_new_name_body = $LogoName;
                    $uploadedLogo->image_resize = true;
                    $uploadedLogo->image_ratio = true;
                    $uploadedLogo->image_x = 100;
                    $uploadedLogo->image_y = 100;
                    $uploadedLogo->process($RealPath . 'thumbnails/');
                }
                if ($uploadedIcon != null) {
                    //-----------Icon Marker
                    $uploadedIcon->file_new_name_body = $IconName;
                    $uploadedIcon->image_resize = true;
                    //$uploadedIcon->image_ratio = true;
                    $uploadedIcon->image_x = 49;
                    $uploadedIcon->image_y = 64;
                    $uploadedIcon->process($RealPath . 'icons/');
                }

                if ($uploadedCpLogo != null) {
                    //-----------Cpanel Logo
                    $uploadedCpLogo->file_new_name_body = $CpLogoName;
                    $uploadedCpLogo->image_resize = true;
                    $uploadedCpLogo->image_ratio = true;
                    $uploadedCpLogo->image_x = 188;
                    $uploadedCpLogo->image_y = 48;
                    $uploadedCpLogo->process($RealPath . 'Logos/');
                }
                $this->redirect(array('view', 'id' => $model->buid));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'AccData' => $AccData,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        Login::UserAuth('BusinessUnit', 'Update');

        $model = $this->loadModel($id);
        $AccData = BuAccounts::model()->findAll(array("select" => "accid,fname,lname,special_deal_id"));
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $RealArr = Globals::ReturnGlobals();
        $RealPath = $RealArr['ImgPath'] . 'business_unit/';

        $handle = new upload($RealPath . $model->logo);
        $dimensions = $handle->image_src_x . 'x' . $handle->image_src_y;
        if (isset($_POST['BusinessUnit'])) {
            $_POST['BusinessUnit'] = CI_Security::ChkPost($_POST['BusinessUnit']);

            $_POST['BusinessUnit']['logo'] = $model->logo;
            $_POST['BusinessUnit']['urlid'] = $model->urlid;
            $_POST['BusinessUnit']['cpanel_logo'] = $model->cpanel_logo;

            $model->attributes = $_POST['BusinessUnit'];
            $old_logo = $model->logo;
            $old_icon = $model->urlid;
            $old_cplogo = $model->cpanel_logo;

            $rnd = $random = date(time());

            //$uploadedLogo = CUploadedFile::getInstance($model, 'logo');
            //----------Logo
            $uploadedLogo = new upload($_FILES['logo']);

            $LogoName = "";
            //if($uploadedLogo != null){
            if ($uploadedLogo->file_src_name != null) {
                // $LogoName = "{$rnd}-{$uploadedLogo}"; 
                $LogoName = "{$rnd}-$uploadedLogo->file_src_name_body";
                $LogoName = md5($LogoName);
                $model->logo = $LogoName . '.' . $uploadedLogo->file_src_name_ext;
            } else {
                $LogoName = $old_logo;
                $model->logo = $LogoName;
            }
            //-----------Icon Marker
            $uploadedIcon = new upload($_FILES['urlid']);

            $IconName = "";
            if ($uploadedIcon->file_src_name != null) {
                $IconName = "{$rnd}-$uploadedIcon->file_src_name_body";
                $IconName = md5($IconName . 'Icon');
                $model->urlid = $IconName . '.' . $uploadedIcon->file_src_name_ext;
            } else {
                $IconName = $old_icon;
                $model->urlid = $IconName;
            }

            //-----------Cpanel Logo
            $uploadedCpLogo = new upload($_FILES['cpanel_logo']);

            $CpLogoName = "";
            if ($uploadedCpLogo->file_src_name != null) {
                $CpLogoName = "{$rnd}-$uploadedCpLogo->file_src_name_body";
                $CpLogoName = md5($CpLogoName . 'cplogo');
                $model->cpanel_logo = $CpLogoName . '.' . $uploadedCpLogo->file_src_name_ext;
            } else {
                $CpLogoName = $old_cplogo;
                $model->cpanel_logo = $CpLogoName;
            }


            $model->membership = 1;

            if ($model->save()) {

                //if($uploadedLogo != null){
                if ($uploadedLogo->file_src_name != null) {

                    if ($model->logo != '' && $old_logo != 'default.jpg') {

                        if (file_exists($RealPath . $old_logo)) {
                            unlink($RealPath . $old_logo);
                        }
                        if (file_exists($RealPath . 'thumbnails/' . $old_logo)) {
                            unlink($RealPath . 'thumbnails/' . $old_logo);
                        }
                    }
                    /*
                      $uploadedLogo->saveAs($RealPath.$LogoName);
                      $image = new EasyImage($RealPath.$LogoName);
                      $image->resize(100, 100);
                      $image->save($RealPath.'thumbnails/'.$LogoName); */

                    // ---- save resized image -------------
                    $uploadedLogo->file_new_name_body = $LogoName;
                    $uploadedLogo->image_resize = true;
                    $uploadedLogo->image_ratio = true;
                    $uploadedLogo->image_x = strstr($_POST['BusinessUnit']['Dimensions'], 'x', true);
                    $uploadedLogo->image_y = substr($_POST['BusinessUnit']['Dimensions'], strpos($_POST['BusinessUnit']['Dimensions'], "x") + 1);
                    $uploadedLogo->process($RealPath);
                    // ---- save thumbnail image -----------
                    $uploadedLogo->file_new_name_body = $LogoName;
                    $uploadedLogo->image_resize = true;
                    $uploadedLogo->image_ratio = true;
                    $uploadedLogo->image_x = 100;
                    $uploadedLogo->image_y = 100;
                    $uploadedLogo->process($RealPath . 'thumbnails/');
                }
                //------------------Icon Marker
                if ($uploadedIcon->file_src_name != null) {

                    if ($model->urlid != '' && $old_icon != 'icon.png') {

                        if (file_exists($RealPath . 'icons/' . $old_icon)) {
                            unlink($RealPath . 'icons/' . $old_icon);
                        }
                    }

                    // ---- save thumbnail image -----------
                    $uploadedIcon->file_new_name_body = $IconName;
                    $uploadedIcon->image_resize = true;
                    //$uploadedIcon->image_ratio = true;
                    $uploadedIcon->image_x = 49;
                    $uploadedIcon->image_y = 64;
                    $uploadedIcon->process($RealPath . 'icons/');
                }
                //------------------Cpanel Logo
                if ($uploadedCpLogo->file_src_name != null) {

                    if ($model->cpanel_logo != '' && $old_cplogo != 'logo.png') {

                        if (file_exists($RealPath . 'Logos/' . $old_cplogo)) {
                            unlink($RealPath . 'Logos/' . $old_cplogo);
                        }
                    }

                    // ---- save thumbnail image -----------
                    $uploadedCpLogo->file_new_name_body = $CpLogoName;
                    $uploadedCpLogo->image_resize = true;
                    $uploadedCpLogo->image_ratio = true;
                    $uploadedCpLogo->image_x = 188;
                    $uploadedCpLogo->image_y = 48;
                    $uploadedCpLogo->process($RealPath . 'Logos/');
                }
                $this->redirect(array('view', 'id' => $model->buid));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'AccData' => $AccData,
            'dimensions' => $dimensions,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        Login::UserAuth('BusinessUnit', 'Delete');

        $m_model = $this->loadModel($id);

        $RealArr = Globals::ReturnGlobals();
        $RealPath = $RealArr['ImgPath'] . 'business_unit/';
        if ($m_model->logo != '' && $m_model->logo != 'default.jpg') {
            if (file_exists($RealPath . $m_model->logo)) {
                unlink($RealPath . $m_model->logo);
            }
            if (file_exists($RealPath . 'thumbnails/' . $m_model->logo)) {
                unlink($RealPath . 'thumbnails/' . $m_model->logo);
            }
        }
        if ($m_model->urlid != '' && $m_model->urlid != 'icon.png') {
            if (file_exists($RealPath . 'icons/' . $m_model->urlid)) {
                unlink($RealPath . 'icons/' . $m_model->urlid);
            }
        }
        if ($m_model->cpanel_logo != '' && $m_model->cpanel_logo != 'logo.png') {
            if (file_exists($RealPath . 'Logos/' . $m_model->cpanel_logo)) {
                unlink($RealPath . 'Logos/' . $m_model->cpanel_logo);
            }
        }
        // $better_token = md5(uniqid(mt_rand() true))
        //$this->loadModel($id)->delete();
        $m_model->delete();

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
        Login::UserAuth('BusinessUnit', 'Index');

        if (Yii::app()->session['User']['UserRoleID'] == 1) {

            $dataProvider = new CActiveDataProvider('BusinessUnit');
        } else {

            $dataProvider = new CActiveDataProvider('BusinessUnit', array('criteria' => array('condition' => 'accid=' . Yii::app()->session['User']['UserOwnerID'])));
        }
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        Login::UserAuth('BusinessUnit', 'Admin');

        $model = new BusinessUnit('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['BusinessUnit'])) {
            $_GET['BusinessUnit'] = CI_Security::ChkPost($_GET['BusinessUnit']);
            $model->attributes = $_GET['BusinessUnit'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionContactUS($id) {
        Login::UserAuth('BusinessUnit', 'ContactUS');
        $model = $this->loadModel($id);
        $ContactSQL = " SELECT * FROM bu_contacts WHERE bu_contact_bu_id =" . $id . " ORDER BY bu_contact_title ";
        $ContactData = Yii::app()->db->createCommand($ContactSQL)->queryAll();

        $this->render('contact_us', array(
            'model' => $model,
            'Contacts' => $ContactData,
        ));
    }

    public function actionAjaxContactUS() {
        Login::UserAuth('BusinessUnit', 'ContactUS');

        $Chk = 0;

        $_POST = CI_Security::ChkPost($_POST);

        if ($_POST['ContactType'] == '2') {

            $ContactSQL = " SELECT * FROM bu_contacts WHERE bu_contact_type = 2 AND bu_contact_bu_id=" . $_POST['BU_ID'] . " ";
            $ContactData = Yii::app()->db->createCommand($ContactSQL)->queryAll();
            if (count($ContactData) > 0) {
                $Chk = 1;
                var_dump(count($ContactData));
            }
        }

        if ($Chk == 0) {
            $ContactINS = " INSERT INTO bu_contacts(bu_contact_bu_id,bu_contact_type,bu_contact_title,bu_contact_val) 
							VALUES (" . $_POST['BU_ID'] . "," . $_POST['ContactType'] . ",'" . $_POST['ContactTitle'] . "','" . $_POST['ContactVal'] . "') ";
            Yii::app()->db->createCommand($ContactINS)->execute();
            echo $ContactID = Yii::app()->db->getLastInsertID();
            echo 'check = 0';
        } else {
            echo 'check <> 0';
            //echo '0';
        }

        $Chk = 0;
    }

    public function actionAjaxDelContactUS() {
        Login::UserAuth('BusinessUnit', 'ContactUS');
        $_POST = CI_Security::ChkPost($_POST);
        $ContactDel = "DELETE FROM bu_contacts WHERE bu_contact_id =" . $_POST['ConID'];
        Yii::app()->db->createCommand($ContactDel)->execute();
    }

    public function actionGetAvUnitsByD() {
        Login::ChkAuthType('BusinessUnit', 'GetAvUnitsByD');
        Login::ChkBuSess();

        $_POST = CI_Security::ChkPost($_POST);

        $Data = array();
        $BuID = isset(Yii::app()->session['User']['UserBuid']) ? (Yii::app()->session['User']['UserBuid'] > 0 ? Yii::app()->session['User']['UserBuid'] : 0 ) : 0;

        // $BuID = 3;

        if (isset($_POST) && !empty($_POST)) {
            $_POST['res_type'] = 'TA';
            $_POST['buid'] = $BuID;

            $Data['Res'] = CustLib::actionGetAllUnits($_POST);
            $Data['Time'] = CustLib::actionTimeRange();

            $myContent = '';

            if (isset($Data['Res']) && !empty($Data['Res'])) {

                $myContent = '
							  	<table id="Restimes" class="table" style="overflow-x: scroll;">
							  		<thead>
										<th>Table</th>
										<th>No Of Chairs</th>';

                foreach ($Data['Time'] as $key => $val) {
                    $myContent .= '<th>' . $val . '</th>';
                }

                $myContent .= '<tbody>';
                foreach ($Data['Res']['units'] as $key => $row) {
                    $myContent .= '<tr>
														<td>' . $row['Serial'] . '</td>
													<td>' . $row['ChairNo'] . '</td>';



                    foreach ($Data['Time'] as $key3 => $row3) {

                        $CHK = FALSE;
                        $Cla = "";
                        foreach ($row['Reservations'] as $key4 => $row4) {

                            if (strtotime($row3) == strtotime($row4['FromTIME'])) {
                                $CHK = TRUE;
                                $Cla = "From td-start"; // 	
                            }
                            if (strtotime($row3) == strtotime($row4['ToTIME'])) {
                                $CHK = TRUE;
                                $Cla = "To td-end"; // 	
                            }
                        }


                        if ($CHK == TRUE) {
                            $myContent .= "<td class='" . $Cla . "'>" . $row3 . "</td>";
                        } else {
                            $myContent .= "<td></td>";
                        }

                        // if($CHKto = FALSE){
                        // echo "<td></td>";
                        // }
                        // if($check){
                        // echo "<td>". $row3 ."</td>";
                        // } else {
                        // echo "<td></td>";
                        // }
                    }
                }

                $myContent .= '</tbody>
										
									</thead>
							  	</table>
							  ';
            }
            // print_r($myContent);return;
            echo $myContent;
        } else {
            $this->render('reservationRep', array('Data' => $Data));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return BusinessUnit the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = BusinessUnit::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param BusinessUnit $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'business-unit-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGetStoresLangs() {
        $LangArr = array();

        if (isset(Yii::app()->session['User'])) {

            $OwnerID = isset(Yii::app()->session['User']['UserOwnerID']) ? Yii::app()->session['User']['UserOwnerID'] : 0;

            $SQL = " SELECT lang_id,lang_name,lang_code,bu_lang_bu_id
					 FROM languages
					 LEFT JOIN bu_lang_setting ON bu_lang_lang_id = lang_id
					 WHERE bu_lang_bu_id IN (SELECT buid FROM business_unit WHERE accid = " . $OwnerID . ")
					 AND bu_lang_val = 1";
            $COwner = Yii::app()->db->createCommand($SQL)->queryALl();

            if (count($COwner) > 0) {

                foreach ($COwner AS $key => $row) {

                    $LangArr[$row['bu_lang_bu_id']][$row['lang_id']]['LangID'] = $row['lang_id'];
                    $LangArr[$row['bu_lang_bu_id']][$row['lang_id']]['LangN'] = $row['lang_name'];
                    $LangArr[$row['bu_lang_bu_id']][$row['lang_id']]['LangC'] = $row['lang_code'];
                }
            }
        }

        echo json_encode($LangArr);
    }

    //-------------------------------------------

    public function actionAssignTables() {
        Login::ChkAuthType('BusinessUnit', 'AssignTables');
        Login::ChkBuSess();
        $_POST = CI_Security::ChkPost($_POST);
        $BuID = isset(Yii::app()->session['User']['UserBuid']) ? Yii::app()->session['User']['UserBuid'] : 0;
        $Data = array();
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $BuID > 0) {
            //------ Delete Old Assign
            Yii::app()->db->createCommand(" Delete FROM bu_tables WHERE bu_table_buid = " . $BuID)->execute();
           //------ Save New Assign
            $TchairsArr = $_POST;
            var_dump($TchairsArr);
            $Num = 1;
            $InsTables = " INSERT INTO bu_tables (bu_table_serial,bu_table_buid,bu_table_num_chairs) VALUES ";
            foreach ($TchairsArr as $Key => $Val) {
                if ($Val > 0) {
                    $ChairNum = ltrim($Key, 'table');
                    
                    $ChairNum = rtrim($ChairNum, 'chairs');
              
                    for ($i = 0; $i < $Val; $i ++) {

                        $InsTables .= " (" . $Num . "," . $BuID . "," . $ChairNum . "),";
                        $Num++;
                    }
                }
            }
            $InsTables = rtrim($InsTables, ',');
            echo Yii::app()->db->createCommand($InsTables)->execute();
            $this->UpdateTablesQrcode();
        }

        $TablesSQL = " SELECT 
        IFNULL((SELECT COUNT(bu_table_id)FROM bu_tables WHERE bu_table_buid = " . $BuID . " AND bu_table_num_chairs = 2),'')  AS T2chairs,
        IFNULL((SELECT COUNT(bu_table_id)FROM bu_tables WHERE bu_table_buid = " . $BuID . " AND bu_table_num_chairs = 4),'')  AS T4chairs,
        IFNULL((SELECT COUNT(bu_table_id)FROM bu_tables WHERE bu_table_buid = " . $BuID . " AND bu_table_num_chairs = 6),'')  AS T6chairs,
        IFNULL((SELECT COUNT(bu_table_id)FROM bu_tables WHERE bu_table_buid = " . $BuID . " AND bu_table_num_chairs = 8),'')  AS T8chairs,
	IFNULL((SELECT COUNT(bu_table_id)FROM bu_tables WHERE bu_table_buid = " . $BuID . " AND bu_table_num_chairs = 10),'') AS T10chairs	
	FROM bu_tables WHERE bu_table_buid = " . $BuID . " LIMIT 0,1";
        $TablesRow = Yii::app()->db->createCommand($TablesSQL)->queryRow();
        $Data['T2chairs'] = $TablesRow['T2chairs'];
        $Data['T4chairs'] = $TablesRow['T4chairs'];
        $Data['T6chairs'] = $TablesRow['T6chairs'];
        $Data['T8chairs'] = $TablesRow['T8chairs'];
        $Data['T10chairs'] = $TablesRow['T10chairs'];
        $this->render('AssignTables', array('Data' => $Data));
    }

    private function UpdateTablesQrcode() {
        $BuID = isset(Yii::app()->session['User']['UserBuid']) ? Yii::app()->session['User']['UserBuid'] : 0;

        if ($BuID > 0) {

            $TablesSQl = " SELECT * FROM bu_tables WHERE bu_table_buid = " . $BuID;
            $TablesData = Yii::app()->db->createCommand($TablesSQl)->queryAll();

            $Cipher = new Cipher('secret passphrase');

            $UpSQl = " UPDATE bu_tables SET bu_table_qrcode = CASE ";
            $TableIDs = " ";

            foreach ($TablesData as $Key => $Row) {

                $EncryptedTxt = $Row['bu_table_id'] . '-' . $BuID . '-cafe' . date(time());
                $EncryptedQr = $Cipher->encrypt($EncryptedTxt);

                $UpSQl .= " WHEN bu_table_id = " . $Row['bu_table_id'] . " THEN '" . $EncryptedQr . "' ";
                $TableIDs .= $Row['bu_table_id'] . ',';
            }
            $TableIDs = rtrim($TableIDs, ',');
            $UpSQl .= " END WHERE bu_table_buid = " . $BuID . " AND bu_table_id IN (" . $TableIDs . ")";
            Yii::app()->db->createCommand($UpSQl)->execute();
        }
    }

    public function actionTotalTables() {
        Login::ChkAuthType('BusinessUnit', 'TotalTables');
        Login::ChkBuSess();

        $BuID = isset(Yii::app()->session['User']['UserBuid']) ? Yii::app()->session['User']['UserBuid'] : 0;

        $TablesSQl = " SELECT * FROM bu_tables WHERE bu_table_buid = " . $BuID;
        $TablesData = Yii::app()->db->createCommand($TablesSQl)->queryAll();


        $this->render('TotalTables', array('TablesData' => $TablesData));
    }

    public function actionCheckTables() {

        //check table in restraunt avalible and not avaliable from time to another time according to user input duration

        Login::ChkAuthType('BusinessUnit', 'CheckTables');
        Login::ChkBuSess();
        $CheckTables = array();
        $new = array();
        //to check that the form already post form and the field not empty
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['to']) && !empty($_POST['from'])) {
            $from = $_POST['from'];
            $to = $_POST['to'];



            //to check that the user enter the time to large than time from then i will get the data from database else i will print to check thetime
            if ($to > $from) {

                $BuID = isset(Yii::app()->session['User']['UserBuid']) ? Yii::app()->session['User']['UserBuid'] : 0;
                $date = date('Y-m-d', time());

                $TablesSQl = " SELECT time_from ,time_to,bu_table_serial,avaliable FROM reservations JOIN "
                        . "bu_tables ON reservations.res_unit_id = bu_tables.bu_table_id and bu_tables.bu_table_buid=" . $BuID . " AND reservations.res_type='TA' AND date='$date'";

                $CheckTables = Yii::app()->db->createCommand($TablesSQl)->queryAll();
                if (count($CheckTables) > 0) {

                    foreach ($CheckTables AS $key => $row) {
                        $time_from = $row['time_from'];
                        $time_to = $row['time_to'];
                        //to check aleady from is smaller than to in database else i will print database time error         
                        if ($time_to > $time_from) {
                            if ($from >= $time_from && $to <= $time_to) {
                                if ($row['avaliable'] == 1) {
                                    $new[$row['bu_table_serial']]['ready'] = $row['bu_table_serial'];
                                } else {
                                    $new[$row['bu_table_serial']]['busy'] = $row['bu_table_serial'];
                                }
                            }
                        } else {
                            echo "database time error";
                        }
                    }
                }
            } else {
                echo "check your time";
            }
        }
        $this->render('CheckTables', array('new' => $new));
    }

    public function actionAjaxDeletetable() {

        $BuID = isset(Yii::app()->session['User']['UserBuid']) ? Yii::app()->session['User']['UserBuid'] : 0;

        $upateAvaliablity = " update reservations JOIN "
                . "bu_tables ON reservations.res_unit_id = bu_tables.bu_table_id and bu_tables.bu_table_buid=" . $BuID . " set avaliable=" . $_POST['flag'] . " where bu_table_serial=" . $_POST['tableSerial'] . " ";

        $Res = Yii::app()->db->createCommand($upateAvaliablity)->execute();
    }

    public function actionEditSerial() {
        Login::ChkAuthType('BusinessUnit', 'TotalTables');
        $_POST = CI_Security::ChkPost($_POST);

        $Result = 'False';
        $UpSQL = "UPDATE bu_tables SET bu_table_serial = '" . $_POST['Serial'] . "' WHERE bu_table_id = " . $_POST['T_id'];
        $Res = Yii::app()->db->createCommand($UpSQL)->execute();

        if ($Res > 0) {
            $Result = 'True';
        } else {
            $Result = 'False';
        }
        echo $Result;
    }

    public function actionReCreateQrCode() {
        Login::ChkAuthType('BusinessUnit', 'TotalTables');

        $_POST = CI_Security::ChkPost($_POST);
        $Result = 'False';

        $Cipher = new Cipher('secret passphrase');
        $DecryptedQr = $Cipher->decrypt($_POST['QRCode']);
        if ($DecryptedQr != false) {

            $QrArr = explode('-', $DecryptedQr);
            $T_id = $QrArr[0];
            $BuID = $QrArr[1];

            $EncryptedTxt = $T_id . '-' . $BuID . '-cafe' . date(time());
            $EncryptedQr = $Cipher->encrypt($EncryptedTxt);

            $UpSQL = "UPDATE bu_tables SET bu_table_qrcode = '" . $EncryptedQr . "' WHERE bu_table_id = " . $T_id;
            $Res = Yii::app()->db->createCommand($UpSQL)->execute();
            if ($Res > 0) {
                $Result = 'True';
            }
        }
        echo $Result;
    }

    public function actionDownLoadQrCode() {
        Login::ChkAuthType('BusinessUnit', 'TotalTables');

        $QRCode = '';
        $Serial = '';

        $Query = urldecode($_SERVER["QUERY_STRING"]);
        $Param = explode('&', $Query);
        foreach ($Param as $key => $value) {
            $Res = explode("=", $value);
            if ($Res[0] == 'qr') {
                $QRCode = $Res[1];
            }
            if ($Res[0] == 'serial') {
                $Serial = $Res[1];
            }
        }

        $QrUrl = urlencode("http://chart.apis.google.com/chart?chs=500x500&cht=qr&chl=");
        $RealAdrr = Globals::ReturnGlobals();

        $QrUrl = urldecode($QrUrl) . $QRCode;
        $QRName = $Serial . '-qr.png';

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: binary");
        header('Content-Disposition: attachment; filename=' . $QRName);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Accept-Ranges: bytes');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $QrUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $QrFile = curl_exec($ch);
        curl_close($ch);

        echo $QrFile;

        $this->actionTotalTables();
    }

    public function actionDeleteTable() {
        Login::ChkAuthType('BusinessUnit', 'TotalTables');

        $_POST = CI_Security::ChkPost($_POST);
        $DelSQL = "Delete From bu_tables WHERE bu_table_id = " . $_POST['t_id'];
        Yii::app()->db->createCommand($DelSQL)->execute();
    }

    public function actionAddTable() {
        Login::ChkAuthType('BusinessUnit', 'TotalTables');

        $_POST = CI_Security::ChkPost($_POST);

        $TableID = 0;

        $BuID = isset(Yii::app()->session['User']['UserBuid']) ? Yii::app()->session['User']['UserBuid'] : 0;

        if ($BuID > 0) {

            $InsSQL = " INSERT INTO bu_tables (bu_table_serial,bu_table_buid,bu_table_num_chairs)
						VALUES ('" . $_POST['Serial'] . "'," . $BuID . "," . $_POST['Chairs'] . ")";
            Yii::app()->db->createCommand($InsSQL)->execute();

            $TableID = Yii::app()->db->getLastInsertID();

            $Cipher = new Cipher('secret passphrase');

            $EncryptedTxt = $TableID . '-' . $BuID . '-cafe' . date(time());
            $EncryptedQr = $Cipher->encrypt($EncryptedTxt);

            $UpSQL = " UPDATE bu_tables SET bu_table_qrcode = '" . $EncryptedQr . "' WHERE bu_table_id = " . $TableID;
            Yii::app()->db->createCommand($UpSQL)->execute();
        }
        echo $TableID;
    }

}
