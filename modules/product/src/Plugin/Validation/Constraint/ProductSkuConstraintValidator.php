<?php

/**
 * @file
 * Contains \Drupal\commerce_product\Plugin\Validation\Constraint\ProductSkuConstraintValidator.
 */

namespace Drupal\commerce_product\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the ProductSku constraint.
 */
class ProductSkuConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {
    $sku = $items->first()->value;
    if (isset($sku) && $sku !== '') {
      $productId = $items->getEntity()->id();
      $skuExists = (bool) \Drupal::entityQuery('commerce_product')
        ->condition("sku", $sku)
        ->condition('product_id', (int) $productId, '<>')
        ->range(0, 1)
        ->count()
        ->execute();

      if ($skuExists) {
        $this->context->buildViolation($constraint->message)
          ->setParameter('%sku', $this->formatValue($sku))
          ->addViolation();
      }
    }
  }
}
