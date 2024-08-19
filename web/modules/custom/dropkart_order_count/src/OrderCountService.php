<?php

namespace Drupal\dropkart_order_count;

use Drupal\Core\Database\Connection;

/**
 * Service to get the order count for a product.
 */
class OrderCountService {

  protected $database;

  /**
   * Constructs the OrderCountService object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
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
    $query = $this->database->select('commerce_order_item', 'oi')
      ->condition('oi.purchased_entity', $product_id) // Using the product ID to find orders
      ->countQuery()
      ->execute();

    return (int) $query->fetchField();
  }


}
