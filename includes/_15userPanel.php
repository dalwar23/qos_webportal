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
	//Genrate date for default input in search form
	$inputStratDate = date("Y/m/d",strtotime("-1 day")); //Please change the number to cheange start date
	$inputEndDate = date("Y/m/d");
?>
<html>
	<head>
		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" src="../js/jquery.jdpicker.js"></script>
	</head>
	<body>
		<div class="content">
			<div class="report">
				<table align="center" border=0>
					<tr>
						<td><h1>Make your choice and press - search all BSC</h1></td>
					</tr>
					<tr>
						<td class="errorMsg"><?php echo $errorMessage ;?></td>
					</tr>
					<tr>
						<td>
							<form name="overNetworkElement" method="POST" action="analysisResults2.php">
									<table border="0">
										<tr>
											<td>From (Date)</td>
											<td>To (Date)</td>
										</tr>
										<tr>
											<td><input type="text" class="jdpicker" name="from" value="<?php echo $inputStratDate; ?>"></td>
											<td><input type="text" class="jdpicker" name="to" value="<?php echo $inputEndDate; ?>"></td>
										</tr>
										<tr>
											<td colspan="2">Select Data Type</td>
										</tr>
										<tr>
											<td>
												<select name="dataType" id="dataType">
													<option value="summary" selected="selected">Aggregated Summary</option>
													<option value="details">Details Data</option>
												</select>
											</td>
											<td align="right"><input type="submit" name="allbscSearch" value="Search All BSC" class="button"></td>
										</tr>
									</table>
							</form>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>
<?php
	//flush the output buffer
	ob_flush();
?>