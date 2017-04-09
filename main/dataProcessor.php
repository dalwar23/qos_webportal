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
	//*****************************************************************************************************
	//*****************************************************************************************************
	// -> The following function details:
	// ->> WHAT : SUBMIT function for processing data for Dashboard and Dashboard KPI table
	// ->>> Process Primary Data and Move them to permanent table
	// ->>>> Truncate the primary tables
	//Get the posted values
	if(isset($_POST['dailyDataProcess'])){
		//Create a status report entry
		statusReport();
		//Check the NSN dashboard capability
		$nsnArray = array('siemens','flexi');
		$seRows = checkPrimaryTable($nsnArray[0]);
		$flRows = checkPrimaryTable($nsnArray[1]);
		//Now check that either of the table is empty or not
		if($seRows == 0 && $flRows == 0){
			$vendorArray = array('ericsson','huawei');	
		}
		elseif($seRows != 0 && $flRows == 0){
			$vendorArray = array('siemens','ericsson','huawei');	
		}
		elseif($seRows == 0 && $flRows != 0){
			$vendorArray = array('ericsson','flexi','huawei');	
		}
		//Please modify the array according to vendor tables in DB
		else{
			$vendorArray = array('siemens','ericsson','flexi','huawei','nsn');
		}
		$succCount = 0;
		$vendorCount = count($vendorArray);
		//Generate the dashboard for the given primary tables
		for($i = 0; $i < $vendorCount; $i++){
			$succ = generateDashBoard($vendorArray[$i]);
			$succCount++;
		}
		if($succCount > 0){	//Move Data if the step count is grater then 0, that means dashboard created
			//Now move the data from Primary table to Final table
			$hourlyVendor = array("siemens_nbh");
			$mergedArray = array_merge($vendorArray,$hourlyVendor);
			$totalArrayLength = count($mergedArray);
			$moveCount = 0;
			for($j = 0; $j < $totalArrayLength; $j++){
				$moveData = moveData($mergedArray[$j]);
				if($moveData){
					$moveCount++;
				}
				else{
					// ->>> No Increment
				}
			}
			//if all the data has been moved then truncate the tables
			if($moveCount > 0){ // To remove the empty tables like NSN
				$truncateCount = 0;
				for($k = 0; $k < $totalArrayLength; $k++){
					$truncateTable = truncateTable($mergedArray[$k]);
					if($truncateTable){
						$truncateCount++;
					}
					else{
						// ->>> No Increment
					}
				}
				if($truncateCount > 0){	//Remains problamatic when "NSN" comes into count.
					// ->>> Move to Step 2
					header("Location:adminHome.php?id=3&vendor=Process&flag=lambda");
					exit(0);
				}
				else{
					// ->>> Data processing is not successful fully but Partially
					// ->>> There may be something wrong with truncate functions
				}
			}
		}
		else{
			// ->>> Step 1
			// ->>> No Dash board Generated
			header("Location:adminHome.php?id=3");
			exit(0);
		}

	}

	//****************************************************************************************************************
	//****************************************************************************************************************
	// -> Following Section Deals with Missing NSN data
	// ->> If Flexi data was not loaded with all other data
	// ->>> Then NSN Dashboard won't be created, this panel
	// ->>>> Will create NSN dashboard based on Siemens and Flexi data
	elseif(isset($_POST['nsnDsh'])){
		$nsnDate = $_POST['date'];
		//echo $nsnDate;
		//Check the KPI dashboard "dashboard_kpi" table for the POSTED date to avoud die function
		$checkQueryKPI = "SELECT * FROM dashboard_kpi WHERE DATES='{$nsnDate}'";
		$checkKPIResult = mysql_query($checkQueryKPI);
		confirmQuery($checkKPIResult,506);
		$checkKPIRows = mysql_num_rows($checkKPIResult);
		if($checkKPIRows == 0){
			header("Location:adminHome.php?id=4&vendor=Error&flag=xi");
			exit(0);
		}
		else{
			//Write the ultimate NSN query to get the KPI values
			$nsnQuery = " SELECT DATES, TRAFFIC_RBH, TRAFFIC_NBH,
			if(CDR_D=0,0,(CDR_N/CDR_D)*100) AS CDR,
			((1-if(SDCCH_DROP_D=0,0,(SDCCH_DROP_N/SDCCH_DROP_D))) * (if(TCH_ATTEMPTS=0,0,(TCH_SUCC/TCH_ATTEMPTS)*100))) AS CSSR,
			if(BH_TCH_ATTEMPTS=0,0,(BH_TCH_CONG/BH_TCH_ATTEMPTS)*100) AS CSBR_RBH,
			if(NBH_TCH_ATTEMPTS=0,0,(NBH_TCH_CONG/NBH_TCH_ATTEMPTS)*100) AS CSBR_NBH,
			if(SDCCH_BLOCKING_D=0,0,(SDCCH_BLOCKING_N/SDCCH_BLOCKING_D)*100) AS SDBR_NBH
			FROM
			(
				SELECT DATES,
				SUM(TRAFFIC_RBH) 			AS TRAFFIC_RBH,
				SUM(TRAFFIC_NBH) 			AS TRAFFIC_NBH,
				SUM(CDR_D)					AS CDR_D,
				SUM(CDR_N)					AS CDR_N,
				SUM(SDCCH_DROP_N)			AS SDCCH_DROP_N,
				SUM(SDCCH_DROP_D)			AS SDCCH_DROP_D,
				SUM(TCH_SUCC)				AS TCH_SUCC,
				SUM(TCH_ATTEMPTS)			AS TCH_ATTEMPTS,
				SUM(BH_TCH_CONG)			AS BH_TCH_CONG,
				SUM(BH_TCH_ATTEMPTS)		AS BH_TCH_ATTEMPTS,
				SUM(NBH_TCH_CONG)			AS NBH_TCH_CONG,
				SUM(NBH_TCH_ATTEMPTS) 		AS NBH_TCH_ATTEMPTS,
				SUM(NBH_SDCCH_BLOCKING_N)  	AS SDCCH_BLOCKING_N,
				SUM(NBH_SDCCH_BLOCKING_D)  	AS SDCCH_BLOCKING_D

				FROM dashboard_kpi AS NSN_KPI
				WHERE DATES = '{$nsnDate}' 
				AND (VENDOR = 'Flexi' OR VENDOR='Siemens')
			) AS NSN ";
			
			//Check for duplicate entry on dashboard
			$nsnCheckQuery = "SELECT * FROM dashboard WHERE VENDOR='NSN' AND DATES = '{$nsnDate}' ";
			$nsnCheckResult = mysql_query($nsnCheckQuery);
			confirmQuery($nsnCheckResult,505);
			$nsnCheckRows = mysql_num_rows($nsnCheckResult);

			//now insert the values into Dashboard
			if($nsnCheckRows == 0){
				//Now execute this query
				$nsnResult  = mysql_query($nsnQuery);
				confirmQuery($nsnResult,504);
				$nsnRows = mysql_fetch_assoc($nsnResult);	
				//Get the DB values			
				$_dates = $nsnRows['DATES'];
				$_vendor = "NSN";
				$_traffic_rbh = $nsnRows['TRAFFIC_RBH'];
				$_traffic_nbh = $nsnRows['TRAFFIC_NBH'];
				$_cdr = $nsnRows['CDR'];
				$_cssr = $nsnRows['CSSR'];
				$_csbr_rbh = $nsnRows['CSBR_RBH'];
				$_csbr_nbh = $nsnRows['CSBR_NBH'];
				$_sdbr_nbh = $nsnRows['SDBR_NBH'];
				//echo $nsnRows['DATES'] . "/" . $nsnRows['CDR'];
				$nsnInsertQuery = "INSERT INTO dashboard 
								  (DATES,VENDOR,TRAFFIC_RBH,TRAFFIC_NBH,CDR,CSSR,CSBR_RBH,CSBR_NBH,SDBR_NBH)
								  VALUES
								  ('$_dates','$_vendor','$_traffic_rbh','$_traffic_nbh','$_cdr','$_cssr','$_csbr_rbh','$_csbr_nbh','$_sdbr_nbh')
								  ";
				$nsnInsertResult = mysql_query($nsnInsertQuery);
				confirmQuery($nsnInsertResult,507);
				if($nsnInsertResult){
					// ->>> Insert Data to dashboard was successful.
					header("Location:adminHome.php?id=4&vendor=Process&flag=lambda");
					exit(0);
				}
				else{
					// ->>> Can' tinsert NSN data to dashboard
					header("Location:adminHome.php?id=4&vendor=Error&flag=xi");
					exit(0);
				}
			}
			else{
				// ->>> Already NSN data is in DB Dashboard, so Ignore
				header("Location:adminHome.php?id=4&vendor=Database&flag=omicron");
				exit(0);
			}
		}
	}
	
	//**************************************************************************************************************
	//**************************************************************************************************************
	// -> Following function details
	// ->> This function Creates allvendorDashboard
	// ->>> Calculates Network data from the dashboard KPI table
	// ->>>> Currently considering 04 Vendors [Ericsson,Siemens,Huawei,Flexi]
	elseif(isset($_POST['network'])){
		$networkDate = $_POST['date'];
		//echo $networkDate;
		//Check the KPI dashboard "dashboard_kpi" table for the POSTED date to avoid die function
		$checkQueryKPI = "SELECT * FROM dashboard_kpi WHERE DATES='{$networkDate}'";
		$checkKPIResult = mysql_query($checkQueryKPI);
		confirmQuery($checkKPIResult,506);
		$checkKPIRows = mysql_num_rows($checkKPIResult);
		if($checkKPIRows == 0){
			header("Location:adminHome.php?id=5&vendor=Error&flag=pi");
			exit(0);
		}
		elseif($checkKPIRows > 0 && $checkKPIRows < 4){
			header("Location:adminHome.php?id=5&vendor=Error&flag=rho");
			exit(0);
		}
		else{
			// Dashboard KPI has data for specific date, Now Calculations start
			//Write the ultimate nested query for the calculations
			$networkQuery = " SELECT
			DATES,
			TRAFFIC_RBH,
			TRAFFIC_NBH,
			if(CDR_D=0,0,(CDR_N/CDR_D)*100) AS CDR,
			((1-if(SDCCH_DROP_D=0,0,(SDCCH_DROP_N/SDCCH_DROP_D))) * (if(TCH_ATTEMPTS=0,0,(TCH_SUCC/TCH_ATTEMPTS)*100))) AS CSSR,
			if(BH_TCH_ATTEMPTS=0,0,(BH_TCH_CONG/BH_TCH_ATTEMPTS)*100) AS CSBR_RBH,
			if(NBH_TCH_ATTEMPTS=0,0,(NBH_TCH_CONG/NBH_TCH_ATTEMPTS)*100) AS CSBR_NBH,
			if(SDCCH_BLOCKING_D=0,0,(SDCCH_BLOCKING_N/SDCCH_BLOCKING_D)*100) AS SDBR_NBH
			FROM
			(
				SELECT
				DATES,
				SUM(TRAFFIC_RBH) 			AS TRAFFIC_RBH,
				SUM(TRAFFIC_NBH) 			AS TRAFFIC_NBH,
				SUM(CDR_N)					AS CDR_N,
				SUM(CDR_D)					AS CDR_D,
				SUM(SDCCH_DROP_N)			AS SDCCH_DROP_N,
				SUM(SDCCH_DROP_D)			AS SDCCH_DROP_D,
				SUM(TCH_SUCC)				AS TCH_SUCC,
				SUM(TCH_ATTEMPTS)			AS TCH_ATTEMPTS,
				SUM(BH_TCH_CONG)			AS BH_TCH_CONG,
				SUM(BH_TCH_ATTEMPTS)		AS BH_TCH_ATTEMPTS,
				SUM(NBH_TCH_CONG)			AS NBH_TCH_CONG,
				SUM(NBH_TCH_ATTEMPTS) 		AS NBH_TCH_ATTEMPTS,
				SUM(NBH_SDCCH_BLOCKING_N)  	AS SDCCH_BLOCKING_N,
				SUM(NBH_SDCCH_BLOCKING_D)  	AS SDCCH_BLOCKING_D
				
				FROM dashboard_kpi	AS NETWORK_KPI
				WHERE DATES = '{$networkDate}'
				AND VENDOR != 'NSN'
			) AS NETWORK			
			";
			//Now check the DB to avoid duplicate entry
			$checkQuery = "SELECT * FROM dashboard_all WHERE DATES = '{$networkDate}'";
			$checkResult = mysql_query($checkQuery);
			confirmQuery($checkResult,601);
			$dbNoRows = mysql_num_rows($checkResult);
			if($dbNoRows == 0){
				// ->>> Insert data to DB
				//Now execute the Query
				$networkResult = mysql_query($networkQuery);
				confirmQuery($networkResult,602);
				$netRows = mysql_fetch_assoc($networkResult);
				$_dates = $netRows['DATES'];
				$_networkVendor = "Network";	//To maintain same number of columns in both table
				$_traffic_rbh = $netRows['TRAFFIC_RBH'];
				$_traffic_nbh = $netRows['TRAFFIC_NBH'];
				$_cdr = $netRows['CDR'];
				$_cssr = $netRows['CSSR'];
				$_csbr_rbh = $netRows['CSBR_RBH'];
				$_csbr_nbh = $netRows['CSBR_NBH'];
				$_sdbr_nbh = $netRows['SDBR_NBH'];

				//echo $_dates . "/" . $_cdr;
				$insertQuery = "INSERT INTO dashboard_all
							   (DATES,VENDOR,TRAFFIC_RBH,TRAFFIC_NBH,CDR,CSSR,CSBR_RBH,CSBR_NBH,SDBR_NBH)
							   VALUES
							   ('$_dates','$_networkVendor','$_traffic_rbh','$_traffic_nbh','$_cdr','$_cssr','$_csbr_rbh','$_csbr_nbh','$_sdbr_nbh')
							   ";
				$insertResult = mysql_query($insertQuery);
				confirmQuery($insertResult,603);
				if($insertResult){
					// ->>> Insert Data to dashboard was successful.
					updateStatus($_dates,'all');
					//Create Daily report ( custom format)table
					$dailyReportStatus = createDailyReport($_dates);
					//Now redirect to mother page
					if($dailyReportStatus){
						header("Location:adminHome.php?id=5&vendor=Process&flag=lambda");
						exit(0);
					}
					else{
						//->>> Can't create Daily repot
						header("Location:adminHome.php?id=5&vendor=Error&flag=upsilon");
						exit(0);
					}
				}
				else{
					// ->>> Can't insert NSN data to dashboard
					header("Location:adminHome.php?id=5&vendor=Error&flag=xi");
					exit(0);
				}
			}
			else{
				// ->>> Data base already contains specific day's data
				header("Location:adminHome.php?id=5&vendor=Database&flag=omicron");
				exit(0);
			}
		}
	}
?>
<?php
	//Flush Output buffer
	ob_flush();
?>