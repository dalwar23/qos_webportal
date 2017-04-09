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
<html>
	<body>
		<div class="content">
			<div class="report">
				<p align="center"><?php echo $vendor . " - " .$errorMessage;?></p>
				<?php
					echo "
						<p align='center' style='color:red;'> ! Please make sure that all primary tables are filled. Check primary table status !</p>
						<div class='processPanel'>
							<form name='dailyDataProcess' method='POST' action='dataProcessor.php'>
								<input type='submit' name='dailyDataProcess' value='Process Daily 2G Data' class='button'>
							</form>
						</div>
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