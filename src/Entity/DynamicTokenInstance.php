<?php

namespace Drupal\dynamic_token_manager\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the DynamicTokenInstance config entity.
 *
 * @ConfigEntityType(
 *   id = "dynamic_token_instance",
 *   label = @Translation("Dynamic token instance"),
 *   handlers = {
 *     "list_builder" = "Drupal\dynamic_token_manager\Entity\DynamicTokenInstanceListBuilder",
 *     "form" = {
 *       "add" = "Drupal\dynamic_token_manager\Form\DynamicTokenInstanceForm",
 *       "edit" = "Drupal\dynamic_token_manager\Form\DynamicTokenInstanceForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider"
 *     }
 *   },
 *   admin_permission = "administer dynamic tokens",
 *   config_prefix = "dynamic_token_instance",
 *   config_export = {
 *     "id",
 *     "label",
 *     "status",
 *     "plugin",
 *     "speed",
 *     "plugin_config"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "status" = "status"
 *   },
 *   links = {
 *     "collection" = "/admin/config/content/dynamic-tokens",
 *     "add-form" = "/admin/config/content/dynamic-tokens/add",
 *     "edit-form" = "/admin/config/content/dynamic-tokens/{dynamic_token_instance}",
 *     "delete-form" = "/admin/config/content/dynamic-tokens/{dynamic_token_instance}/delete"
 *   }
 * )
 */
class DynamicTokenInstance extends ConfigEntityBase {

  protected $id;
  protected $label;
  protected $status = TRUE;
  protected $plugin;
  protected $speed = 5;
  protected $plugin_config = [];

  public function getPlugin(): string { return (string) $this->plugin; }
  public function getSpeed(): int { return (int) $this->speed; }
  public function getPluginConfig(): array { return (array) $this->plugin_config; }
  public function setPluginConfig(array $cfg): self { $this->plugin_config = $cfg; return $this; }

}
