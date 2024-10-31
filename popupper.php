<?php
/*
Plugin Name: Popupper
Plugin URI: http://ehogan.itis5am.com:8080/popupper
Description: Popupper is a plugin that enables a blogger to add popups of images and text into their posts. A popup is an image/text blob that appears when the reader puts their mouse over some words in the text.
Version: 1.6
Author: Ed Hogan
Author URI: http://ehogan.itis5am.com:8080/
*/
/*  Popupper v1.6 Plugin for WordPress 
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
/* The Popupper button is taken from the Silk set of FamFamFam. See more at
 * http://www.famfamfam.com/lab/icons/silk/
 */

  /* protect against direct calls */
  if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die(__('You are not allowed to call this page directly.', 'popupper-v10'));
  }

  $root = dirname(dirname(dirname(dirname(__FILE__))));
  require_once($root.'/wp-config.php');
  /* version check wordpress 2.1 and above */
  global $wp_version, $wpdb;
  if (version_compare($wp_version, '2.1', '>=') == FALSE) {
    add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error fade"><p><strong>' . __('Sorry, Popupper works only under WordPress 2.1 or higher', 'popupper-v10') . '</strong></p></div>\';'));
    return;
  }
  /* i18n support */
  if (version_compare($wp_version, '2.6', '<')) {
    load_plugin_textdomain("popupper-v10",
                           PLUGINDIR . "/" . dirname(plugin_basename(__FILE__)) . "/i18n");
  }
  else {
    load_plugin_textdomain("popupper-v10",
                           PLUGINDIR . "/" . dirname(plugin_basename(__FILE__)),
                           "popupper-v10/i18n");
  }

  function popupper_plugin_init() {
    /* actions observe events */
    add_action('wp_head',              'popupper_action__wp_head');
    add_action('admin_head',           'popupper_action__admin_head');
    add_action('admin_print_scripts',  'popupper_action__admin_print_scripts');
    add_action('save_post',            'popupper_action__save_post', 99, 2);
    /* filters can alter content */
    add_filter('the_content',          'popupper_filter__the_content', 99);
    if (current_user_can('edit_posts') || current_user_can('edit_pages')) {
      if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_buttons',          'popupper_filter__mce_buttons');
        add_filter('mce_external_plugins', 'popupper_filter__mce_external_plugins');			
      }
      else {
        /* sorry no mce2 support */
      }
    }
  }

  /************************************
   * popupper actions
   ************************************/
  function popupper_action__admin_print_scripts() {
    global $editing;
    if (!isset($editing) || !$editing || !user_can_richedit()) {
      return;
    }
    /* these scripts are now available when the admin edits a page/post */
    wp_enqueue_script('popupper_library', get_option('siteurl') . '/' . PLUGINDIR . '/popupper-v10/scripts/yui2.5.2_tooltip.js', 1);
    wp_enqueue_script('popupper_tinymce', get_option('siteurl') . '/' . PLUGINDIR . '/popupper-v10/scripts/editor_plugin.js', 1);
  }

  /** puts the needed .css and .js on the posts/pages that need to render posts */
  function popupper_action__wp_head() {
    echo  '<link rel="stylesheet" type="text/css" href="' . get_option('siteurl') . '/' . PLUGINDIR . '/popupper-v10/css/yui2.5.2_tooltip.css" media="screen" />';
    echo "\n";
    echo  '<script src="' . get_option('siteurl') . '/' . PLUGINDIR . '/popupper-v10/scripts/yui2.5.2_tooltip.js"></script>';
    echo "\n";
  }
	
  /** puts the needed .css on the pages that edit posts/pages */
  function popupper_action__admin_head() {
    echo  '<link rel="stylesheet" type="text/css" href="' . get_option('siteurl') . '/' . PLUGINDIR . '/popupper-v10/css/yui2.5.2_tooltip.css" media="screen" />';
    echo "\n<script type=\"text/javascript\">\n";
    echo "//<![CDATA[\n";
    echo "var _popupper_v10 = {\n";
    echo "        i18n: {\n";
    echo "          title: \"" . js_escape(__("Create Popup", 'popupper-v10')) . "\"\n";
    echo "        }\n";
    echo "};\n";
    echo "//]]>\n";
    echo "</script>\n";
  }

  function popupper_action__save_post($post_id, $post) {
    global $wp_version, $wpdb;
    /* autosave and other things appear to bump the post_id */
    $sql_get_real_post_id = "SELECT post_parent FROM " . $wpdb->posts . " WHERE ID = " . $post_id;
    $post_id_answer = $wpdb->get_results($sql_get_real_post_id);
    if ($post_id_answer[0]->post_parent != 0) {
      $post_id = $post_id_answer[0]->post_parent;
    }
    /* examine content of $post for negative ctxs
     * - if found replace them in the database with the post_id 
     * - remove the negative number from the anchors
     */
    $find_token = '<a id="ctx_';
    $startpos = 0;
    do {
      $pos = strpos($post->post_content, $find_token, $startpos);
      if ($pos !== false) {
        $substring = substr($post->post_content, $pos);
        /* look for the ctx_ anchor */
        if (version_compare($wp_version, '2.8', '>=')) {
            /* in WP 2.8, post_id suddenly becomes an unsigned int... thanks for nothing. Guess I should created my own database but I was trying to play nice.
             * so now start inserting from post_id 100000000000000000 forward. If you have posted more times than the age of the universe in seconds this
             * hack won't work for you. */
            $n = sscanf($substring, "<a id=\"ctx_%u\">", $uniq_key);
            if ($n == 1) {
              $huge_num = 100000000000000000;
              $uniq_key += $huge_num; 
              /* update the database */
              $sql_update_statement = sprintf("UPDATE %s SET post_id = %u WHERE post_id = %u AND meta_key = '_popupper_ctx'", $wpdb->postmeta, $post_id, $uniq_key);
              $wpdb->query($sql_update_statement);
            }
        }
        else {
            $n = sscanf($substring, "<a id=\"ctx_%d\">", $uniq_key);
            if ($n == 1) {
              $uniq_key *= -1;
              /* update the database */
              $sql_update_statement = "UPDATE " . $wpdb->postmeta . " SET post_id = " . $post_id . " WHERE post_id = " . $uniq_key . " AND meta_key = '_popupper_ctx'";
              $wpdb->query($sql_update_statement);
            }
        }
      }
      $startpos = $pos + 1;
    } while ($pos !== false);
  }

  /************************************
  * popupper filters
  ************************************/
	 
  /** connect the popupper button to its functionality */
  function popupper_filter__mce_external_plugins($plugins) {
    /* try to address issues that the plugin doesn't appear for some browsers */
    /* $plugins['popupper'] = get_option('siteurl') . '/' . PLUGINDIR . '/popupper-v10/scripts/editor_plugin.js'; */
    $plugins['popupper'] = WP_CONTENT_URL . '/plugins/popupper-v10/scripts/editor_plugin.js';
    return $plugins;
  }
    
  /** put a button on the wordpress 2.5+ editor */
  function popupper_filter__mce_buttons($buttons) {
    /* I'd like to put a separator here, and most plugins use 'separator'
     * to mean a separator. It appears that Wordpress actually uses '|'.
     * Also, I think popupper is getting burned interacting with other plugins
     * possibly due to the separator. */
    /* array_push($buttons, 'separator', 'popupper'); */
    array_push($buttons, 'popupper');
    return $buttons;
  }
	
  function popupper_filter__the_content($content = '') {
    /* this filter is run when displaying a post,
     * look to see if there was any popup metadata that needs to be put in the posting */
    if ($keys = get_post_custom_keys()) {
      foreach ($keys as $key) {
        $keyt = trim($key);
        if ("_popupper_ctx" == $keyt) {
          $content .= "<script type='text/javascript'><!--\n";
          $values = array_map('trim', get_post_custom_values($key));
          foreach ($values as $value) {
            $content .= "//------\n";
            $content .= $value;
            $content .= "\n//------\n";
          }
          $content .= "//--></script>\n";
        }
      }
    }
    return $content;
  }

  add_action('init', 'popupper_plugin_init');

?>
