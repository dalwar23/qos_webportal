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
	//Get the status of the vendors from Dashboard for last few days
	$statusQuery = "SELECT * FROM status ORDER BY DATES DESC";
	$statusResult = mysql_query($statusQuery);
	$statusRows = mysql_num_rows($statusResult);
	confirmQuery($statusResult,502);
?>
<html>
	<body>
		<div class="content">
			<div class="dataView">
				<p align="center">
					<?php echo "Dashboard status - total [ " . $statusRows . " ] rows were found!" ;?>
				</p>
				<table border="1px" align="center" width="60%">
					<tr class="tblheader">
						<td>DATE</td>
						<td>DAILY KPI</td>
						<td>RAW KPI</td>
						<td>NETWORK KPI</td>
						<td>DAILY REPORT</td>
						<td>COMMON KPI</td>
					</tr>
					<?php
						while($rows = mysql_fetch_assoc($statusResult)){
							$dates = $rows['dates'];
							//Get the total vendor count
							$dailyCount = countRecords("dashboard",$dates);
							$kpiCount = countRecords("dashboard_kpi",$dates);
							//Create status for dashboard daily - Stores all daily report KPIs
							if($rows['dashboard_daily'] == 1 && $dailyCount == 4){$daily = "<img src='../images/ok.png' title='OK'>";}
							elseif($rows['dashboard_daily'] == 1 && $dailyCount == 0){$daily = "<img src='../images/not_ok.png' title='No Data'>";}
							elseif($rows['dashboard_daily'] == 1 && ( $dailyCount > 1 || $dailyCount < 4)){$daily = "<img src='../images/incomplete.png' title='Incomplete'>";}
							else{$daily="<img src='../images/not_ok.png' title='N/A'>";}
							//Create Status for dashbord all - Vendor indipendent KPI dashboard
							if($rows['dashboard_all'] == 1){$all = "<img src='../images/ok.png' title='OK'>";}
							else{$all="<img src='../images/not_ok.png' title='N/A'>";}
							//Create status for Dashboard KPI table - Stores all Dashboard KPIs
							if($rows['dashboard_kpi'] == 1 && $kpiCount == 4){$kpi = "<img src='../images/ok.png' title='OK'>";}
							elseif($rows['dashboard_kpi'] == 1 && $kpiCount == 0){$kpi = "<img src='../images/not_ok.png' title='No Data'>";}
							elseif($rows['dashboard_kpi'] == 1 && ( $kpiCount > 1 || $kpiCount < 4)){$kpi = "<img src='../images/incomplete.png' title='Incomplete'>";}
							else{$kpi="<img src='../images/not_ok.png' title='No Data'>";}
							//Create status for Common KPI table - Stores all common KPI values (4 vendors) (5-4=1; it will be ok only four vendors deduct perfectly)
							if($rows['common_kpi'] == 1){$common = "<img src='../images/ok.png' title='OK'>";}
							elseif($rows['common_kpi'] > 1 && $rows['common_kpi'] < 4) {$common = "<img src='../images/incomplete.png' title='Incomplete'>" ;}
							else{$common="<img src='../images/not_ok.png' title='No Data'>";}
							//Create status Daily Report table - Stores all daily reports
							if($rows['daily_report'] == 1){$dailyReport = "<img src='../images/ok.png' title='OK'>";}
							else{$dailyReport="<img src='../images/not_ok.png' title='No Data'>";}
							//View them in a table
							echo "
								<tr>
								<td align='center'>{$dates}</td>
								<td align='center'>{$daily}</td>
								<td align='center'>{$kpi}</td>
								<td align='center'>{$all}</td>
								<td align='center'>{$dailyReport}</td>
								<td align='center'>{$common}</td>						
								</tr>
							";
						}
					?>
				</table>
				<br><br>
			</div>
		</div>
	</body>
</html>
<?php
	//flush the output buffer
	ob_flush();
?>