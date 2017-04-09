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
	//->>> Get the posted values
	if(isset($_POST['commonKPI'])){
		//Get on with the creating common KPI table
		//->>> Get the values
		$inputDate = $_POST['inputDate'];
		$vendor = $_POST['vendor'];
		
		//->>> Now check whether all the values are posted or not
		if($inputDate && $vendor){
			//->>> Check the format of date and vendor name
			if($vendor == "none"){
				//->>> No vendor selected
				header("Location:adminHome.php?id=2&flag=zeta&vendor=Process");
				exit(0);
			}
			else{
				//->>>vendor selected
				$tableName = $vendor."_final";
				$queryOne = "SELECT * FROM {$tableName} WHERE C_DATE = '{$inputDate}'";
				$resultOne = mysql_query($queryOne);
				confirmQuery($resultOne, 901);
				
				//->>> Check for duplicate entry and insert
				$checkDuplicate = checkDuplicate($vendor,$inputDate);
				//->>>Now process all data for posted vendor and for specific date
				if($checkDuplicate == TRUE){
					$insertCheck = insertCommon($resultOne,$vendor);
					if($insertCheck == TRUE){
						//->>>Data updated successfully
						updateCommonStatus($inputDate,1);
						$vendor = ucfirst($vendor);
						header("Location:adminHome.php?id=2&flag=lambda&vendor=$vendor");
						exit(0);
					}
					else{
						//->>> Can't insert data to common KPI Table
						updateCommonStatus($inputDate,0);
						$vendor = ucfirst($vendor);
						header("Location:adminHome.php?id=2&flag=mu&vendor=$vendor");
						exit(0);
					}
				}
				else{
					//->>> Specific day's data is already processed
					$vendor = ucfirst($vendor);
					header("Location:adminHome.php?id=2&flag=sigma&vendor=$vendor");
					exit(0);
				}
			}
		}
		else{
			//One or more field is blank
			header("Location:adminHome.php?id=2&flag=zeta&vendor=Process");
			exit(0);
		}
	}
	else{
		//->>> Do nothing
	}
?>
<?php
	//Flush Output buffer
	ob_flush();
?>