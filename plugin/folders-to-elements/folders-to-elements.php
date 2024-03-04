<?php

/*
Plugin Name: Folders to Elements 
Description: Generates elements on your website to represent folders on the server.
Author: Alexander Marini
*/

// Uncomment line below for testing in a different environment.
// header('Access-Control-Allow-Origin: *');

function add_plugin_css()
{
   $css_url = plugins_url('project-cards.css', __FILE__);

   wp_enqueue_style('project-cards', $css_url, array(), null);
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

         $elements .= "<div class='projectCard'></div>";
      }

      $elements .= "</div>";
   } else {
      $elements = "<h4 class='emptyContent'>Ingenting att visa här ännu...</h4>";
   }

   return $elements;
}

add_shortcode('folders_to_elements', 'folders_to_elements');
