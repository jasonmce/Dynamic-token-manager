<?php

namespace Drupal\dynamic_token_manager\Plugin;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\dynamic_token_manager\Annotation\DynamicToken;

/**
 * Manages discovery and instantiation of DynamicToken plugins.
 *
 * @method \Drupal\dynamic_token_manager\Plugin\DynamicTokenInterface createInstance($plugin_id, array $configuration = [])
 */
class DynamicTokenManager extends DefaultPluginManager {

  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/DynamicToken', $namespaces, $module_handler, DynamicTokenInterface::class, DynamicToken::class);
    $this->alterInfo('dynamic_token_info');
    $this->setCacheBackend($cache_backend, 'dynamic_token_plugins');
    // No setFactory* calls for cross-version (D10/D11) compatibility.
  }

  /**
   * Creates a plugin instance and injects the DynamicTokenInstance if provided.
   */
  public function createWithInstance(string $plugin_id, $instance, array $configuration = []) : DynamicTokenInterface {
    /** @var \Drupal\dynamic_token_manager\Plugin\DynamicTokenInterface $plugin */
    $plugin = $this->createInstance($plugin_id, $configuration);
    if (method_exists($plugin, 'setInstance') && $instance) {
      $plugin->setInstance($instance);
    }
    return $plugin;
  }
}
