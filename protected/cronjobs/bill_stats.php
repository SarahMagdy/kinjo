<?php
	
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	require_once (getcwd().'/DBConnection.php');
	
	
	$today = date("Y-m-d"); // '2017-01-06';// 
	
	
	$SQL = "SELECT business_unit.buid AS BuID , business_unit.title AS BuName , bcid,bc_duration,bc_type,
				   (CASE WHEN bc_type = 0 THEN CONCAT(bc_duration, ' Days') 
						 WHEN bc_type = 1 THEN CONCAT(bc_duration, ' Months') 
						 WHEN bc_type = 2 THEN CONCAT(bc_duration, ' Years') END) AS Duration , 
					packages.pkgid AS PkgID , packages.title AS PkgN , packages.amount AS PkgAmount , packages.currency AS PkgCurr ,
				    DATE(IFNULL(LASTDate,business_unit.created)) AS LASTDate,
				    DATE((CASE WHEN bc_type = 0 THEN DATE_ADD(IFNULL(LASTDate,business_unit.created), INTERVAL bc_duration DAY)  
						  WHEN bc_type = 1 THEN DATE_ADD(IFNULL(LASTDate,business_unit.created), INTERVAL bc_duration Month) 
						  WHEN bc_type = 2 THEN DATE_ADD(IFNULL(LASTDate,business_unit.created), INTERVAL bc_duration YEAR) 
				     END))AS DueDate,
				    (SELECT IFNULL(SUM(dollor_price) , 0) AS OrdTotal FROM orders_details 
				     WHERE ord_buid = business_unit.buid AND pay_type = 1
				     AND close_date BETWEEN LASTDate AND DueDate) AS OnSiteOrdTotal , 
				     
					(SELECT IFNULL(SUM(dollor_price) , 0) AS OrdTotal FROM orders_details 
				     WHERE ord_buid = business_unit.buid AND pay_type = 0
				     AND close_date BETWEEN LASTDate AND DueDate) AS OnLineOrdTotal ,
					 
				    (SELECT COUNT( mlog_id )  FROM messages_log
					 LEFT JOIN messages ON messages_log.mid = messages.mid
					 WHERE (is_group =0 OR is_group =3 OR is_group =4 )
					 AND messages.buid = business_unit.buid) AS GCMCount 
					  
			FROM business_unit 
			LEFT JOIN bu_accounts ON business_unit.accid = bu_accounts.accid
			LEFT JOIN packages
				LEFT JOIN billing_cycle ON packages.pkg_bill_cycleID = billing_cycle.bcid
			ON business_unit.pkg_id = packages.pkgid
			LEFT JOIN (SELECT MAX(LBill.bill_d_pay_date)AS LASTDate,LBill.bill_d_bu_sp_id
					   FROM
							(SELECT bill_d_pay_date , bill_d_bill_id , bill_d_bu_sp_id , bill_d_amount , bill_d_due_date
							 FROM bills_details
							 WHERE bill_d_bu_or_sp = 0
							 ORDER BY bill_d_pay_date DESC) AS LBill
							 GROUP BY LBill.bill_d_bu_sp_id)AS DueBill
			ON DueBill.bill_d_bu_sp_id = business_unit.buid
			WHERE special_deal_id = 0 AND business_unit.active = 0";
	
	$billQ = $conn->prepare($SQL);
	$billQ->execute();
	$Billresu = $billQ->fetchAll();
	
	if(isset($Billresu) && !empty($Billresu)){
		$insSQL = "INSERT INTO bill_stats 
				   (b_stats_buid,b_stats_BuName ,b_stats_bcid ,b_stats_bc_duration ,b_stats_bc_type ,b_stats_PkgID,
				    b_stats_PkgN ,b_stats_bill_LASTDate ,b_stats_DueDate ,b_stats_OnSiteOrdTotal ,b_stats_OnLineOrdTotal ,b_stats_buType,
				    b_stats_Amount , b_stats_Curr , b_stats_GCMCount) VALUES ";
		
		foreach ($Billresu as $key => $val) {
			// echo '<pre/>';
			if($val['DueDate'] == date('Y-m-d', strtotime($today. ' + 1 days'))){
				
				$insSQL .="(".$val['BuID']." , '".$val['BuName']."' , ".$val['bcid'].",".$val['bc_duration'].",".$val['bc_type'].",".$val['PkgID'].",'".
							  $val['PkgN']."','".$val['LASTDate']."','".$val['DueDate']."',".$val['OnSiteOrdTotal']." , ".$val['OnLineOrdTotal']." , 0 , ".
							  $val['PkgAmount'].",'".$val['PkgCurr']."',".$val['GCMCount']."),";
			}
		}
		
		$insSQL = rtrim($insSQL , ",");
		$sql_Q = $conn->prepare($insSQL);
		$sql_Q->execute();
	
	}
	
	$AccSQL = "SELECT accid,CONCAT( fname, '', lname ) AS accName, bcid,bc_duration,bc_type, sp_d_id,sp_d_title,sp_d_amount,sp_d_currency , 
					 
					 DATE(IFNULL(LASTDate,bu_accounts.created)) AS LASTDate,
					 DATE((CASE WHEN bc_type = 0 THEN DATE_ADD(IFNULL(LASTDate,bu_accounts.created),INTERVAL bc_duration DAY)  
						  	    WHEN bc_type = 1 THEN DATE_ADD(IFNULL(LASTDate,bu_accounts.created), INTERVAL bc_duration Month) 
							    WHEN bc_type = 2 THEN DATE_ADD(IFNULL(LASTDate,bu_accounts.created), INTERVAL bc_duration YEAR) 
						END))AS DueDate	,					 
					 
					 (SELECT IFNULL( SUM( dollor_price ) , 0 ) AS OrdTotal
					  FROM orders_details
					  WHERE ord_buid IN(SELECT buid FROM business_unit WHERE business_unit.accid =  bu_accounts.accid )
					  AND pay_type =1 AND close_date
					  BETWEEN LASTDate AND DueDate) AS OnSiteOrdTotal ,
									    
					 (SELECT IFNULL( SUM( dollor_price ) , 0 ) AS OrdTotal
					  FROM orders_details
					  WHERE ord_buid IN(SELECT buid FROM business_unit WHERE business_unit.accid =  bu_accounts.accid )
					  AND pay_type =0 AND close_date
					  BETWEEN LASTDate AND DueDate) AS OnLineOrdTotal,
					  
					 (SELECT COUNT( mlog_id ) FROM messages_log
					  LEFT JOIN messages ON messages_log.mid = messages.mid
					  WHERE (is_group =0 OR is_group =3 OR is_group =4)
					  AND messages.buid IN (SELECT buid FROM business_unit WHERE business_unit.accid = bu_accounts.accid)) AS GCMCount 				
					  	
				FROM bu_accounts
				LEFT JOIN special_deals
					LEFT JOIN billing_cycle ON sp_d_bill_cycle_id = billing_cycle.bcid 
				ON special_deal_id = sp_d_id
				LEFT JOIN (SELECT MAX(LBill.bill_d_pay_date)AS LASTDate,LBill.bill_d_bu_sp_id , LBill.bill_owner_id
						   FROM
							(SELECT bill_d_pay_date,bill_d_bill_id,bill_d_bu_sp_id , bill_d_amount,bill_d_due_date,bill_owner_id
							 FROM bills_details bills_details LEFT JOIN bills
     						 ON bill_d_bill_id = bill_id
							 WHERE bill_d_bu_or_sp = 1
							 ORDER BY bill_d_pay_date DESC) AS LBill
							 GROUP BY LBill.bill_owner_id)AS DueBill
				ON DueBill.bill_d_bu_sp_id = bu_accounts.accid
				WHERE special_deal_id !=0
				AND bu_accounts.status =1";
	
	$AccQ = $conn->prepare($AccSQL);
	$AccQ->execute();
	$AccResu = $AccQ->fetchAll();
	
	if(isset($AccResu) && !empty($AccResu)){
		// echo '<pre/>';
		
		$insSQL2 = "INSERT INTO bill_stats 
				   (b_stats_buid , b_stats_BuName , b_stats_bcid ,b_stats_bc_duration ,b_stats_bc_type ,b_stats_PkgID,
				    b_stats_PkgN ,b_stats_bill_LASTDate ,b_stats_DueDate ,b_stats_OnSiteOrdTotal ,b_stats_OnLineOrdTotal ,b_stats_buType,
				    b_stats_Amount , b_stats_Curr , b_stats_GCMCount) VALUES ";
		foreach ($AccResu as $key => $val) {
			if($val['DueDate'] == date('Y-m-d', strtotime($today. ' + 1 days'))){
				
				$insSQL2 .= "(".$val['accid'].",'".$val['accName']."',".$val['bcid'].",".$val['bc_duration'].",".$val['bc_type'].",".
								$val['sp_d_id'].",'".$val['sp_d_title']."','".$val['LASTDate']."','".$val['DueDate']."',".$val['OnSiteOrdTotal'].",".$val['OnLineOrdTotal'].",1,".
								$val['sp_d_amount'].",'".$val['sp_d_currency']."',".$val['GCMCount']."),";
			}
		}
		
		$insSQL2 = rtrim($insSQL2 , ",");
		// print_r($insSQL2);
		$sql_Q2 = $conn->prepare($insSQL2);
		$sql_Q2->execute();
	}
	
	
	
	
	