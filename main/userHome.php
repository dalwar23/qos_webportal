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
	//If user is already logged in then redirect to respective page
	if($_SESSION['LOGGEDIN'] == FALSE){
		header("Location:index.php?flag=delta");
		exit(0);		
	}
?>
<?php
	//Include the functions file
	include '../includes/functions.php';
?>
<?php
	//Get view panel ID
	if(isset($_GET['id'])){
		$viewPanel = $_GET['id'];
	}
	else{
		$viewPanel = 1;
	}
?>
<?php
	//Get message flags
	if(isset($_GET['flag'])){
		$flag = $_GET['flag'];
		//Get the message for respective flag
		$errorMessage = getMessage($flag);
	}
	else{
		$errorMessage = "";
	}
?>
<?php	
	//Call the header function to create the header of the site
	$title = "Welcome | " . $_SESSION['USERNAME'] . " | User Profile";
	site_header($title);
	
	//This function Creates the menu of the site
	menu();
?>
<?php
	//Generate view Panel
	switch ($viewPanel){
		case 1:
			//Welcome greetings
			echo "
		        <div class='welcome'>
		            <h1>Welcome<br>" . $_SESSION['NAME'] . "</h1>
		        </div>
	    	";
		break;
		case 2:
			//Daily reporting data
			require_once '../includes/_02userPanel.php';
		break;
		case 3:
			//Weekly reporting data
			require_once '../includes/_03userPanel.php';
		break;
		case 4:
			//Weekly reporting data
			require_once '../includes/_04userPanel.php';
		break;
		case 11:
			//Analytical Data, Over network element
			require_once '../includes/_11userPanel.php';
		break;
		case 12:
			//Analytical Data, Over network element
			require_once '../includes/_12userPanel.php';
		break;
		case 13:
			//Analytical Data, Over network element
			require_once '../includes/_13userPanel.php';
		break;
		case 14:
			//Analytical Data, Over network element
			require_once '../includes/_14userPanel.php';
		break;
		case 15:
			//Analytical Data, Over network element
			require_once '../includes/_15userPanel.php';
		break;
		case 16:
			//Analytical Data, Over network element
			require_once '../includes/_16userPanel.php';
		break;
		case 21:
			//Edit user name
			editProfile($_SESSION['USERNAME'],$viewPanel,$flag);
		break;
		case 22:
			//Edit user email
			editProfile($_SESSION['USERNAME'],$viewPanel,$flag);
		break;
		case 23:
			//Edit user password
			editProfile($_SESSION['USERNAME'],$viewPanel,$flag);
		break;
		case 35:
			//Show vendor status
			require_once '../includes/_35userPanel.php';
		break;
		case 36:
			//Show dashboard status
			require_once '../includes/_36userPanel.php';
		break;
		default:
			//Nothing
		break;
	}
?>
<?php
	//This fuction creates the footer of the site
	footer();
?>
<?php
	//flush the output buffer
	ob_flush();
?>