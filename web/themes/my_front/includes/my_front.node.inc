<?php

/**
 * @file
 * Contains node hooks
 */

/**
 * Implements hook_preprocess_node().
 */
function my_theme_preprocess_node(&$variables, $hook) {
  $view_modes_to_hide_title = [
    'view_mode_to_hide',
    'another_view_mode_to_hide',
  ];
  if (in_array($variables['view_mode'], $view_modes_to_hide_title)) {
    $variables['page'] = TRUE;
  }
}


