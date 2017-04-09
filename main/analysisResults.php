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
	//Get posted values
	if(isset($_POST['analyticalData2D'])){
		$startDate = mysqlPrep($_POST['from']);
		$endDate = mysqlPrep($_POST['to']);
		$dataType = mysqlPrep($_POST['dataType']);
		$category = mysqlPrep($_POST['categories']);
		if($category =="network"){
			$item = "network";
		}
		else{
			$item = mysqlPrep($_POST['item']);
		}
		//echo $startDate ."<br>".$endDate."<br>".$category."<br>".$item ."<br>".$dataType;
		if($startDate && $endDate && $category && $item){
			//All values are posted
			if($category == "none" || $item == "none"){
				header("Location:userHome.php?id=11&flag=tau");
				exit(0);
			}
			else{
				if($dataType == "summary"){
					$searchQuery = create2DSearchQuery($startDate,$endDate,$category,$item);
					$viewQuery = $searchQuery;
				}
				else{
					$capCategory = strtoupper($category);
					
					if($item == "network"){
						$searchQuery = "SELECT * FROM common_kpi WHERE DATES BETWEEN '" . $startDate . "' AND '" . $endDate ."'";
						$viewQuery = "SELECT * FROM common_kpi WHERE DATES BETWEEN '" . $startDate . "' AND '" . $endDate ."' ORDER BY CELL_NAME DESC LIMIT 0,50";
						//echo $searchQuery;						
					}
					else{
						$searchQuery = "SELECT * FROM common_kpi WHERE " . $capCategory . " = '" . $item ."' AND DATES BETWEEN '" . $startDate . "' AND '" . $endDate ."'";
						$viewQuery = "SELECT * FROM common_kpi WHERE " . $capCategory . " = '" . $item ."' AND DATES BETWEEN '" . $startDate . "' AND '" . $endDate ."' ORDER BY CELL_NAME DESC LIMIT 0,50";
						//echo $searchQuery;
					}
				}
			}
		}
		else{
			header("Location:userHome.php?id=11&flag=zeta");
			exit(0);
		}
		
	}
	elseif(isset($_POST['analyticalData3D'])){
		$startDate = mysqlPrep($_POST['from']);
		$endDate = mysqlPrep($_POST['to']);
		$dataType = mysqlPrep($_POST['dataType']);
		$category = mysqlPrep($_POST['categories']);
		$item = mysqlPrep($_POST['item']);
		$cell = mysqlPrep($_POST['cell']);
		//echo $startDate ."<br>".$endDate."<br>".$category."<br>".$item ."<br>".$dataType . "<br>" .$site;
		if($startDate && $endDate && $category && $item && $cell){
			if($category == "none" || $item == "none" || $cell == "none"){
				header("Location:userHome.php?id=12&flag=tau");
				exit(0);
			}
			else{
				if($dataType == "summary"){
					$searchQuery = create3DSearchQuery($startDate,$endDate,$category,$item,$cell);
					$viewQuery = $searchQuery;
				}
				else{
					$capCategory = strtoupper($category);
					$searchQuery = "SELECT * FROM common_kpi WHERE " . $capCategory . " = '" . $item ."' AND CELL_NAME = '$cell' AND DATES BETWEEN '" . $startDate . "' AND '" . $endDate ."'";
					$viewQuery = "SELECT * FROM common_kpi WHERE " . $capCategory . " = '" . $item ."' AND CELL_NAME = '$cell' AND DATES BETWEEN '" . $startDate . "' AND '" . $endDate ."' ORDER BY CELL_NAME DESC LIMIT 0,50";
				}
			}
		}
		else{
			header("Location:userHome.php?id=12&flag=zeta");
			exit(0);			
		}
	}
?>
<?php
	//This function concats the QUERY
	function create2DSearchQuery($startDate,$endDate,$category,$item){
		$capCategory = strtoupper($category);
		$searchQuery2D = headof2DQuery($category) . commonSegemnt() . footerof2DQuery($startDate,$endDate,$capCategory,$item);
		return $searchQuery2D;
	}
	function create3DSearchQuery($startDate,$endDate,$category,$item,$cell){
		$capCategory = strtoupper($category);
		$searchQuery3D = headof3DQuery($category) . commonSegemnt() . footerof3DQuery($startDate,$endDate,$capCategory,$item,$cell);
		return $searchQuery3D;
	}
?>
<?php
	//This will create the QUERY header
	function headof2DQuery($category){
		switch ($category){
			case "network":
				$headerSegment = "
				SELECT
				DATES AS DATES,
				";
			break;			
			case "msc_name":
				$headerSegment = "
				SELECT
				DATES AS DATES,
				MSC_NAME AS MSC_NAME,
				";
			break;
			case "bsc_name":
				$headerSegment = "
				SELECT
				DATES AS DATES,
				BSC_NAME AS BSC_NAME,
				";
			break;
			case "division":
				$headerSegment = "
				SELECT
				DATES AS DATES,
				DIVISION AS DIVISION,
				";
			break;
			case "district":
				$headerSegment = "
				SELECT
				DATES AS DATES,
				DISTRICT AS DISTRICT,
				";
			break;
			case "thana":
				$headerSegment = "
				SELECT
				DATES AS DATES,
				THANA AS THANA,
				";
			break;
			default:
				$headerSegment = "
				SELECT
				DATES AS DATES,
				MSC_NAME AS MSC_NAME,
				";
			break;
		}
		return  $headerSegment;
	}
	function headof3DQuery($category){
		$headerSegment = "
		SELECT
		DATES AS DATES,
		MSC_NAME AS MSC_NAME,
		BSC_NAME AS BSC_NAME,
		CELL_NAME AS CELL_NAME,
		";
		return $headerSegment;
	}
?>
<?php
	//This function returns the QUERY common segment
	function commonSegemnt(){
		$commonSegment = "
		SUM(AVAIL_TCH_MEAN)	AS	AVAIL_TCH_MEAN,
		SUM(AVAIL_TCH_MEAN_OL)	AS	AVAIL_TCH_MEAN_OL,
		SUM(AVAIL_TCH_MEAN_UL)	AS	AVAIL_TCH_MEAN_UL,
		SUM(BH_TRAFFIC)	AS	BH_TRAFFIC,
		SUM(BH_TRAFFIC_FR)	AS	BH_TRAFFIC_FR,
		SUM(BH_TRAFFIC_HR)	AS	BH_TRAFFIC_HR,
		SUM(BH_TRAFFIC_OL)	AS	BH_TRAFFIC_OL,
		SUM(BH_TRAFFIC_UL)	AS	BH_TRAFFIC_UL,
		SUM(RADIO_DROP)	AS	RADIO_DROP,
		SUM(HO_DROP)	AS	HO_DROP,
		SUM(BSS_DROP)	AS	BSS_DROP,
		SUM(CDR_N)	AS	CDR_N,
		if(SUM(CDR_D)=0,0,(SUM(CDR_N)/SUM(CDR_D)))*100	AS	CALL_DROP_RATE,
		SUM(CDR_D)	AS	CDR_D,
		SUM(CDR_N_OL)	AS	CDR_N_OL,
		SUM(CDR_N_UL)	AS	CDR_N_UL,
		if(SUM(TCH_ATTEMPTS_BH)=0,0,(SUM(TCH_ASS_FAIL_CONG_BH)/SUM(TCH_ATTEMPTS_BH)))*100	AS	CSBR,
		((1-if(SUM(SDCCH_DROP_D)=0,0,(SUM(SDCCH_DROP_N)/SUM(SDCCH_DROP_D))))*(if(SUM(TCH_ATTEMPTS)=0,0,(SUM(TCH_SUCC)/SUM(TCH_ATTEMPTS)))))*100 AS CSSR,
		if(SUM(ATT_OUT_HO)=0,0,(SUM(UL_LEV_HO_Vol)/SUM(ATT_OUT_HO)))*100	AS	UL_LEV_HO,
		if(SUM(ATT_OUT_HO)=0,0,(SUM(DL_LEV_HO_Vol)/SUM(ATT_OUT_HO)))*100	AS	DL_LEV_HO,
		if(SUM(ATT_OUT_HO)=0,0,(SUM(UL_QUALITY_HO_Vol)/SUM(ATT_OUT_HO)))*100	AS	UL_QUALITY_HO,
		if(SUM(ATT_OUT_HO)=0,0,(SUM(DL_QUALITY_HO_Vol)/SUM(ATT_OUT_HO)))*100	AS	DL_QUALITY_HO,
		if(SUM(ATT_OUT_HO)=0,0,(SUM(BETTER_CELL_HO_Vol)/SUM(ATT_OUT_HO)))*100	AS	BETTER_CELL_HO,
		if(SUM(ATT_OUT_HO)=0,0,(SUM(DR_HO_Vol)/SUM(ATT_OUT_HO)))*100	AS	DR_HO,
		SUM(SUCC_OUT_HO_INTERBSC)	AS	SUCC_OUT_HO_INTERBSC,
		SUM(SUCC_OUT_HO_INTRABSC)	AS	SUCC_OUT_HO_INTRABSC,
		SUM(ATT_OUT_HO_INTERBSC)	AS	ATT_OUT_HO_INTERBSC,
		SUM(ATT_OUT_HO_INTRABSC)	AS	ATT_OUT_HO_INTRABSC,
		SUM(HOSR_D)	AS	HOSR_D,
		SUM(HOSR_N)	AS	HOSR_N,
		if(SUM(HOSR_D)=0,0,(SUM(HOSR_N)/SUM(HOSR_D)))*100	AS	HO_SUCCESS_RATE,
		SUM(AVAIL_SDCCH_MEAN)	AS	AVAIL_SDCCH_MEAN,
		SUM(SDCCH_BH_TRAFFIC)	AS	SDCCH_BH_TRAFFIC,
		SUM(SDCCH_BLOCKING_N)	AS	SDCCH_BLOCKING_N,
		SUM(SDCCH_BLOCKING_D)	AS	SDCCH_BLOCKING_D,
		if(SUM(SDCCH_BLOCKING_D)=0,0,(SUM(SDCCH_BLOCKING_N)/SUM(SDCCH_BLOCKING_D)))*100	AS	SDCCH_BLOCKING_RATE,
		SUM(SDCCH_DROP_N)	AS	SDCCH_DROP_N,
		SUM(SDCCH_DROP_D)	AS	SDCCH_DROP_D,
		if(SUM(SDCCH_DROP_D)=0,0,(SUM(SDCCH_DROP_N)/SUM(SDCCH_DROP_D)))*100	AS	SDCCH_DROP_RATE,
		SUM(TCH_ASS_FAIL)	AS	TCH_ASS_FAIL,
		SUM(TCH_ASS_FAIL_BSS)	AS	TCH_ASS_FAIL_BSS,
		SUM(TCH_ASS_FAIL_CONG)	AS	TCH_ASS_FAIL_CONG,
		SUM(TCH_ASS_FAIL_CONG_BH)	AS	TCH_ASS_FAIL_CONG_BH,
		SUM(TCH_ASS_FAIL_DR)	AS	TCH_ASS_FAIL_DR,
		SUM(TCH_ASS_FAIL_N)	AS	TCH_ASS_FAIL_N,
		SUM(TCH_ASS_FAIL_RADIO)	AS	TCH_ASS_FAIL_RADIO,
		SUM(TCH_ATTEMPTS)	AS	TCH_ATTEMPTS,
		SUM(TCH_ATTEMPTS_BH)	AS	TCH_ATTEMPTS_BH,
		SUM(TCH_BLOCKING_D)	AS	TCH_BLOCKING_D,
		SUM(TCH_BLOCKING_N)	AS	TCH_BLOCKING_N,
		if(SUM(TCH_BLOCKING_D)=0,0,(SUM(TCH_BLOCKING_N)/SUM(TCH_BLOCKING_D)))*100	AS	TCH_BLOCKING_RATE,
		SUM(TCH_SUCC)	AS	TCH_SUCC,
		SUM(TOTAL_TRAFFIC)	AS	TOTAL_TRAFFIC,
		SUM(TS_IB3_MAX)	AS	TS_IB3_MAX,
		SUM(TS_IB4_MAX)	AS	TS_IB4_MAX,
		SUM(TS_IB5_MAX)	AS	TS_IB5_MAX,
		SUM(UL_LEV_HO_Vol)	AS	UL_LEV_HO_Vol,
		SUM(DL_LEV_HO_Vol)	AS	DL_LEV_HO_Vol,
		SUM(UL_QUALITY_HO_Vol)	AS	UL_QUALITY_HO_Vol,
		SUM(DL_QUALITY_HO_Vol)	AS	DL_QUALITY_HO_Vol,
		SUM(BETTER_CELL_HO_Vol)	AS	BETTER_CELL_HO_Vol,
		SUM(DR_HO_Vol)	AS	DR_HO_Vol,
		SUM(ATT_OUT_HO)	AS	ATT_OUT_HO		
		";
		return $commonSegment;
	}
?>
<?php
	//This is the footer segment of the QUERY
	function footerof2DQuery($startDate,$endDate,$capCategory,$item){
		if($item == "network"){
			$footerSegement2D = "
			FROM
			common_kpi
			WHERE
			DATES BETWEEN '".$startDate."' AND '".$endDate."'
			GROUP BY
			DATES
			";			
		}
		else{
			$footerSegement2D = "
			FROM
			common_kpi
			WHERE
			DATES BETWEEN '".$startDate."' AND '".$endDate."'
			AND
			".$capCategory ." = '".$item."'
			GROUP BY
			DATES,
			".$capCategory."
			";
		}
		return $footerSegement2D;
	}
	function footerof3DQuery($startDate,$endDate,$capCategory,$item,$cell){
		$footerSegement3D = "
		FROM
		common_kpi
		WHERE
		DATES BETWEEN '".$startDate."' AND '".$endDate."'
		AND
		".$capCategory ." = '".$item."'
		AND
		CELL_NAME = '".$cell."'
		GROUP BY
		DATES,
		".$capCategory."
		";
		return $footerSegement3D;
	}
?>
<?php
	//This function will cary out the query and show result data
	echo "
		<div class='content'>
			<div class='dataView'>
	";
	viewCommonData($viewQuery);
	echo "</div>";
	//echo  $searchQuery;
?>
<?php
	echo"<div class='export'>";
?>
	<form name="result" method="POST" action="export.php">
		<input type="hidden" name="query" value="<?php echo $searchQuery;?>">
		<input type="hidden" name="vendor" value="<?php echo $item;?>">
		<input type="submit" name="export" value="Export full data to CSV" class="button">
	</form>
<?php
	echo"
		</div>
	</div>
	";
?>
<?php
	//This fuction creates the footer of the site
	footer();
?>
<?php
	//flush the output buffer
	ob_flush();
?>