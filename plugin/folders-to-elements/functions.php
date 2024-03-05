<?php

/*
Plugin Name: Folders to Elements 
Description: Generates elements on your website to represent folders on the server.
Author: Alexander Marini
*/

// Uncomment line below for testing in a different environment.
// header('Access-Control-Allow-Origin: *');

function add_plugin_js()
{
   $js_url = plugins_url('blur-effect.js', __FILE__);

   $js_file_path = plugin_dir_path(__FILE__) . 'blur-effect.js';
   $js_version = filemtime($js_file_path);

   $js_url = add_query_arg('ver', $js_version, $js_url);

   wp_enqueue_script('blur-effect', $js_url, array(), $js_version);
}

add_action('wp_enqueue_scripts', 'add_plugin_js');

function add_plugin_css()
{
   $css_url = plugins_url('styles.css', __FILE__);

   $css_file_path = plugin_dir_path(__FILE__) . 'styles.css';
   $css_version = filemtime($css_file_path);

   $css_url = add_query_arg('ver', $css_version, $css_url);

   wp_enqueue_style('project-cards', $css_url, array(), $css_version);
}

add_action('wp_enqueue_scripts', 'add_plugin_css');

function folders_to_elements($atts)
{
   $atts = shortcode_atts(
      array(
         'program' => null,
         'year' => null
      ),
      $atts,
      'folders_to_elements'
   );

   $program = $atts['program'];
   $year = $atts['year'];

   if (isset($program)) {
      $elements = "<div class='projectCardsContainer'>";

      // Base for URLs relative to the page document.
      $page_relative_base = "../$program";

      if (isset($year)) {
         $script_relative_base = plugin_dir_path(__FILE__) . "../../../$program/arskurs-$year";
         $year_dynamic_path = "$page_relative_base/arskurs-$year";
      } else {
         $script_relative_base = plugin_dir_path(__FILE__) . "../../../$program";
         $year_dynamic_path = "$page_relative_base";
      }

      $folders = array_filter(glob("$script_relative_base/*", GLOB_ONLYDIR), 'is_dir');

      foreach ($folders as $folder) {
         $folder_name = basename($folder);

         $data = json_decode(file_get_contents("$folder/settings.json"), true);
         $author = $data['author'];
         $title = $data['title'];
         $description = $data['description'];

         if ($data['altLink'] != null) {
            $link = $data['altLink'];
         } else {
            $link = "$year_dynamic_path/$folder_name";
         }

         if (file_exists("$folder/icon.png")) {
            $icon = "$year_dynamic_path/$folder_name/icon.png";
         } else {
            $icon = "$page_relative_base/standard-icon.png";
         }

         $elements .= "
            <a class='projectCard' href='$link' target='_blank'>
               <div class='cardIcon' style='background: center / contain no-repeat url($icon)'></div>
               <div class='cardText'>
                  <h3 class='title'>$title</h3>
                  <h4 class='author'>$author</h4>
                  <p>$description</p>
               </div>
            </a>
         ";
      }

      $elements .= "</div>";
   } else {
      $elements = "<h4 class='emptyContent'>Ingenting att visa här ännu...</h4>";
   }

   return $elements;
}

add_shortcode('folders_to_elements', 'folders_to_elements');
