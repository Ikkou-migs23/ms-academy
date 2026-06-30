<?php
session_start();


session_destroy();
header('Location: /ms-academy/admin/login.php');
exit;
