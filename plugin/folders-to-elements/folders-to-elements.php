<?php

/*
Plugin Name: Folders to Elements 
Description: Generates elements on your website to represent folders on the server.
Author: Alexander Marini
*/

// Uncomment line below for testing in a different environment.
// header('Access-Control-Allow-Origin: *');

function foldersToElements($atts)
{
   $atts = shortcode_atts(
      array(
         'program' => 'aw',
         'year' => '3'
      ),
      $atts,
      'foldersToElements'
   );

   $program = $atts['program'];
   $year = $atts['year'];

   // Base to find folders relative to this plugin.
   $scriptRelativeBase = plugin_dir_path(__FILE__) . "../../../$program/arskurs-$year";
   $folders = array_filter(glob("$scriptRelativeBase/*", GLOB_ONLYDIR), 'is_dir');

   // Base for URLs relative to the page document.
   $pageRelativeBase = "../$program";

   $elements = "<div class='wp-block-columns alignwide is-layout-flex wp-container-core-columns-layout-1 wp-block-columns-is-layout-flex'>";

   foreach ($folders as $folder) {
      $folderName = basename($folder);
      $link = "$pageRelativeBase/arskurs-$year/$folderName";
      $icon = null;

      if (file_exists("$folder/icon.png")) {
         $icon = "$pageRelativeBase/arskurs-$year/$folderName/icon.png";
      } else {
         $icon = "$pageRelativeBase/standard-icon.png";
      }

      $data = json_decode(file_get_contents("$folder/settings.json"), true);
      $author = $data['author'];
      $title = $data['title'];
      $description = $data['description'];

      $elements .= "
         <div class='wp-block-column is-layout-flow wp-block-column-is-layout-flow'>
            <div style='height: 45px' aria-hidden='true' class='wp-block-spacer'></div>
            <figure class='wp-block-image aligncenter size-full is-resized is-style-rounded'>
               <a href='$link' style='text-decoration: none'>
                  <img src='$icon' class='wp-image-82' style='width: 275px; height: auto'/>
               </a>
            </figure>
            <h3 class='wp-block-heading has-text-align-center'>
               <a href='$link' style='text-decoration: none'>
                  $title
               </a>
            </h3>
            <h4 class='wp-block-heading has-text-align-center' style='margin-top: 5px;'>
               <a href='$link' style='text-decoration: none'>
                  $author
               </a>
            </h4>
            <p>
               <a href='$link' style='text-decoration: none'>
                  $description
               </a>
            </p>
         </div>
      ";
   }

   $elements .= "</div>";

   return $elements;
}

add_shortcode('foldersToElements', 'foldersToElements');
