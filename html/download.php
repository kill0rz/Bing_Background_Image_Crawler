<?php

if (isset($_GET['getpic']) && file_exists("./pics/" . trim($_GET['getpic']))) {
	$filename = sprintf("%s/%s", "pics/", trim($_GET['getpic']));
	header("Content-Type: application/octet-stream");
	$save_as_name = basename("/pics/" . trim($_GET['getpic']));
	header("Content-Disposition: attachment; filename=\"$save_as_name\"");
	readfile($filename);
}

if (!isset($_GET['pic']) || !file_exists("./pics/" . trim($_GET['pic']))) {
	header('Location: ./index.php');
} else {
	echo "<center><img height=80% src='./pics/" . htmlentities($_GET['pic'], ENT_QUOTES) . "' alt='" . htmlentities($_GET['pic'], ENT_QUOTES) . "' /><br /><br /><a href='download.php?getpic=" . urldecode($_GET['pic']) . "'>DOWNLOAD</a></center>";
}