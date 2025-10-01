<?php

namespace Drupal\dynamic_token_manager\Plugin\Filter;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Render\Markup;
use Drupal\dynamic_token_manager\DynamicTokenSpanRenderer;
use Drupal\dynamic_token_manager\Plugin\DynamicTokenManager;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Filter(
 *   id = "dynamic_tokens",
 *   title = @Translation("Dynamic Tokens"),
 *   description = @Translation("Replaces [dynamic:{id}] with live-updating spans."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_REVERSIBLE
 * )
 */
final class FilterDynamicTokens extends FilterBase {

  protected EntityTypeManagerInterface $entityTypeManager;
  protected DynamicTokenManager $pluginManager;

  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = \Drupal::entityTypeManager();
    $this->pluginManager = \Drupal::service('plugin.manager.dynamic_token');
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->pluginManager = $container->get('plugin.manager.dynamic_token');
    return $instance;
  }

  public function process($text, $langcode) {
    $result = new FilterProcessResult($text);
    if (!preg_match_all('/\[dynamic:([a-z0-9_]+)\]/', $text, $matches, PREG_SET_ORDER)) {
      return $result;
    }

    $ids = array_values(array_unique(array_map(fn($m) => $m[1], $matches)));
    $instances = $this->entityTypeManager->getStorage('dynamic_token_instance')->loadMultiple($ids);
    if (!$instances) {
      return $result;
    }

    $attachments = ['library' => ['dynamic_token_manager/base']];
    $min_speed = Cache::PERMANENT;
    $tags = [];

    $replacements = [];
    foreach ($instances as $id => $instance) {
      if (!$instance->status()) {
        continue;
      }
      $plugin_id = $instance->get('plugin');
      $plugin = $this->pluginManager->createWithInstance($plugin_id, $instance);
      $value = $plugin->value();
      $attrs = method_exists($plugin, 'spanExtraAttributes') ? $plugin->spanExtraAttributes() : [];

      $span = DynamicTokenSpanRenderer::render($id, $plugin_id, (int) $instance->get('speed'), $value, $attrs);
      $replacements[$id] = $span;

      $speed = (int) $instance->get('speed');
      $min_speed = is_int($min_speed) ? min($min_speed, $speed) : $speed;
      $tags[] = 'dynamic_token_instance:' . $id;

      $attachments = array_merge_recursive($attachments, $plugin->attachments());
    }

    if ($replacements) {
      $processed = preg_replace_callback('/\[dynamic:([a-z0-9_]+)\]/', function($m) use ($replacements) {
        $id = $m[1];
        return $replacements[$id] ?? $m[0];
      }, $text);
      $result->setProcessedText(Markup::create($processed));
    }

    // Set cacheability directly on the FilterProcessResult.
    if (is_int($min_speed)) {
      $result->setCacheMaxAge($min_speed);
    }
    if ($tags) {
      $result->addCacheTags($tags);
    }

    $result->setAttachments($attachments);
    return $result;
  }

}