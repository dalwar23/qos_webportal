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
	$startYear = date("Y")- 3;
	$endYear = $startYear + 15;
	/*Generate a date
	$month_c = date("Y/m");
	$month_p = date("Y/m",strtotime("-1 months"));
	$day_p = 26;
	$day_c = 25;
	$from = $month_p."/".$day_p;
	$to = $month_c."/".$day_c;*/
?>
<?php
	$months = array(
	"1" => "January", "2" => "February", "3" => "March",
	"4" => "April",	"5" => "May", "6" => "June",
	"7" => "July", "8" => "August", "9" => "September",
	"10" => "October", "11" => "November", "12" => "December"
	);
?>
<html>
	<head>
		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" src="../js/jquery.jdpicker.js"></script>
	</head>
	<body>
		<div class="content">
			<div class="report">
				<?php
					echo "
							<form name='monthly' method='POST' action='dashboardResults.php'>
								<table align='center' border=0>
									<tr>
										<td colspan='2'><h1>Make your choice and press search</h1></td>
									</tr>
									<tr>
										<td colspan='2' class='errorMsg'>". $errorMessage ."</td>
									</tr>
									<tr>
										<td>
											Year:&nbsp;<select name='year'>
												";
													for($j = $startYear; $j <= $endYear; $j++){
														echo "
															<option value='".$j."'>".$j."</option>
														";
													}
					echo"
										</td>
										<td>
											Month:&nbsp;<select name='month'>
												<option value='0'>-Select-</option>";
												for($i = 0; $i < 12; $i++){
													$value = $i+1;
													echo "
														<option value='".$value."'>".$months[$value]."</option>
													";
												}
					echo"
											</select>
										</td>
									</tr>
									<tr>
										<td colspan='2' align='right'>
											<input type='submit' name='monthly' value='Search Monthly Report' class='button'>
										</td>
									</tr>
								</table>
							";
					
					echo"
							</form>
						" ;		
				?>
			</div>			
		</div>
	</body>
</html>
<?php
	//flush the output buffer
	ob_flush();
?>