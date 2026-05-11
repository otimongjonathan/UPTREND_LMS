<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\LoanProduct;

class LoanProductValidationService
{
    /**
     * Validate loan application against product limits
     */
    public static function validateLoanApplication(LoanApplication $loan)
    {
        $product = $loan->product;
        $errors = [];

        if (!$product) {
            $errors[] = 'No loan product associated';
            return $errors;
        }

        // Check amount limits
        if ($loan->amount < $product->min_amount) {
            $errors[] = "Amount UGX " . number_format($loan->amount) . " is below minimum: UGX " . number_format($product->min_amount);
        }

        if ($loan->amount > $product->max_amount) {
            $errors[] = "Amount UGX " . number_format($loan->amount) . " is above maximum: UGX " . number_format($product->max_amount);
        }

        // Check term limits
        if ($loan->term_months < $product->min_term) {
            $errors[] = "Term {$loan->term_months} months is below minimum: {$product->min_term} months";
        }

        if ($loan->term_months > $product->max_term) {
            $errors[] = "Term {$loan->term_months} months is above maximum: {$product->max_term} months";
        }

        return $errors;
    }

    /**
     * Fix loan applications that violate product limits
     */
    public static function fixLoanApplicationLimits($providerId = null)
    {
        $query = LoanApplication::with('product');
        
        if ($providerId) {
            $query->whereHas('product', function($q) use ($providerId) {
                $q->where('provider_id', $providerId);
            });
        }

        $applications = $query->get();
        $fixed = [];

        foreach ($applications as $loan) {
            $errors = self::validateLoanApplication($loan);
            
            if (!empty($errors)) {
                $product = $loan->product;
                $oldAmount = $loan->amount;
                
                // Fix amount if outside limits
                $newAmount = $loan->amount;
                if ($loan->amount < $product->min_amount) {
                    $newAmount = $product->min_amount;
                } elseif ($loan->amount > $product->max_amount) {
                    $newAmount = $product->max_amount;
                }

                // Fix term if outside limits
                $newTerm = $loan->term_months;
                if ($loan->term_months < $product->min_term) {
                    $newTerm = $product->min_term;
                } elseif ($loan->term_months > $product->max_term) {
                    $newTerm = $product->max_term;
                }

                // Update the loan
                $loan->update([
                    'amount' => $newAmount,
                    'term_months' => $newTerm,
                    'disbursed_amount' => in_array($loan->status, ['approved', 'active', 'overdue', 'completed']) ? $newAmount : 0,
                ]);

                $fixed[] = [
                    'loan_id' => $loan->id,
                    'applicant' => $loan->applicant_full_name,
                    'product' => $product->name,
                    'old_amount' => $oldAmount,
                    'new_amount' => $newAmount,
                    'old_term' => $loan->term_months,
                    'new_term' => $newTerm,
                ];

                // Regenerate repayment schedule if loan is active
                if (in_array($loan->status, ['approved', 'active', 'overdue'])) {
                    $loan->syncWithProduct();
                }
            }
        }

        return $fixed;
    }

    /**
     * Get suggested amount within product limits
     */
    public static function getSuggestedAmount($requestedAmount, LoanProduct $product)
    {
        if ($requestedAmount < $product->min_amount) {
            return $product->min_amount;
        }
        
        if ($requestedAmount > $product->max_amount) {
            return $product->max_amount;
        }
        
        return $requestedAmount;
    }

    /**
     * Get all applications that violate product limits
     */
    public static function getViolatingApplications($providerId = null)
    {
        $query = LoanApplication::with('product');
        
        if ($providerId) {
            $query->whereHas('product', function($q) use ($providerId) {
                $q->where('provider_id', $providerId);
            });
        }

        $applications = $query->get();
        $violations = [];

        foreach ($applications as $loan) {
            $errors = self::validateLoanApplication($loan);
            
            if (!empty($errors)) {
                $violations[] = [
                    'loan' => $loan,
                    'errors' => $errors,
                ];
            }
        }

        return $violations;
    }
}