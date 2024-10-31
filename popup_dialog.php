<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--
    Popupper v1.6 Plugin for WordPress 
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
--->
<?php
  require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/wp-config.php");
  if (version_compare($wp_version, '2.6', '<')) {
    load_plugin_textdomain("popupper-v10",
                           PLUGINDIR . "/" . dirname(plugin_basename(__FILE__)) . "/i18n");
  }
  else {
    load_plugin_textdomain("popupper-v10",
                           PLUGINDIR . "/" . dirname(plugin_basename(__FILE__)),
                           "popupper-v10/i18n");
  }
  echo "  <title>" . __('Insert Popup', 'popupper-v10') . "</title>\n";
?>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <script type="text/javascript" src="../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
  <script type="text/javascript" src="../../../wp-includes/js/tinymce/utils/mctabs.js"></script>
  <script type="text/javascript" src="../../../wp-includes/js/tinymce/utils/form_utils.js"></script>
  <script type="text/javascript" src="scripts/popup_dialog.js?v=1.6"></script>
  <script type="text/javascript" src="scripts/yui2.5.2_tooltip.js"></script>
  <link rel="stylesheet" type="text/css" href="css/yui2.5.2_tooltip.css" media="screen" />
</head>
<body id="image" style="display: none">
<form name="popup_form" method="POST" action="popup_post.php">
  <input type="hidden" id="version" name="version" value="1.6" />
<?php
  /* it should be the case that you can't edit more than 1 popup per second */
  $gtod = gettimeofday();
  $uniq_key = (($gtod["sec"] % 10000) * 100000) + $gtod["usec"];
  /* post_ID could be real or could be temp_ID */
  echo '  <input type="hidden" id="uniq_key" name="uniq_key" value="' . $uniq_key . '" />';
?>

  <div class="tabs">
    <ul>
      <li id="general_tab" class="current">
<?php
  echo "      <span><a href=\"javascript:mcTabs.displayTab('general_tab','general_panel');\" onmousedown=\"return false;\">" . __('Insert Popup', 'popupper-v10') . "</a></span>\n";
?>
      </li>
    </ul>
  </div>

  <div class="panel_wrapper">
    <div id="general_panel" class="panel current">
     <table border="0" cellpadding="4" cellspacing="0">
          <tr>
<?php
  echo "            <td nowrap=\"nowrap\"><label for=\"src\">" . __('Image URL', 'popupper-v10') . "</label></td>\n";
?>
            <td><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input id="src" name="src" type="text" class="mceFocus" value="" style="width: 200px" onchange="popup_dialog.getImageData();" onblur="popup_dialog.getImageData();"/></td>
                  <td id="srcbrowsercontainer">&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr>
<?php
  echo "            <td nowrap=\"nowrap\"><label for=\"alt\">" . __('Image Description', 'popupper-v10') . "</label></td>\n";
?>
            <td><input id="alt" name="alt" type="text" value="" style="width: 200px" /></td>
          </tr>
          <tr>
<?php
  echo "            <td nowrap=\"nowrap\"><label for=\"width\">" . __('Dimensions', 'popupper-v10') . "</label></td>\n";
?>
            <td><input id="width" name="width" type="text" value="" size="3" maxlength="5" />
              x
              <input id="height" name="height" type="text" value="" size="3" maxlength="5" /></td>
          </tr>
          <tr>
<?php
  echo "            <td nowrap=\"nowrap\"><label for=\"linkdecorate\">" . __('Link Decoration', 'popupper-v10') . "</label></td>\n";
  echo "            <td>\n";
  echo "              <select id=\"linkdecorate\" name=\"linkdecorate\" onchange=\"popup_dialog.linkDecorateOnChange();\">\n";
  echo "                <optgroup label=\"" . __('Underlining', 'popupper-v10') . "\">\n";
  echo "                  <option value=\"underline\">" . __('underline', 'popupper-v10') . "</option>\n";
  echo "                  <option value=\"double-underline\">" . __('double underline', 'popupper-v10') . "</option>\n";
  echo "                  <option value=\"dashed-underline\">" . __('dashed underline', 'popupper-v10') . "</option>\n";
  echo "                  <option value=\"bold\">" . __('bold', 'popupper-v10') . "</option>\n";
  echo "                  <option value=\"bold-underline\">" . __('bold underline', 'popupper-v10') . "</option>\n";
  echo "                </optgroup>\n";
  echo "                <optgroup label=\"" . __('Bordering', 'popupper-v10') . "\">\n";
  echo "                  <option value=\"dashed-box\">" . __('dashed box', 'popupper-v10') . "</option>\n";
  echo "                  <option value=\"dotted-box\">" . __('dotted box', 'popupper-v10') . "</option>\n";
  echo "                </optgroup>\n";
  echo "                <optgroup label=\"" . __('Highlighting', 'popupper-v10') . "\">\n";
  echo "                  <option value=\"yellow-highlight\">" . __('yellow highlight', 'popupper-v10') . "</option>\n";
  echo "                  <option value=\"light-blue-highlight\">" . __('light-blue highlight', 'popupper-v10') . "</option>\n";
  echo "                  <option value=\"light-green-highlight\">" . __('light-green highlight', 'popupper-v10') . "</option>\n";
  echo "                  <option value=\"pink-highlight\">" . __('pink highlight', 'popupper-v10') . "</option>\n";
  echo "                </optgroup>\n";
  echo "              </select>\n";
  echo "              <a id=\"preview\"><span id=\"linksampletext\" style=\"text-decoration:underline\">" . __('sample', 'popupper-v10') . "</span></a>\n";
  echo "            </td>\n";
  echo "          </tr>\n";
  echo "          <tr>\n";
  echo "            <td nowrap=\"nowrap\"><label for=\"popuptext\">" . __('Popup Text', 'popupper-v10') . "</label></td>\n";
?>
            <td><textarea id="popuptext" name="popuptext" rows="6" cols="30"></textarea></td>
          </tr>
        </table>
    </div>
  </div>

  <div class="mceActionPanel">
    <div style="float: left">
      <input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
    </div>
    <div style="float: right">
<?php
  echo "      <input type=\"button\" id=\"preview\" name=\"preview\" value=\"" . __('Preview', 'popupper-v10') . "\" class=\"updateButton\" onclick=\"popup_dialog.preview();\" />\n";
?>
      <input type="submit" id="insert" name="insert" value="{#insert}" onclick="popup_dialog.update();" />
    </div>
  </div>
</form>
</body>
</html>
