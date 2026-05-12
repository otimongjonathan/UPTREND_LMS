# Loan Status Flow - Complete Guide

## 📊 Status Definitions

### 1. **pending** 
- Initial status when customer submits application
- Appears in: **Applications** page

### 2. **approved** ✅
- Status after staff approves the application
- Appears in: **Loans > Pending Issue** section
- Action: Ready to be issued/disbursed

### 3. **active** 💰
- Status after loan is disbursed
- Appears in: **Loans > Issued** section
- Repayment schedule is generated
- Payments can be recorded

### 4. **completed** ✔️
- Status when all installments are paid
- Appears in: **Loans > Completed** section

### 5. **overdue** 🚨
- Status when payments are past due
- Appears in: **Loans > Overdue** section

### 6. **rejected** ❌
- Status when application is rejected
- Appears in: **Applications** page

---

## 🔄 Complete Workflow

### Step 1: Application Submitted
```
Customer submits loan application
    ↓
Status: 'pending'
    ↓
Appears in: Applications page
```

### Step 2: Application Approved
```
Staff reviews application
    ↓
Clicks "Approve" button
    ↓
Status: 'approved'
    ↓
Appears in: Loans > Pending Issue (X)
```

### Step 3: Loan Issued & Disbursed
```
Staff clicks "Issue" button in Pending Issue section
    ↓
Redirected to Disbursement Form
    ↓
Staff fills form and submits
    ↓
System creates disbursement record
    ↓
Status: 'active'
    ↓
Repayment schedule generated
    ↓
Appears in: Loans > Issued (X)
```

### Step 4: Payments Recorded
```
Staff records installment payments
    ↓
Schedule updates automatically
    ↓
Status remains: 'active'
    ↓
Still in: Loans > Issued (X)
```

### Step 5: Loan Completed
```
All installments paid
    ↓
Status: 'completed'
    ↓
Appears in: Loans > Completed (X)
```

---

## 📍 Where Loans Appear

### Applications Page (`/applications`)
Shows loans with status:
- `pending` - Awaiting approval
- `rejected` - Rejected applications

### Loans Page (`/loans`)

#### Pending Issue Section
Shows loans with status:
- `approved` - Approved but not yet disbursed
- **Action**: Click "Issue" to disburse

#### Issued Section
Shows loans with status:
- `active` - Disbursed and active
- **Action**: View details, record payments

#### Completed Section
Shows loans with status:
- `completed` - Fully paid

#### Overdue Section
Shows loans with status:
- `overdue` - Past due payments

---

## 🎯 Key Points

### Pending Issue vs Issued

**Pending Issue (Approved)**
- Loan is approved ✅
- NOT yet disbursed ❌
- NO repayment schedule yet ❌
- Waiting for staff to issue/disburse
- Count: Loans with status = 'approved'

**Issued (Active)**
- Loan is approved ✅
- Funds disbursed ✅
- Repayment schedule generated ✅
- Payments being tracked
- Count: Loans with status = 'active'

---

## 💡 Example Timeline

### Loan #123

**Day 1 - Application**
```
Status: pending
Location: Applications page
```

**Day 2 - Approval**
```
Status: approved
Location: Loans > Pending Issue (1)
Action Available: Issue button
```

**Day 3 - Disbursement**
```
Staff clicks "Issue"
Fills disbursement form
Submits

Status: active
Location: Loans > Issued (1)
Repayment Schedule: Generated ✅
```

**Day 4-365 - Active Period**
```
Status: active
Location: Loans > Issued (1)
Staff records payments as they come in
```

**Day 366 - Completion**
```
All installments paid
Status: completed
Location: Loans > Completed (1)
```

---

## 🔍 How to Check Status

### In Database
```sql
-- Pending Issue (Approved but not disbursed)
SELECT * FROM loan_applications WHERE status = 'approved';

-- Issued (Disbursed and active)
SELECT * FROM loan_applications WHERE status = 'active';

-- Check if disbursement exists
SELECT * FROM loan_disbursements WHERE loan_application_id = ?;

-- Check if schedule exists
SELECT * FROM loan_repayment_schedules WHERE loan_application_id = ?;
```

### In Code
```php
// Pending Issue
$pendingIssue = LoanApplication::where('status', 'approved')->count();

// Issued
$issued = LoanApplication::where('status', 'active')->count();

// Check if loan is disbursed
$isDisbursed = $loan->disbursements()->where('status', 'disbursed')->exists();

// Check if schedule exists
$hasSchedule = $loan->loanRepaymentSchedule()->exists();
```

---

## ✅ Current Implementation

The system is **already correctly implemented**:

1. ✅ Approved loans appear in **Pending Issue** section
2. ✅ Active loans appear in **Issued** section
3. ✅ Status changes from `approved` → `active` when disbursed
4. ✅ Repayment schedule generated on disbursement
5. ✅ Counts are accurate

### Verification

**Loans Index View** (`resources/views/loans/index.blade.php`):
```blade
<!-- Pending Issue Section -->
@forelse ($loans->where('status', 'approved') as $loan)
    <!-- Shows approved loans -->
@endforelse

<!-- Issued Section -->
@forelse ($loans->where('status', 'active') as $loan)
    <!-- Shows active loans -->
@endforelse
```

**Loans Controller** (`app/Http/Controllers/LoanController.php`):
```php
$pendingIssue = (clone $query)->where('status', 'approved')->count();
$active = (clone $query)->where('status', 'active')->count();
```

**Disbursement Controller** (`app/Http/Controllers/LoanDisbursementController.php`):
```php
// Updates loan status to 'active' when disbursed
$loan->update(['status' => 'active']);
```

---

## 🎉 Summary

The system correctly implements the flow:

1. **Application Submitted** → Status: `pending`
2. **Application Approved** → Status: `approved` → **Pending Issue (X)**
3. **Loan Disbursed** → Status: `active` → **Issued (X)**
4. **Loan Completed** → Status: `completed` → **Completed (X)**

**Everything is working as expected!** ✅
