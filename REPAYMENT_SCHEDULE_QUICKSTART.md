# Repayment Schedule - Quick Start

## What's Different Now?

### OLD WAY ❌
- Multiple database records per loan (one per installment)
- Loan could appear multiple times in lists
- Separate forms for each installment payment

### NEW WAY ✅
- **ONE record per loan** in `loan_repayment_schedules` table
- All installments stored in JSON within that single record
- **Same form** to record all installment payments
- Loan appears only once in the list

## How It Works

### 1. Loan Gets Disbursed
```
Loan #123 → Status: 'disbursed' → Auto-creates ONE schedule record
```

### 2. Schedule Created
```
loan_repayment_schedules
├── id: 1
├── loan_application_id: 123 (UNIQUE - only one schedule per loan)
├── total_installments: 12
├── installments: [JSON array with all 12 installments]
├── installments_paid: 0
├── installments_pending: 12
└── grace_period_months: 2
```

### 3. Recording Payments
Staff opens the schedule and sees ALL installments in one view:
- Installment 1: Due July 15 → Click "Record Payment"
- Installment 2: Due Aug 15 → Click "Record Payment"
- Installment 3: Due Sep 15 → Click "Record Payment"
- ... all in the SAME form/page

### 4. No Duplicates
```sql
-- This ensures only ONE schedule per loan
loan_application_id UNIQUE
```

## Routes

```
/repayment-schedules              → List all loans with schedules
/repayment-schedules/{schedule}   → View ONE loan's full schedule
                                     (shows all installments)
```

## Key Features

✅ **2-month grace period** - First payment due 2 months after disbursement  
✅ **Same day installments** - If disbursed on 15th, all installments due on 15th of each month  
✅ **Single record** - One schedule per loan (no duplicates)  
✅ **All installments visible** - See all payments in one view  
✅ **Same form** - Record any installment payment from same page  
✅ **Auto-summary** - Counts paid/pending/overdue automatically  
✅ **Status tracking** - pending → paid → overdue (auto-updated)  

## Database Tables

### Main Table: `loan_repayment_schedules`
- One row per loan
- Contains JSON array of all installments
- Auto-calculates summaries

### JSON Structure (installments column)
```json
[
  {
    "number": 1,
    "due_date": "2024-07-15",
    "total_amount": 100000,
    "paid_amount": 100000,
    "status": "paid"
  },
  {
    "number": 2,
    "due_date": "2024-08-15",
    "total_amount": 100000,
    "paid_amount": 0,
    "status": "pending"
  }
]
```

## Migration

```bash
php artisan migrate
```

This creates the `loan_repayment_schedules` table.

## Daily Cron

```bash
php artisan repayments:update-overdue
```

Updates installment statuses to 'overdue' if past due date.

## Example Usage

### Loan Disbursed: May 15, 2024

**Grace Period**: May 15 - July 14 (2 months)

**Installment Schedule** (Monthly, 12 installments):
- Installment 1: **July 15, 2024** (same day as disbursement, 2 months later)
- Installment 2: **August 15, 2024** (same day of next month)
- Installment 3: **September 15, 2024** (same day of next month)
- Installment 4: **October 15, 2024** (same day of next month)
- ... and so on, always on the **15th** of each month

### View All Schedules
```
Visit: /repayment-schedules
See: List of all loans with their schedules (one entry per loan)
```

### View Specific Loan Schedule
```
Click: "View Details" on any loan
See: All 12 installments for that loan
Do: Record payment for any installment
```

### Record Payment
```
1. Click "Record Payment" on any installment
2. Enter amount, method, reference
3. Submit
4. Installment updates to "paid" or "partial"
5. Summary counts update automatically
```

## Benefits

1. **Resource Efficient** - 1 record instead of 12+ records per loan
2. **No Confusion** - Loan appears once in lists
3. **Easy Management** - All installments in one place
4. **Same Form** - Record all payments from same view
5. **Auto-tracking** - Summaries calculated automatically

## That's It!

The system now creates ONE schedule per loan with all installments tracked inside it. Staff can manage all payments from a single form without the loan appearing multiple times in lists.
