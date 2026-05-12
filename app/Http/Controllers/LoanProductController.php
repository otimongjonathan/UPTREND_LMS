<?php

namespace App\Http\Controllers;

use App\Models\LoanProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoanProductController extends Controller
{
    public function index(): View
    {
        // For staff: show only their business's products
        if (auth('staff')->check()) {
            $products = LoanProduct::query()
                ->get()
                ->filter(fn ($product) => (int) $product->provider_id === (int) auth('staff')->id())
                ->values();
            return view('loan-products.index', compact('products'));
        }
        
        // For customers: show only active products with provider details
        $products = LoanProduct::with('provider')->where('is_active', true)->paginate(15);
        return view('customer.loan-products', compact('products'));
    }

    public function create(): View
    {
        return view('loan-products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:loan_products',
            'description' => 'nullable|string',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|min:0|gte:min_amount',
            'min_term' => 'required|integer|min:1',
            'max_term' => 'required|integer|min:1|gte:min_term',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'processing_fee_percent' => 'required|numeric|min:0|max:100',
            'late_payment_fee_percent' => 'required|numeric|min:0|max:100',
            'insurance_premium_percent' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'requirements' => 'nullable|json',
            'features' => 'nullable|json',
        ]);

        // Automatically set the provider as the logged-in staff user
        $validated['provider_id'] = auth('staff')->id();
        $validated['provider_company'] = auth('staff')->user()->name;

        $product = LoanProduct::create($validated);

        // Notify all customers about new product
        if ($product->is_active) {
            \App\Services\ComprehensiveNotificationService::notifyNewLoanProduct($product);
        }

        return redirect()->route('loan-products.index')
            ->with('success', "Loan product '{$product->name}' has been created successfully!" . ($product->is_active ? ' Customers have been notified.' : ''));
    }

    public function edit(LoanProduct $loanProduct): View
    {
        // Ensure staff can only edit their own business's products
        if (User::isStaffRole(auth('staff')->user()->role) && $loanProduct->provider_id !== auth('staff')->id()) {
            abort(403, 'Unauthorized access to this loan product.');
        }
        
        return view('loan-products.edit', compact('loanProduct'));
    }

    public function update(Request $request, LoanProduct $loanProduct): RedirectResponse
    {
        // Ensure staff can only update their own business's products
        if (User::isStaffRole(auth('staff')->user()->role) && $loanProduct->provider_id !== auth('staff')->id()) {
            abort(403, 'Unauthorized access to this loan product.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:loan_products,name,' . $loanProduct->id,
            'description' => 'nullable|string',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|min:0|gte:min_amount',
            'min_term' => 'required|integer|min:1',
            'max_term' => 'required|integer|min:1|gte:min_term',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'processing_fee_percent' => 'required|numeric|min:0|max:100',
            'late_payment_fee_percent' => 'required|numeric|min:0|max:100',
            'insurance_premium_percent' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'requirements' => 'nullable|json',
            'features' => 'nullable|json',
        ]);

        $loanProduct->update($validated);

        return redirect()->route('loan-products.index')
            ->with('success', "Loan product '{$loanProduct->name}' has been updated successfully!");
    }

    public function destroy(LoanProduct $loanProduct): RedirectResponse
    {
        // Ensure staff can only delete their own business's products
        if (User::isStaffRole(auth('staff')->user()->role) && $loanProduct->provider_id !== auth('staff')->id()) {
            abort(403, 'Unauthorized access to this loan product.');
        }
        
        $productName = $loanProduct->name;
        LoanProduct::query()->whereKey($loanProduct->getKey())->delete();

        return redirect()->route('loan-products.index')
            ->with('success', "Loan product '{$productName}' has been deleted successfully!");
    }

    public function toggle(LoanProduct $loanProduct): RedirectResponse
    {
        // Ensure staff can only toggle their own business's products
        if (User::isStaffRole(auth('staff')->user()->role) && $loanProduct->provider_id !== auth('staff')->id()) {
            abort(403, 'Unauthorized access to this loan product.');
        }
        
        $loanProduct->update(['is_active' => !$loanProduct->is_active]);

        $status = $loanProduct->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Loan product '{$loanProduct->name}' has been {$status} successfully!");
    }
}
