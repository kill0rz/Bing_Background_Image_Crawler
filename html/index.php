<?php

if ($handle = opendir('prevs')) {
	$datestring = '';

	while (false !== ($file = readdir($handle))) {
		$picarray[] = $file;
	}
	natsort($picarray);
	rsort($picarray);
	foreach ($picarray as $file) {
		if ($file != "." and $file != ".." and $file != "index.php") {
			$datestring = "";
			$date = explode("_", substr($file, 0, 10));
			$date = array_reverse($date);
			for ($i = 0; $i < 3; $i++) {
				$datestring .= $date[$i];
				if ($i < 2) {
					$datestring .= ".";
				}
			}
			echo "<div style='float:left'><a href='download.php?pic=" . str_replace("_preview", "", $file) . "' target='_blank'><img src='./prevs/" . $file . "' height='100px'alt='{$file}'/></a><br /><small>{$datestring}</small></div>\n";
		}
	}
	closedir($handle);
}