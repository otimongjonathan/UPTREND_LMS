<?php

namespace App\Services;

use App\Models\LoanApplication;
use Illuminate\Support\Facades\Auth;

class LoanFeeCalculationService
{
    /**
     * Calculate all fees and taxes for a loan application
     */
    public static function calculateFees(LoanApplication $loan, array $feeSettings = [])
    {
        $loanAmount = $loan->amount;
        
        // Default fee settings (can be overridden)
        $settings = array_merge([
            'processing_fee_percent' => $loan->product->processing_fee_percent ?? 2.5,
            'insurance_fee_percent' => $loan->product->insurance_fee_percent ?? 1.0,
            'vat_percent' => 18.0, // Uganda VAT
            'withholding_tax_percent' => 0.0,
        ], $feeSettings);
        
        // Calculate processing fee
        $processingFeeAmount = ($loanAmount * $settings['processing_fee_percent']) / 100;
        
        // Calculate insurance fee
        $insuranceFeeAmount = ($loanAmount * $settings['insurance_fee_percent']) / 100;
        
        // Total fees before tax
        $totalFeesBeforeTax = $processingFeeAmount + $insuranceFeeAmount;
        
        // Calculate VAT on fees
        $vatAmount = ($totalFeesBeforeTax * $settings['vat_percent']) / 100;
        
        // Calculate withholding tax (usually on interest, but can be applied to fees)
        $withholdingTaxAmount = ($loanAmount * $settings['withholding_tax_percent']) / 100;
        
        // Total calculations
        $totalFees = $totalFeesBeforeTax;
        $totalTaxes = $vatAmount + $withholdingTaxAmount;
        $netDisbursementAmount = $loanAmount - $totalFees - $totalTaxes;
        $grossRepaymentAmount = $loanAmount + $totalFees + $totalTaxes;
        
        return [
            'processing_fee_percent' => $settings['processing_fee_percent'],
            'processing_fee_amount' => round($processingFeeAmount, 2),
            'insurance_fee_percent' => $settings['insurance_fee_percent'],
            'insurance_fee_amount' => round($insuranceFeeAmount, 2),
            'vat_percent' => $settings['vat_percent'],
            'vat_amount' => round($vatAmount, 2),
            'withholding_tax_percent' => $settings['withholding_tax_percent'],
            'withholding_tax_amount' => round($withholdingTaxAmount, 2),
            'total_fees' => round($totalFees, 2),
            'total_taxes' => round($totalTaxes, 2),
            'net_disbursement_amount' => round($netDisbursementAmount, 2),
            'gross_repayment_amount' => round($grossRepaymentAmount, 2),
        ];
    }
    
    /**
     * Apply calculated fees to loan application
     */
    public static function applyFeesToLoan(LoanApplication $loan, array $feeSettings = [])
    {
        $calculations = self::calculateFees($loan, $feeSettings);
        
        $loan->update(array_merge($calculations, [
            'fees_calculated' => true,
            'fees_calculated_at' => now(),
            'fees_calculated_by' => Auth::id(),
        ]));
        
        return $calculations;
    }
    
    /**
     * Get fee breakdown for display
     */
    public static function getFeeBreakdown(LoanApplication $loan)
    {
        if (!$loan->fees_calculated) {
            $calculations = self::calculateFees($loan);
        } else {
            $calculations = [
                'processing_fee_percent' => $loan->processing_fee_percent,
                'processing_fee_amount' => $loan->processing_fee_amount,
                'insurance_fee_percent' => $loan->insurance_fee_percent,
                'insurance_fee_amount' => $loan->insurance_fee_amount,
                'vat_percent' => $loan->vat_percent,
                'vat_amount' => $loan->vat_amount,
                'withholding_tax_percent' => $loan->withholding_tax_percent,
                'withholding_tax_amount' => $loan->withholding_tax_amount,
                'total_fees' => $loan->total_fees,
                'total_taxes' => $loan->total_taxes,
                'net_disbursement_amount' => $loan->net_disbursement_amount,
                'gross_repayment_amount' => $loan->gross_repayment_amount,
            ];
        }
        
        return [
            'loan_amount' => $loan->amount,
            'fees' => [
                'processing' => [
                    'percent' => $calculations['processing_fee_percent'],
                    'amount' => $calculations['processing_fee_amount']
                ],
                'insurance' => [
                    'percent' => $calculations['insurance_fee_percent'],
                    'amount' => $calculations['insurance_fee_amount']
                ],
                'total' => $calculations['total_fees']
            ],
            'taxes' => [
                'vat' => [
                    'percent' => $calculations['vat_percent'],
                    'amount' => $calculations['vat_amount']
                ],
                'withholding' => [
                    'percent' => $calculations['withholding_tax_percent'],
                    'amount' => $calculations['withholding_tax_amount']
                ],
                'total' => $calculations['total_taxes']
            ],
            'summary' => [
                'gross_loan_amount' => $loan->amount,
                'total_deductions' => $calculations['total_fees'] + $calculations['total_taxes'],
                'net_disbursement' => $calculations['net_disbursement_amount'],
                'total_repayment' => $calculations['gross_repayment_amount']
            ]
        ];
    }
    
    /**
     * Recalculate fees when loan terms change
     */
    public static function recalculateOnTermsChange(LoanApplication $loan)
    {
        if ($loan->fees_calculated) {
            // Preserve existing fee percentages but recalculate amounts
            $feeSettings = [
                'processing_fee_percent' => $loan->processing_fee_percent,
                'insurance_fee_percent' => $loan->insurance_fee_percent,
                'vat_percent' => $loan->vat_percent,
                'withholding_tax_percent' => $loan->withholding_tax_percent,
            ];
            
            return self::applyFeesToLoan($loan, $feeSettings);
        }
        
        return null;
    }
    
    /**
     * Get default fee settings from product or system defaults
     */
    public static function getDefaultFeeSettings(LoanApplication $loan)
    {
        $product = $loan->product;
        
        return [
            'processing_fee_percent' => $product->processing_fee_percent ?? 2.5,
            'insurance_fee_percent' => $product->insurance_fee_percent ?? 1.0,
            'vat_percent' => 18.0, // Uganda standard VAT
            'withholding_tax_percent' => 0.0,
        ];
    }
}