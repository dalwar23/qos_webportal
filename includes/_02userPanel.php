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
						<td colspan="2"><h1>Make your choice and press search</h1></td>
					</tr>
					<tr>
						<td colspan="2" class="errorMsg"><?php echo $errorMessage ;?></td>
					</tr>					
					<tr>
						<td>
							<form name="overTime" method="POST" action="dashboardResults.php">
								<table>
									<tr>
										<td>Vendor Name</td>
										<td>
											<select name="vendor">
												<option Value="none" selected="selected">-Select-</option>
												<option value="dailyReport">Daily Report</option>
												<option value="vind">Whole Network</option>
												<option value="vall">Vendors-All</option>
												<option value="Huawei">Huawei</option>
												<option value="Ericsson">Ericsson</option>
												<option value="Flexi">Nokia/Flexi</option>
												<option value="Siemens">Siemens</option>
												<option value="NSN">NSN</option>
												<option value="ipaccess" disabled>IP Access</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>From (Date)</td>
										<td>To (Date)</td>
									</tr>
									<tr>
										<td><input type="text" class="jdpicker" name="from" value="<?php echo $inputStratDate; ?>"></td>
										<td><input type="text" class="jdpicker" name="to" value="<?php echo $inputEndDate; ?>"></td>
									</tr>
									<tr>
										<td colspan="2">Select KPI</td>
									</tr>
									<tr>
										<td colspan="2">
										<input type="checkbox" checked="checked" name="check_list[0]" value="all"><label>&nbsp;All</label>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<input type="checkbox" name="check_list[1]" value="TRAFFIC_RBH"><label>&nbsp;RBH Traffic</label>
											<input type="checkbox" name="check_list[2]" value="TRAFFIC_NBH"><label>&nbsp;NBH Traffic</label>
											<input type="checkbox" name="check_list[3]" value="CDR"><label>&nbsp;CDR</label>
											<input type="checkbox" name="check_list[4]" value="CSSR"><label>&nbsp;CSSR</label>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<input type="checkbox" name="check_list[5]" value="CSBR_RBH"><label>&nbsp;CSBR RBH</label>
											<input type="checkbox" name="check_list[6]" value="CSBR_NBH"><label>&nbsp;CSBR NBH</label>
											<input type="checkbox" name="check_list[7]" value="SDBR_NBH"><label>&nbsp;SDBR NBH</label>
										</td>
									</tr>
									<tr>
										<td colspan="2" align="right">
											<input type="submit" name="dashBoardSearch" value="Search" class="button">
										</td>
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