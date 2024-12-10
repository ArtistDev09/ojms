<?php
require('connection.php');
$jID = $_GET['id'];

$sql = $conn->prepare("INSERT INTO click(count, journalID) VALUES(1, $jID)");
$sql->execute();
echo $jID;
//header('location: display_journal.php');
?>