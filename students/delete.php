<?php 
require_once('../connection.php');
if(isset($_GET['code'])){
    $code = $_GET['code'];
} else{
    echo "<h2 align='center'> you aren't allowed to  do this ! </h2>";
    exit();

}
$result = $connection->query("delete from students where code = $code");
header("location: index.php");