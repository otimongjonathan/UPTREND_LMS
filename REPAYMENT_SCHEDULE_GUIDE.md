# Repayment Schedule System

## Overview
Single repayment schedule per loan with all installments tracked within one record. Automatic generation with 2-month grace period when loans are disbursed.

## Key Concept
**One Schedule Per Loan** - Each loan gets ONE repayment schedule record that contains ALL installments in JSON format. This means:
- No duplicate schedules for the same loan
- All installment payments recorded in the same form
- Efficient resource usage
- Easy tracking of overall loan progress

## Features

### 1. Single Schedule Record
- One `loan_repayment_schedules` record per loan
- All installments stored in JSON array
- Automatic summary calculations (paid, pending, overdue)
- Real-time status updates

### 2. Grace Period
- **Default**: 2 months from disbursement date
- First payment due after grace period ends
- Grace period end date automatically calculated

### 3. Installment Tracking
Each installment in the JSON array includes:
- Installment number
- Due date
- Principal amount
- Interest amount
- Total amount
- Paid amount
- Remaining balance
- Status (pending, paid, overdue, partial)
- Payment details (date, method, reference, notes)

### 4. Summary Tracking
The schedule record maintains:
- Total installments count
- Installments paid count
- Installments pending count
- Installments overdue count
- Total paid amount
- Total outstanding amount
- Overall status (active, completed, defaulted, restructured)

## Usage

### Automatic Generation
When a loan disbursement status changes to 'disbursed':
1. System checks if schedule already exists for this loan
2. Deletes old schedule if exists (prevents duplicates)
3. Calculates first payment date (disbursement + 2 months)
4. Generates all installments
5. Creates single schedule record with all data

### View All Schedules
```php
// List all active schedules
Route: /repayment-schedules
```

### View Single Schedule
```php
// View specific loan's schedule with all installments
Route: /repayment-schedules/{schedule}
```

### Record Payment
```php
use App\Services\RepaymentScheduleService;

$schedule = LoanRepaymentSchedule::where('loan_application_id', $loanId)->first();
RepaymentScheduleService::recordPayment(
    $schedule, 
    $installmentNumber,  // Which installment to pay
    $amount,
    [
        'payment_method' => 'bank_transfer',
        'payment_reference' => 'TXN123456',
        'notes' => 'Payment received'
    ]
);
```

### Regenerate Schedule
```php
$loan = LoanApplication::find($id);
$disbursement = $loan->disbursements()->where('status', 'disbursed')->first();
RepaymentScheduleService::generateSchedule($disbursement);
```

### Update Overdue Statuses
```bash
# Run daily via cron
php artisan repayments:update-overdue
```

## Database Schema

### loan_repayment_schedules
- `id` - Primary key
- `loan_application_id` - Unique foreign key (one schedule per loan)
- `loan_disbursement_id` - Foreign key to disbursement
- `total_installments` - Total number of installments
- `payment_frequency` - weekly, bi-weekly, monthly, quarterly
- `installment_amount` - Amount per installment
- `total_loan_amount` - Original loan amount
- `total_interest` - Total interest amount
- `total_repayable` - Total amount to repay
- `grace_period_months` - Grace period (default: 2)
- `grace_period_end_date` - When grace period ends
- `first_payment_date` - First installment due date
- `final_payment_date` - Last installment due date
- `installments` - JSON array of all installments
- `installments_paid` - Count of paid installments
- `installments_pending` - Count of pending installments
- `installments_overdue` - Count of overdue installments
- `total_paid` - Total amount paid
- `total_outstanding` - Remaining balance
- `status` - active, completed, defaulted, restructured
- `completed_at` - Date when fully paid

### Installment JSON Structure
```json
[
  {
    "number": 1,
    "due_date": "2024-07-15",
    "principal_amount": 83333.33,
    "interest_amount": 16666.67,
    "total_amount": 100000.00,
    "paid_amount": 100000.00,
    "remaining_balance": 900000.00,
    "status": "paid",
    "paid_date": "2024-07-15",
    "payment_method": "bank_transfer",
    "payment_reference": "TXN123456",
    "notes": "Payment received"
  },
  {
    "number": 2,
    "due_date": "2024-08-15",
    "principal_amount": 83333.33,
    "interest_amount": 16666.67,
    "total_amount": 100000.00,
    "paid_amount": 0,
    "remaining_balance": 816666.67,
    "status": "pending",
    "paid_date": null,
    "payment_method": null,
    "payment_reference": null,
    "notes": null
  }
]
```

## Routes

```php
// List all schedules
GET /repayment-schedules

// View specific schedule
GET /repayment-schedules/{schedule}

// Record payment
POST /repayment-schedules/{schedule}/payment

// Regenerate schedule
POST /loans/{loan}/regenerate-schedule
```

## Models & Relationships

### LoanApplication
```php
$loan->loanRepaymentSchedule(); // HasOne - single schedule
```

### LoanRepaymentSchedule
```php
$schedule->loanApplication(); // BelongsTo
$schedule->loanDisbursement(); // BelongsTo
$schedule->getNextDueInstallment(); // Get next unpaid
$schedule->getOverdueInstallments(); // Get all overdue
$schedule->updateCounts(); // Recalculate summaries
```

## Service Methods

### RepaymentScheduleService

#### generateSchedule($disbursement)
Generates complete repayment schedule with grace period.
- Deletes existing schedule (prevents duplicates)
- Creates single record with all installments
- Returns: LoanRepaymentSchedule

#### recordPayment($schedule, $installmentNumber, $amount, $data)
Records payment for specific installment.
- Updates installment in JSON array
- Recalculates summary counts
- Returns: Updated schedule

#### updateOverdueStatuses($schedule)
Updates overdue status for past-due installments.
- Checks each installment's due date
- Updates status to 'overdue' if past due
- Recalculates counts

## Example Workflow

1. **Loan Approved**: Staff approves loan application
2. **Disbursement Created**: Staff creates disbursement record
3. **Loan Disbursed**: Status changed to 'disbursed'
4. **Schedule Auto-Generated**: 
   - Checks for existing schedule (deletes if found)
   - Grace period: 2 months
   - First payment: Disbursement date + 2 months
   - Single record created with all installments
5. **View Schedule**: Staff views `/repayment-schedules/{schedule}`
6. **Record Payments**: Staff records each installment payment in same form
7. **Status Updates**: Daily cron updates overdue statuses
8. **Completion**: When all installments paid, status → 'completed'

## Benefits

1. **No Duplicates**: One schedule per loan, guaranteed
2. **Resource Efficient**: Single record instead of multiple
3. **Easy Tracking**: All installments in one place
4. **Same Form**: Record all payments in same schedule view
5. **Automatic Summaries**: Counts and totals auto-calculated
6. **Grace Period**: 2-month buffer before first payment
7. **Flexible**: Supports multiple payment frequencies
8. **Real-time**: Instant updates when payments recorded

## Migration

Run migrations to create the new table:

```bash
php artisan migrate
```

## Scheduled Tasks

Add to `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('repayments:update-overdue')->daily();
}
```
