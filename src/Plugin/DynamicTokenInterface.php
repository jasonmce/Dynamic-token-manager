<?php

namespace Drupal\dynamic_token_manager\Plugin;

use Drupal\Core\Form\FormStateInterface;
use Drupal\dynamic_token_manager\Entity\DynamicTokenInstance;

/**
 * Interface for Dynamic Token plugins.
 */
interface DynamicTokenInterface {

  /**
   * Returns the plugin's current value as a string.
   */
  public function value(): string;

  /**
   * Returns attachments required for client-side updates.
   */
  public function attachments(): array;

  /**
   * Builds plugin-specific configuration form elements.
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state, array $config): array;

  /**
   * Handles plugin-specific submission.
   *
   * @return array
   *   Normalized plugin_config to store on the instance.
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state): array;

  /**
   * Returns extra data-* attributes to add to the span.
   *
   * @return array
   *   E.g., ['data-target-datetime' => '2025-12-01T00:00:00Z']
   */
  public function spanExtraAttributes(): array;

  /**
   * Sets the instance on the plugin (called by the manager).
   */
  public function setInstance(DynamicTokenInstance $instance): void;

}
