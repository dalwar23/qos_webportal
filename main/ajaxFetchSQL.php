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
	//Include the functions
	include_once '../includes/functions.php';
?>
<?php
	//Get the POSTED value
	if(isset($_POST['id']))
	{
		$id = strtoupper($_POST['id']); //Thana, Division, District

		if($id == "MSC_NAME"){
			$sqlQuery = "SELECT DISTINCT(MSC_NAME) AS MSC FROM tracking ORDER BY MSC ASC";
		}
		elseif($id == "BSC_NAME"){
			$sqlQuery = "SELECT DISTINCT(BSC_NAME) AS BSC FROM tracking ORDER BY BSC ASC";
		}
		elseif($id == "DIVISION"){
			$sqlQuery = "SELECT DISTINCT(DIVISION) AS DIVISION FROM tracking ORDER BY DIVISION ASC";
		}
		elseif($id == "DISTRICT"){
			$sqlQuery = "SELECT DISTINCT(DISTRICT) AS DISTRICT FROM tracking ORDER BY DISTRICT ASC";
		}
		elseif($id == "THANA"){
			$sqlQuery = "SELECT DISTINCT(THANA) AS THANA FROM tracking ORDER BY THANA ASC";
		}
		$resultSet = mysql_query($sqlQuery);
		confirmQuery($resultSet,103);

		while($rows = mysql_fetch_assoc($resultSet))
		{
			if($id == "MSC_NAME"){ $value = $rows['MSC']; }
			elseif($id == "BSC_NAME"){ $value = $rows['BSC']; }
			elseif($id == "DIVISION"){ $value = $rows['DIVISION']; }
			elseif($id == "DISTRICT"){ $value = $rows['DISTRICT']; }
			elseif($id == "THANA"){ $value = $rows['THANA']; }

			echo '<option value="'.$value.'">'.$value.'</option>';
		}
	}
?>
<?php
	//Flush Output buffer
	ob_flush();
?>