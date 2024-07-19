<?php

declare(strict_types=1);

namespace Drupal\product_review\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form controller for the product review entity edit forms.
 */
final class ProductReviewForm extends ContentEntityForm {

  public function buildForm(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\product_review\Entity\ProductReview $entity */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    // Ensure the rating field is included and handled correctly
    $form['rating'] = [
      '#type' => 'fivestar_rating',
      '#title' => $this->t('Rating'),
      '#default_value' => $entity->get('rating')->value,
      '#required' => FALSE,
    ];

    $form['langcode'] = [
      '#title' => $this->t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages' => Language::STATE_ALL,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {
    $result = parent::save($form, $form_state);

    $message_args = ['%label' => $this->entity->toLink()->toString()];
    $logger_args = [
      '%label' => $this->entity->label(),
      'link' => $this->entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New product review %label has been created.', $message_args));
        $this->logger('product_review')->notice('New product review %label has been created.', $logger_args);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The product review %label has been updated.', $message_args));
        $this->logger('product_review')->notice('The product review %label has been updated.', $logger_args);
        break;

      default:
        throw new \LogicException('Could not save the entity.');
    }

    $form_state->setRedirectUrl($this->entity->toUrl());

    return $result;
  }

}
