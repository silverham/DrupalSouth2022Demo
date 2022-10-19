<?php

/**
 * @file
 * Contains block hooks.
 */

/**
 * Implements hook_preprocess_block().
 */
function my_front_preprocess_block(&$variables, $hook) {
  // Layout builder logic.
  if (\Drupal::moduleHandler()->moduleExists('layout_builder') && isset($variables['plugin_id'])) {
    // Add classes to field block.
    if ((strpos($variables['plugin_id'], 'field_block:') === 0) && isset($variables['content']['#theme'])
      && ($variables['content']['#theme'] == 'field')) {

      $variables['attributes']['class'][] = "lb--field--name-{$variables['content']['#field_name']}";
      $variables['attributes']['class'][] = "lb--field--type-{$variables['content']['#field_type']}";
      $variables['attributes']['class'][] = "lb--field--label-{$variables['content']['#label_display']}";
    }
  }
}

