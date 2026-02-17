<?php

unset($_SESSION['student_id']);
unset($_SESSION['timestamp']);
header('Location: /login');
exit;
?>
