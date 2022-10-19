<?php

/**
 * @file
 * Contains element info hooks.
 */

use Drupal\my_front\Render\Element\MyViewElement;

/**
 * Implements hook_element_info_alter().
 */
function my_front_element_info_alter(&$types) {
  if (isset($types['view'])) {
    $types['view']['#pre_render'] = $types['view']['#pre_render'] ?? [];
    // Fix title is an array in Off canvas dialog.
    // Remove when fixed.
    // @see https://www.drupal.org/project/drupal/issues/2663316
    $types['view']['#pre_render'][] = MyViewElement::class . '::preRender';
  }
}
