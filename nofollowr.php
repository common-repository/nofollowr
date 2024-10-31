<?php
/*
Plugin Name: NoFollowr
Plugin URI: https://nofollowr.com
Description:When logged in as an administrator, green "tick" and red "stop" icons appear next to all external links in a post indicating whether rel="nofollow" is currently applied to them. Simply click an icon to toggle between these two states and alter the link's nofollow status. This change is remotely reflected in the database without requiring a page reload.
Version: 1.2.0
Author: Joel Birch
Author URI: https://nofollowr.com
License: GPLv2
*/

/*  Copyright 2017  Joel Birch  (email : joeldbirch@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class NoFollowr {

  private static $trigger = 'nofollowr';
  private static $version = '1.2.0';
  private static $initiated = false;

  public static function plugin_activation()
  {
    if ( is_admin() ) {
      $exit_msg=' <style media="screen">
          * {
            font: bold 12px/1 "Lucida Grande", Verdana, Arial,"Bitstream Vera Sans", sans-serif;
            margin: 0;
            padding: 0;
          }
          p {
            padding: 2em;
            background: white;
            border: 1px solid #E6DB55;
          }
        </style>
        <p>NoFollowr requires WordPress 3.2 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update.</a></p>';
      if (version_compare(get_bloginfo('version'), '3.2', '<')) {
        exit ($exit_msg);
      }
    }
  }

  public static function init() {
    if ( ! self::$initiated ) {
      self::$initiated = true;

      if( empty($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
        add_action( 'wp_enqueue_scripts', array('NoFollowr', 'add_assets') );
        add_filter( 'the_content', array('NoFollowr', 'wrap_content') );
      } else if( strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        add_filter( 'query_vars', array('NoFollowr', 'add_trigger') );
        add_action( 'template_redirect', array('NoFollowr', 'trigger_check') );
      }
    }
  }

  public static function add_assets() {
    if (!is_admin() && current_user_can( 'manage_options' ) ) {
      $plugin_url = plugins_url().'/'.str_replace(basename( __FILE__), '', plugin_basename(__FILE__));

      wp_enqueue_style( 'NoFollowr', $plugin_url.'css/NoFollowr-min.css', null, self::$version );
      wp_enqueue_script('NoFollowr', $plugin_url.'js/NoFollowr-min.js', array('jquery'), self::$version, true);

      wp_localize_script( 'NoFollowr', 'jbirchPlugin', array(
        'ajaxURL' => get_bloginfo('url') . '/?' . self::$trigger . '=1',
        'postSelector' => '.jbPost'
      ) );
    }
  }

  public static function wrap_content( /*.string.*/$content ) {
    global $post;
    if ( current_user_can('manage_options') ) {
      $content = "<div id=\"jbID-$post->ID\" class=\"jbPost\">$content</div>";
    }
    return $content;
  }

  public static function add_trigger( /*.array.*/$vars ) {
      $vars[] = self::$trigger;
      return $vars;
  }

  public static function add_nofollow_callback( /*.array.*/$matches ) {
    $orig = $matches[0];
    $matches[0] = preg_replace( "| rel=([\"\']??)([^\"\'>]*?)\\1|siU" , ' rel="$2 nofollow"', $matches[0]);
    return ($matches[0] === $orig ) ? (string) stripslashes(wp_rel_nofollow($matches[0])) : (string) $matches[0];
  }

  private static function add_nofollow( /*.string.*/$href, /*.string.*/$content ){
    return preg_replace_callback(
      '|<a\s[^>]*href=([\"\']??)('.preg_quote($href).'[^\" >]*?)\\1[^>]*>(.*)<\/a>|siU',
      array('NoFollowr', 'add_nofollow_callback'),
      $content
    );
  }

  public static function remove_nofollow_callback( /*.array.*/$matches ) {
    $rel = array();
    preg_match( '| rel=[\"\'](.+)[\"\']|si', $matches[0], $rel);
    $remainingVal = str_replace( 'nofollow', '', $rel[1] );
    $remainingVal = trim($remainingVal);
    $replacement = ($remainingVal==='') ? '' : ' rel="'.(string) $remainingVal.'"';
    return str_replace( $rel[0], $replacement, $matches[0]);
  }

  private static function remove_nofollow( /*.string.*/$href, /*.string.*/$content ) {
    return preg_replace_callback(
      '|<a\s[^>]*href=([\"\']??)('.preg_quote($href).'[^\" >]*?)\\1[^>]*>(.*)<\/a>|siU',
      array('NoFollowr', 'remove_nofollow_callback'),
      $content
    );
  }

  /**
   * validates $_POST data and return array of the bits we need
   * @param array $post_data the raw $_POST global array
   * @return array
   */
  private static function validate( /*.array.*/$post_data ) {
    if (
      key_exists('postId', $post_data)
      && key_exists('changeToNofollow', $post_data)
      && key_exists('href', $post_data)
      && is_numeric($post_data['postId'])
      && current_user_can('edit_post', $post_data['postId'])
    ) {
      return array(
        'changeToNofollow' => sanitize_text_field($_POST['changeToNofollow']),
        'href' => sanitize_text_field($_POST['href']),
        'postId' => sanitize_text_field($_POST['postId']),
      );
    } else {
      return array();
    }
  }

  /**
   * handles the ajax request to alter the post
   * @return string a message echoed to back to the JavaScript: 'success' or 'failed'
   */
  private static function ajaxHandler() {
    $data = self::validate($_POST);
    if ( empty($data) ) return 'failed (no data)' . json_encode($data);

    // Get the content
    $thepost = get_posts(array(
      'include' => $data['postId'],
      'post_status' => 'any',
      'post_type' => 'any'
    ));

    if ( count($thepost) !== 1) return 'failed (no post found)';

    $thecontent = $thepost[0]->post_content;

    if ('true' === $data['changeToNofollow']) {
      $thecontent = self::add_nofollow($data['href'], $thecontent);
    } else {
      $thecontent = self::remove_nofollow($data['href'], $thecontent);
    }

    // Update post
    $my_post = array(
      'ID' => $data['postId'],
      'post_content' => $thecontent
    );

    // Update the post into the database
    return wp_update_post( $my_post ) ? 'success' : 'failed' . print_r($my_post);
  }

  public static function trigger_check() {
    if(intval(get_query_var(self::$trigger)) == 1) {
      echo self::ajaxHandler();
      exit;
    }
  }

} //end class

register_activation_hook( __FILE__, array( 'NoFollowr', 'plugin_activation' ) );
add_action( 'init', array( 'NoFollowr', 'init' ) );

