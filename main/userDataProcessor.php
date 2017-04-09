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
	//Include functions
	include_once '../includes/functions.php';
	include_once '../includes/adminFunctions.php';
?>
<?php
if(isset($_GET['action'])){
	$action = $_GET['action'];
	$userName = $_GET['user'];
}
else{
	$action ="";
}
?>
<?php
	//->>> Get POSTed data
	if(isset($_POST['createUser'])){
		$name = mysqlPrep($_POST['name']);
		$userName = mysqlPrep($_POST['userName']);
		$password = mysqlPrep($_POST['password']);
		$password2 = mysqlPrep($_POST['password2']);
		$email = mysqlPrep($_POST['email']);
		$userType = mysqlPrep($_POST['userType']);
		
		//->>>Now check both password matches or not
		if(strcmp($password,$password2) == 0){
			//Both password matched, hash the password
			$hashedPassword = md5($password);
			if($name && $userName && $password && $email && $userType){
				//Chek for duplicate username
				$checkQuery = "SELECT * FROM users WHERE userName = '{$userName}' ";
				$checkResult = mysql_query($checkQuery);
				confirmQuery($checkResult, 701);
				$numRows  = mysql_num_rows($checkResult);
				if($numRows == 0){
					//User doesn't have a duplicate, can create an user with this user name
					$insertQuery = " INSERT INTO users (serial, name, userName, password, email, type)
									VALUES('','$name','$userName','$hashedPassword','$email','$userType')
								   ";
					$insertResult = mysql_query($insertQuery);
					confirmQuery($insertResult, 702);
					if($insertResult){
						//User created successfully
						header("Location:adminHome.php?id=11&flag=iota");
						exit(0);
					}
					else{
						header("Location:adminHome.php?id=11&flag=theta");
						exit(0);
					}					
				}
				else{
					header("Location:adminHome.php?id=11&flag=kappa");
					exit(0);
				}
			}
			else{
				//At least one or more field is/are blank
				header("Location:adminHome.php?id=11&flag=zeta");
				exit(0);
			}
		}
		else{
			header("Location:adminHome.php?id=11&flag=beta");
			exit(0);
		}
		
	}
?>
<?php
if(isset($_GET['action'])){
	if($action == 'delete'){
		$deleteQuery = "DELETE FROM users WHERE userName = '{$userName}'";
		$deleteResult = mysql_query($deleteQuery);
		confirmQuery($deleteResult, 702);
		if($deleteResult){
			//User deleted successfully
			header("Location:adminHome.php?id=12&flag=dltsucc");
			exit(0);
		}
		else{
			header("Location:adminHome.php?id=12&flag=dltunsucc");
			exit(0);
		}
	}
}
?>
<?php
	//Flush Output buffer
	ob_flush();
?>