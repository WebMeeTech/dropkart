<?php

declare(strict_types=1);

namespace Drupal\product_review;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for the product review entity type.
 */
final class ProductReviewListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['id'] = $this->t('ID');
//    $header['label'] = $this->t('Label');
    $header['rating'] = $this->t('Rating');
    $header['comment'] = $this->t('Comment');
    $header['uid'] = $this->t('Author');
    $header['created'] = $this->t('Created');
    $header['changed'] = $this->t('Updated');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var \Drupal\product_review\ProductReviewInterface $entity */
    $row['id'] = $entity->id();
    $row['rating'] = $entity->get('rating');
    $row['comment'] = $entity->get('comment');


//    $row['label'] = $entity->toLink();
    $username_options = [
      'label' => 'hidden',
      'settings' => ['link' => $entity->get('uid')->entity->isAuthenticated()],
    ];
    $row['uid']['data'] = $entity->get('uid')->view($username_options);
    $row['created']['data'] = $entity->get('created')->view(['label' => 'hidden']);
    $row['changed']['data'] = $entity->get('changed')->view(['label' => 'hidden']);
    return $row + parent::buildRow($entity);
  }

}
