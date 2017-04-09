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
<?php
//Assign the variables for DB connection
$host = "localhost";
$username = "root";
$password = "skywalker";
$database = "kpi_cell_pool";

//Define the variables for furthur use
define("HOST", $host);
define("USER", $username);
define("PASSWORD", $password);
define("DATABASE", $database);

?>
<?php
//flush the output buffer
ob_flush();
?>