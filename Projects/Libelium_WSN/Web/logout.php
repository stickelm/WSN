<?php
require_once ("defs.php");

session_start();
unset($_SESSION["secret_id"]);
session_destroy();
header("Location: index.html");
exit();
?>
