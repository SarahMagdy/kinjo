<style type="text/css">
	/*----- Tabs -----*/
.tabs {
    width:100%;
    display:inline-block;
}
 
    /*----- Tab Links -----*/
    /* Clearfix */
    .tab-links:after {
        display:block;
        clear:both;
        content:'';
    }
 
    .tab-links li {
        margin:0px 5px;
        float:left;
        list-style:none;
    }
 
        .tab-links a {
            padding:9px 15px;
            display:inline-block;
            border-radius:3px 3px 0px 0px;
            background:#7FB5DA;
            font-size:16px;
            font-weight:600;
            color:#4c4c4c;
            transition:all linear 0.15s;
        }
 
        .tab-links a:hover {
            background:#a7cce5;
            text-decoration:none;
        }
 
    li.active a, li.active a:hover {
        background:#fff;
        color:#4c4c4c;
    }
 
    /*----- Content of Tabs -----*/
    .tab-content {
        padding:15px;
        border-radius:3px;
        box-shadow:-1px 1px 1px rgba(0,0,0,0.15);
        background:#fff;
    }
 
        .tab {
            display:none;
        }
 
        .tab.active {
            display:block;
        }
        .test{
            width: 20px;
           margin-bottom: 2px;
            background-color: blueviolet;
        }
</style>
<html>
    <head>
    <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen"
     href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">
  </head>
  <body>
    
    
<div>
	<h1>tables avaliblity</h1>
</div>
      <form action="<?php echo Yii::app()->getBaseUrl(true).'/BusinessUnit/CheckTables'?>" method="POST">
 
    <label>from:</label>           
 
  <div id="date_from" class="input-append">
    <input data-format="hh:mm:ss" type="text" name="from"></input>
    <span class="add-on">
      <i data-time-icon="icon-time" data-date-icon="icon-calendar">
      </i>
    </span>
  </div>
     
          
          
   <label>to:</label>    

  <div id="date_to" class="input-append">
    <input data-format="hh:mm:ss" type="text" name="to"></input>
    <span class="add-on">
      <i data-time-icon="icon-time" data-date-icon="icon-calendar">
      </i>
    </span>
  </div>

  
   
   <input TYPE = "Submit" Name = "Submit" VALUE = "check">
   
   <form>
   
<!--   <button type="button" class="btn btn-success pull-right" onclick="checkdate()">Check</button>-->
   
  

</br></br>

<div class="tabs">
    <ul class="tab-links">
        <li class="active"><a href="#tab1">avaliable</a></li>
        <li><a href="#tab2">occupied</a></li>
       
    </ul>
 
    <div class="tab-content">
        <div id="tab1" class="tab active">
            <p>avaliable tables go here!</p>
            <?php if(isset($new) &&!empty($_POST['to']) && !empty($_POST['from'])){
	     foreach($new AS $Key=>$Row){
            
               if(isset($Row['ready']) && $Row['ready'] !=null){  
                echo'   <a href="#" id="'.$Row['ready'].'" flag= "0" onClick="Deletetable(this.id) ">
 		<img border="0"  src="/assets/8626beb4/gridview/DeleteRed.png" width="8" height="8">
 	                    </a>';
                   echo '<div class="test">';
                   echo $Row['ready'];
                   echo '</div>';
            	
	
              
             }
            }
             
            }?>
                    
        </div>
 
        <div id="tab2" class="tab">
            <p>occupied tables go here!</p>
             <?php if(isset($new) && !empty($_POST['to']) && !empty($_POST['from'])){
                 
	    foreach($new AS $Key=>$Row){
              
              if(isset($Row['busy']) && $Row['busy'] !=null) {
                  
               echo'   <a href="#"id="'.$Row['busy'].'" flag= "1" onClick="Deletetable(this.id) ">
 		<img border="0"  src="/assets/8626beb4/gridview/DeleteRed.png" width="8" height="8">
 	                    </a>';
                  echo '<div class="test">';
                  echo $Row['busy'];
                  echo '</div>';
             
            }
            }
            
            }?>
            
        </div>
 
        
    </div>
</div>
  </body>
</html>
  <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> 
  <script type="text/javascript" src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js"></script>
  <script type="text/javascript" src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js">
</script>
<script type="text/javascript">
  
	jQuery(document).ready(function() {
        jQuery('.tabs .tab-links a').on('click', function(e)  {
        var currentAttrValue = jQuery(this).attr('href');
 
        // Show/Hide Tabs
        jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
 
        // Change/remove current tab to active
        jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
 
        e.preventDefault();
    });
});

    $(function() {
    $('#date_from').datetimepicker({
      pickDate: false
      
    });
  });
  
   $(function() {
    $('#date_to').datetimepicker({
     pickDate: false
     
    });
  });
  
  
  function Deletetable(tableSerial){
      
		var flag = document.getElementById(tableSerial).getAttribute("flag");
		var data ={
			tableSerial : tableSerial,
			flag : flag,
			
		}; 
		
		$.post( "AjaxDeletetable/",data, function( data ) {
			
			location.reload();
		});
	}
	
  
               

     
</script>
