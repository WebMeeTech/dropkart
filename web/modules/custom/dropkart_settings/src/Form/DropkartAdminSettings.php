<?php

namespace Drupal\dropkart_settings\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DropkartAdminSettings extends FormBase
{

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructs a new DropkartSettings.
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
    return 'dropkart_settings_admin';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $enable_reviews = $this->state->get('dropkart_settings_admin.enable_reviews', FALSE);

    $form['site_information'] = [
      '#type' => 'details',
      '#title' => $this->t('Product Review'),
      '#open' => TRUE,
    ];

    $form['site_information'] ['settings'] = [
      '#markup' => $this->t('Settings form for a product review toggle. You can enable or disable product reviews here.'),
    ];

    $form['site_information'] ['enable_reviews'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Product Reviews'),
      '#default_value' => $enable_reviews,
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
    $this->state->set('dropkart_settings_admin.enable_reviews', $form_state->getValue('enable_reviews'));
    $this->messenger()->addStatus($this->t('The configuration has been updated.'));
  }
}
