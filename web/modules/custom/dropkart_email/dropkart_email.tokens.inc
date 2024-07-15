<?php

use Drupal\Core\Mail\MailManagerInterface;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\token\TokenEntityMapperInterface;
use Drupal\token\TokenEntityMapper;

/**
 * @file
 * Primary module hooks for dropkart_email module.
 */

/*
 * ],
 * Implements hook_token_info().
 */
function dropkart_email_token_info() {
  $entity_type_manager = \Drupal::entityTypeManager();
  $entity_type = $entity_type_manager->getDefinition('commerce_order');
  assert($entity_type !== NULL);

  $info = [];

  // Define the token type and token.
  $info['types']['dropkart_email'] = [
    'name' => t('Custom Email'),
    'description' => t('Tokens for custom email body'),
  ];

  $info['tokens']['dropkart_email']['order_details'] = [
    'name' => t('Order Details'),
    'description' => t('Displays the details of the order'),
  ];

  // Define individual tokens for order details.
  $info['tokens']['dropkart_email']['total'] = [
    'name' => t('Total'),
    'description' => t('Displays the total price of the order'),
  ];

  $info['tokens']['dropkart_email']['quantity'] = [
    'name' => t('Quantity'),
    'description' => t('Displays the quantity of the order'),
  ];

  $info['tokens']['dropkart_email']['item_text'] = [
    'name' => t('Quantity'),
    'description' => t('Displays the quantity of the order'),
  ];

  $info['tokens']['dropkart_email']['unit_price'] = [
    'name' => t('Quantity'),
    'description' => t('Displays the quantity of the order'),
  ];

  $info['tokens']['dropkart_email']['subtotal'] = [
    'name' => t('Quantity'),
    'description' => t('Displays the quantity of the order'),
  ];

  $info['tokens']['dropkart_email']['billing_address'] = [
    'name' => t('Quantity'),
    'description' => t('Displays the quantity of the order'),
  ];
  return $info;
}

/**
 * Implements hook_tokens().
 * @param $type
 * @param $tokens
 * @param array $data
 * @param array $options
 * @param BubbleableMetadata $bubbleable_metadata
 * @return array
 */
function dropkart_email_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $replacements = [];

  if ($type === 'dropkart_email' && isset($data['commerce_order'])) {
    /** @var \Drupal\commerce_order\Entity\OrderInterface $order */
    $order = $data['commerce_order'];

    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'order_details':
          $order_items = $order->getItems();
          $items_text = '';
          $unit_price = '';
          $billing_address = '';
          $quantity = '';
          $subtotal = '';
          $order_nu =  $order->getOrderNumber();

          foreach ($order_items as $order_item) {
            $quantity = $order_item->getQuantity();
            $title = $order_item->getTitle();
            $unit_price = $order_item->getUnitPrice();
            $subtotal = number_format($order_item->getTotalPrice()->getNumber(), 2); // Assuming subtotal is a numeric value
            $billing_address = $order_item->getOrder();
//            var_dump($billing_address);
            // Format each item's details
            $item_details = "{$quantity} x {$title} (Unit Price: {$unit_price})";
            $items_text .= $item_details;
          }

          // Format the total price
          $total = number_format($order->getTotalPrice()->getNumber(), 2); // Assuming total is a numeric value

          $build = [
            '#theme' => 'my_custom_template',
            '#order_details' => "Order Items:{$items_text}Total: {$total}",
            '#total' => $total,
            '#quantity' => $quantity,
            '#item_text'  => $items_text,
            '#unit_price' => $unit_price,
            '#subtotal'  => $subtotal,
            '#order_nu' =>$order_nu,
          ];
          /** @var \Drupal\Core\Render\Renderer $renderer */
          $renderer = \Drupal::service('renderer');
          $output = $renderer->render($build);

          // Create the replacement pattern
          $replacements[$original] = $output;

          // Add cacheable dependency
          $bubbleable_metadata->addCacheableDependency($order);
          break;
      }
    }
  }

  return $replacements;
}

//function dropkart_email_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
//  $replacements = [];
//
//  if ($type === 'dropkart_email' && isset($data['commerce_order'])) {
//    /** @var \Drupal\commerce_order\Entity\OrderInterface $order */
//    $order = $data['commerce_order'];
//
//    foreach ($tokens as $name => $original) {
//      switch ($name) {
//        case 'order_details':
//          // Render the order details using Drupal's rendering system.
//          $order_items = $order->getItems();
//          $items_text = '';
//
//          foreach ($order_items as $order_item) {
//            $quantity = $order_item->getQuantity();
//            $title = $order_item->getTitle();
//            $subtotal = number_format($order_item->getTotalPrice()->getNumber(), 2); // Assuming subtotal is a numeric value
//
//            // Format each item's details
//            $item_details = "{$quantity} x {$title} (Subtotal: {$subtotal})";
//            $items_text .= $item_details . "\n";
//          }
//
//          // Format the total price
//          $total = number_format($order->getTotalPrice()->getNumber(), 2); // Assuming total is a numeric value
//
//          // Replace the placeholders in the template with actual values.
//          $replacement = "Order Items:\n{$items_text}\nTotal: {$total}";
//          $replacements[$original] = $replacement;
//
//          // Add cacheable dependency
//          $bubbleable_metadata->addCacheableDependency($order);
//          break;
//
//        case 'quantity':
//          // Example implementation: replace with your actual logic
//          $quantity = count($order->getItems());
//          $replacements[$original] = $quantity;
//          break;
//
//        case 'total':
//          // Example implementation: replace with your actual logic
//          $total = number_format($order->getTotalPrice()->getNumber(), 2);
//          $replacements[$original] = $total;
//          break;
//
//          $build = [
//            '#theme' => 'my_custom_template',
//            '#order_details' => [
//              'quantity' => $quantity,
//              'total' => $total,
//            ],
//          ];
//
//          /** @var \Drupal\Core\Render\Renderer $renderer */
//          $renderer = \Drupal::service('renderer');
//          $output = $renderer->render($build);
//
//          // Create the replacement pattern
//          $replacements[$original] = $output;
//
//          // Add cacheable dependency
//          $bubbleable_metadata->addCacheableDependency($order);
//          break;
//      }
//    }
//  }
//
//  return $replacements;
//}


/**
 * Implements hook_tokens().
 */
//function dropkart_email_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
//  $replacements = [];
//
//  if ($type === 'dropkart_email' && isset($data['commerce_order'])) {
//    /** @var \Drupal\commerce_order\Entity\OrderInterface $order */
//    $order = $data['commerce_order'];
//
//    foreach ($tokens as $name => $original) {
//      switch ($name) {
//        case 'order_details':
//          $order_items = $order->getItems();
//          $items_text = '';
//
//          foreach ($order_items as $order_item) {
//            $quantity = $order_item->getQuantity();
//            $title = $order_item->getTitle();
//            $subtotal = number_format($order_item->getTotalPrice()->getNumber(),2); // Assuming subtotal is a numeric value
//
//            // Format each item's details
//            $item_details = "{$quantity} x {$title} (Subtotal: {$subtotal})";
//            $items_text .= $item_details . "\n";
//          }
//
//          // Format the total price
//          $total = number_format($order->getTotalPrice()->getNumber(),2); // Assuming total is a numeric value
//
//          $build = [
//            '#theme' => 'my_custom_template',
//            '#order_details' => "Order Items:\n{$items_text}\nTotal: {$total}",
//          ];
//          /** @var \Drupal\Core\Render\Renderer $renderer */
//          $renderer = \Drupal::service('renderer');
//          $output = $renderer->render($build);
//          // Create the replacement pattern
//          $replacements[$original] = (String) $output;
//
//
//
//
//          // Add cacheable dependency
//          $bubbleable_metadata->addCacheableDependency($order);
//          break;
//      }
//    }
//  }
//
//  return $replacements;
//}


