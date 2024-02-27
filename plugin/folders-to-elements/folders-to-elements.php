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
         'program' => null,
         'year' => null
      ),
      $atts,
      'foldersToElements'
   );

   $program = $atts['program'];
   $year = $atts['year'];

   $elements = "";

   if (isset($program)) {
      // Base to find folders relative to this plugin.
      $scriptRelativeBase = plugin_dir_path(__FILE__) . "../../../$program/arskurs-$year";
      $folders = array_filter(glob("$scriptRelativeBase/*", GLOB_ONLYDIR), 'is_dir');

      // Base for URLs relative to the page document.
      $pageRelativeBase = "../$program";

      $counter = 0;

      foreach ($folders as $folder) {
         $counter++;

         if ($counter % 3 == 1) {
            if ($counter != 1) {
               $elements .= "</div>";
            }
            $elements .= "<div class='wp-block-columns alignwide is-layout-flex wp-container-core-columns-layout-1 wp-block-columns-is-layout-flex'>";
         }

         $folderName = basename($folder);
         $link = null;
         $icon = null;

         $data = json_decode(file_get_contents("$folder/settings.json"), true);
         $author = $data['author'];
         $title = $data['title'];
         $description = $data['description'];

         if ($data['altLink'] != null) {
            $link = $data['altLink'];
         } else {
            $link = "$pageRelativeBase/arskurs-$year/$folderName";
         }

         if (file_exists("$folder/icon.png")) {
            $icon = "$pageRelativeBase/arskurs-$year/$folderName/icon.png";
         } else {
            $icon = "$pageRelativeBase/standard-icon.png";
         }

         $elements .= "
            <div class='wp-block-column is-layout-flow wp-block-column-is-layout-flow'>
               <div style='height: 45px' aria-hidden='true' class='wp-block-spacer'></div>
               <figure class='wp-block-image aligncenter size-full is-resized is-style-rounded'>
                  <a href='$link' target='_blank' style='text-decoration: none'>
                     <img src='$icon' class='wp-image-82' style='width: 275px; height: auto'/>
                  </a>
               </figure>
               <h3 class='wp-block-heading has-text-align-center'>
                  <a href='$link' target='_blank' style='text-decoration: none'>
                     $title
                  </a>
               </h3>
               <h4 class='wp-block-heading has-text-align-center' style='margin-top: 5px;'>
                  <a href='$link' target='_blank' style='text-decoration: none'>
                     $author
                  </a>
               </h4>
               <p>
                  <a href='$link' target='_blank' style='text-decoration: none'>
                     $description
                  </a>
               </p>
            </div>
         ";
      }

      $elements .= "</div>";
   } else {
      $elements .= "<h4 style='text-align: center; margin-top: 40px'>Ingenting att visa här ännu...</h4>";
   }

   return $elements;
}

wp_add_inline_style('wp-block-columns', '.wp-block-columns{align-items:normal!important;box-sizing:border-box;display:flex;flex-wrap:wrap!important}@media (min-width:782px){.wp-block-columns{flex-wrap:nowrap!important}}.wp-block-columns.are-vertically-aligned-top{align-items:flex-start}.wp-block-columns.are-vertically-aligned-center{align-items:center}.wp-block-columns.are-vertically-aligned-bottom{align-items:flex-end}@media (max-width:781px){.wp-block-columns:not(.is-not-stacked-on-mobile)>.wp-block-column{flex-basis:100%!important}}@media (min-width:782px){.wp-block-columns:not(.is-not-stacked-on-mobile)>.wp-block-column{flex-basis:0;flex-grow:1}.wp-block-columns:not(.is-not-stacked-on-mobile)>.wp-block-column[style*=flex-basis]{flex-grow:0}}.wp-block-columns.is-not-stacked-on-mobile{flex-wrap:nowrap!important}.wp-block-columns.is-not-stacked-on-mobile>.wp-block-column{flex-basis:0;flex-grow:1}.wp-block-columns.is-not-stacked-on-mobile>.wp-block-column[style*=flex-basis]{flex-grow:0}:where(.wp-block-columns){margin-bottom:1.75em}:where(.wp-block-columns.has-background){padding:1.25em 2.375em}.wp-block-column{flex-grow:1;min-width:0;overflow-wrap:break-word;word-break:break-word}.wp-block-column.is-vertically-aligned-top{align-self:flex-start}.wp-block-column.is-vertically-aligned-center{align-self:center}.wp-block-column.is-vertically-aligned-bottom{align-self:flex-end}.wp-block-column.is-vertically-aligned-stretch{align-self:stretch}.wp-block-column.is-vertically-aligned-bottom,.wp-block-column.is-vertically-aligned-center,.wp-block-column.is-vertically-aligned-top{width:100%}');

add_shortcode('foldersToElements', 'foldersToElements');
