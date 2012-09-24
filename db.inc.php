<?php
$db = new mysqli("localhost", "root", "root", "ajax_milena");
/* check connection */
if ($db->connect_errno) {
    die('Database Error (' . $db->connect_errno . ') ' . $db->connect_error);
}
?>