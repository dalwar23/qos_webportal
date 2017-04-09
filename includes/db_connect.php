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
//include the constants file
include 'constants.php';

//conncet to the database
$connect = mysql_connect(HOST,USER,PASSWORD);

//Select the database
$select = mysql_select_db(DATABASE,$connect);

?>
<?php
//flush the output buffer
ob_flush();
?>

