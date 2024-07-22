<?php

namespace Drupal\product_review\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ReviewController extends ControllerBase
{
  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

    /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a ReviewController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Database\Connection $database
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager,Connection $database)
  {
    $this->database = $database;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
    $container->get('entity_type.manager'),
    $container->get('database')

    );
  }

  /**
   * Returns the average rating for a specific product entity.
   *
   * @param int $entity_id
   *   The product entity ID.
   *
   * @return array
   *   A render array.
   */
  public function getAverageRating($entity_id)
  {
    // Check if the database connection is valid.
    if ($this->database === NULL) {
      \Drupal::logger('product_review')->error('Database service is not injected.');
      return [
        '#type' => 'markup',
        '#markup' => $this->t('Database service is not available.'),
      ];
    }

    // Prepare the SQL query to calculate the average rating.
    $query = "
      SELECT AVG(prfd.rating__rating) AS average_rating
      FROM {commerce_product__field_product_reviews} cpfr
      INNER JOIN {product_review_field_data} prfd ON prfd.id = cpfr.field_product_reviews_target_id
      WHERE cpfr.entity_id = :entity_id AND cpfr.deleted = 0
    ";

    try {
      // Execute the query and fetch the result.
      $result = $this->database->query($query, [':entity_id' => $entity_id])->fetchAssoc();
      $average_rating = $result['average_rating'] ?? 0;

      // Convert the average rating to a scale of 0 to 5 stars.
      // The stored values are 20, 40, 60, 80, 100, which correspond to 1, 2, 3, 4, 5 stars.
      $converted_rating = $average_rating / 20; // Convert to a scale of 0 to 5 stars.
    } catch (\Exception $e) {
      \Drupal::logger('product_review')->error('Query execution failed: @message', ['@message' => $e->getMessage()]);
      return [
        '#type' => 'markup',
        '#markup' => $this->t('An error occurred while fetching the average rating.'),
      ];
    }

    return [
      '#theme' => 'product_review',
      '#average_rating' => number_format($converted_rating, 1),
      '#entity_id' => $entity_id,
    ];
  }
}
//    return[
//      '#type' => 'markup',
//      '#markup' => $this->t('The average rating for entity ID @entity_id is @average_rating', [
//        '@entity_id' => $entity_id,
//        '@average_rating' => number_format($converted_rating, 1),
//      ]),
//    ];
