<?php

namespace App\Rules;

use App\Models\LoanProduct;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LoanProductLimits implements ValidationRule
{
    protected $productId;
    protected $field;

    public function __construct($productId, $field = 'amount')
    {
        $this->productId = $productId;
        $this->field = $field;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $product = LoanProduct::find($this->productId);
        
        if (!$product) {
            $fail('Invalid loan product selected.');
            return;
        }

        if ($this->field === 'amount') {
            if ($value < $product->min_amount) {
                $fail("The {$attribute} must be at least UGX " . number_format($product->min_amount) . " for {$product->name}.");
            }

            if ($value > $product->max_amount) {
                $fail("The {$attribute} cannot exceed UGX " . number_format($product->max_amount) . " for {$product->name}.");
            }
        }

        if ($this->field === 'term') {
            if ($value < $product->min_term) {
                $fail("The {$attribute} must be at least {$product->min_term} months for {$product->name}.");
            }

            if ($value > $product->max_term) {
                $fail("The {$attribute} cannot exceed {$product->max_term} months for {$product->name}.");
            }
        }
    }
}