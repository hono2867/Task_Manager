<?php
require'Task.php';


if (isset($_GET['id'])) {
	Task::delete($_GET['id']);
	header('Location: index.php');
	exit;
}
?>


