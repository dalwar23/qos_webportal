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
<html>
	<head>
		<title>Login</title>
	</head>
	<body>
		<div class="login">
			<form name="loginForm" method="POST" action="loginProcess.php">
				<table align="center" border="0" width="30%">
					<tr>
						<td colspan="2"><h1>Please authenticate yourself</h1></td>
					</tr>
					<tr>
						<td colspan="2" class="errorMsg"><?php echo $errorMessage;?></td>
					</tr>
					<tr>
						<td align="right" width="50%">User Name</td>
						<td align="left"><input type="text" name="userName" maxlength="16" required="required" autocomplete="on" autofocus class="txtBox"></td>
					</tr>
					<tr>
						<td align="right" width="50%">Password</td>
						<td align="left"><input type="password" name="password" maxlength="16" required="required" class="txtBox"></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" name="loginSubmit" value="Login" class="flatButton"></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>