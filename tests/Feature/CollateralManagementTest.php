<?php

use App\Models\Collateral;
use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function loanApplicationForTesting(User $customer): LoanApplication
{
    return LoanApplication::create([
        'user_id' => $customer->id,
        'amount' => 1000000,
        'term_months' => 12,
        'purpose' => 'Working capital',
        'monthly_income' => 250000,
        'status' => 'approved',
    ]);
}

it('stores multiple collateral records with pdf proof and supervisor', function () {
    Storage::fake('public');

    $staff = User::factory()->create([
        'name' => 'Supervisor One',
        'business_name' => 'Main Branch',
        'address' => 'Central',
        'email' => 'supervisor1@example.com',
        'tel_no' => '0777111111',
        'role' => 'staff',
    ]);

    $customer = User::factory()->create([
        'name' => 'Borrower One',
        'email' => 'borrower@example.com',
        'role' => 'customer',
    ]);

    $loan = loanApplicationForTesting($customer);

    $payload = [
        'collateral_type' => 'Car Logbook',
        'loan_supervisor_id' => $staff->id,
        'description' => 'Toyota Land Cruiser logbook',
        'estimated_value' => 25000000,
        'valuation_date' => now()->toDateString(),
        'collateral_document' => UploadedFile::fake()->create('proof.pdf', 100, 'application/pdf'),
        'notes' => 'First record',
    ];

    $this->actingAs($staff, 'staff')
        ->post(route('collaterals.store', $loan), $payload)
        ->assertRedirect(route('loans.show', $loan));

    $payload['description'] = 'Second collateral document';
    $payload['collateral_type'] = 'Land Title';
    $payload['collateral_document'] = UploadedFile::fake()->create('proof-2.pdf', 100, 'application/pdf');

    $this->actingAs($staff, 'staff')
        ->post(route('collaterals.store', $loan), $payload)
        ->assertRedirect(route('loans.show', $loan));

    expect(Collateral::where('loan_application_id', $loan->id)->count())->toBe(2);

    $this->assertDatabaseHas('collaterals', [
        'loan_application_id' => $loan->id,
        'loan_supervisor_id' => $staff->id,
        'collateral_type' => 'Car Logbook',
    ]);
});

it('blocks disbursement when no collateral has been captured', function () {
    $staff = User::factory()->create([
        'name' => 'Disbursement Staff',
        'business_name' => 'HQ',
        'address' => 'Head Office',
        'email' => 'disburse@example.com',
        'tel_no' => '0777222222',
        'role' => 'staff',
    ]);

    $customer = User::factory()->create([
        'name' => 'Borrower Two',
        'email' => 'borrower2@example.com',
        'role' => 'customer',
    ]);

    $loan = loanApplicationForTesting($customer);

    $this->actingAs($staff, 'staff')
        ->post(route('disbursements.store', $loan), [
            'disbursement_amount' => 1000000,
            'disbursement_date' => now()->toDateString(),
            'disbursement_method' => 'cash',
            'payment_frequency' => 'monthly',
            'number_of_installments' => 12,
        ])
        ->assertRedirect(route('collaterals.create', $loan));

    $this->assertDatabaseCount('loan_disbursements', 0);
});