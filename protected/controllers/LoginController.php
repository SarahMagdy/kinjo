<?php

class LoginController extends Controller
{
	public function actionIndex()
	{
		 $this->render('index');
		//$this->renderPartial('v_test');
	}
	
	public function init()
	{
		parent::init();
		Yii::app()->language = Yii::app()->session['Language']['UserLang'];
	}
	
	public function actionCpanel()
	{
			
		$data['action']='cpanel';
		if( isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['pass']) && !empty($_POST['pass']))
		{
			$_POST = CI_Security::ChkPost($_POST);
			
			$criteria=New CDbCriteria;
			$criteria->condition='username=:username AND password=:password';
			$criteria->params = array(":username"=>$_POST['username'] , ":password"=>$_POST['pass']);
			
			$x = Cpanel::model()->find($criteria);
			if(!empty($x)){
				$this->redirect("/index.php/cpanel");
			}else{
				$this->render('index' , $data);
			}
			//var_dump($x->username);
			
		}else{
			$this->render('index' , $data);
			
		}
		
		
	}
	
	
	public function actionAdmin()
	{			
		$data['action']='admin';
		
		if( isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['pass']) && !empty($_POST['pass']))
		{
			/*$x = Admins::model()->findByAttributes(
			    array('username'=>$_POST['username'],'password'=>$_POST['pass'])//,
			    //'status=1'
			);
			*/
			
			$_POST = CI_Security::ChkPost($_POST);
					
			$criteria=New CDbCriteria;
			$criteria->condition='username=:username AND password=:password';
			//$critieria->condition='';
			$criteria->params = array(":username"=>$_POST['username'] , ":password"=>$_POST['pass']);
			
			$x = Admins::model()->find($criteria);
			
			//echo $x->username;
			if(!empty($x)){
				$this->redirect("/index.php/admin");
			}else{
				$this->render('index' , $data);
			}
		
		}else{
			$this->render('index' , $data);
			
		}


			
		
	}
	
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	
	
	
	/*
		 1. Supply a $condition as string:

			Person::model()->findByAttributes(
			    array('first_name'=>$firstName,'last_name'=>$lastName),
			    'status=1'
			);
			
			
			
			2. Supply a $condition as string that contains placeholder and $params as array with placeholder values:
			
			Person::model()->findByAttributes(
			    array('first_name'=>$firstName,'last_name'=>$lastName),
			    'status=:status',
			    array(':status'=>1)
			);
			
			
			
			3. Supply a $condition as a CDbCriteria:
			
			$criteria=New CDbCritieria;
			$critieria->condition='status=1';
			
			Person::model()->findByAttributes(
			    array('first_name'=>$firstName,'last_name'=>$lastName),
			    $criteria
			);
			
			
			
			4. Supply a $condition as array with property values for CDbCriteria:
			
			Person::model()->findByAttributes(
			    array('first_name'=>$firstName,'last_name'=>$lastName),
			    array(
			        'condition'=>'status=:status', 
			        'params'=>array(':status'=>1)
			    )
			);
		 */	
			
	
	
	
}