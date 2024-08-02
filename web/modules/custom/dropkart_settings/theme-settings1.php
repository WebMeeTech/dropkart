<?php

function themag_form_system_theme_settings_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state)
{
  $form['theme_color'] = [
    '#type' => 'color',
    '#title' => t('Theme Color'),
    '#default_value' => theme_get_setting('theme_color'),
  ];
}
//function dropkart_preprocess_html(&$variables) {
//  $config = \Drupal::config('dropkart_settings.color_settings');
//  $theme_color = $config->get('theme_color') ?: '#000000';
//  $variables['theme_color'] = $theme_color;
//  \Drupal::service('page_cache_kill_switch')->trigger();
//  $variables['#attached']['drupalSettings']['themeColor'] = $theme_color;
//  $variables['#attached']['library'][] = 'dropkart/global-styling';
//}
