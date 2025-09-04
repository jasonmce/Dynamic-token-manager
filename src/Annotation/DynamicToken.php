<?php

namespace Drupal\dynamic_token_manager\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a DynamicToken annotation object.
 *
 * @Annotation
 */
class DynamicToken extends Plugin {
  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the plugin.
   *
   * @var \Drupal\Core\StringTranslation\TranslatableMarkup|string
   */
  public $label;
}
