<?php

namespace Drupal\product_review\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\product_review\ProductReviewStorage;

/**
 * Controller to handle product review actions.
 */
class ReviewController extends ControllerBase
{

  /**
   * The product review storage handler.
   *
   * @var \Drupal\product_review\ProductReviewStorage
   */
  protected $productReviewStorage;

  /**
   * Constructs a ReviewController object.
   *
   * @param \Drupal\product_review\ProductReviewStorage $product_review_storage
   *   The product review storage handler.
   */
  public function __construct(ProductReviewStorage $product_review_storage)
  {
    $this->productReviewStorage = $product_review_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager')->getStorage('product_review')
    );
  }

  /**
   * Gets the average rating for a product.
   *
   * @param int $entity_id
   *   The entity ID.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The JSON response with the average rating.
   */
  public function getAverageRating($entity_id)
  {
    $average_rating = $this->productReviewStorage->getAverageRating($entity_id);
    return new JsonResponse(['average_rating' => $average_rating]);
  }
}
