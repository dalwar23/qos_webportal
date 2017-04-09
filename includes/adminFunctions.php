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
	//include the data base connection file
	include 'db_connect.php';
?>
<?php
	//This function will view all the users
	function viewAllUsers(){
		//Get all the users from DB
		$query = "SELECT * FROM users";
		$rowCount = 1;
		$resultSet = mysql_query($query);
		confirmQuery($resultSet,703);
		$noRows = mysql_num_rows($resultSet);
		echo "
				<p>Total " . $noRows . " user(s) were selected</p>
				<table align='center' border='1' width='75%'>
					<tr class='tblheader'>
						<td>SERIAL</td>
						<td>NAME</td>
						<td>USER NAME</td>
						<td>E-MAIL</td>
						<td>USER TYPE</td>
						<td>ACTION</td>
					</tr>
			";
		while ($rows = mysql_fetch_assoc($resultSet)){
			$userName = $rows['userName'];
			if($rowCount%2==0){
				$tblRowColor = 'tblRowColor';
			}
			else{
				$tblRowColor = 'tblRowColorMono';
			}
			if($rows['type'] == 1){$userType = "Administrator";}
			elseif($rows['type'] == 2){$userType = "Moderate User";}
			elseif($rows['type'] == 3){$userType = "Normal User";}
			else{$userType = "Unknown";}
			echo "
				<tr class='" .$tblRowColor ."'>
					<td>{$rows['serial']}</td>
					<td>{$rows['name']}</td>
					<td>{$userName}</td>
					<td>{$rows['email']}</td>
					<td>{$userType}</td>
					<td><a href='userDataProcessor.php?action=delete&user=".$userName."'>Delete</a></td>
				</tr>
			";
			$rowCount++;
		}
		echo "</table>";		
	}
?>
<?php
	//*****************************************************************************
	//This function will update the C_DATE and Generate Dash board
	function generateDashBoard($vendor){
		//Check whether primary table is empty or not?
		//This will check for blank primary tables, if blank then it will generate error massage
		//Otherwise execute
		if($vendor == "nsn"){
			$noRows = 100; // An imaginary number to pass the test
		}
		else{
			// ->>> Do nothing, $vendor is not nsn
			$noRows = checkPrimaryTable($vendor);
		}
		if($noRows == 0){
			//Primary Table is Empty
			// ->>> $vendor_primary table empty
		}
		else{
			//Primary Table is loaded, execute
			if($vendor != "siemens"){
				if($vendor == "nsn"){
					//$vendor is not siemens but nsn
					$dshNSNKPI = createDashBoardKPI($vendor);
					$dshNSN = createDashBoard($vendor);
					// ->>> Now return to mail call
				}
				else{
					$dbDate = getDateFromDb($vendor);
					$dbUpdate = dbUpdate($vendor,$dbDate);
					if($dbUpdate){
						//Create dashboard kpi table entry for that $vendor
						$ucVendor = ucfirst($vendor);
						$dshKPI = createDashBoardKPI($ucVendor);
						$dsh = createDashBoard($ucVendor);
						// ->>> Now return to main call
					}
					else{
						// ->>> Can't Create dashboard kpi table for $vendor
					}
				}
			}
			elseif($vendor == "siemens"){
				$noRowsSe = checkPrimaryTable("siemens_nbh");
				if($noRowsSe > 0){
					$seNBHDbDate = getDateFromDb("siemens_nbh");
					$seNBHDbUpdate = dbUpdate("siemens_nbh",$seNBHDbDate);
					$cellNameUpdate = cellNameUpdate("Siemens");
					$dbDate = getDateFromDb("siemens");
					$dbUpdate = dbUpdate("siemens",$dbDate);
					if($dbUpdate){
						//Create dashboard entry for siemens
						$dshKPI = createDashBoardKPI("Siemens");
						$dsh = createDashBoard("Siemens");
						// ->>> Now return to the main call for processing
					}
					else{
						// ->>> Can't Create Dashboard for Siemens
					}
				}
				else{
					// ->>> Siemens NBH table Empty
				}
			}
			else{
				// ->>> Other vendor name, Do nothing at all
			}
		}
	}
?>
<?php
	//*******************************************************************************
	//This function will get the date from DB
	function getDateFromDb($vendor){
		$tableName = $vendor . "_primary";
		$query = "SELECT DISTINCT(V_DATE) FROM {$tableName}";
		$result = mysql_query($query);
		confirmQuery($result,201);
		$rows = mysql_fetch_assoc($result);
		$charDate = $rows['V_DATE'];
		$dbDate = dateGenerator($charDate);
		return $dbDate;
	}
?>
<?php
	//*******************************************************************************
	//This Function will update C_DATE
	function dbUpdate($vendor,$date){
		$tableName = $vendor . "_primary";
		$updateQuery = "UPDATE {$tableName} SET C_DATE = '{$date}'";
		$resultSet = mysql_query($updateQuery);
		$updateResult = confirmQuery($resultSet,202);
		if($updateResult)
			return TRUE;
		else
			die("There is some problem with RAW date format in : " . $vendor);
	}
?>
<?php
	//********************************************************************************
	//This function will update the cell Name column of siemens table
	function cellNameUpdate($vendor){
		//Extract BTS numbers from primary table
		$extractQuery = "SELECT BTS FROM siemens_primary";
		$resultSet = mysql_query($extractQuery);
		confirmQuery($resultSet,207);
		while($row = mysql_fetch_assoc($resultSet)){
			set_time_limit(0);
			$btsNo = $row['BTS'];
			//echo $btsNo;
			$getCellQuery = "SELECT CELL_NAME FROM tracking WHERE VENDOR = '{$vendor}' AND CELL_ID = {$btsNo} ";
			$result = mysql_query($getCellQuery);
			confirmQuery($result,208);
			$row = mysql_fetch_assoc($result);
			$cellName = $row['CELL_NAME'];
			$insertQuery = "UPDATE siemens_primary SET CELL_NAME = '{$cellName}' WHERE BTS = {$btsNo} ";
			$insertResult = mysql_query($insertQuery);
			confirmQuery($insertResult,209);
		}
	}
?>
<?php
	//********************************************************************************
	//This function will create dash board from the views
	function createDashBoard($vendor){
		$viewName = $vendor."dashboard";
		$query = "SELECT * FROM {$viewName}";
		$resultSet = mysql_query($query);
		confirmQuery($resultSet,203);
		$rows = mysql_fetch_assoc($resultSet);
			//Get the values
			$dates = $rows['DATES'];
			$vendor = $rows['VENDOR'];
			$traffic_rbh = $rows['TRAFFIC_RBH'];
			$traffic_nbh = $rows['TRAFFIC_NBH'];
			$cssr = $rows['CSSR'];
			$cdr = $rows['CDR'];
			$csbr_rbh = $rows['CSBR_RBH'];
			$csbr_nbh = $rows['CSBR_NBH'];
			$sdbr_nbh = $rows['SDBR_NBH'];
			//echo $dates . ">" . $cssr .">". $csbr_nbh;			
			//Check for duplicate entry
			$check = "SELECT * FROM dashboard WHERE DATES = '$dates' AND VENDOR = '$vendor' ";
			$result = mysql_query($check);
			confirmQuery($result,204);
			$numRows = mysql_num_rows($result);
			if($numRows == 0){
				//Insert to to main table
				//If KPPI added then , need to change the "inserQuery" accordingly
				//Also change the "dataProcessor.php" file accordingly
				$insertQuery = "INSERT INTO dashboard
								(DATES, VENDOR, TRAFFIC_RBH, TRAFFIC_NBH, CDR, CSSR, CSBR_RBH, CSBR_NBH,SDBR_NBH)
								VALUES ('$dates','$vendor','$traffic_rbh','$traffic_nbh','$cdr','$cssr','$csbr_rbh','$csbr_nbh','$sdbr_nbh')
				";
				$insertResult = mysql_query($insertQuery);
				confirmQuery($insertResult,205);
				if($insertResult){
					updateStatus($dates,'daily');
					return TRUE;
				}
				else
					return FALSE;
			}
			else{
				//die("Dash board already has data for " . $vendor);
				return FALSE;
			}
	}
?>
<?php
	//********************************************************************************
	//This function will create dash board from the views
	function createDashBoardKPI($vendor){
		if($vendor == "nsn"){
			$viewName = $vendor."view";
		}
		else{
			$viewName = $vendor."view_kpi";
		}
		$query = "SELECT * FROM {$viewName}";
		$resultSet = mysql_query($query);
		confirmQuery($resultSet,301);
		$rows = mysql_fetch_assoc($resultSet);
			//Get the values
			$dates = $rows['DATES'];
			$vendor = $rows['VENDOR'];
			/*$traffic_rbh = $rows['TRAFFIC_RBH'];
			$traffic_nbh = $rows['TRAFFIC_NBH'];*/
			//echo $dates . ">" . $cssr .">". $csbr_nbh;			
			//Check for duplicate entry
			$check = "SELECT * FROM dashboard_kpi WHERE DATES = '$dates' AND VENDOR = '$vendor' ";
			$result = mysql_query($check);
			confirmQuery($result,302);
			$numRows = mysql_num_rows($result);
			if($numRows == 0){
				//Insert to to main table
				$insertQuery = "INSERT IGNORE INTO dashboard_kpi SELECT * FROM {$viewName}";
				$insertResult = mysql_query($insertQuery);
				confirmQuery($insertResult,303);
				if($insertResult){
					updateStatus($dates,'kpi');
					return TRUE;
				}
				else
					return FALSE;
			}
			else{
				//die("Dash board already has data for " . $vendor);
				return FALSE;
			}
	}
?>
<?php
	//*****************************************************************************************
	//This function will create daily report table
	function createDailyReport($date){
		//Add vendor parameters to daily report
		$vendorKPI = "SELECT * FROM dashboard WHERE DATES = '$date' AND VENDOR != 'Flexi' AND VENDOR != 'Siemens'";
		$extractResult = mysql_query($vendorKPI);
		confirmQuery($extractResult, 2001);
		while($vendorRows = mysql_fetch_assoc($extractResult)){
			$vVendor = $vendorRows['VENDOR'];
			$vtraffic_RBH = $vendorRows['TRAFFIC_RBH'];
			$vtraffic_NBH = $vendorRows['TRAFFIC_NBH'];
			$vcdr = $vendorRows['CDR'];
			$vcssr = $vendorRows['CSSR'];
			$vcsbr_RBH = $vendorRows['CSBR_RBH'];
			$vcsbr_NBH = $vendorRows['CSBR_NBH'];
			$vcsdbr_NBH = $vendorRows['SDBR_NBH'];
			
			$vInsertQuery = "INSERT INTO daily_report (DATES,VENDOR,TRAFFIC_RBH,TRAFFIC_NBH,CDR,CSSR,CSBR_RBH,CSBR_NBH,SDBR_NBH)
							VALUES
							('$date','$vVendor','$vtraffic_RBH','$vtraffic_NBH','$vcdr','$vcssr','$vcsbr_RBH','$vcsbr_NBH','$vcsdbr_NBH')
						   ";
			$vInsertResult = mysql_query($vInsertQuery);
			confirmQuery($vInsertResult, 2002);
		}
		if($vInsertResult){
			$vFlag = 1;
		}
		else{
			$vFlag = 0;
		}
		//Now add network parameters to daily report
		$networkKPI = "SELECT * FROM dashboard_all WHERE DATES = '$date' ";
		$networkResult = mysql_query($networkKPI);
		confirmQuery($networkResult, 2003);
		while($networkRows = mysql_fetch_assoc($networkResult)){
			$nVendor = $networkRows['VENDOR'];
			$ntraffic_RBH = $networkRows['TRAFFIC_RBH'];
			$ntraffic_NBH = $networkRows['TRAFFIC_NBH'];
			$ncdr = $networkRows['CDR'];
			$ncssr = $networkRows['CSSR'];
			$ncsbr_RBH = $networkRows['CSBR_RBH'];
			$ncsbr_NBH = $networkRows['CSBR_NBH'];
			$nsdbr_NBH = $networkRows['SDBR_NBH'];
			
			$nInsertQuery = "INSERT INTO daily_report (DATES,VENDOR,TRAFFIC_RBH,TRAFFIC_NBH,CDR,CSSR,CSBR_RBH,CSBR_NBH,SDBR_NBH)
							VALUES
							('$date','$nVendor','$ntraffic_RBH','$ntraffic_NBH','$ncdr','$ncssr','$ncsbr_RBH','$ncsbr_NBH','$nsdbr_NBH')
						   ";
			$nInsertResult = mysql_query($nInsertQuery);
			confirmQuery($nInsertResult, 2002);
		}
		if($nInsertResult){
			$nFlag = 1;
		}
		else{
			$nFlag = 0;
		}
		//Now update status table
		if($vFlag == 1 && $nFlag == 1){
			//Both Data updated correctly
			$value = 1;
			updateDailyReportStatus($date,$value);
			return TRUE;
		}
		elseif($vFlag == 1 && $nFlag == 0){
			//Only Venodrs data updated
			$value = 2;
			updateDailyReportStatus($date,$value);
			return FALSE;
		}
		elseif($vFlag == 0 && $nFlag == 1){
			//Only Network data updated
			$value = 3;
			updateDailyReportStatus($date,$value);
			return FALSE;
		}
		elseif($vFlag == 0 && $nFlag == 0)
		{
			//No data updated
			//Do nothing
		}
	}
?>
<?php
	//*****************************************************************************************
	//This function will move data from one table to another table
	function moveData($vendor){
		if($vendor != "nsn"){
			$firstTable = $vendor . "_primary";
			$secondTable = $vendor . "_final";
			$sqlQuery = "INSERT IGNORE INTO {$secondTable} SELECT * FROM {$firstTable}";
			$resultSet = mysql_query($sqlQuery);
			confirmQuery($resultSet,105);
			if($resultSet)
			{
				return TRUE;
			}
			else{
				return FALSE;
			}			
		}
		else{
			// ->>> Do nothing.
			// ->>> Return TRUE to match the move count
			return TRUE;
		}
	}
?>
<?php
	//****************************************************************************************	
	//Actual truncate function
	function truncateTable($vendor){
		if($vendor == "nsn"){
			return TRUE;
		}
		else{
			$tableName = $vendor."_primary";
			$truncateQuery = "TRUNCATE TABLE {$tableName}";
			$truncateResult = mysql_query($truncateQuery);
			confirmQuery($truncateResult,213);
			if($truncateResult){
				return TRUE;
			}
		}
	}
?>
<?php
	//*****************************************************************************************
	//This function will check for empty tables
	function checkPrimaryTable($vendor){
		$tableName = $vendor . "_primary";
		$query = "SELECT * FROM {$tableName}";
		$result = mysql_query($query);
		confirmQuery($result,300);
		$noRows = mysql_num_rows($result);
		return $noRows;
	}
?>
<?php
	//******************************************************************************************
	//This function will create the status report
	function statusReport(){
		$chkER = checkPrimaryTable("huawei");
		if($chkER > 0){
			$date = getDateFromDb("huawei");
			$checkQuery = "SELECT DATES FROM status WHERE DATES = '{$date}'";
			$checkResult = mysql_query($checkQuery);
			confirmQuery($checkResult,401);
			$noRows = mysql_num_rows($checkResult);
			if($noRows == 0){
				// ->>> Current date doesn't exixts in DB so insert one row
				$query = "INSERT INTO status 
									 (serial, dates, dashboard_daily, dashboard_all, dashboard_kpi,common_kpi,daily_report)
									 VALUES
									 ('','$date',0,0,0,5,0) 
						 ";
				$result = mysql_query($query);
				confirmQuery($result,401);
			}
			else{
				// ->>> Do nothing
			}			
		}
		else{
			// ->>> Ericsson primary table is empty
			header("location:adminHome.php?id=3&flag=nu&vendor=Ericsson");
			exit(0);
		}
	}
?>
<?php
	//********************************************************************************************
	//This function will update the staus
	function updateStatus($date,$suffix){
		$colName = "dashboard_".$suffix;
		$query = "UPDATE status SET {$colName} = '1' WHERE dates = '{$date}' ";
		$result = mysql_query($query);
		confirmQuery($result,402);
	}
?>
<?php
	//********************************************************************************************
	//This function will update the staus of daily report column in staus table
	function updateDailyReportStatus($date,$value){
		$query = "UPDATE status SET daily_report = '$value' WHERE dates = '{$date}' ";
		$result = mysql_query($query);
		confirmQuery($result,402);
	}
?>
<?php
	//********************************************************************************************
	//This function will update common kpi table's staus
	function updateCommonStatus($date,$value){
		$cQuery = "SELECT common_kpi FROM status WHERE DATES = '{$date}'";
		$cResult = mysql_query($cQuery);
		confirmQuery($cResult, 1006);
		$cRows = mysql_fetch_assoc($cResult);
		$dbValue = $cRows['common_kpi'];
		$newValue = $dbValue - $value;
		$colName = "common_kpi";
		$query = "UPDATE status SET {$colName} = '". $newValue. "' WHERE dates = '{$date}' ";
		$result = mysql_query($query);
		confirmQuery($result,1007);
	}
?>
<?php
	//********************************************************************************************
	//->>> This function will prevent double processing of any day's data
	function checkDuplicate($vendor,$inputDate){
		set_time_limit(0);
		$checkQuery = "SELECT DATES FROM common_kpi WHERE VENDOR = '{$vendor}' and DATES = '{$inputDate}'";
		$checkResult = mysql_query($checkQuery);
		confirmQuery($checkResult, 1001);
		$numOfROws = mysql_num_rows($checkResult);
		if($numOfROws == 0){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
?>
<?php
	//********************************************************************************************
	//->>>This function will query tracking database and gather info about cells
	function insertCommon($resultSet,$vendor){
		//->>> Get the result set and one by one extract data
		while($mainRows = mysql_fetch_assoc($resultSet)){
			set_time_limit(0);
			$cellName = $mainRows['CELL_NAME'];
			$siteName = substr($cellName,0,9);
			//->>>Now extract information according to CELL_NAME
			$queryTwo = "SELECT LAC, BSC_NAME, MSC_NAME, VENDOR, THANA, DISTRICT, DIVISION FROM tracking WHERE CELL_NAME='{$cellName}'";
			$resultTwo = mysql_query($queryTwo);
			confirmQuery($resultTwo, 902);
			set_time_limit(0);
			$trackingRows = mysql_fetch_assoc($resultTwo);
			//*******************************************************
			//->>> Create KPIs for common KPI table
			$dates = $mainRows['C_DATE'];
			$mscName = mysqlPrep($trackingRows['MSC_NAME']);
			if($vendor == "siemens"){
				$bscName = mysqlPrep($trackingRows['BSC_NAME']);
			}
			else{
				$bscName = mysqlPrep($mainRows['BSC_NAME']);
			}
			$division = mysqlPrep($trackingRows['DIVISION']);
			$district = mysqlPrep($trackingRows['DISTRICT']);
			$thana = mysqlPrep($trackingRows['THANA']);
			if($vendor == "ericsson"){
				$lac = $trackingRows['LAC'];
			}
			else{
				$lac = $mainRows['LAC'];
			}
			//********************************************************
			//CELL NAME,SITE NAME, VENDOR DEFINED EARLIER
			//********************************************************
			//->>> If KPI has same name for all vendors then add the KPI below
			$att_out_ho = $mainRows['ATT_OUT_HO'];
			$att_out_ho_interbsc = $mainRows['ATT_OUT_HO_INTERBSC'];
			$att_out_ho_intrabsc = $mainRows['ATT_OUT_HO_INTRABSC'];
			$avail_sdcch_mean = $mainRows['AVAIL_SDCCH_MEAN'];
			$avail_tch_mean = $mainRows['AVAIL_TCH_MEAN'];
			$avail_tch_mean_ol = $mainRows['AVAIL_TCH_MEAN_OL'];
			$avail_tch_mean_ul = $mainRows['AVAIL_TCH_MEAN_UL'];
			$better_cell_ho = $mainRows['BETTER_CELL_HO'];
			$bh_traffic = $mainRows['BH_TRAFFIC'];
			$bh_traffic_fr = $mainRows['BH_TRAFFIC_FR'];
			$bh_traffic_hr = $mainRows['BH_TRAFFIC_HR'];
			$bh_traffic_ol = $mainRows['BH_TRAFFIC_OL'];
			$bh_traffic_ul = $mainRows['BH_TRAFFIC_UL'];
			$bss_drop = $mainRows['BSS_DROP'];
			$call_drop_rate = $mainRows['CALL_DROP_RATE'];
			$cdr_d = $mainRows['CDR_D'];
			$cdr_n = $mainRows['CDR_N'];
			$cdr_n_ol = $mainRows['CDR_N_OL'];
			$cdr_n_ul = $mainRows['CDR_N_UL'];
			$cssr = $mainRows['CSSR'];
			$dl_lev_ho = $mainRows['DL_LEV_HO'];
			$dl_lev_ho_vol = $mainRows['DL_LEV_HO_VOL'];
			$dl_quality_ho = $mainRows['DL_QUALITY_HO'];
			$dr_ho = $mainRows['DR_HO'];
			$dr_ho_vol = $mainRows['DR_HO_VOL'];
			$ho_drop = $mainRows['HO_DROP'];
			$ho_success_rate = $mainRows['HO_SUCCESS_RATE'];
			$hosr_d = $mainRows['ATT_OUT_HO_INTERBSC'] + $mainRows['ATT_OUT_HO_INTRABSC'];
			$hosr_n = $mainRows['SUCC_OUT_HO_INTERBSC'] + $mainRows['SUCC_OUT_HO_INTRABSC'];
			$radio_drop = $mainRows['RADIO_DROP'];
			$sdcch_bh_traffic = $mainRows['SDCCH_BH_TRAFFIC'];
			$sdcch_blocking_d = $mainRows['SDCCH_BLOCKING_D'];
			$sdcch_blocking_n = $mainRows['SDCCH_BLOCKING_N'];
			$sdcch_blocking_rate = $mainRows['SDCCH_BLOCKING_RATE'];
			$sdcch_drop_d = $mainRows['SDCCH_DROP_D'];
			$sdcch_drop_n = $mainRows['SDCCH_DROP_N'];
			$sdcch_drop_rate = $mainRows['SDCCH_DROP_RATE'];
			$succ_out_ho_interbsc = $mainRows['SUCC_OUT_HO_INTERBSC'];
			$succ_out_ho_intrabsc = $mainRows['SUCC_OUT_HO_INTRABSC'];
			$tch_ass_fail = $mainRows['TCH_ASS_FAIL'];
			$tch_ass_fail_bss = $mainRows['TCH_ASS_FAIL_BSS'];
			$tch_ass_fail_cong = $mainRows['TCH_ASS_FAIL_CONG'];
			$tch_ass_fail_cong_bh = $mainRows['TCH_ASS_FAIL_CONG_BH'];
			$tch_ass_fail_dr = $mainRows['TCH_ASS_FAIL_DR'];
			$tch_ass_fail_n = $mainRows['TCH_ASS_FAIL_N'];
			$tch_ass_fail_radio = $mainRows['TCH_ASS_FAIL_RADIO'];
			$tch_attempts = $mainRows['TCH_ATTEMPTS'];
			$tch_attempts_bh = $mainRows['TCH_ATTEMPTS_BH'];
			$tch_blocking_d = $mainRows['TCH_BLOCKING_D'];
			$tch_blocking_n = $mainRows['TCH_BLOCKING_N'];
			$tch_blocking_rate = $mainRows['TCH_BLOCKING_RATE'];
			$tch_succ = $mainRows['TCH_SUCC'];
			$total_traffic = $mainRows['TOTAL_TRAFFIC'];
			$ts_ib3_max = $mainRows['TS_IB3_MAX'];
			$ts_ib4_max = $mainRows['TS_IB4_MAX'];
			$ts_ib5_max = $mainRows['TS_IB5_MAX'];
			$ul_lev_ho = $mainRows['UL_LEV_HO'];
			$ul_lev_ho_vol = $mainRows['UL_LEV_HO_VOL'];
			$ul_quality_ho = $mainRows['UL_QUALITY_HO'];
			//************************************************************
			//->>> If KPI has differenet name in different vendors then
			//->>> add at here
			if($vendor == "huawei"){
				$att_out_ho = $mainRows['ATT_OUT_HO_VOL'];
				$avail_tch_mean_ol = $mainRows['AVAIL_TCH_MAX_OL'];
				$avail_tch_mean_ul = $mainRows['AVAIL_TCH_MAX_UL'];
				$bh_traffic_ol = $mainRows['BH_OL_TRAFFIC'];
				$bh_traffic_ul = $mainRows['BH_UL_TRAFFIC'];
				$call_drop_rate = $mainRows['CDR'];
				$cdr_n_ol = $mainRows['OVERLAID_DROP'];
				$cdr_n_ul = $mainRows['UNDERLAID_DROP'];
				$csbr = $mainRows['CALL_SETUP_TCH_BLOCKING'];
				$dl_quality_ho = $mainRows['DL_QUAL_HO'];
				$dl_quality_ho_vol = $mainRows['DL_QUAL_HO_VOL'];
				$ho_success_rate = $mainRows['HANDOVER_SUCCESS_RATE'];
				$sdcch_blocking_d = $mainRows['BH_SDCCH_SUC_D'];
				$sdcch_drop_rate = $mainRows['SDCCH_DROP'];
				$tch_ass_fail = $mainRows['TCH_ASS_FAIL_RATE'];
				$tch_ass_fail_cong_bh = $mainRows['BH_TCH_ASS_FAIL_CONG'];
				$tch_attempts_bh = $mainRows['BH_TCH_ATTEMPTS'];
				$ul_quality_ho = $mainRows['UL_QUAL_HO'];
				$ul_quality_ho_vol = $mainRows['UL_QUAL_HO_VOL'];
			}
			elseif($vendor == "flexi"){
				$better_cell_ho_vol = $mainRows['BETTER_CELL_VOL'];
				$csbr = $mainRows['CALL_SETUP_TCH_BLOCKING_RATE'];
				$dl_quality_ho_vol = $mainRows['DL_QUAL_HO_VOL'];
				$ul_quality_ho_vol = $mainRows['UL_QUAL_HO_VOL'];
				$hosr_d = $mainRows['HOSR_D'];
				$hosr_n = $mainRows['HOSR_N'];
			}
			elseif($vendor == "siemens"){
				$avail_tch_mean_ol = "";
				$avail_tch_mean_ul = "";
				$better_cell_ho_vol = $mainRows['BETTER_CELL_VOL'];
				$bh_traffic_ol = "";
				$bh_traffic_ul = "";
				$cdr_n_ol = "";
				$cdr_n_ul = "";
				$csbr = $mainRows['CALL_SETUP_TCH_BLOCKING_RATE'];
				$cssr = $mainRows['CALL_SETUP_SUCCESS_RATE'];
				$dl_quality_ho_vol = $mainRows['DL_QUAL_HO_VOL'];
				$ul_quality_ho_vol = $mainRows['UL_QUAL_HO_VOL'];
			}
			elseif($vendor == "ericsson"){
				$att_out_ho = $mainRows['TOTAL_HO_ATT'];
				$avail_sdcch_mean = $mainRows['AVAIL_SD_MEAN'];
				$better_cell_ho = "";
				$better_cell_ho_vol = "";
				$bh_traffic_ol = "";
				$bh_traffic_ul = "";
				$bss_drop = $mainRows['CDR_N']-$mainRows['RADIO_DROP'];
				$csbr = $mainRows['CALL_SETUP_BLOCKING_RATE'];
				$dl_lev_ho_vol = "";
				$dl_quality_ho = "";
				$dl_quality_ho_vol = $mainRows['DL_QUALITY_VOL'];
				$dr_ho = "";
				$dr_ho_vol = "";
				$sdcch_bh_traffic = $mainRows['SDCCH_TRAFFIC'];
				$sdcch_blocking_d = $mainRows['SD_BLOCKING_D'];
				$sdcch_blocking_n = $mainRows['SD_BLOCKING_N'];
				$sdcch_blocking_rate = $mainRows['SD_BLOCKING_RATE'];
				$sdcch_drop_d = $mainRows['SD_DROP_D'];
				$sdcch_drop_n = $mainRows['SD_DROP_N'];
				$sdcch_drop_rate = $mainRows['SD_DROP_RATE'];
				if($mainRows['TCH_ATTEMPT'] == 0){
					$mainRows['TCH_ATTEMPT'] = 0;
				}
				else{
					$tch_ass_fail = ($mainRows['TCH_ATTEMPT'] - $mainRows['TCH_SUCC'])/ $mainRows['TCH_ATTEMPT'];
				}
				$tch_ass_fail_bss = "";
				$tch_ass_fail_cong_bh = $mainRows['BH_TCH_CONG'];
				$tch_ass_fail_dr = "";
				$tch_ass_fail_n = $mainRows['TCH_ATTEMPT'] - $mainRows['TCH_SUCC'];
				$tch_ass_fail_radio = "";
				$tch_attempts = $mainRows['TCH_ATTEMPT'];
				$tch_attempts_bh = $mainRows['BH_TCH_ATTEMPT'];
				$tch_blocking_d = "";
				$tch_blocking_n = "";
				$tch_blocking_rate = "";
				$ts_ib3_max = $mainRows['IB_3'];
				$ts_ib4_max = $mainRows['IB_4'];
				$ts_ib5_max = $mainRows['IB_5'];
				$ul_lev_ho = "";
				$ul_lev_ho_vol = "";
				$ul_quality_ho = "";
				$ul_quality_ho_vol = "";
			}
			$insertQuery = "
				INSERT INTO common_kpi
				(
					DATES, MSC_NAME, BSC_NAME, DIVISION, DISTRICT, THANA, SITE_NAME, CELL_NAME, VENDOR, LAC,
					AVAIL_TCH_MEAN, AVAIL_TCH_MEAN_OL, AVAIL_TCH_MEAN_UL,BH_TRAFFIC,BH_TRAFFIC_FR,BH_TRAFFIC_HR,
					BH_TRAFFIC_OL,BH_TRAFFIC_UL,RADIO_DROP,HO_DROP,BSS_DROP,CDR_N,CALL_DROP_RATE,CDR_D,
					CDR_N_OL, CDR_N_UL, CSBR, CSSR, UL_LEV_HO, DL_LEV_HO, UL_QUALITY_HO, DL_QUALITY_HO,
					BETTER_CELL_HO, DR_HO, SUCC_OUT_HO_INTERBSC, SUCC_OUT_HO_INTRABSC,ATT_OUT_HO_INTERBSC,
					ATT_OUT_HO_INTRABSC, HOSR_N, HOSR_D, HO_SUCCESS_RATE, AVAIL_SDCCH_MEAN, SDCCH_BH_TRAFFIC,
					SDCCH_BLOCKING_N, SDCCH_BLOCKING_D, SDCCH_BLOCKING_RATE,SDCCH_DROP_N,SDCCH_DROP_D,SDCCH_DROP_RATE,
					TCH_ASS_FAIL,TCH_ASS_FAIL_BSS,TCH_ASS_FAIL_CONG,TCH_ASS_FAIL_CONG_BH,TCH_ASS_FAIL_DR,TCH_ASS_FAIL_N,
					TCH_ASS_FAIL_RADIO,TCH_ATTEMPTS,TCH_ATTEMPTS_BH,TCH_BLOCKING_D,TCH_BLOCKING_N,TCH_BLOCKING_RATE,
					TCH_SUCC,TOTAL_TRAFFIC,TS_IB3_MAX,TS_IB4_MAX,TS_IB5_MAX,UL_LEV_HO_Vol,DL_LEV_HO_Vol,
					UL_QUALITY_HO_Vol,DL_QUALITY_HO_Vol,BETTER_CELL_HO_Vol,DR_HO_Vol,ATT_OUT_HO
				)
				VALUES
				(
					'$dates','$mscName','$bscName','$division','$district','$thana','$siteName','$cellName','$vendor','$lac',
					'$avail_tch_mean','$avail_tch_mean_ol','$avail_tch_mean_ul','$bh_traffic','$bh_traffic_fr','$bh_traffic_hr',
					'$bh_traffic_ol','$bh_traffic_ul','$radio_drop','$ho_drop','$bss_drop','$cdr_n','$call_drop_rate','$cdr_d',
					'$cdr_n_ol','$cdr_n_ul','$csbr','$cssr','$ul_lev_ho','$dl_lev_ho','$ul_quality_ho','$dl_quality_ho',
					'$better_cell_ho','$dr_ho','$succ_out_ho_interbsc','$succ_out_ho_intrabsc','$att_out_ho_interbsc',
					'$att_out_ho_intrabsc','$hosr_n','$hosr_d','$ho_success_rate','$avail_sdcch_mean','$sdcch_bh_traffic',
					'$sdcch_blocking_n','$sdcch_blocking_d','$sdcch_blocking_rate','$sdcch_drop_n','$sdcch_drop_d','$sdcch_drop_rate',
					'$tch_ass_fail','$tch_ass_fail_bss','$tch_ass_fail_cong','$tch_ass_fail_cong_bh','$tch_ass_fail_dr','$tch_ass_fail_n',
					'$tch_ass_fail_radio','$tch_attempts','$tch_attempts_bh','$tch_blocking_d','$tch_blocking_n','$tch_blocking_rate',
					'$tch_succ','$total_traffic','$ts_ib3_max','$ts_ib4_max','$ts_ib5_max','$ul_lev_ho_vol','$dl_lev_ho_vol',
					'$ul_quality_ho_vol','$dl_quality_ho_vol','$better_cell_ho_vol','$dr_ho_vol','$att_out_ho'
				)
			";
			set_time_limit(0);
			$insertResult = mysql_query($insertQuery);
			confirmQuery($insertResult, 1000);
		}
		if($insertResult){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
?>
<?php
	//flush the output buffer
	ob_flush();
?>