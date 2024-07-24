<?php

namespace Drupal\product_review\Entity;
use Drupal\Core\Entity\ContentEntityTypeInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorageSchema;
class ProductReviewStorageSchema extends SqlContentEntityStorageSchema {

  protected function getEntitySchema(ContentEntityTypeInterface $entity_type, $reset = FALSE) {
    $schema = parent::getEntitySchema($entity_type, $reset);

    // Modify the schema for your field to allow NULL values
    $schema['product_review_field_data']['fields']['rating__rating']['not null'] = FALSE;
    $schema['product_review_field_data']['fields']['rating__target']['not null'] = FALSE;
    $schema['product_review_field_data']['fields']['feedback']['not null'] = FALSE;

    return $schema;
  }
}
