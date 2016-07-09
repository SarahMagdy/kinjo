
<!--
<?php if($Data['RoleID'] == 2):?>
	<?php if(isset($Data['Stores'])):?>
		<?php foreach($Data['Stores'] AS $Key=>$Row):?>
			<div id="div_<?=$Row['buid'];?>" class="S_div" SID="<?=$Row['buid'];?>" BuType="<?=$Row['type'];?>" BuLogo="<?=$Row['cpanel_logo'];?>">
				<div class="img_holder">
					<img src="<?= Yii::app()->request->baseUrl.'/images/upload/business_unit/'.$Row['logo']?>" alt="<?=$Row['title'];?>"> 
				</div>				
				<div Class="link_describtion">
			 		<a id="link_<?=$Row['buid'];?>" class="S_link" href="#" ><center><?=$Row['title'];?></center></a> 
			 	</div>
			</div>
		<?php endforeach;?>
	<?php endif;?>*/
-->
	
	<?php if(isset($Data['BuTypes'])):?>
		<?php foreach($Data['BuTypes'] AS $TKey=>$TRow):?>
			 <div id="Div_<?=$TRow['TypeID']?>" style="width: 100%;float:left;">
			 	<p><?=$TRow['TypeN']?></p>
			 	<?php if(isset($TRow['BU'])):?>
			 		<?php foreach($TRow['BU'] AS $BuKey=>$BuTRow):?>
			 			<div id="div_<?=$BuTRow['BuID'];?>" class="S_div" SID="<?=$BuTRow['BuID'];?>" BuType="<?=$TRow['TypeID'];?>" BuLogo="<?=$BuTRow['BuClogo'];?>">
							<div class="img_holder">
								<img src="<?= Yii::app()->request->baseUrl.'/images/upload/business_unit/'.$BuTRow['Bulogo']?>" alt="<?=$BuTRow['BuN'];?>"> 
							</div>				
							<div Class="link_describtion">
						 		<a id="link_<?=$BuTRow['BuID'];?>" class="S_link" href="#" ><center><?=$BuTRow['BuN'];?></center></a> 
						 	</div>
						</div>
		 			<?php endforeach;?>
			 	<?php endif;?>
			 </div>	
			 
		<?php endforeach;?>
	<?php endif;?>
	
<?php endif;?>

<script>
	$('.S_div').click(function(e){
		
	
		var store_id = {store_id:$(this).attr('SID'),logo:$(this).attr('BuLogo'),type:$(this).attr('BuType'),};
		 $.post('/index.php/auth/AjaxBuHome/',store_id,function(data){
			  //location.reload();
									//location.href=  '/index.php/auth/BuHome';
			 if(data.trim() == 'True'){
				 location.href = '/index.php/auth/BuHome';
			 }else{
				 $(this).click();
			 }
		 });
	
		
				 
		
		  
		
					
					 
					 /*
					  $.ajax({
												type:"POST" ,
												crossDomain:true,
												url : 'http://kinjo.local/index.php/APIApp/DBSignIn?callback=?' ,
												data : {UserName: 'DeliveryBoy' ,Password: 'MTIz'},
												success: function(data){ 
													 console.log(data);
												  }
												});*/
					 
					
					 
					
					
					/*
					 $.getJSON('http://192.168.1.3/kinjo/public/index.php/APIApp/DBSignIn?callback=?',  
																	{UserName: 'DeliveryBoy' ,Password: 'MTIzMw=='},  function(data){                           
												
												
																															
												
												//var json_data = data.toString();
																			//console.log(json_data);
																			var json_data = data;
																			if ('Result' in json_data) {
																				
																				
																			}
																			//if(json_data.length > 10){
																				alert(json_data['error']);						
																				//var end_data = $.parseJSON(json_data);
																				for (var key in json_data){
																					
																					for (var key1 in json_data[key]){
																						alert(key1 +' -'+json_data[key][key1]);
																					}
																				}
																			//}
												
												if (data != null) {
												   if ('Result' in data) {
													  console.log(data['Result']['Token']);
													}else
													{
														console.log(data);
																											 }
												} else{
												   console.log(data);
												};
												
																															
											});*/
					
	
					
		  
		
	});
</script>
 