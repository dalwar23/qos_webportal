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
	//Get vendors status in common kpi table
	$flagArray = array(0,0,0,0);
	$vArray = array('Huawei','Ericsson','Siemens','Flexi');
	$dateQuery = "SELECT DISTINCT DATES FROM common_kpi ORDER BY DATES DESC LIMIT 0,7";
	$dateQueryResult = mysql_query($dateQuery);
	confirmQuery($dateQueryResult, 1002);
?>
<html>
	<body>
		<div class="content">
			<div class="dataView">
				<p align="center" style="font-size:18px; font-family:Verdana,Tahoma;text-decoration:underline;"><strong>Common KPI Table Status</strong></p>
				<table border="1" align="center" width="40%">
					<tr class="tblheader">
						<td>DATE</td>
						<td>HUAWEI</td>
						<td>ERICSSON</td>
						<td>SIEMENS</td>
						<td>FLEXI</td>
					</tr>
					<?php
						while($dateRows = mysql_fetch_assoc($dateQueryResult)){
							set_time_limit(0);
							$date = $dateRows['DATES'];
							for($i = 0; $i < count($vArray); $i++){
								$commonQuery = "SELECT DATES, VENDOR FROM common_kpi WHERE DATES = '{$date}' AND VENDOR = '{$vArray[$i]}'";
								$commonResult = mysql_query($commonQuery);
								confirmQuery($commonResult, 1003);
								$rows = mysql_fetch_assoc($commonResult);
								if(!empty($rows['VENDOR'])){
									$flagArray[$i] = 1;
								}
								else{
									$flagArray[$i] = 0;
								}
							}
							echo "
								<tr>
										<td align='center'>{$date}</td>
							";
							for($j = 0; $j < count($flagArray); $j++){
								if($flagArray[$j] == 1){
									$status = "<img src='../images/ok.png' title='OK'>";
									echo "<td align='center'>{$status}</td>";
								}
								elseif($flagArray[$j] == 0){
									$status = "<img src='../images/not_ok.png' title='No Data'>";
									echo "<td align='center'>{$status}</td>";
								}
							}
							echo "</tr>";
						}
					?>
				</table>
			</div>
		</div>
	</body>
</html>
<?php
	//flush the output buffer
	ob_flush();
?>