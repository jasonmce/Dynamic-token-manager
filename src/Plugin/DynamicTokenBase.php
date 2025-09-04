<?php

namespace Drupal\dynamic_token_manager\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Datetime\TimeInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dynamic_token_manager\Entity\DynamicTokenInstance;

/**
 * Base class for DynamicToken plugins.
 */
abstract class DynamicTokenBase extends PluginBase implements DynamicTokenInterface {

  /** @var \Drupal\Core\Datetime\TimeInterface */
  protected $time;

  public function __construct(array $configuration = [], $plugin_id = '', $plugin_definition = []) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->time = \Drupal::time();
  }

  /** @var \Drupal\dynamic_token_manager\Entity\DynamicTokenInstance */
  protected $instance;

  
  public function setInstance(DynamicTokenInstance $instance): void {
    $this->instance = $instance;
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state, array $config): array {
    return $form;
  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state): array {
    return [];
  }

  public function spanExtraAttributes(): array {
    return [];
  }

  protected function speed(): int {
    return (int) $this->instance->get('speed');
  }

  protected function cfg(): array {
    return (array) $this->instance->get('plugin_config');
  }

  protected function instanceId(): string {
    return $this->instance->id();
  }

  protected function requestTime(): int {
    return (int) $this->time->getRequestTime();
  }

}
