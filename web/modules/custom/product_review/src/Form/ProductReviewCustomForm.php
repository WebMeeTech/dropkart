<?php

namespace Drupal\product_review\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\product_review\Entity\ProductReview;

/**
 * Class ProductReviewCustomForm.
 */
class ProductReviewCustomForm extends FormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'product_review_custom_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state,  $product_id = NULL) {
    $form['product_id'] = [
      '#type' => 'hidden',
      '#value' => $product_id,
    ];

    $form['rating'] = [
      '#type' => 'fivestar',
      '#title' => $this->t('Rating'),
      '#default_value' => 100,
    ];

//    $form['rating'] = [
//      '#type' => 'fivestar_rating',
//      '#title' => $this->t('Rating'),
//      '#default_value' => 20,
//      '#required' => FALSE,
//    ];
//    $form['rating'] = [
//      '#type' => 'fivestar',
//      '#title' => $this->t('Rating'),
//      '#default_value' => 20,
//      '#plugin_settings' => [
//        'fivestar_widget' => 'select',
//      ],
//    ];

    $form['feedback'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Comment'),
      '#required' => TRUE,
    ];
    $form_state->set('product_id', $product_id);
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save the data to the ProductReview entity.
    $values = $form_state->getValues();
    $product_review = ProductReview::create([
      'rating' => $values['rating'],
      'feedback' => $values['feedback'],
      'product_id'=> $values['product_id']
    ]);
    $product_review->save();

    $this->messenger()->addStatus($this->t('Your review has been saved.'));
  }
}
