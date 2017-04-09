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
?>
<?php
	//Call the header function to create the header of the site
	$title = "Welcome | " . $_SESSION['USERNAME'] . " | User Profile";
	site_header($title);
	
	//This function Creates the menu of the site
	menu()
?>
<?php
	//Get the POSTed values
	if(isset($_POST['dashBoardSearch'])){
		//Assign view flag
		$view = "daily";
		//Assign values
		$vendor = mysqlPrep($_POST['vendor']);
		$startDate = mysqlPrep($_POST['from']);
		$endDate = mysqlPrep($_POST['to']);
		//Check for empty check list
		if(!empty($_POST['check_list'])){
			if(!empty($_POST['check_list'][0])){
				$allKpi = $_POST['check_list'][0];
				//Go for the query and show the results
				if($vendor && $_POST['from'] && $_POST['to'])
				{
					if($vendor == "none"){
						header("Location:userHome.php?id=2&flag=zeta");
						exit(0);						
					}
					elseif($vendor == "vall"){
						//echo $vendor . ">2" . $startDate . ">" . $endDate;
						$sqlQuery = "SELECT * FROM dashboard WHERE DATES BETWEEN '{$startDate}' AND '{$endDate}' ";
					}
					elseif($vendor == "vind"){
						//echo $vendor . ">2" . $startDate . ">" . $endDate;
						$sqlQuery = "SELECT * FROM dashboard_all WHERE DATES BETWEEN '{$startDate}' AND '{$endDate}' ";
					}
					elseif($vendor == "dailyReport"){
						$sqlQuery = "SELECT * FROM daily_report WHERE DATES BETWEEN '{$startDate}' AND '{$endDate}' ";
					}
					else{
						$sqlQuery = "SELECT * FROM dashboard WHERE VENDOR = '{$vendor}' AND DATES BETWEEN '{$startDate}' AND '{$endDate}'";
					}

				}
				else{
					header("Location:userHome.php?id=2&flag=zeta");
					exit(0);
				}
			}
			else{
				//Get all the selected KPIs name and add them to array
				for($counter = 1; $counter <= 8; $counter++){ //Number of KPI = 8
					if(!empty($_POST['check_list'][$counter])){
						$kpiArray[] = $_POST['check_list'][$counter];
						//echo $kpiArray[] . "<br>";
					}
				}
				//Get the selected KPI array length
				$length = count($kpiArray);
				
				for($i = 0; $i < $length; $i++){
					if($i == $length-1){
						$kpiString .= $kpiArray[$i]; 
					}
					else{
						$kpiString .= $kpiArray[$i] . ", ";
					}
				}
				if($vendor == "vind"){
					$kpiString = "DATES, " . $kpiString;
				}
				else{
					$kpiString = "DATES, VENDOR, " . $kpiString;
				}
				//echo $kpiString;
				//Go for the query here and show the results
				if($vendor && $_POST['from'] && $_POST['to']){
					if($vendor == "none"){
						header("Location:userHome.php?id=2&flag=zeta");
						exit(0);
					}
					elseif($vendor == "vall"){
						//All vendor but separately
						//echo $vendor;
						$sqlQuery = "SELECT {$kpiString} FROM dashboard WHERE DATES BETWEEN '{$startDate}' AND '{$endDate}' ";
					}
					elseif ($vendor == "vind"){
						//Vendor Indipendent
						//echo $vendor;
						$sqlQuery = "SELECT {$kpiString} FROM dashboard_all WHERE DATES BETWEEN '{$startDate}' AND '{$endDate}' ";
					}
					elseif($vendor = "dailyReport"){
						//Daily Report
						$sqlQuery = "SELECT {$kpiString} FROM daily_report WHERE DATES BETWEEN '{$startDate}' AND '{$endDate}' ";
					}
					else{
						//Individual vendor
						//echo $vendor;
						$sqlQuery = "SELECT {$kpiString} FROM dashboard WHERE VENDOR = '{$vendor}' AND DATES BETWEEN '{$startDate}' AND '{$endDate}' ";
					}
				}
				else{
					header("Location:userHome.php?id=2&flag=zeta");
					exit(0);				
				}
			}			
		}
		else{
			header("Location:userHome.php?id=2&flag=eta");
			exit(0);
		}
	}
	/*************************************************************************************************
	 * ->> Following function details
	 * ->> This function will extract weekly data and show
	 */
	elseif(isset($_POST['weekly'])){
		//Assign View flag
		$view = "weekly";
		//Get posted values
		$startDate = mysqlPrep($_POST['startDate']);
		$endDate = mysqlPrep($_POST['endDate']);
		//$startDate = get_startDate($endDate);
		if($endDate){
			$sqlQuery = "SELECT * FROM dashboard_all WHERE VENDOR = 'Network' AND DATES BETWEEN '{$startDate}' AND '{$endDate}'";
			$weekSqlQuery = "SELECT	WEEKOFYEAR(DATES) AS DATES,
				VENDOR				AS VENDOR,
				avg(TRAFFIC_RBH)	AS TRAFFIC_RBH,
				avg(TRAFFIC_NBH)	AS TRAFFIC_NBH,
				avg(CDR) 			AS CDR,
				avg(CSSR)			AS CSSR,
				avg(CSBR_RBH)		AS CSBR_RBH,
				avg(CSBR_NBH)		AS CSBR_NBH,
				avg(SDBR_NBH)		AS SDBR_NBH
				FROM dashboard_all 
				WHERE DATES BETWEEN '{$startDate}' AND '{$endDate}'";			
		}
		else{
			//No date selected
			//->> Error message
			header("Location:userHome.php?id=3&flag=phi");
			exit(0);
		}
	}
	/*************************************************************************************************
	 * ->> Following function details
	 * ->> This function will extract monthly data and show
	 */	
	elseif(isset($_POST['monthly'])){
		//Assign view flag
		$view = "monthly";
		//Get posted value
		$year = mysqlPrep($_POST['year']);
		$month = mysqlPrep($_POST['month']);
		//Divide in two parts
		if($year == 0 && $month == 0){
			//didn't selected any year or month
			//->> Show error message
			header("Location:userHome.php?id=4&flag=chi");
			exit(0);
		}
		elseif($year > 0 && $month == 0){
			//didn't selected any year or month
			//->> Show error message
			header("Location:userHome.php?id=4&flag=chi");
			exit(0);
		}
		elseif($year == 0 && $month > 0){
			//didn't selected any year or month
			//->> Show error message
			header("Location:userHome.php?id=4&flag=chi");
			exit(0);
		}				
		elseif($year > 0 && $month > 0){
			//Year and date selectedcarry on with the monthly report
			$monthRange = get_month_range($year,$month);
			$startDate = mysqlPrep($monthRange[0]);
			$endDate = mysqlPrep($monthRange[1]);
			$sqlQuery = "SELECT * FROM dashboard_all WHERE DATES BETWEEN '{$startDate}' AND '{$endDate}' ORDER BY DATES DESC";
			$topEightsqlQuery = "SELECT * FROM dashboard_all WHERE DATES BETWEEN '{$startDate}' AND '{$endDate}' ORDER BY TRAFFIC_NBH DESC LIMIT 0,8";
			$monthlySqlQuery = "SELECT MONTHNAME('$endDate') AS	DATES,
				VENDOR AS	VENDOR,
				avg(TRAFFIC_RBH) AS TRAFFIC_RBH,
				avg(TRAFFIC_NBH) AS TRAFFIC_NBH,
				avg(CDR)				AS CDR,
				avg(CSSR)			AS CSSR,
				avg(CSBR_RBH)		AS CSBR_RBH,
				avg(CSBR_NBH)		AS CSBR_NBH,
				avg(SDBR_NBH)		AS SDBR_NBH
				FROM
				(
					SELECT *
					FROM dashboard_all
					AS MONTHLY_TOP_8
					WHERE DATES BETWEEN '{$startDate}' AND '{$endDate}'
					ORDER BY TRAFFIC_NBH DESC LIMIT 0,8
				) AS MONTHLY";
		}
	}
?>
<?php
	switch ($view){
		case "daily":
			//View daily data - different format
			echo "
				<div class='content'>
					<div class='dataView'>
			";
						viewDashboardData($sqlQuery);
						//echo $sqlQuery;
					echo "</div>";
					echo"<div class='export'>";
?>
				<form name="result" method="POST" action="export.php">
					<input type="hidden" name="query" value="<?php echo $sqlQuery;?>">
					<input type="hidden" name="vendor" value="<?php echo $vendor;?>">
					<input type="submit" name="export" value="Export to CSV" class="button">
				</form>
<?php
					echo"
					</div>
				</div>";
		break;
		case "weekly":
			//View one row average of seven days
			echo "
				<div class='content'>
					<div class='dataView'>
			";
						viewDashboardData($weekSqlQuery);
						//echo $weekSqlQuery;
					echo "</div>";
					echo"<div class='export'>";
?>
				<form name="result" method="POST" action="export.php">
					<input type="hidden" name="query" value="<?php echo $weekSqlQuery;?>">
					<input type="hidden" name="vendor" value="Weekly_AVG">
					<input type="submit" name="export" value="Export to CSV" class="button">
				</form>
<?php
					echo"
					</div>
				</div>";			
			//View all seven days data
			echo "
				<div class='content'>
					<div class='dataView'>
			";
						viewDashboardData($sqlQuery);
						//echo $sqlQuery;
					echo "</div>";
					echo"<div class='export'>";
?>
				<form name="result" method="POST" action="export.php">
					<input type="hidden" name="query" value="<?php echo $sqlQuery;?>">
					<input type="hidden" name="vendor" value="Weekly_7days">
					<input type="submit" name="export" value="Export to CSV" class="button">
				</form>
<?php
					echo"
					</div>
				</div>";			
		break;
		case "monthly":
			//View one row - avarage of last month (26-25)
			echo "
				<div class='content'>
					<div class='dataView'>
			";
						viewDashboardData($monthlySqlQuery);
					echo "</div>";
					echo"<div class='export'>";
?>
				<form name="result" method="POST" action="export.php">
					<input type="hidden" name="query" value="<?php echo $monthlySqlQuery;?>">
					<input type="hidden" name="vendor" value="Monthly_AVG">
					<input type="submit" name="export" value="Export to CSV" class="button">
				</form>
<?php
					echo"
					</div>
				</div>";			
			//View top 8 entries for last month
			echo "
				<div class='content'>
					<div class='dataView'>
			";
						viewDashboardData($topEightsqlQuery);
					echo "</div>";
					echo"<div class='export'>";
?>
				<form name="result" method="POST" action="export.php">
					<input type="hidden" name="query" value="<?php echo $topEightsqlQuery;?>">
					<input type="hidden" name="vendor" value="Monthly_Top_8days">
					<input type="submit" name="export" value="Export to CSV" class="button">
				</form>
<?php
					echo"
					</div>
				</div>";			
			//View all daily data for last month
			echo "
				<div class='content'>
					<div class='dataView'>
			";
						viewDashboardData($sqlQuery);
					echo "</div>";
					echo"<div class='export'>";
?>
				<form name="result" method="POST" action="export.php">
					<input type="hidden" name="query" value="<?php echo $sqlQuery;?>">
					<input type="hidden" name="vendor" value="Monthly_30days">
					<input type="submit" name="export" value="Export to CSV" class="button">
				</form>
<?php
					echo"
					</div>
				</div>";			
		break;
		default:
			echo "No query Defied!";
		break;
	}
?>
<?php
	//This fuction creates the footer of the site
	footer();
?>
<?php
	//flush the output buffer
	ob_flush();
?>