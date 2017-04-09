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
	//This function protects the inputs form SQL injections
	function mysqlPrep($value){
	        $magic_quotes_active = get_magic_quotes_gpc();
	        $new_enough_php = function_exists("mysql_real_escape_string"); //i.e. PHP >= v4.3.0
	        if($new_enough_php){ //PHP v4.3.0 or higher
	                //undo any magic quote effects so mysql_real_escape_string can do the work
	                if($magic_quotes_active){
	                        $value = stripslashes($value);
	                }else {
	                        $value = mysql_real_escape_string($value);
	                }
	        }else { //before PHP v4.3.0
	                //if magic quotes aren't already on then addslashes manually
	                if(!$magic_quotes_active){
	                        $value = addslashes($value);
	                } //if magic quotes are active then the slashes already exists;
	        }
	        return $value;
	}
?>
<?php
	//This function confirms the query is executed perfectly
	function confirmQuery($resultSet,$queryNumber){
		if(!$resultSet){
			die("Database Query failed ! " . "</br>Query Number: " . $queryNumber . "<br> Error no: " . mysql_errno() . "<br>Error: ". mysql_error());
		}
		else{
			return TRUE;
		}
	}
?>
<?php
	//This function will create the header of the views
	function site_header($title){
	    echo "
	<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
	<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
		<head>
			<title>{$title}</title>
			<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
			<meta http-equiv='X-UA-Compatible' content='IE=edge'>
			<meta name='description' content='Web Tamplate for Inhouse tools'/>
			<meta name='author' content='arif, MArif@banglalinkgsm.com , www.sites.google.com/site/dharif23'/>
			<link href='../css/style.css' rel='stylesheet' type='text/css'/>
			<link href='../css/style-ui.css' rel='stylesheet' type='text/css'/>		
			<link href='../css/jquery-ui.css' rel='stylesheet' type='text/css'/>
			<link href='../css/jdpicker.css' rel='stylesheet' type='text/css'/>
			<script type='text/javascript' src='../js/jquery.min.js'></script>
			<script type='text/javascript' src='../js/jquery-1.9.1.js'></script>
	  		<script type='text/javascript' src='../js/jquery-ui.js'></script>		
	        <script type='text/javascript' src='../js/menu_script.js'></script>
		</head>
		<body>
			<div id='main_wrapper'>
				<div id='container'>
					<div id='header'>
						<div class='head'>
	                                            <div class='head_left_bg'>
	                                                <div class='head_left_1'></div>
	                                                <div class='head_left_2'><span><img src='../images/making_difference.gif'</span></div>
	                                                
	                                            </div>
	                                            <div class='head_right_bg'>
	                                                <div class='head_right_1'><span><img src='../images/bl_logo.gif'</span></div>
	                                                <div class='head_right_2'></div>
	                                            </div>
	                                            <div class='menu_gap'></div>
	    ";
	}
?>
<?php
	//This function creates the menu
	function menu(){
		    echo "
		                                        <div class='menu'>
		    ";
		                                    require_once('../includes/_menu.php');
		    echo"                         </div>
		                                <div class='menu_shade'></div>
		                            </div>
		                    </div>
		    <div id='content'>
		    ";		
	}
?>
<?php
	//This function creates the nice footer of the site
	function footer(){
	    echo "
	                      </div>
	        				<div id='footer'>
						<div class='footer_shade'></div>
						<div class='footer_bg'>
	         ";
	                                            require_once('../includes/_footer.php');
	    echo"
	                                        </div>
					</div>
				</div>
			</div>
		</body>
	</html>
	    ";
	}
?>
<?php
	//This function will generate Error message for login screen
	function getMessage($flag){
		switch($flag){
			case alpha:
				$errorMessage = "User doesn't exists! Try again !";
			break;
			case beta:
				$errorMessage = "Password doesn't match! Try again !";
			break;
			case gama:
				$errorMessage = "No credential submited! Try again !";
			break;
			case delta:
				$errorMessage = "You must login to browse !";
			break;
			case epsilon:
				$errorMessage = "You have been logged out !";
			break;
			case zeta:
				$errorMessage = "At least one of the field is blank !";
			break;
			case eta:
				$errorMessage = "No KPI selected !";
			break;
			case theta:
				$errorMessage = "Couldn't create user! Check mySQL query !";
			break;
			case iota:
				$errorMessage = "User created susscessfully !";
			break;
			case kappa:
				$errorMessage = "User already exists! Try another username !";
			break;
			case lambda:
				$errorMessage = "Data has been updated successfully !";
			break;
			case mu:
				$errorMessage = "Data update failed! Try again !";
			break;
			case nu:
				$errorMessage = "Error! Or may be Primary table is Blank !";
			break;
			case xi:
				$errorMessage = "Specified date doen't exist in DB. Please check NSN Vendor status !";
			break;
			case omicron:
				$errorMessage = "Already contains specific day's data. Check dashboard status !";
			break;
			case pi:
				$errorMessage = "Already contains specific day's data. Check dashboard status !";
			break;
			case rho:
				$errorMessage = "KPI Dashboard is incomplete for the specific date !";
			break;
			case sigma:
				$errorMessage = "Common KPI has been already proceesed for the specific date !";
			break;
			case tau:
				$errorMessage = "You didn't select any category or item name !";
			break;
			case upsilon:
				$errorMessage = "Can't create daily report, contact admin !";
			break;
			case phi:
				$errorMessage = "No date selected !";
			break;
			case chi:
				$errorMessage = "Year or Month wasn't selected !";
			break;
			case dltsucc:
				$errorMessage = "User Deleted successfully!";
			break;
			case dltunsucc:
				$errorMessage = "User Deletion unsuccessful! Please contact ADMIN!";
			break;
			default:
				$errorMessage = " ";
			break;
		}
		return $errorMessage;
	}
?>
<?php
	//This function generates Dates in format of (YYYY-MM-dd)
	function dateGenerator($date){
		$dateArray = explode("/",$date);

		$month = (int)$dateArray[0];
		$day = (int)$dateArray[1];
		$year = (int)$dateArray[2];
		
		$cDate = $year . "-" . $month . "-" . $day ;
		
		return $cDate;
	}
?>
<?php
	//This function will show query results
	function viewDashboardData($query){
		$rowCount = 1;
		$resultSet = mysql_query($query);
		confirmQuery($resultSet,206);
		$noRows = mysql_num_rows($resultSet);
		echo "
				<p>Total " . $noRows . " row(s) were selected</p>
				<table align='center' border='1' width='75%'>
					<tr class='tblheader'>
						<td>DATES</td>
						<td>VENDOR</td>
						<td>TRAFFIC_RBH</td>
						<td>TRAFFIC_NBH</td>
						<td>CDR</td>
						<td>CSSR</td>
						<td>CSBR_RBH</td>
						<td>CSBR_NBH</td>
						<td>SDBR_NBH</td>
					</tr>
			";
		while ($rows = mysql_fetch_assoc($resultSet)){
			if($rowCount%2==0){
				$tblRowColor = 'tblRowColor';
			}
			else{
				$tblRowColor = 'tblRowColorMono';
			}
			$traffic_rbh = number_format($rows['TRAFFIC_RBH'],4);
			$traffic_nbh= number_format($rows['TRAFFIC_NBH'],4);
			$cdr = number_format($rows['CDR'],4);
			$cssr = number_format($rows['CSSR'],4);
			$csbr_rbh = number_format($rows['CSBR_RBH'],4); 
			$csbr_nbh = number_format($rows['CSBR_NBH'],4);
			$sdbr_nbh = number_format($rows['SDBR_NBH'],4);
			echo "
				<tr class='" .$tblRowColor ."'>
					<td>{$rows['DATES']}</td>
					<td>{$rows['VENDOR']}</td>
					<td>{$traffic_rbh}</td>
					<td>{$traffic_nbh}</td>
					<td>{$cdr}</td>
					<td>{$cssr}</td>
					<td>{$csbr_rbh}</td>
					<td>{$csbr_nbh}</td>
					<td>{$sdbr_nbh}</td>
				</tr>
			";
			$rowCount++;
		}
		echo "</table>";
	}
?>
<?php
	//This function will view common KPI data
	function viewCommonData($query){
		set_time_limit(0);
		$rowCount = 1;
		$resultSet = mysql_query($query);
		confirmQuery($resultSet,1004);
		$noRows = mysql_num_rows($resultSet);
		echo "
				<p>Total " . $noRows . " row(s) were selected (Sample data)</p>
				<div class='commonView'>
				<table align='center' border='1'>
					<tr class='tblheader'>
						<td>DATES</td>
						<td>MSC_NAME</td>
						<td>BSC_NAME</td>
						<td>DIVISION</td>
						<td>DISTRICT</td>
						<td>THANA</td>
						<td>CELL_NAME</td>
						<td>VENDOR</td>
						<td>BH_TRAFFIC</td>
						<td>CDR</td>
						<td>CSSR</td>
						<td>CSBR</td>
						<td>HOSR</td>
						<td>SDBR</td>
					</tr>
			";
		while ($rows = mysql_fetch_assoc($resultSet)){
			set_time_limit(0);
			if($rowCount%2==0){
				$tblRowColor = 'tblRowColor';
			}
			else{
				$tblRowColor = 'tblRowColorMono';
			}
			$bh_traffic = number_format($rows['BH_TRAFFIC'],3);
			$cdr = number_format($rows['CALL_DROP_RATE'],2);
			$cssr = number_format($rows['CSSR'],2);
			$csbr = number_format($rows['CSBR'],2);
			$hosr = number_format($rows['HO_SUCCESS_RATE'],2);
			$sdbr = number_format($rows['SDCCH_BLOCKING_RATE'],2);
			echo "
				<tr class='" .$tblRowColor ."'>
					<td>{$rows['DATES']}</td>
					<td>{$rows['MSC_NAME']}</td>
					<td>{$rows['BSC_NAME']}</td>
					<td>{$rows['DIVISION']}</td>
					<td>{$rows['DISTRICT']}</td>
					<td>{$rows['THANA']}</td>
					<td>{$rows['CELL_NAME']}</td>
					<td>{$rows['VENDOR']}</td>
					<td>{$bh_traffic}</td>
					<td>{$cdr}</td>
					<td>{$cssr}</td>
					<td>{$csbr}</td>
					<td>{$hosr}</td>
					<td>{$sdbr}</td>
				</tr>
			";
			$rowCount++;
		}
		echo "</table>
			</div>
		";		
	}
?>
<?php
	//This function will create profile edit page
	function editProfile($user,$id,$flag){
		$errorMessage = getMessage($flag);
		$userDetails = getUserDetails($user);
		$user = mysql_fetch_assoc($userDetails);
		echo "
			<html>
				<body>
					<div class='content'>
						<div class='report'>
							<form name='editProfile' action='editProfile.php' method='POST'>
						";
		if($id == 21){
			//Change Name
			echo "
				<table align='center' border=0>
					<tr>
						<td colspan='2'><h1>Please provide necessary user information</h1></td>
					</tr>
					<tr>
						<td colspan='2' class='errorMsg'>".$errorMessage."</td>
					</tr>
					<tr>
						<td>Current Full Name</td>
						<td><input type='text' name='name' required='required' maxlength='32' value='".$user['name']."' readonly></td>
					</tr>
					<tr>
						<td>Type New Full Name</td>
						<td>
							<input type='hidden' name='userName' value='".$user['userName']."'>
							<input type='text' name='name' required='required' maxlength='32' autofocus>
						</td>
					</tr>
					<tr>
						<td colspan='2' align='right'><input type='submit' name='changeName' value='Change Name' class='button'></td>
					</tr>
				</table>
			";			
		}
		elseif($id == 22){
			//Change Email
			echo "
				<table align='center' border=0>
					<tr>
						<td colspan='2'><h1>Please provide necessary user information</h1></td>
					</tr>
					<tr>
						<td colspan='2' class='errorMsg'>".$errorMessage."</td>
					</tr>
					<tr>
						<td>Current E-mail</td>
						<td><input type='text' name='email' required='required' maxlength='32' value='".$user['email']."' readonly></td>
					</tr>
					<tr>
						<td>Type New E-mail</td>
						<td>
							<input type='hidden' name='userName' value='".$user['userName']."'>
							<input type='text' name='email' required='required' maxlength='32' autofocus>
						</td>
					</tr>
					<tr>
						<td colspan='2' align='right'><input type='submit' name='changeEmail' value='Change Email' class='button'></td>
					</tr>
				</table>
			";
		}
		elseif($id == 23){
			//Change Password
			echo "
				<table align='center' border=0>
					<tr>
						<td colspan='2'><h1>Please provide necessary user information</h1></td>
					</tr>
					<tr>
						<td colspan='2' class='errorMsg'>".$errorMessage."</td>
					</tr>
					<tr>
						<td>Type new password</td>
						<td><input type='password' name='password' required='required' maxlength='16'></td>
					</tr>
					<tr>
						<td>Re-type new password</td>
						<td>
							<input type='hidden' name='userName' value='".$user['userName']."'>
							<input type='password' name='password2' required='required' maxlength='16'>
						</td>
					</tr>
					<tr>
						<td colspan='2' align='right'><input type='submit' name='changePassword' value='Change Password' class='button'></td>
					</tr>
				</table>
			";
		}
		echo"
							</form>
						</div>
					</div>
				</body>
			</html>
		";		
	}
?>
<?php
	//This function will get details of users
	function getUserDetails($userName){
		$query = "SELECT * FROM users WHERE userName = '{$userName}'";
		$resultSet = mysql_query($query);
		confirmQuery($resultSet, 801);
		return $resultSet;
	}
?>
<?php
	//This function counts records of a table according to condition
	function countRecords($tableName,$date){
		$countQuery = "SELECT COUNT(*) AS COUNT FROM {$tableName} WHERE DATES = '{$date}'";
		$countResult = mysql_query($countQuery);
		confirmQuery($countResult,503);
		$CountRows = mysql_fetch_assoc($countResult);
		$count = $CountRows['COUNT'];
		return $count;
	}
?>
<?php 
	//This function will detect the range of monthy reporting start and end date
	function get_month_range($year,$month){
		$day_p = 26;
		$day_c = 25;
		//Check wheter it's january or not
		if($month == 1){
			$year_p = $year - 1;
			$month_p = 12;
			$monthRange[0] = $year_p."/".$month_p."/".$day_p;
			$monthRange[1] = $year."/".$month."/".$day_c;
		}
		else{
			$month_p = $month - 1;
			$monthRange[0] = $year."/".$month_p."/".$day_p;
			$monthRange[1] = $year."/".$month."/".$day_c;
		}
		return $monthRange;
	}
?>
<?php
	//This function generates Dates in format of (YYYY-MM-dd)
	function get_startDate($date){

		$dateArray = explode("/",$date);

		$year = (int)$dateArray[0];
		$month = (int)$dateArray[1];
		$day = (int)$dateArray[2]-6;
		
		$startDate = $year."/".$month."/".$day;
		
		return $startDate;
	}
?>
<?php
	//flush the output buffer
	ob_flush();
?>