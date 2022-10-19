<?php

namespace Drupal\my_front\Render\Element;

use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Render\Element\RenderCallbackInterface;

/**
 * MyViewElement helper class.
 */
class MyViewElement implements RenderCallbackInterface {

  /**
   * Fix title is an array in Off canvas dialog.
   *
   * Remove when fixed.
   *
   * @see https://www.drupal.org/project/drupal/issues/2663316
   */
  public static function preRender($element) {
    if (isset($element['#title']) && is_array($element['#title'])) {
      $title_array = $element['#title'];
      $element['#title'] = \Drupal::service('renderer')->renderPlain($title_array);
      // Merge metadat such as #attached and #cache.
      BubbleableMetadata::createFromRenderArray($element)
        ->merge(BubbleableMetadata::createFromRenderArray($title_array))
        ->applyTo($element);
    }
    return $element;
  }

}
