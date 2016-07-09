<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
    <head>
     <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css">

        <style type="text/css">
            #container{
                height: 100%;
                width: 100%;

            }
            #r{
                margin-top: 5%;
                margin-bottom: 5%;
                margin-right: 5%;
                margin-left: 5%;
                float: center;
                background-color: #b7bcbd;

            }
            .dropdown-menu{
                height: 250px; 
                width: 250px; 
                
            }

        </style>

    </head>

    <body>
        <div id="container"> 

            <div id="r">
                <form action="<?php echo Yii::app()->getBaseUrl(true) . '/Customers/AssignRooms' ?>" method="POST">
                    <h2 align="center" id="h"><u><i>Book Room</i></u></h2>
                    <table >

                        <tr>
                            <td width="113">Check in Date</td>
                            <td width="215">
                                 <div class="row">
                                <div class='col-sm-6'>
                                <div class="form-group">
                                    <div class='input-group date' id='start_date'  >
                                        <input type='text' class="form-control" name='startdate'/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar">
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="113">Check out Date</td>
                            <td width="215">
                                 <div class="row">
                                 <div class='col-sm-6'>
                                <div class='input-group date' id='end_date' onchange='Roomassign()' >
                                    <input type='text' class="form-control" name='enddate'/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar">
                                        </span>
                                    </span>
                                </div>
                                 </div>
                                 </div>

                            </td>
                        </tr>

                    </table>


                    <table >
                        <tr>
                            <td width="113"></td>
                            <td width="215">
                            </td>
                        </tr>
                        <tr>
                            <td>Room Type </td>
                            <td>
                                <select class="text_select" id="type" name="type" >  
                                    <option value="00">- Select -</option>             

                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Price per Room</td>
                            <td>
                                <span id="price"  ></span>$
                            </td>
                        </tr>
                        <tr>
                            <td>Room number</td>
                            <td>
                                <select id="number"  name="number">
                                    <option value="00">- Select -</option>   
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>select customer id</td>
                            <td>
                                <?php
                                $customers = Yii::app()->db->createCommand("select cid from customers")->queryAll();
                                ?>  
                                <select name="customer" id="customer">

                                    <option selected="selected">-Choose-</option>
                                    <?php foreach ($customers as $customer) { ?>
                                        <option value="<?= $customer['cid'] ?>"><?= $customer['cid'] ?></option>
                                    <?php }
                                    ?>   

                                </select></td>
                        </tr>                   
                    
                        <tr>
                            <td colspan="2" align="center">
                                <input type="submit"  value="Pay & Book" /></td>
                        </tr>
                        <form>
                    </table>


 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>        
<script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.4.0/lang/en-gb.js"></script>                
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.0.0/js/bootstrap-datetimepicker.min.js"></script>

<script language="javascript" type="text/javascript">
                        $(function () {
                            $('#start_date').datetimepicker({
                                viewMode: 'years',
                                format: 'YYYY-MM-DD'
                            });
                        });

                        $(function () {
                            $('#end_date').datetimepicker({
                                viewMode: 'years',
                                format: 'YYYY-MM-DD'
                            });
                        });
                        function notEmpty() {

                            var e = document.getElementById("type");
                            var strUser = e.options[e.selectedIndex].value;
                            var strUser = document.getElementById('price').innerHTML = strUser;

                        }
                        notEmpty()

                        document.getElementById("type").onchange = notEmpty;


//                        function gettotal() {
//                            var gender = document.getElementById('price').innerHTML;
////                            var gender2 = document.getElementById('room_nos').value;
////                            var gender3 = parseFloat(gender1) * parseFloat(gender2);
//
//                            document.getElementById('total').value = gender;
//
//                        }


                        function Roomassign() {
                            var startdate = document.getElementById(startdate);

                            var data = {
                                startdate: startdate,
                            };

                            $.post("AjaxRoomassign/", data, function (data) {

                                var json_data = data.toString();


                                var room_data = $.parseJSON(json_data);
                                if (room_data.length > 0) {
                                    $('#type').html('');
                                    for (var key in room_data) {
                                        $('#type').append('<option id="' + room_data[key]['room_id'] + '" value="' + room_data[key]['roomprice'] + '">' + room_data[key]['roomtype'] + '</option>');


//					if($.inArray(room_data[key]['room_id'],s3) >= 0 ){
//					}
                                    }

                                }


                            });
                        }


                        $("#type").click(function () {

                            var e = document.getElementById("type");
                            var roomid = e.options[e.selectedIndex].id;

                            var data = {
                                roomid: roomid,
                            };

                            $.post("AjaxRoomnumber/", data, function (data) {

                                var data = data.toString();

                                var room_number = $.parseJSON(data);
                                if (room_number.length > 0) {
                                    $('#number').html('');
                                    for (var key in room_number) {
                                        $('#number').append('<option value="' + room_number[key]['room_number'] + '">' + room_number[key]['room_number'] + '</option>');


                                        //					if($.inArray(room_data[key]['room_id'],s3) >= 0 ){
                                        //					}
                                    }

                                }


                            });
                        });



                    </script>


            </div>
        </div>
    </body>
</html>
