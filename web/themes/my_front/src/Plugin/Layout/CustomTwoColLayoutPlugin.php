<?php

namespace Drupal\my_front\Plugin\Layout;

use Drupal\Core\Entity\Display\EntityDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\SubformStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\layout_builder\DefaultsSectionStorageInterface;

/**
 * Custom default layout class plugin.
 */
class CustomTwoColLayoutPlugin extends CustomMultiWidthLayoutBasePlugin {

  /**
   * {@inheritdoc}
   */
  protected function getWidthOptions() {
    return [
      '50-50' => '50%/50%',
      '33-67' => '33%/67%',
      '67-33' => '67%/33%',
      '25-75' => '25%/75%',
      '75-25' => '75%/25%',
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultWidth() {
    return '50-50';
  }

}
