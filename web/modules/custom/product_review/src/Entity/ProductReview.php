<?php

declare(strict_types=1);

namespace Drupal\product_review\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\product_review\ProductReviewInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the product review entity class.
 *
 * @ContentEntityType(
 *   id = "product_review",
 *   label = @Translation("Product review"),
 *   label_collection = @Translation("Product reviews"),
 *   label_singular = @Translation("product review"),
 *   label_plural = @Translation("product reviews"),
 *   label_count = @PluralTranslation(
 *     singular = "@count product reviews",
 *     plural = "@count product reviews",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\product_review\ProductReviewListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\product_review\Form\ProductReviewForm",
 *       "edit" = "Drupal\product_review\Form\ProductReviewForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "product_review",
 *   data_table = "product_review_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer product_review",
 *   entity_keys = {
 *     "id" = "id",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid",
 *     "rating" = "rating",
 *     "comment" = "comment",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/product-review",
 *     "add-form" = "/product-review/add",
 *     "canonical" = "/product-review/{product_review}",
 *     "edit-form" = "/product-review/{product_review}/edit",
 *     "delete-form" = "/product-review/{product_review}/delete",
 *     "delete-multiple-form" = "/admin/content/product-review/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.product_review.settings",
 * )
 */
final class ProductReview extends ContentEntityBase implements ProductReviewInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['rating'] = BaseFieldDefinition::create('fivestar')
//      ->setTranslatable(TRUE)
      ->setLabel(t('Rating'))
      ->setRequired(FALSE)
//      ->setSetting('max_length', 255)
//        ->setSetting('vote_type', 'fivestar')
      ->setDefaultValue(3)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'fivestar',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['comment'] = BaseFieldDefinition::create('text_long')
      ->setTranslatable(TRUE)
      ->setLabel(t('Comment'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'label' => 'above',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setTranslatable(TRUE)
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(self::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the product review was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the product review was last edited.'));

    return $fields;
  }

}
