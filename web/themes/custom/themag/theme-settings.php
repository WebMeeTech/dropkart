<?php

/**
 * @file
 * Advanced theme settings.
 */

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function themag_form_system_theme_settings_alter(&$form, FormStateInterface &$form_state, $form_id = NULL) {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  if (isset($form_id)) {
    return;
  }

  // Create vertical tabs for all TheMAG related settings.
  $form['themag'] = [
    '#type' => 'vertical_tabs',
    '#weight' => -10,
  ];

  // ========================
  // General.
  // ========================
  $form['general'] = [
    '#type' => 'details',
    '#title' => t('General'),
    '#group' => 'themag',
  ];

  // ------------------------
  // Options.
  // ------------------------
  $form['general']['options'] = [
    '#type' => 'details',
    '#title' => t('Options'),
    '#weight' => 10,
    '#open' => TRUE,
  ];

  $form['general']['options']['themag_toggle_scroll_to_top'] = [
    '#type' => 'checkbox',
    '#title' => t('Enable scroll to top button'),
    '#default_value' => theme_get_setting('themag_toggle_scroll_to_top'),
    '#description' => t('When a user scrolls past a certain point on the website, this helpful button appears, enabling users to easily return to the top of a page.'),
  ];

  $form['general']['options']['themag_sticky_sidebar'] = [
    '#type' => 'checkbox',
    '#title' => t('Enable sticky sidebars'),
    '#default_value' => theme_get_setting('themag_sticky_sidebar'),
    '#description' => t('Sticky elements taller than the viewport can scroll independently up and down, meaning you don\'t have to worry about your content being cut off.'),
  ];

  // ========================
  // Header
  // ========================
  $form['header'] = [
    '#type' => 'details',
    '#title' => t('Header'),
    '#group' => 'themag',
  ];

  // ------------------------
  // Header Settings
  // ------------------------
  $form['header']['heder_options'] = [
    '#type' => 'details',
    '#title' => t('Options'),
    '#weight' => 5,
    '#open' => TRUE,
  ];

  // Select header style.
  $form['header']['heder_options']['themag_header_style'] = [
    '#type' => 'select',
    '#title' => t('Header style'),
    '#options' => [
      'header_a' => 'Header style (A)',
      'header_b' => 'Header style (B)',
      'header_c' => 'Header style (C)',
      'header_d' => 'Header style (D)',
      'custom_header' => t('Custom Header'),
    ],
    '#default_value' => theme_get_setting('themag_header_style'),
    '#description' => t('Choose a header for your site. You can also choose a "Custom Header" and create your own header by editing the "<strong>' . \Drupal::service('extension.path.resolver')->getPath('theme', 'themag_st') . '/templates/header/custom_header.inc</strong>" file.'),
  ];

  // Header Banner
  // * Show the header banner slot field when either
  // * the header(C) style is used
  // * the header(D) style is used
  // * or the custom header is used.
  $form['header']['heder_options']['themag_header_banner'] = [
    '#type' => 'textarea',
    '#title' => t('Header banner slot'),
    '#default_value' => theme_get_setting('themag_header_banner'),
    '#description' => t('This header style supports header banner ad. The recommended ad size is 728x90px.'),
    '#states' => [
      'visible' => [
        [':input[name="themag_header_style"]' => ['value' => 'header_c']],
        [':input[name="themag_header_style"]' => ['value' => 'header_d']],
        [':input[name="themag_header_style"]' => ['value' => 'custom_header']],
      ],
    ],
  ];

  $form['header']['heder_options']['themag_sticky_header'] = [
    '#type' => 'checkbox',
    '#title' => t('Enable sticky header'),
    '#default_value' => theme_get_setting('themag_sticky_header'),
    '#description' => t('The sticky header can help to make it easier for visitors to navigate through a site as they can quickly access the navigation menu rather than having to scroll back to the top of the page.')
  ];

  $form['header']['header_element_display'] = [
    '#type' => 'details',
    '#title' => t('Header Action Menu'),
    '#weight' => 5,
    '#open' => TRUE,
  ];

  $form['header']['header_element_display']['themag_header_user_icon'] = [
    '#type' => 'checkbox',
    '#title' => t('Show user menu'),
    '#default_value' => theme_get_setting('themag_header_user_icon'),
  ];

  $form['header']['header_element_display']['themag_header_search_icon'] = [
    '#type' => 'checkbox',
    '#title' => t('Show search icon'),
    '#default_value' => theme_get_setting('themag_header_search_icon'),
    '#disabled' => (\Drupal::service('module_handler')->moduleExists('search') ? '' : 'disabled'),
  ];

  $form['header']['header_element_display']['search_settings'] = [
    '#type' => 'details',
    '#title' => t('Search settings'),
    '#open' => TRUE,
    '#states' => [
      'visible' => [
        [':input[name="themag_header_search_icon"]' => ['checked' => TRUE]],
      ],
    ],
  ];

  $form['header']['header_element_display']['search_settings']['themag_header_search_icon_as_link'] = [
    '#type' => 'checkbox',
    '#title' => t('Use the search icon as a link to the search page instead of the search form toggle button'),
    '#default_value' => theme_get_setting('themag_header_search_icon_as_link'),
    '#disabled' => (\Drupal::service('module_handler')->moduleExists('search') ? '' : 'disabled'),
    '#states' => [
      'visible' => [
        [':input[name="themag_header_search_icon"]' => ['checked' => TRUE]],
      ],
    ],
  ];

  $form['header']['header_element_display']['search_settings']['themag_search_page_path'] = [
    '#type' => 'url',
    '#title' => t('Search page URL'),
    '#description' => t('Enter URL for the search page. E.g. http://example.com/search'),
    '#default_value' => theme_get_setting('themag_search_page_path'),
    '#disabled' => (\Drupal::service('module_handler')->moduleExists('search') ? '' : 'disabled'),
    '#states' => [
      'visible' => [
        [':input[name="themag_header_search_icon_as_link"]' => ['checked' => TRUE]],
      ],
    ],
  ];

  $form['header']['header_element_display']['themag_header_cart_icon'] = [
    '#type' => 'checkbox',
    '#title' => t('Show shopping cart'),
    '#default_value' => theme_get_setting('themag_header_cart_icon'),
    '#disabled' => (\Drupal::service('module_handler')->moduleExists('commerce_cart') ? '' : 'disabled'),
  ];

  // ========================
  // Social Media
  // ========================

  $form['social'] = [
    '#type' => 'details',
    '#title' => t('Social media pages'),
    '#group' => 'themag',
  ];

  // ------------------------
  // Social Media Pages
  // ------------------------
  $form['social']['social_media_pages'] = [
    '#type' => 'details',
    '#title' => t('Social Media Pages'),
    '#open' => TRUE,
  ];

  $social_media_pages = [
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'google-plus' => 'Google+',
    'youtube' => 'YouTube',
    'instagram' => 'Instagram',
    'pinterest' => 'Pinterest',
    'tumblr' => 'Tumblr',
    'linked-in' => 'LinkedIn',
  ];

  foreach ($social_media_pages as $sm_page => $sm_name) {
    $form['social']['social_media_pages'][$sm_page] = [
      '#type' => 'details',
      '#title' => t($sm_name),
      '#open' => FALSE,
    ];

    $form['social']['social_media_pages'][$sm_page]['themag_' . $sm_page] = [
      '#type' => 'url',
      '#title'  => t($sm_name),
      '#description' => t('Enter the URL of your ' . $sm_name . ' profile.'),
      '#attributes' => ['placeholder' => 'http://'],
      '#default_value' => theme_get_setting('themag_' . $sm_page),
    ];

    $form['social']['social_media_pages'][$sm_page]['themag_' . $sm_page . '_enable'] = [
      '#type' => 'checkbox',
      '#title' => t('Enable'),
      '#default_value' => theme_get_setting('themag_' . $sm_page . '_enable'),
    ];
  }

  // ========================
  // Copyright text
  // ========================

  $form['footer'] = [
    '#type' => 'details',
    '#title' => t('Footer'),
    '#group' => 'themag',
  ];

  $form['footer']['footer_options'] = [
    '#type' => 'details',
    '#title' => t('Options'),
    '#weight' => 10,
    '#open' => TRUE,
  ];

  $form['footer']['footer_options']['themag_copyright_text'] = [
    '#type' => 'text_format',
    '#format' => 'basic_html',
    '#title' => t('Copyright Text'),
    '#description' => t('Enter copyright text for your website.'),
//    '#default_value' => theme_get_setting('themag_copyright_text')['value'],
  ];
}
