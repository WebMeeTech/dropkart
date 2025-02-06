<?php

namespace Drupal\dropkart_order_count;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\commerce_order\Entity\OrderItem;

/**
 * Service to get the order count for a product.
 */
class OrderCountService {

  protected $routeMatch;

  /**
   * Constructs the OrderCountService object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match service.
   */
  public function __construct(RouteMatchInterface $route_match) {
    $this->routeMatch = $route_match;
  }

  /**
   * Retrieve the variation ID for a given product ID.
   *
   * @param int $product_id
   *   The product ID.
   *
   * @return int|null
   *   The corresponding variation ID or NULL if not found.
   */
  protected function getVariationId($product_id) {
    // Query the commerce_product_variation table to get the variation ID.
    $query = \Drupal::entityTypeManager()->getStorage('commerce_product_variation')
      ->getQuery()
      ->condition('product_id', $product_id)
      ->accessCheck(FALSE);

    $variation_ids = $query->execute();

    // Return the first variation ID found, or NULL if none found.
    return !empty($variation_ids) ? reset($variation_ids) : NULL;
  }

  /**
   * Get the number of orders placed for a given product.
   *
   * @param int $product_id
   *   The product ID.
   *
   * @return int
   *   The number of orders.
   */
  public function getOrderCount($product_id) {
    // Get the variation ID corresponding to the product_id.
    $variation_id = $this->getVariationId($product_id);
    if (!$variation_id) {
      return 0;
    }
    // Get the order item storage.
    $order_item_storage = \Drupal::entityTypeManager()->getStorage('commerce_order_item');

    // Create an entity query on the commerce_order_item entity.
    $query = $order_item_storage
      ->getQuery()
      ->accessCheck(FALSE);

    // Add a condition to match the purchased_entity field with the variation ID.
    $query->condition('purchased_entity', $variation_id);

    // Execute the query and count the number of results.
    $order_count = $query->count()->execute();
    return $order_count;
  }

  /**
   * Get the current product ID from the route.
   *
   * @return int|null
   *   The current product ID or NULL if not available.
   */
  public function getCurrentProductId() {
    $product = $this->routeMatch->getParameter('commerce_product');
    return $product ? $product->id() : NULL;
  }

  /**
   * Get the order count for the current product in the route.
   *
   * @return int
   *   The number of orders.
   */
  public function getOrderCountForCurrentProduct() {
    $product_id = $this->getCurrentProductId();
    if ($product_id) {
      return $this->getOrderCount($product_id);
    }
    return 0;
  }
}

