<?php

/*
 * if first argument is passed, use that as a filename
 * otherwise, loop through all pngs in current directory.
 * Works with any size sprite, but was created for a 16x16 sprite frame.
 * Made for an LED matrix built from an LED strip that threads left-to-right 
 * on odd lines and left-to-right on even lines.
 */

if (!empty($argv[1])) {
	processImage($argv[1]);
} else { 
	foreach(glob("*.png") as $filename) {
		processImage($filename);
	}
}
print "Done\n";


function processImage($filename) {
	$img = imagecreatefrompng($filename);
	$width = imagesx($img);
	$height = imagesy($img);

	print "const long " . pathinfo($filename, PATHINFO_FILENAME) . "[] PROGMEM = \n";
	print "{\n";
	$oscillator = 1;
	for($y = 0; $y < $height; $y++) {
		if ($oscillator > 0) {
		    for($x = 0; $x < $width; $x++) {
		        printColor(imagecolorat($img, $x, $y));
		    }
		} else {
		    for($x = $width - 1; $x >= 0; $x--) {
		        printColor(imagecolorat($img, $x, $y));
		    }
		}
	    print "\n";
	    $oscillator *= -1;
	}
	print "};\n\n";
	imagedestroy($img);
}

function printColor($rgb) {
	$r = dechex(($rgb >> 16) & 0xFF);
	$g = dechex(($rgb >> 8) & 0xFF);
	$b = dechex($rgb & 0xFF);
	print '0x' . str_pad($r, 2, '0', STR_PAD_LEFT) . str_pad($g, 2, '0', STR_PAD_LEFT) . str_pad($b, 2, '0', STR_PAD_LEFT) . ", ";
}
