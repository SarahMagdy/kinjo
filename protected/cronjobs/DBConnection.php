<?php

	try {
	    $conn = new PDO('mysql:host=localhost;dbname=kinjo', 'root' , '1234');
	
	} catch (PDOException $e) {
	    print "Error!: " . $e->getMessage() . "<br/>";
	    die();
	}

?>