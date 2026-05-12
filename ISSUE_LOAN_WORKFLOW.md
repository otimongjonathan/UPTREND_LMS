# Issue Loan Workflow - Complete Process

## Overview
When a loan is approved, staff must complete the disbursement process which automatically generates the repayment schedule.

---

## Step-by-Step Workflow

### Step 1: Loan Approval
- Staff reviews loan application
- Clicks "Approve" button
- Loan status changes to **"Approved"**

### Step 2: Issue Loan Button
- On approved loan details page
- Staff sees "ISSUE LOAN & DISBURSE" button
- Clicking this button opens the **Disbursement Form**

### Step 3: Disbursement Form
Staff completes the form with:

#### A. Disbursement Details
- **Disbursement Amount**: Amount to disburse (defaults to loan amount)
- **Disbursement Date**: Date of disbursement (defaults to today)
- **Disbursement Method**: Choose from:
  - Bank Transfer (shows bank fields)
  - Mobile Money
  - Cash (shows cash receiver field)
  - Cheque

#### B. Method-Specific Fields

**Bank Transfer:**
- Bank Name
- Account Holder Name
- Account Number
- Transaction ID

**Cash:**
- Cash Received By (person's name)

#### C. Repayment Schedule Configuration
- **Payment Frequency**: Weekly, Bi-Weekly, Monthly, Quarterly
- **Number of Installments**: Auto-calculated based on frequency and loan term
- **Grace Period**: Automatically set to 2 months

**Example:**
```
Loan Term: 12 months
Frequency: Monthly
Installments: 12
Grace Period: 2 months
First Payment: Disbursement date + 2 months
```

#### D. Notes (Optional)
- Any additional information

### Step 4: Submit & Auto-Process
When staff clicks **"Disburse Loan & Generate Schedule"**:

1. **Disbursement Record Created**
   - Status: 'disbursed'
   - All transaction details saved
   - Reference number generated

2. **Loan Status Updated**
   - Status changes to 'active'

3. **Repayment Schedule Generated**
   - ONE schedule record created
   - All installments calculated and stored in JSON
   - Grace period applied (2 months)
   - First payment date = Disbursement date + 2 months
   - All subsequent payments on same day of month

4. **Redirect to Schedule**
   - Staff automatically redirected to view the generated schedule
   - Can see all installments
   - Can start recording payments

---

## Example Timeline

### Scenario
- **Loan Amount**: UGX 1,000,000
- **Disbursement Date**: May 15, 2024
- **Frequency**: Monthly
- **Term**: 12 months

### What Happens

**Disbursement:**
```
Date: May 15, 2024
Amount: UGX 1,000,000
Method: Bank Transfer
Status: Disbursed ✓
```

**Grace Period:**
```
Start: May 15, 2024
End: July 14, 2024
Duration: 2 months
```

**Repayment Schedule Generated:**
```
Total Installments: 12
Installment Amount: ~UGX 83,333 + interest

Installment 1: July 15, 2024   (15th - same as disbursement)
Installment 2: August 15, 2024 (15th)
Installment 3: September 15, 2024 (15th)
...
Installment 12: June 15, 2025 (15th)
```

---

## Routes & Navigation

### Route Flow
```
/loans/{loan}                          (Approved loan details)
    ↓ Click "ISSUE LOAN & DISBURSE"
/loans/{loan}/disbursements/create    (Disbursement form)
    ↓ Submit form
/repayment-schedules/{schedule}       (Generated schedule)
```

### Key Routes
- `GET /loans/{loan}` - View loan details
- `GET /loans/{loan}/disbursements/create` - Disbursement form
- `POST /loans/{loan}/disbursements` - Process disbursement
- `GET /repayment-schedules/{schedule}` - View schedule

---

## Database Changes

### When Disbursement is Submitted

**loan_disbursements table:**
```sql
INSERT INTO loan_disbursements (
    loan_application_id,
    disbursement_amount,
    disbursement_date,
    disbursement_method,
    payment_frequency,
    number_of_installments,
    status,
    approved_by,
    disbursed_by,
    ...
) VALUES (...);
```

**loan_applications table:**
```sql
UPDATE loan_applications 
SET status = 'active' 
WHERE id = ?;
```

**loan_repayment_schedules table:**
```sql
INSERT INTO loan_repayment_schedules (
    loan_application_id,
    loan_disbursement_id,
    total_installments,
    payment_frequency,
    installments, -- JSON array
    grace_period_months,
    first_payment_date,
    ...
) VALUES (...);
```

---

## Benefits of This Workflow

✅ **Single Process**: Disbursement and schedule generation in one flow  
✅ **No Manual Steps**: Everything automated after form submission  
✅ **Validation**: Form validates all required fields  
✅ **Flexibility**: Staff can configure payment frequency and installments  
✅ **Grace Period**: Automatically applied (2 months)  
✅ **Immediate View**: Staff sees generated schedule right away  
✅ **Audit Trail**: All disbursement details recorded  

---

## Staff Instructions

### Quick Guide for Staff

1. **Find Approved Loan**
   - Go to Loans list
   - Find loan with "Approved" status

2. **Click Issue Loan**
   - Open loan details
   - Click "ISSUE LOAN & DISBURSE" button

3. **Fill Disbursement Form**
   - Enter disbursement amount
   - Select disbursement method
   - Fill method-specific fields
   - Confirm payment frequency
   - Verify number of installments
   - Add notes if needed

4. **Submit**
   - Click "Disburse Loan & Generate Schedule"
   - Wait for processing

5. **View Schedule**
   - Automatically redirected to schedule
   - Review all installments
   - Note the 2-month grace period
   - First payment date shown

6. **Record Payments**
   - As payments come in, click "Record Payment" on any installment
   - Enter amount, method, reference
   - Submit

---

## Troubleshooting

### Common Issues

**Issue**: "Only approved loans can be disbursed"
- **Solution**: Ensure loan status is "approved" before issuing

**Issue**: "Loan must be linked to a loan product"
- **Solution**: Assign a loan product to the loan first

**Issue**: Form validation errors
- **Solution**: Check all required fields are filled correctly

**Issue**: Schedule not generated
- **Solution**: Check if disbursement status is "disbursed" in database

---

## Summary

The new workflow ensures:
- Staff completes disbursement details
- Repayment schedule is automatically generated
- Grace period is applied
- All data is recorded
- Staff can immediately start managing repayments

**One form, complete process!** 🎯
