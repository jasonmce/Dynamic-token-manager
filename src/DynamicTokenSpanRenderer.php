<?php

namespace Drupal\dynamic_token_manager;

use Drupal\Component\Utility\Html;

/**
 * Renders the standard <span> wrapper for dynamic tokens.
 */
final class DynamicTokenSpanRenderer {

  public static function render(string $instance_id, string $plugin_id, int $speed, string $value, array $extra_attributes = []): string {
    $attrs = [
      'class' => 'dynamic-token',
      'data-token-id' => $instance_id,
      'data-token-type-id' => $plugin_id,
      'data-speed' => (string) max(1, (int) $speed),
      'role' => 'status',
      'aria-live' => 'polite',
    ] + $extra_attributes;

    $parts = [];
    foreach ($attrs as $k => $v) {
      $parts[] = Html::escape($k) . '="' . Html::escape((string) $v) . '"';
    }
    $value_escaped = Html::escape($value);
    return '<span ' . implode(' ', $parts) . '>' . $value_escaped . '</span>';
  }

}
