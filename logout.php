<?php
session_start();
session_destroy();
header('Location: affichplat.php');
exit();
?>