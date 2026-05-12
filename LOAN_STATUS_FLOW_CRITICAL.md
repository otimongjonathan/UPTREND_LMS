# LOAN STATUS FLOW - CRITICAL DOCUMENTATION

## ⚠️ IMPORTANT: MONEY MATTERS - ACCURATE STATUS TRACKING

This document defines the **EXACT** flow of loan statuses from application to completion.

---

## 📋 LOAN STATUS DEFINITIONS

### 1. **pending** - Initial Application
- **When**: Customer submits a loan application
- **Location**: Applications page
- **Action Required**: Staff review and approve/reject
- **Money Status**: No funds involved yet

### 2. **approved** - PENDING ISSUE ⏳
- **When**: Staff approves the loan application
- **Location**: **LOANS PAGE → PENDING ISSUE SECTION**
- **Action Required**: **AWAITING DISBURSEMENT OF FUNDS**
- **Money Status**: Approved but NOT YET DISBURSED
- **Critical**: Loan is approved but customer has NOT received money yet

### 3. **active** - ISSUED ✅
- **When**: **FUNDS HAVE BEEN DISBURSED** to customer
- **Location**: **LOANS PAGE → ISSUED SECTION**
- **Action Required**: Monitor repayments
- **Money Status**: **FUNDS DISBURSED** - Customer has received the money
- **Critical**: This is when money actually leaves the business

### 4. **completed** - Fully Repaid ✔️
- **When**: All repayments completed
- **Location**: LOANS PAGE → COMPLETED SECTION
- **Action Required**: Archive/record keeping
- **Money Status**: All funds repaid

### 5. **overdue** - Payment Overdue 🚨
- **When**: Repayment deadline passed without payment
- **Location**: LOANS PAGE → OVERDUE SECTION
- **Action Required**: Follow up with customer
- **Money Status**: Outstanding balance exists

### 6. **rejected** - Application Rejected ❌
- **When**: Staff rejects the application
- **Location**: Applications page
- **Action Required**: None
- **Money Status**: No funds involved

---

## 🔄 COMPLETE LOAN LIFECYCLE FLOW

```
┌─────────────────────────────────────────────────────────────────┐
│                    LOAN LIFECYCLE FLOW                          │
└─────────────────────────────────────────────────────────────────┘

1. CUSTOMER SUBMITS APPLICATION
   ↓
   Status: "pending"
   Location: Applications Page
   Money: No funds involved
   
2. STAFF REVIEWS APPLICATION
   ↓
   Decision: Approve or Reject?
   
   ├─→ REJECT
   │   ↓
   │   Status: "rejected"
   │   Location: Applications Page
   │   Money: No funds involved
   │   END
   │
   └─→ APPROVE
       ↓
       Status: "approved" ⏳ PENDING ISSUE
       Location: LOANS PAGE → PENDING ISSUE SECTION
       Money: APPROVED BUT NOT DISBURSED
       Action: AWAITING DISBURSEMENT
       
       3. STAFF DISBURSES FUNDS 💰
          ↓
          Status: "active" ✅ ISSUED
          Location: LOANS PAGE → ISSUED SECTION
          Money: FUNDS DISBURSED TO CUSTOMER
          Action: Monitor repayments
          
          4. CUSTOMER MAKES REPAYMENTS
             ↓
             ├─→ ON TIME
             │   ↓
             │   Status: "active"
             │   Continue monitoring
             │
             ├─→ LATE/MISSED
             │   ↓
             │   Status: "overdue" 🚨
             │   Location: LOANS PAGE → OVERDUE SECTION
             │   Action: Follow up immediately
             │
             └─→ ALL PAID
                 ↓
                 Status: "completed" ✔️
                 Location: LOANS PAGE → COMPLETED SECTION
                 Money: Fully repaid
                 END
```

---

## 💰 CRITICAL MONEY FLOW POINTS

### Point 1: APPROVAL (Status: approved)
```
✅ Loan is APPROVED
❌ Money is NOT disbursed yet
📍 Appears in: PENDING ISSUE section
⏳ Waiting for: Disbursement
```

### Point 2: DISBURSEMENT (Status: active)
```
✅ Loan is APPROVED
✅ Money IS DISBURSED
📍 Appears in: ISSUED section
💰 Customer has received funds
📊 Repayment tracking begins
```

---

## 🎯 WHERE LOANS APPEAR

### Applications Page
Shows loans with status:
- `pending` - Awaiting review
- `approved` - Approved, awaiting disbursement (also shows in Loans)
- `rejected` - Rejected applications

### Loans Page - PENDING ISSUE Section ⏳
Shows loans with status:
- `approved` - **APPROVED BUT NOT YET DISBURSED**
- **Action**: Click "Issue" button to disburse funds

### Loans Page - ISSUED Section ✅
Shows loans with status:
- `active` - **FUNDS DISBURSED, ACTIVELY BEING REPAID**
- **Money Status**: Customer has received the money

### Loans Page - COMPLETED Section ✔️
Shows loans with status:
- `completed` - Fully repaid

### Loans Page - OVERDUE Section 🚨
Shows loans with status:
- `overdue` - Payment deadline passed

---

## 🔧 TECHNICAL IMPLEMENTATION

### ApplicationController@approve
```php
// When staff approves application
$application->update(['status' => 'approved']);

// Result: Loan appears in PENDING ISSUE section
// Money: NOT disbursed yet
```

### LoanDisbursementController@store
```php
// When staff disburses funds
$loan->update([
    'status' => 'active',  // ← Changes to ISSUED
    'disbursement_date' => $validated['disbursement_date']
]);

// Result: Loan moves to ISSUED section
// Money: DISBURSED to customer
```

---

## 📊 DATABASE STATUS VALUES

| Status | Database Value | Display Name | Section |
|--------|---------------|--------------|---------|
| Pending | `pending` | Pending Review | Applications |
| Approved | `approved` | Pending Issue | Loans - Pending Issue |
| Active | `active` | Issued/Active | Loans - Issued |
| Completed | `completed` | Completed | Loans - Completed |
| Overdue | `overdue` | Overdue | Loans - Overdue |
| Rejected | `rejected` | Rejected | Applications |

---

## ✅ VERIFICATION CHECKLIST

### After Approval
- [ ] Loan status is `approved`
- [ ] Loan appears in PENDING ISSUE section
- [ ] "Issue" button is visible
- [ ] Customer receives "Pending Final Approval" notification
- [ ] Money is NOT disbursed yet

### After Disbursement
- [ ] Loan status is `active`
- [ ] Loan appears in ISSUED section
- [ ] Disbursement date is recorded
- [ ] Customer receives "Loan Disbursed" notification
- [ ] Repayment schedule is generated
- [ ] Money HAS BEEN disbursed

---

## 🚨 COMMON MISTAKES TO AVOID

### ❌ WRONG: Changing status to 'active' on approval
```php
// DON'T DO THIS
$application->update(['status' => 'active']); // On approval
```
**Problem**: Loan appears as ISSUED before money is disbursed!

### ✅ CORRECT: Status 'approved' on approval
```php
// DO THIS
$application->update(['status' => 'approved']); // On approval
```
**Result**: Loan appears in PENDING ISSUE, awaiting disbursement

### ❌ WRONG: Keeping status 'approved' after disbursement
```php
// DON'T DO THIS
// Disburse funds but don't change status
```
**Problem**: Loan stays in PENDING ISSUE even after money is disbursed!

### ✅ CORRECT: Status 'active' after disbursement
```php
// DO THIS
$loan->update(['status' => 'active']); // After disbursement
```
**Result**: Loan moves to ISSUED section after money is disbursed

---

## 📝 STAFF WORKFLOW

### Step 1: Review Application
1. Go to Applications page
2. Click on pending application
3. Review details
4. Click "Approve" or "Reject"

### Step 2: Disburse Funds (For Approved Loans)
1. Go to Loans page
2. Look in **PENDING ISSUE** section
3. Find approved loan
4. Click "Issue" button
5. Fill disbursement form:
   - Disbursement amount
   - Disbursement date
   - Payment method
   - Bank/Mobile Money details
6. Submit form
7. **Money is disbursed**
8. Loan moves to **ISSUED** section

### Step 3: Monitor Repayments
1. Go to Loans page
2. Look in **ISSUED** section
3. Click on loan to view details
4. Monitor repayment schedule
5. Record payments as received

---

## 🎯 SUMMARY

### PENDING ISSUE = APPROVED BUT NOT DISBURSED
- Status: `approved`
- Money: **NOT disbursed**
- Action: **Awaiting disbursement**
- Location: Loans → Pending Issue

### ISSUED = FUNDS DISBURSED
- Status: `active`
- Money: **DISBURSED**
- Action: **Monitor repayments**
- Location: Loans → Issued

---

## 🔍 TESTING SCENARIOS

### Test 1: Approval Flow
1. Create loan application (status: pending)
2. Approve application
3. **Verify**: Status is `approved`
4. **Verify**: Appears in PENDING ISSUE section
5. **Verify**: Money NOT disbursed

### Test 2: Disbursement Flow
1. Find approved loan in PENDING ISSUE
2. Click "Issue" button
3. Complete disbursement form
4. Submit
5. **Verify**: Status is `active`
6. **Verify**: Appears in ISSUED section
7. **Verify**: Disbursement date recorded
8. **Verify**: Customer notified

### Test 3: Complete Flow
1. Submit application → Status: `pending`
2. Approve → Status: `approved` (PENDING ISSUE)
3. Disburse → Status: `active` (ISSUED)
4. Make payments → Status: `active` (ISSUED)
5. Complete all payments → Status: `completed` (COMPLETED)

---

## 📞 SUPPORT

If loans are appearing in the wrong section:
1. Check loan status in database
2. Verify status matches expected value
3. Check controller logic for status updates
4. Review view filters for each section

**Expected Behavior**:
- `approved` → PENDING ISSUE
- `active` → ISSUED
- `completed` → COMPLETED
- `overdue` → OVERDUE

---

**Last Updated**: 2026-05-12  
**Status**: ✅ Verified and Documented  
**Critical**: This flow handles actual money disbursement
