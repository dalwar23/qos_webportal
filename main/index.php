<?php
/**
 * @name: KPI Integration Tool
 * @author: Md.Dalwar Hossain Arif(www.arif23.com)
 * @access: QoS, Technical Programs and Network Quality
 * @copyright: Arif, Banglalink
 * @license: N/A
 * @uses: PHP/MYSQL/HTML5/CSS3
 * @since: 09-September-2013
 */ 
?>
<?php 
	//Start the output buffer
	ob_start();
	
	//Start Session
	session_start();
	if (!isset($_SESSION['SESSION'])) require ("../includes/session_init.php");
?>
<?php
	//check the browser doesn't cache the page
	header ("Expires: Thu, 17 May 2001 10:17:17 GMT");    // Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
	header ("Pragma: no-cache");                          // HTTP/1.0
?>
<?php
	//Include the functions file
	include '../includes/functions.php';
?>
<?php
	//Call the header function to create the header of the site
	site_header("Login | KPI Tool");
	
	//This function Creates the menu of the site
	menu();
?>
<?php
	//Assign the flag message
	if(isset($_GET['flag'])){
		$flag = $_GET['flag'];
		$errorMessage = getMessage($flag);
	}
	else{
		$errorMessage = "";
	}
?>
<?php
	//If user is already logged in then redirect to respective page
	if($_SESSION['LOGGEDIN'] == TRUE){
		if($_SESSION['USERTYPE'] == 1){
			//Redirect to Admin Page
			header("Location:adminHome.php");
			exit(0);
		}
		elseif($_SESSION['USERTYPE'] == 2){
			//Redirect to User Page
			header("location:userHome.php");
			exit(0);
		}
		elseif($_SESSION['USERTYPE'] == 3){
		//Redirect to User Page
		header("location:userHome.php");
		exit(0);
		}
	}
?>

<?php
    require_once '../includes/_login.php';
?>
<?php
	//This fuction creates the footer of the site
	footer();
?>
<?php
	//flush the output buffer
	ob_flush();
?>