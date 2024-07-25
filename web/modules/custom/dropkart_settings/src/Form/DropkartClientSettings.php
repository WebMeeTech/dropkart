<?php

namespace Drupal\dropkart_settings\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\file\Entity\File;

class DropkartClientSettings extends FormBase
{
  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructs a new DropkartClientSettings.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   */
  public function __construct(StateInterface $state)
  {
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'dropkart_settings_client_admin';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
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
      '#default_value' => $this->state->get('dropkart_settings_client_admin.custom_logo', []),
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'FileIsImage' => [],
      ],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save configuration'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $logo_fid = $form_state->getValue('custom_logo');
    if (!empty($logo_fid)) {
      $file = File::load(reset($logo_fid));
      if ($file) {
        $file->setPermanent();
        $file->save();
        $this->state->set('dropkart_settings_client_admin.custom_logo', $logo_fid);
        $this->messenger()->addStatus($this->t('The custom logo has been uploaded.'));
      }
    }
  }
}

