<?PHP
/* This script is setting all vars */
##### Setting SQL Type #####
$sql_type = "1"; // 1 --> MySQL ; 2 --> MSSQL

 if($sql_type == "1"){
  include ("mysql.inc.php");		
 }elseif($sql_type == "2"){
  include ("mssql.inc.php");
 }

##### Setting SQL Vars #####
$sql_host = "localhost";
$sql_name = "autenticador2fa"; 
$sql_user = "ComodinUser7";
$sql_pass = "comodincontra77";

##### Setting Other Vars #####
$per_page = "10";
?>