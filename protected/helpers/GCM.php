<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GCM
 *
 * @author Ravi Tamada
 */
class GCM {

    //put your code here
    // constructor
    // function __construct() {
        // define("GOOGLE_API_KEY", "AIzaSyD0YEKrzQT7PRBlpcwsUOnSTgUW5b8TDng");
    // }

    /**
     * Sending Push Notification
     */
    static public function SendNotification($RegIDs, $Mess) {
        // include config
        // include_once './config.php';

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $RegIDs,
            'data' => array("message" =>$Mess),
        );

        $headers = array(
            'Authorization: key=' . "AIzaSyD0YEKrzQT7PRBlpcwsUOnSTgUW5b8TDng",//GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
     
	    return $result;
    }

}

?>
