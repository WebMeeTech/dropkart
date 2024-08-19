<?php

namespace Drupal\dropkart_order_count\Twig;

use Drupal\dropkart_order_count\OrderCountService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Provides a Twig extension for order count.
 */
class OrderCountTwigExtension extends AbstractExtension {

  protected $orderCountService;

  /**
   * Constructs the OrderCountTwigExtension object.
   *
   * @param \Drupal\dropkart_order_count\OrderCountService $orderCountService
   *   The order count service.
   */
  public function __construct(OrderCountService $orderCountService) {
    $this->orderCountService = $orderCountService;
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new TwigFunction('get_order_count', [$this, 'getOrderCount']),
    ];
  }

  /**
   * Get the number of orders placed for a product.
   *
   * @param int $product_id
   *   The product ID.
   *
   * @return int
   *   The number of orders.
   */
  public function getOrderCount($product_id) {
    return $this->orderCountService->getOrderCount($product_id);
  }

}
