<?php

namespace Drupal\dropkart_email\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\commerce_order\Entity\OrderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\RendererInterface;

class DropKartController extends ControllerBase
{
  protected $renderer;

  public function __construct(RendererInterface $renderer)
  {
    $this->renderer = $renderer;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('renderer')
    );
  }

  protected function getCustomerProfileAndBillingAddress(OrderInterface $order) {
    $customer = $order->getCustomer();
    $customer_profile = [
      'uid' => $customer->id(),
      'name' => $customer->getDisplayName(),
      'mail' => $customer->getEmail(),
    ];

    $billing_profile = $order->getBillingProfile();
    if ($billing_profile) {
      $billing_address = $billing_profile->get('address')->first()->getValue();
    } else {
      $billing_address = [];
    }

    return [
      'customer_profile' => $customer_profile,
      'billing_address' => $billing_address,
    ];
  }

  public function customPage(OrderInterface $order)
  {
    $customer_info = $this->getCustomerProfileAndBillingAddress($order);
    $order_items = $order->getItems();
    $items_text = '';
    $unit_price = '';
    $billing_address = '';
    $customer_info;
    $order_nu =  $order->getOrderNumber();

    foreach ($order_items as $order_item) {
      $quantity = $order_item->getQuantity();
      $title = $order_item->getTitle();
      $unit_price = $order_item->getUnitPrice();
      $subtotal = number_format($order_item->getTotalPrice()->getNumber(), 2); // Assuming subtotal is a numeric value

      // Format each item's details
      $item_details = "{$quantity} x {$title} (Subtotal: {$subtotal})";
      $items_text .= $item_details . "\n";
    }

    // Format the total price
    $total = number_format($order->getTotalPrice()->getNumber(), 2); // Assuming total is a numeric value


    $build = [
      '#theme' => 'my_custom_template',
      '#order_details' => "Order Items:\n{$items_text}\nTotal: {$total}",
          '#total' => "Total{$total}",
          '#quantity' => $quantity,
          '#item_text'  => $items_text,
          '#unit_price' => $unit_price,
          '#subtotal'  => $subtotal,
          '#order_nu' =>$order_nu,
          '#customer_profile'  =>$customer_info
    ];
    return $build;
  }
}
