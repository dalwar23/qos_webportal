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
	// ->>> Primary table array
	$vArray = array('ericsson_primary','huawei_primary','siemens_primary','siemens_nbh_primary','flexi_primary');
?>
<html>
	<body>
		<div class="content">
			<div class="dataView">
				<p align="center"><strong>Primary Table Status</strong></p>
				<table border="1" align="center" width="25%">
					<tr class="tblheader">
						<td>PRIMARY TABLE NAME</td>
						<td>STATUS</td>
					</tr>
					<?php
						for($i = 0; $i < count($vArray); $i++){
									$priTableName = $vArray[$i];
									$query = "SELECT * FROM {$priTableName}";
									$result = mysql_query($query);
									confirmQuery($result,605);
									$rows = mysql_num_rows($result);
									if($rows == 0){
										$status = "<img src='../images/not_ok.png' title='Blank' border='none'>";
									}
									else{
										$status = "<img src='../images/ok.png' title='Filled' border='none'>";
									}
									echo "
										<tr>
											<td align='center'>{$vArray[$i]}</td>
											<td align='center'>{$status}</td>
										</tr>
									";
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