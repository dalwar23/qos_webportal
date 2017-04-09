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
?>
<!-- Menu -->
<ul class="menu_item" id="menu">
    <li><a href="index.php" class="menulink">Home</a>
    <?php
    	if($_SESSION['LOGGEDIN'] == TRUE && $_SESSION['USERTYPE'] == 1){
    		echo "
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='#' class='menulink'>2G Data Management</a>
				    	<ul>
				    		<li><a href='#'>&raquo;&nbsp;Process daily data - X</a></li>
				    		<li class='menu_break'>Search Reporting Data</li>
				    		<li><a href='userHome.php?id=2'>&raquo;&nbsp;Daily reporting data</a></li>
				    		<li><a href='userHome.php?id=3'>&raquo;&nbsp;Weekly reporting data</a></li>
				    		<li><a href='userHome.php?id=4'>&raquo;&nbsp;Monthly reporting data</a></li>
				    		<li class='menu_break'>Search Analytical Data</li>
				    		<li><a href='userHome.php?id=11'>&raquo;&nbsp;Second degree data search</a></li>
				    		<li><a href='userHome.php?id=12'>&raquo;&nbsp;Third degree data search</a></li>
				    		<li><a href='userHome.php?id=13'>&raquo;&nbsp;All thana data search</a></li>
				    		<li><a href='userHome.php?id=14'>&raquo;&nbsp;n-Thana data search</a></li>				    		
				    		<li><a href='userHome.php?id=15'>&raquo;&nbsp;All BSC data search</a></li>
				    		<li><a href='userHome.php?id=16'>&raquo;&nbsp;Average data search</a></li>
				    	</ul>
				    </li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='#' class='menulink'>2G Data Status</a>
				    	<ul>
				    		<li><a href='adminHome.php?id=31'>&raquo;&nbsp;Primary table status</a></li>
				    		<li><a href='userHome.php?id=35'>&raquo;&nbsp;Vendor status report</a></li>
				    		<li><a href='userHome.php?id=36'>&raquo;&nbsp;Dashboard status report</a></li>
				    	</ul>
				    </li>			    
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='#' class='menulink'>User Management</a>
				    	<ul>
				    		<li><a href='adminHome.php?id=11'>&raquo;&nbsp;Create new user</a></li>
				    		<li><a href='adminHome.php?id=12'>&raquo;&nbsp;User details</a></li>
				    	</ul>
				    </li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='#' class='menulink'>Admin Profile</a>
				    	<ul>
				    		<li><a href='userHome.php?id=21'>&raquo;&nbsp;Change name</a></li>
				    		<li><a href='userHome.php?id=22'>&raquo;&nbsp;Change email</a></li>
				    		<li><a href='userHome.php?id=23'>&raquo;&nbsp;Change password</a></li>
				    	</ul>
				    </li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='logout.php' class='menulink'>Logout</a></li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
    		";
    	}
    	elseif($_SESSION['LOGGEDIN'] == TRUE && $_SESSION['USERTYPE'] == 2){
    		echo "
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='#' class='menulink'>2G Reporting Data</a>
				    	<ul>
				    		<li><a href='userHome.php?id=2'>&raquo;&nbsp;Daily reporting data</a></li>
				    		<li><a href='userHome.php?id=3'>&raquo;&nbsp;Weekly reporting data</a></li>
				    		<li><a href='userHome.php?id=4'>&raquo;&nbsp;Monthly reporting data</a></li>
				    	</ul>
				    </li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='#' class='menulink'>2G Analytical Data</a>
				    	<ul>
				    		<li><a href='userHome.php?id=11'>&raquo;&nbsp;Second degree data search</a></li>
				    		<li><a href='userHome.php?id=12'>&raquo;&nbsp;Third degree data search</a></li>
				    		<li><a href='userHome.php?id=13'>&raquo;&nbsp;All thana data search</a></li>
				    		<li><a href='userHome.php?id=14'>&raquo;&nbsp;n-Thana data search</a></li>				    		
				    		<li><a href='userHome.php?id=15'>&raquo;&nbsp;All BSC data search</a></li>
				    		<li><a href='userHome.php?id=16'>&raquo;&nbsp;Average data search</a></li>
				    	</ul>
				    </li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='#' class='menulink'>2G Data Status</a>
				    	<ul>
				    		<li><a href='userHome.php?id=35'>&raquo;&nbsp;Vendor status report</a></li>
				    		<li><a href='userHome.php?id=36'>&raquo;&nbsp;Dashboard status report</a></li>
				    	</ul>
				    </li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='#' class='menulink'>User Profile</a>
				    	<ul>
				    		<li><a href='userHome.php?id=21'>&raquo;&nbsp;Change name</a></li>
				    		<li><a href='userHome.php?id=22'>&raquo;&nbsp;Change email</a></li>
				    		<li><a href='userHome.php?id=23'>&raquo;&nbsp;Change password</a></li>
				    	</ul>
				    </li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='logout.php' class='menulink'>Logout</a></li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
    		";
    	}
    	elseif($_SESSION['LOGGEDIN'] == TRUE && $_SESSION['USERTYPE'] == 3){
    		echo "
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='#' class='menulink'>2G Reporting Data</a>
				    	<ul>
				    		<li><a href='userHome.php?id=2'>&raquo;&nbsp;Daily reporting data</a></li>
				    		<li><a href='userHome.php?id=3'>&raquo;&nbsp;Weekly reporting data</a></li>
				    		<li><a href='userHome.php?id=4'>&raquo;&nbsp;Monthly reporting data</a></li>
				    	</ul>
				    </li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='#' class='menulink'>2G Analytical Data</a>
				    	<ul>
				    		<li><a href='userHome.php?id=11'>&raquo;&nbsp;Second degree data search</a></li>
				    		<li><a href='userHome.php?id=12'>&raquo;&nbsp;Third degree data search</a></li>
				    		<li><a href='userHome.php?id=13'>&raquo;&nbsp;All thana data search</a></li>
				    		<li><a href='userHome.php?id=14'>&raquo;&nbsp;n-Thana data search</a></li>				    		
				    		<li><a href='userHome.php?id=15'>&raquo;&nbsp;All BSC data search</a></li>
				    	</ul>
				    </li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='#' class='menulink'>2G Data Status</a>
				    	<ul>
				    		<li><a href='userHome.php?id=35'>&raquo;&nbsp;Vendor status report</a></li>
				    		<li><a href='userHome.php?id=36'>&raquo;&nbsp;Dashboard status report</a></li>
				    	</ul>
				    </li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='#' class='menulink'>User Profile</a>
				    	<ul>
				    		<li><a href='userHome.php?id=21'>&raquo;&nbsp;Change name</a></li>
				    		<li><a href='userHome.php?id=22'>&raquo;&nbsp;Change email</a></li>
				    		<li><a href='userHome.php?id=23'>&raquo;&nbsp;Change password</a></li>
				    	</ul>
				    </li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
				    <li><a href='logout.php' class='menulink'>Logout</a></li>
				    <li><img src='../images/constant_menu_div.gif' align='absbottom' border='0' alt=''></li>
    		";
    	}    	
    ?>
</ul>

<script type="text/javascript"> 
	var menu=new menu.dd("menu");
	menu.init("menu","menuhover");
</script>

<?php
//flush the output buffer
ob_flush();
?>