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
	//Start output buffer
	ob_start();
?>
<?php
	//check the browser doesn't cache the page
	header ("Expires: Thu, 17 May 2001 10:17:17 GMT");    // Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
	header ("Pragma: no-cache");                          // HTTP/1.0
?>
<?php	
	//Start Session
	session_start();
	if (!isset($_SESSION['SESSION'])) require ("../includes/session_init.php");
	
	//Reset session variables
	$_SESSION['LOGGEDIN'] = FALSE;
	$_SESSION['NAME'] = "";
	$_SESSION['USERNAME'] = "";
	$_SESSION['EMAIL'] = "";
	$_SESSION['USERTYPE'] = "";
	$_SESSION['IPADDRESS'] = "";
	$_SESSION['LOGINTIME'] = "";
?>
<?php	
	//Include required files and functions
	require_once ("../includes/functions.php");
	require_once ("../includes/db_connect.php");
?>
<?php
	//Check for POSTed Values
	if(isset($_POST['loginSubmit'])){
		$userName = mysqlPrep($_POST['userName']);
		$webPassword = mysqlPrep($_POST['password']);
		
		//Hash the posted password value to match with the DB password
		$hashPassword = md5($webPassword);
		
		//Query to check for username
		$loginQuery = "SELECT * FROM users WHERE userName = '{$userName}'";
		$loginResult = mysql_query($loginQuery);
		confirmQuery($loginResult, 1);
		
		//Extract entities from the DB
		$row = mysql_fetch_assoc($loginResult);
		
		//Check for affected rows
		$noRows = mysql_num_rows($loginResult);
		
		if($noRows > 0){
			//User exists, check for password match
			if(strcmp($row['password'],$hashPassword) == 0){
				//Password matches with username,carry on
				//Set Session variables
				$_SESSION['LOGGEDIN'] = TRUE;
				$_SESSION['NAME'] = $row['name'];
				$_SESSION['USERNAME'] = $userName;
				$_SESSION['EMAIL'] = $row['email'];
				$_SESSION['USERTYPE'] = $row['type'];
				$_SESSION['IPADDRESS'] = $_SERVER['REMOTE_ADDR'];
				$_SESSION['LOGINTIME'] = time();
				
				//Redirect to respective pages according to user type
				if($_SESSION['USERTYPE'] == 1){
					//Redirect to Admin Page
					header("Location:adminHome.php?id=1");
					exit(0);
				}
				elseif($_SESSION['USERTYPE'] == 2 || $_SESSION['USERTYPE'] == 3){
					//Redirect to User Page
					header("Location:userHome.php?id=1");
					exit(0);
				}
			}
			else{
				//Username and password doesn't match
				header("Location:index.php?flag=beta");
				exit(0);
			}
		}
		else{
			//User doesn't exist
			header("Location:index.php?flag=alpha");
			exit(0);
		}
	}
	else{
		//Redirect to login page
		header("Location:index.php?flag=gama");
		exit(0);
	}
?>
<?php
	//Flush Output Buffer
	ob_flush();
?>