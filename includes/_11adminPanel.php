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
				<form name="createUser" action="userDataProcessor.php" method="post">
					<table align="center" border=0>
						<tr>
							<td colspan="2"><h1>Please provide necessary user information</h1></td>
						</tr>
						<tr>
							<td colspan="2" class="errorMsg"><?php echo $errorMessage ;?></td>
						</tr>
						<tr>
							<td>Full Name</td>
							<td><input type="text" name="name" required="required" maxlength="32" autofocus></td>
						</tr>
						<tr>
							<td>User Name</td>
							<td><input type="text" name="userName" required="required" maxlength="16"></td>
						</tr>
						<tr>
							<td>Password</td>
							<td><input type="password" name="password" required="required" maxlength="16"></td>
						</tr>
						<tr>
							<td>Re-type Password</td>
							<td><input type="password" name="password2" required="required" maxlength="16"></td>
						</tr>
						<tr>
							<td>Email</td>
							<td><input type="email" name="email" required="required" maxlength="32">
						</tr>
						<tr>
							<td>User Type</td>
							<td>
								<select name="userType" required="required">
									<option value="3">Normal User</option>
									<option value="2">Moderate User</option>
									<option value="1">Admin User</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="right"><input type="submit" name="createUser" value="Create New User" class="button"></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</body>
</html>
<?php
	//flush the output buffer
	ob_flush();
?>