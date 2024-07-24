<?php

namespace Drupal\product_review;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Database\Database;
use Drupal\Core\Logger\LoggerChannelTrait;

/**
 * Defines the storage handler class for product reviews.
 *
 * This extends the base storage class, adding required special handling
 * for product reviews.
 */
class ProductReviewStorage extends SqlContentEntityStorage
{
  use LoggerChannelTrait;

  /**
   * Gets the average rating for a given product entity ID.
   *
   * @param int $entity_id
   *   The entity ID of the product.
   *
   * @return float
   *   The average rating.
   */
  public static function getAverageRating($entity_id)
  {
    try {
      $database = Database::getConnection();
      $query = "SELECT AVG(rating__rating) as average_rating
                FROM product_review_field_data
                WHERE product_id = :product_id
                GROUP BY product_id";
      $result = $database->query($query, [':product_id' => $entity_id])->fetchField();

      if ($result) {
        // Convert the average rating to a scale of 0 to 5 stars (optional)
        $converted_rating = $result / 20; // Convert to a scale of 0 to 5 stars.
        return $converted_rating;
      }

      return 0;
    } catch (\Exception $e) {
      \Drupal::logger('product_review')->error('Query execution failed: @message', ['@message' => $e->getMessage()]);
      return 0;
    }
  }
}
