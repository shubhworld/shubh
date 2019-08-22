<?php
/**
 * Recursively copy files
 *
 * @param $src
 * @param $dst
 */
function siteorigin_recurse_copy( $src, $dst ) {
	$dir = opendir($src);
	@mkdir($dst);
	while( false !== ( $file = readdir( $dir ) ) ) {
		// Skip hidden and non files/directories
		if ( $file[0] == '.' ) continue;
		if ( is_dir( $src . '/' . $file ) ) {
			siteorigin_recurse_copy( $src . '/' . $file,$dst . '/' . $file );
		}
		else {
			copy( $src . '/' . $file, $dst . '/' . $file );
		}
	}
	closedir($dir);
}
function siteorigin_recurse_rmdir($src) {
	$dir = opendir($src);
	while(false !== ( $file = readdir($dir)) ) {
		if (( $file != '.' ) && ( $file != '..' )) {
			$full = $src . '/' . $file;
			if ( is_dir($full) ) {
				siteorigin_recurse_rmdir($full);
			}
			else {
				unlink($full);
			}
		}
	}
	closedir($dir);
	rmdir($src);
}