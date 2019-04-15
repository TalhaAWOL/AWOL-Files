<?php
	
	session_start();

	if(array_key_exists("content", $_POST)){
		$link = mysqli_connect("shareddb1e.hosting.stackcp.net", "siteusers-3637bdb6", "hcldbp17mw", "siteusers-3637bdb6");	
		if(mysqli_connect_error()) {
			die("there was an error");
		}
		$query = "UPDATE `secret` SET diary = '".mysqli_real_escape_string($link, $_POST["content"])."' WHERE email = '".$_SESSION["email"]."' LIMIT 1";
		mysqli_query($link, $query);
	}

?>