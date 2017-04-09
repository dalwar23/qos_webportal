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
	//Get POSTED values
	if(isset($_POST['changeName'])){
		$newName = $_POST['name'];
		$userName = $_POST['userName'];
		//Check whether text box contain data or not
		if($newName){
			$updateQuery = "UPDATE users
							SET name = '{$newName}'
							WHERE userName = '{$userName}'
						   ";
			$updateResult = mysql_query($updateQuery);
			confirmQuery($updateResult, 802);
			if($updateResult){
				//Name update successful!
				header("Location:userHome.php?id=21&flag=lambda");
				exit(0);
			}
			else{
				//Can't Update
				header("Location:userHome.php?id=21&flag=mu");
				exit(0);
			}
		}
		else{
			header("Location:userHome.php?id=21&flag=zeta");
			exit(0);
		}
	}
	elseif(isset($_POST['changeEmail'])){
		$newEmail = $_POST['email'];
		$userName = $_POST['userName'];
		//Check whether text box contain data or not
		if($newEmail){
			$updateQuery = "UPDATE users
							SET email = '{$newEmail}'
							WHERE userName = '{$userName}'
						   ";
			$updateResult = mysql_query($updateQuery);
			confirmQuery($updateResult, 803);
			if($updateResult){
				//Name update successful!
				header("Location:userHome.php?id=22&flag=lambda");
				exit(0);
			}
			else{
				//Can't Update
				header("Location:userHome.php?id=22&flag=mu");
				exit(0);
			}
		}
		else{
			header("Location:userHome.php?id=22&flag=zeta");
			exit(0);
		}
	}
	elseif(isset($_POST['changePassword'])){
		$newPassword = $_POST['password'];
		$newPassword2 = $_POST['password2'];
		$userName = $_POST['userName'];
		//Check whether text box contain data or not
		if($newPassword && $newPassword2){
			//Both password submitted, now match
			if(strcmp($newPassword,$newPassword2) == 0){
				//Password matches, hash the password and update
				$hashedPassword = md5($newPassword);
				$updateQuery = "UPDATE users
								SET password = '{$hashedPassword}'
								WHERE userName = '{$userName}'
							   ";
				$updateResult = mysql_query($updateQuery);
				confirmQuery($updateResult, 804);
				if($updateResult){
					//Name update successful!
					header("Location:userHome.php?id=23&flag=lambda");
					exit(0);
				}
				else{
					//Can't Update
					header("Location:userHome.php?id=23&flag=mu");
					exit(0);
				}
			}
			else{
				//Password doesn't match
				header("Location:userHome.php?id=23&flag=beta");
				exit(0);
			}
		}
		else{
			header("Location:userHome.php?id=23&flag=zeta");
			exit(0);
		}
	}
?>
<?php
	//Flush Output buffer
	ob_flush();
?>