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
	include '../includes/adminFunctions.php';
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
	//Get verdor flags
	if(isset($_GET['vendor'])){
		$vendor = $_GET['vendor'];
	}
	else{
		$vendor = "";
	}
?>
<?php	
	//Call the header function to create the header of the site
	$title = "Welcome | " . $_SESSION['USERNAME'] . " | Admin Profile";
	site_header($title);
	
	//This function Creates the menu of the site
	menu();
?>
<?php
	//Generate view Panel
	if($_SESSION['USERTYPE'] == 1){
		//Admin user, generate view panel
		switch($viewPanel){
			case 1:
				echo "
			        <div class='welcome'>
			            <h1>Welcome ! Administrator<br>" . $_SESSION['NAME'] . "</h1>
			        </div>
		    	";
			break;
			/*case 2:
				//Create Common KPI Table
				require_once '../includes/_02adminPanel.php';
			break;
			case 3:
				//Process daily data
				require_once '../includes/_03adminPanel.php';
			break;
			case 4:
				//Process missing NSN data
				require_once '../includes/_04adminPanel.php';
			break;
			case 5:
				//Compile network data
				require_once '../includes/_05adminPanel.php';
			break;*/
			case 11:
				//Create new user
				require_once '../includes/_11adminPanel.php';
			break;
			case 12:
				//Create new user
				require_once '../includes/_12adminPanel.php';
			break;
			case 31:
				//Primary table status
				require_once '../includes/_31adminPanel.php';
			break;
			case 34:
			//Show vendor status
			require_once '../includes/_34adminPanel.php';
			break;
			default:
				//Nothing
			break;
		}		
	}
	else{
		//Show a message that it's only for admin
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