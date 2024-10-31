<?php
/*  Popupper v1.5 Plugin for WordPress 
    Copyright (C) 2008 Edward P. Hogan    

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
	define('POPUPPER_PATH', dirname(__FILE__).'/');
	define('POPUPPER_ABSPATH', realpath(POPUPPER_PATH . "../../..").'/');

	require_once(POPUPPER_ABSPATH.'wp-config.php');
	
	global $wpdb;

	$version    = $_POST["version"];
	$uniq_key   = $_POST["uniq_key"];
	$img        = $_POST["src"];
	$img_width  = $_POST["width"];
	$img_width_text = "";
	$img_height = $_POST["height"];
	$img_height_text = "";
	$align      = $_POST["align"];
	$hspace     = $_POST["hspace"];
	$alt        = $_POST["alt"];
	$text       = $_POST["popuptext"];
	$text = str_replace("\"", "\\\"", $text);
	
	/* xxx sanity check the version number */

	$popupper_text = "";
	$popupper_text .= "  new YAHOO.widget.Tooltip(\\\"ttt" . $uniq_key . "\\\",\n";
	$popupper_text .= "                           { context:\\\"ctx_" . $uniq_key . "\\\",\n";
	/* 3 types of popups:
	 * - image only popups
	 *    - don't show the alignment
	 * - text only popups
	 *    - have a width argument
	 * - image and text popups
	 *    - build a table to show the thing
	 */
	$img_src = "";
	$extra_align = "";
	if ($img != "") {
		if ($img_width != "") {
			$img_width_text = " width=\\'" . $img_width . "\\'";
		}
		if ($img_height != "") {
			$img_height_text = " height=\\'" . $img_height . "\\'";
		}
		if ($text != "") {
			/* xxx hardcoded hspace, vspace, and image alignment */
			$extra_align = " hspace=\\'10\\' vspace=\\'10\\' align=\\'left\\'";
		}
		$img_src = "<img src=\\'" . $img . "\\'" . $img_width_text . $img_height_text . $extra_align . " alt=\\'" . $alt . "\\' />"; 
	}
	else {
		$img_src = null;
	}

	if ($img_src != null) {
		if ($text == "") {
			/* case 1: image-only */
			$popupper_text .= "                             text:\\\"<p>" . $img_src . "</p>\\\",\n";
		}
		else {
			/* case 2: image and text */
			/* xxx hardcoded text alignment */
			$popupper_text .= "                             text:\\\"<table><tr><td><p>" . $img_src . "</p><p align=\\'justify\\'>" . $text . "</p>\\\",\n";
		}
	}
	else {
		/* case 3: text-only */
		$popupper_text .= "                             text:\\\"<p>" . $text . "</p>\\\",\n";
	}
	/* xxx hardcoded width  */
	$popupper_text .= "                             width:\\\"auto\\\",\n";
	/* xxx hardcoded delay */
	$popupper_text .= "                             showDelay: 50 });\n";


	/* insert the popup info into the postmeta table */
        if (version_compare($wp_version, '2.8', '>=')) {
           /* in WP 2.8, post_id suddenly becomes an unsigned int... thanks for nothing. Guess I should created my own database but I was trying to play nice.
            * so now start inserting from post_id 100000000000000000 forward. If you have posted more times than the age of the universe in seconds this
            * hack won't work for you. */
           $huge_num = 100000000000000000;
           $huge_uniq_key = $huge_num + $uniq_key;
	   $sql_insert_statement = sprintf("INSERT INTO %s (post_id, meta_key, meta_value) VALUES ('%u', '_popupper_ctx', '%s')", $wpdb->postmeta, $huge_uniq_key, $popupper_text);
        }
        else {
	   /* the negative $uniq_key will be found later and converted into the normal post id */
           $uniq_key *= -1;
	   $sql_insert_statement = "INSERT INTO ".$wpdb->postmeta." (post_id, meta_key, meta_value) VALUES ('".$uniq_key."', '_popupper_ctx', '".$popupper_text."')";
        }
	$wpdb->query($sql_insert_statement);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript" src="../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script type="text/javascript">
//<!-- this should close down the dialog box...
		tinyMCEPopup.close();
//-->
	</script>
</head>
</html>
