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
	if(isset($_POST['item'])){
		$cat = $_POST['cat'];
		$item = $_POST['item'];
		//Generate SQL Query
		$sqlQuery = "SELECT DISTINCT(CELL_NAME) AS CELL_NAME FROM tracking WHERE {$cat} = '{$item}' ORDER BY CELL_NAME ASC";
		//Run the query
		$resultSet = mysql_query($sqlQuery);
		confirmQuery($resultSet,703);
		while($rows = mysql_fetch_assoc($resultSet)){
			$value = $rows['CELL_NAME'];
			echo '<option value="'.$value.'">'.$value.'</option>';
		}
	}
?>
<?php
	//Flush Output buffer
	ob_flush();
?>