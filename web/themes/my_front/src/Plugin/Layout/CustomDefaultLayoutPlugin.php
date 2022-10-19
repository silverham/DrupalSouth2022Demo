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
class CustomDefaultLayoutPlugin extends LayoutDefault implements PluginFormInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'custom_entity_bundle' => '',
      'custom_entity_type' => '',
      'custom_entity_view_mode' => '',
      'custom_extra_classes' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $configuration = $this->getConfiguration();
    // Add section name, so we know what we are editing.
    $form['custom_display_only_title'] = [
      '#type' => 'item',
      '#title' => $this->t('@title <br /> (%machine_name)', [
        '@title' => $this->getPluginDefinition()->getLabel(),
        '%machine_name' => $this->getPluginId(),
      ]),
    ];
    $form['custom_extra_classes'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom: Extra classes'),
      '#default_value' => $configuration['custom_extra_classes'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::validateConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $extra_properties = $this->getCustomExtraProperties($form_state);
    $this->configuration['custom_entity_bundle'] = $extra_properties['bundle'];
    $this->configuration['custom_entity_type'] = $extra_properties['entity_type'];
    $this->configuration['custom_entity_view_mode'] = $extra_properties['view_mode'];
    $this->configuration['custom_extra_classes'] = $form_state->getValue('custom_extra_classes');
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);

    // Extract short variable names.
    $entity_type = $this->configuration['custom_entity_type'];
    $bundle = $this->configuration['custom_entity_bundle'];
    $view_mode = $this->configuration['custom_entity_view_mode'];

    // Ensure array exists.
    $build['#attributes'] = $build['#attributes'] ?? [];
    $build['#attributes']['class'] = $build['#attributes']['class'] ?? [];

    // Add Classes.
    $build['#attributes']['class'][] = $this->getPluginDefinition()->getTemplate();
    $build['#attributes']['class'][] = 'layout';
    $build['#attributes']['class'][] = "layout--{$entity_type}";
    $build['#attributes']['class'][] = "layout--{$entity_type}--type--{$bundle}";
    $build['#attributes']['class'][] = "layout--{$entity_type}--view-mode--{$view_mode}";

    // Add extra classes.
    if ($this->configuration['custom_extra_classes']) {
      $build['#attributes']['class'][] = $this->configuration['custom_extra_classes'];
    }
    return $build;
  }

  /**
   * Helper to get entity type, bundle and view mode.
   *
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *   The current form_state.
   *
   * @return array
   *   The properties, keyed by bundle, entity_type and view_mode.
   */
  protected function getCustomExtraProperties(FormStateInterface $fs) {
    $return_options = ['bundle' => '','entity_type' => '', 'view_mode' => ''];
    /** @var Drupal\Core\Form\SubformState $complete_form_state */
    $sub_form_state = ($fs instanceof SubformStateInterface) ? $fs : $fs->getCompleteFormState();
    $buid_info = $sub_form_state->getBuildInfo();
    $args = isset($buid_info['args']) ? $buid_info['args'] : [];
    /** @var Drupal\layout_builder\DefaultsSectonStorageInterface $section_storage */
    $section_storage = NULL;
    foreach ($args as $arg) {
      if ($arg instanceof DefaultsSectionStorageInterface) {
        $section_storage = $arg;
        break;
      }
    }
    if ($section_storage && ($context_values = $section_storage->getContextValues())) {
      if (isset($context_values['display'])
        && ($context_values['display'] instanceof EntityDisplayInterface)) {
        $return_options['bundle'] = $context_values['display']->getTargetBundle();
        $return_options['entity_type'] = $context_values['display']->getTargetEntityTypeId();
      }
      if (isset($context_values['view_mode'])) {
        $return_options['view_mode'] = $context_values['view_mode'];
      }
    }
    return $return_options;
  }

}
