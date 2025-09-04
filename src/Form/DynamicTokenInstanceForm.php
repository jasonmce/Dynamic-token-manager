<?php

namespace Drupal\dynamic_token_manager\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dynamic_token_manager\Entity\DynamicTokenInstance;
use Drupal\dynamic_token_manager\Plugin\DynamicTokenManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form handler for DynamicTokenInstance add/edit.
 */
class DynamicTokenInstanceForm extends EntityForm {

  protected DynamicTokenManager $pluginManager;
  protected $entityTypeManager;

  public static function create(ContainerInterface $container) {
    $instance = new static();
    $instance->pluginManager = $container->get('plugin.manager.dynamic_token');
    $instance->entityTypeManager = $container->get('entity_type.manager');
    return $instance;
  }

  public function form(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\dynamic_token_manager\Entity\DynamicTokenInstance $entity */
    $entity = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => $entity->label(),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $entity->id(),
      '#required' => TRUE,
      '#machine_name' => [
        'exists' => [get_class($this), 'exists'],
      ],
      '#disabled' => !$entity->isNew(),
    ];
    $form['speed'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Speed'),
      '#default_value' => $entity->getSpeed(),
      '#required' => TRUE,
      '#description' => $this->t('The number of seconds between updates.'),
    ];
    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $entity->status(),
    ];

    
    $definitions = $this->pluginManager->getDefinitions();
    $options = [];
    foreach ($definitions as $id => $def) {
      $options[$id] = (string) $def['label'];
    }

    // Resolve a plugin id.
    $plugin_id = $entity->get('plugin');
    if ($entity->isNew()) {
      $current = $form_state->getValue('plugin');
      if ($current) {
        $plugin_id = $current;
      }
      elseif (function_exists('array_key_first')) {
        $plugin_id = array_key_first($options);
      }
      else {
        reset($options); $plugin_id = key($options);
      }
    }

    // Show plugin field. Editable on add; read-only on edit with hidden value.
    if ($entity->isNew()) {
      $form['plugin'] = [
        '#type' => 'select',
        '#title' => $this->t('Plugin'),
        '#options' => $options,
        '#default_value' => $plugin_id,
        '#required' => TRUE,
        '#ajax' => [
          'callback' => '::ajaxRebuild',
          'wrapper' => 'dynamic-token-plugin-config',
          // Prevent validating unrelated fields during AJAX change.
          '#limit_validation_errors' => [],
        ],
        '#description' => $this->t('Choose the token behavior.'),
      ];
    }
    else {
      $label = isset($definitions[$plugin_id]['label']) ? (string) $definitions[$plugin_id]['label'] : $plugin_id;
      $form['plugin_display'] = [
        '#type' => 'item',
        '#title' => $this->t('Plugin'),
        '#markup' => $label,
      ];
      $form['plugin'] = [
        '#type' => 'hidden',
        '#value' => $plugin_id,
      ];
    }

    // Plugin-specific config container.
    $form['plugin_config'] = [
      '#type' => 'details',
      '#title' => $this->t('Plugin configuration'),
      '#open' => TRUE,
      '#tree' => TRUE,
      '#prefix' => '<div id="dynamic-token-plugin-config">',
      '#suffix' => '</div>',
    ];

    if ($plugin_id && isset($definitions[$plugin_id])) {
      $plugin = $this->pluginManager->createWithInstance($plugin_id, $entity);
      $elements = $plugin->buildConfigurationForm([], $form_state, (array) $entity->get('plugin_config'));
      if (is_array($elements)) {
        foreach ($elements as $k => $v) {
          $form['plugin_config'][$k] = $v;
        }
      }
    }
    else {
      $form['plugin_config']['help'] = [
        '#markup' => $this->t('Select a valid plugin to configure its settings.'),
      ];
    }

    return $form;
  }


  public static function exists($id) {
    return \Drupal::entityTypeManager()->getStorage('dynamic_token_instance')->load($id) !== NULL;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    if ((int) $form_state->getValue('speed') < 1) {
      $form_state->setErrorByName('speed', $this->t('Speed must be at least 1.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\dynamic_token_manager\Entity\DynamicTokenInstance $entity */
    $entity = $this->entity;
    $entity->set('label', $form_state->getValue('label'));
    if ($entity->isNew()) {
      $entity->set('id', $form_state->getValue('id'));
      $entity->set('plugin', $form_state->getValue('plugin'));
    }
    $entity->set('status', (bool) $form_state->getValue('status'));
    $entity->set('speed', (int) $form_state->getValue('speed'));

    $plugin_id = $entity->get('plugin') ?: $form_state->getValue('plugin');
    if (!$plugin_id) {
      $definitions = $this->pluginManager->getDefinitions();
      $options = [];
      foreach ($definitions as $id => $def) { $options[$id] = (string) $def['label']; }
      $plugin_id = function_exists('array_key_first') ? array_key_first($options) : (is_array($options) ? (function($o){reset($o); return key($o);} )($options) : NULL);
    }
    $plugin = $this->pluginManager->createWithInstance($plugin_id, $entity);
    $cfg = $plugin->submitConfigurationForm($form, $form_state);
    $entity->setPluginConfig($cfg);

    $entity->save();
    parent::submitForm($form, $form_state);
  }


  /**
   * AJAX callback to rebuild the form when plugin changes.
   */
  public static function ajaxRebuild(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    return $form['plugin_config'] ?? $form;
  }
}
