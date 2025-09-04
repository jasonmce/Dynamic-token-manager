<?php

namespace Drupal\dynamic_token_manager\Entity;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * List builder for DynamicTokenInstance.
 */
class DynamicTokenInstanceListBuilder extends ConfigEntityListBuilder {

  public function buildHeader() {
    $header['label'] = $this->t('Name');
    $header['id'] = $this->t('Machine name');
    $header['plugin'] = $this->t('Plugin');
    $header['speed'] = $this->t('Speed (s)');
    $header['status'] = $this->t('Status');
    return $header + parent::buildHeader();
  }

  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\dynamic_token_manager\Entity\DynamicTokenInstance $entity */
    $row['label'] = Link::fromTextAndUrl($entity->label(), Url::fromRoute('entity.dynamic_token_instance.edit_form', ['dynamic_token_instance' => $entity->id()]));
    $row['id'] = $entity->id();
    $row['plugin'] = $entity->get('plugin');
    $row['speed'] = $entity->get('speed');
    $row['status'] = $entity->status() ? $this->t('Enabled') : $this->t('Disabled');
    return $row + parent::buildRow($entity);
  }

}
