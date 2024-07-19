<?php

declare(strict_types=1);

namespace Drupal\product_review;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a product review entity type.
 */
interface ProductReviewInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
