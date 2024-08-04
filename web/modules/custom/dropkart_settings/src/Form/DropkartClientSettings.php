<?php
namespace Drupal\dropkart_settings\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Class DropkartClientSettings.
 */
class DropkartClientSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['dropkart_settings.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dropkart_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dropkart_settings.settings');

    $form['site_information'] = [
      '#type' => 'details',
      '#title' => $this->t('Site Details'),
      '#open' => TRUE,
    ];

    $form['site_information']['settings'] = [
      '#markup' => $this->t('Upload your custom logo for the site'),
    ];

    $form['site_information']['custom_logo'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Upload logo image'),
      '#description' => $this->t('Upload a custom logo for the site.'),
      '#upload_location' => 'public://',
      '#default_value' => $config->get('custom_logo') ?: [],
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'FileIsImage' => [],
      ],
    ];

    $form['site_theme'] = [
      '#type' => 'details',
      '#title' => $this->t('Theme Information'),
      '#open' => TRUE,
    ];

    $form['site_theme']['site_color'] = [
      '#type' => 'color',
      '#title' => $this->t('Select a color for the site theme'),
      '#default_value' => $config->get('site_color') ?? '#ffffff',
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory()->getEditable('dropkart_settings.settings');

    // Save custom logo
    $logo_fid = $form_state->getValue('custom_logo');
    if (!empty($logo_fid)) {
      $file = File::load(reset($logo_fid));
      if ($file) {
        $file->setPermanent();
        $file->save();
        $config->set('custom_logo', $logo_fid);
      } else {
        $config->clear('custom_logo');
      }
    } else {
      $config->clear('custom_logo');
    }

    // Save site color
    $site_color = $form_state->getValue('site_color');
    $config->set('site_color', $site_color);

    $config->save();

    $this->messenger()->addStatus($this->t('The custom logo has been uploaded and the theme color has been updated.'));

    parent::submitForm($form, $form_state);
  }
}
