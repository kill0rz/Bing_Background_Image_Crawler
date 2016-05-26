<?php

function resizeImage($filepath_old, $filepath_new, $image_dimension, $scale_mode = 0, $overwrite = 0) {
	if ($overwrite == 1) {
		if (!(file_exists($filepath_old))) {
			echo "error1!";
			return false;
		}
	} else {
		if (!(file_exists($filepath_old)) || file_exists($filepath_new)) {
			echo "error2!";
			return false;
		}
	}
	$image_attributes = getimagesize($filepath_old);
	$image_width_old = $image_attributes[0];
	$image_height_old = $image_attributes[1];
	$image_filetype = $image_attributes[2];
	if ($image_width_old <= $image_dimension) {
		if (copy($filepath_old, $filepath_new)) {
			return true;
		} else {
			echo "error3!";
			return false;
		}
	}

	if ($image_width_old <= 0 || $image_height_old <= 0) {
		echo "error4!";
		return false;
	}
	$image_aspectratio = $image_width_old / $image_height_old;
	if ($scale_mode == 0) {
		$scale_mode = ($image_aspectratio > 1 ? -1 : -2);
	} elseif ($scale_mode == 1) {
		$scale_mode = ($image_aspectratio > 1 ? -2 : -1);
	}

	if ($scale_mode == -1) {
		$image_width_new = $image_dimension;
		$image_height_new = round($image_dimension / $image_aspectratio);
	} elseif ($scale_mode == -2) {
		$image_height_new = $image_dimension;
		$image_width_new = round($image_dimension * $image_aspectratio);
	} else {
		echo "error5!";
		return false;
	}

	switch ($image_filetype) {
		case 1:
			$image_old = imagecreatefromgif($filepath_old);
			$image_new = imagecreate($image_width_new, $image_height_new);
			imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $image_width_new, $image_height_new, $image_width_old, $image_height_old);
			imagegif($image_new, $filepath_new);
			break;

		case 2:
			$image_old = imagecreatefromjpeg($filepath_old);
			$image_new = imagecreatetruecolor($image_width_new, $image_height_new);
			imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $image_width_new, $image_height_new, $image_width_old, $image_height_old);
			imagejpeg($image_new, $filepath_new);
			break;

		case 3:
			$image_old = imagecreatefrompng($filepath_old);
			$image_colordepth = imagecolorstotal($image_old);
			if ($image_colordepth == 0 || $image_colordepth > 255) {
				$image_new = imagecreatetruecolor($image_width_new, $image_height_new);
			} else {
				$image_new = imagecreate($image_width_new, $image_height_new);
			}

			imagealphablending($image_new, false);
			imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $image_width_new, $image_height_new, $image_width_old, $image_height_old);
			imagesavealpha($image_new, true);
			imagepng($image_new, $filepath_new);
			break;

		default:
			echo "error6!";
			return false;
	}

	imagedestroy($image_old);
	imagedestroy($image_new);
	return true;
}
if ($handle = opendir('pics')) {

	$datestring = '';

	while (false !== ($file = readdir($handle))) {
		if ($file != "." and $file != ".." and $file != "index.php") {
			$filename = $file;
			$dot = strrpos($filename, ".");
			$filenamecut = substr($filename, 0, $dot);
			$endung = substr($filename, $dot);
			echo $file . "\n";
			if (resizeImage("pics/" . $file, "./prevs/" . $filenamecut . "_preview" . $endung, 150)) {
				echo "Erfolg!\n";
			} else {
				"error";
			}

		}
	}
	closedir($handle);
}