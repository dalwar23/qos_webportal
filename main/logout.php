<?php
	//Start the ouput buffer
	ob_start();
?>
<?php
	//start the session
	session_start();
	if (!isset($_SESSION['SESSION'])) require ("../includes/session_init.php");

	//include the required files and functions
	require_once("../includes/functions.php");
		
	//prevent direct access to logout.php file
	if($_SESSION['LOGGEDIN'] == FALSE)
	{
		header("Location:index.php?falg=delta");
		exit;
	}
	//log out from the page 
	if($_SESSION['LOGGEDIN'] == TRUE)
	{
		// Unset session data
		$_SESSION=array();
		// or...
		session_unset();
		//Destroy the session
		session_destroy();
		//redirect to index page
		header("Location:index.php?flag=epsilon");
		exit;
	}
?>
<?php 
	//flush the output buffer
	ob_flush();
?>