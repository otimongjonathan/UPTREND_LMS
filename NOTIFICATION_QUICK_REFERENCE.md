# Notification System - Quick Reference

## ✅ Implemented Notifications

### STAFF NOTIFICATIONS (5 types)
1. ✅ **New Loan Application** - When customer submits application
2. ✅ **Repayment Overdue** - When payment is overdue
3. ✅ **Loan Rescheduling Request** - When customer requests rescheduling
4. ✅ **Indulgence Request** - When customer requests payment extension
5. ✅ **Complaint Submitted** - When customer files a complaint

### CUSTOMER NOTIFICATIONS (6 types)
1. ✅ **Account Creation** - Welcome email on registration
2. ✅ **Loan Approved - Pending Final Approval** - Come to office for final approval
3. ✅ **Loan Rejected** - Application not approved
4. ✅ **Loan Disbursement** - Funds have been disbursed
5. ✅ **Loan Overdue** - Payment is overdue
6. ✅ **New Loan Product** - New product available

---

## 📁 Files Created/Updated

### New Notification Classes
- `app/Notifications/AccountCreated.php`
- `app/Notifications/LoanApplicationPendingFinalApproval.php`
- `app/Notifications/RepaymentOverdueStaff.php`
- `app/Notifications/LoanReschedulingRequest.php`
- `app/Notifications/IndulgenceRequest.php`
- `app/Notifications/ComplaintSubmitted.php`

### Existing Notifications (Already Present)
- `app/Notifications/LoanApplicationSubmitted.php`
- `app/Notifications/LoanApplicationApproved.php`
- `app/Notifications/LoanApplicationRejected.php`
- `app/Notifications/LoanDisbursed.php`
- `app/Notifications/RepaymentOverdue.php`
- `app/Notifications/NewLoanProductAvailable.php`

### Services
- `app/Services/ComprehensiveNotificationService.php` (NEW)

### Controllers Updated
- `app/Http/Controllers/Customer/AuthController.php` - Added account creation notification
- `app/Http/Controllers/Customer/LoanController.php` - Added loan submission notification
- `app/Http/Controllers/ApplicationController.php` - Updated approval/rejection notifications

### Commands
- `app/Console/Commands/TestNotifications.php` - Test all notifications

### Documentation
- `EMAIL_NOTIFICATION_SYSTEM.md` - Complete documentation
- `NOTIFICATION_QUICK_REFERENCE.md` - This file

---

## 🚀 Testing Notifications

### Test Individual Notifications
```bash
# List all available tests
php artisan notifications:test

# Test specific notification
php artisan notifications:test account-created
php artisan notifications:test loan-submitted
php artisan notifications:test loan-approved
php artisan notifications:test loan-rejected
php artisan notifications:test loan-disbursed
php artisan notifications:test repayment-overdue
php artisan notifications:test loan-rescheduling
php artisan notifications:test indulgence-request
php artisan notifications:test complaint
php artisan notifications:test new-product
```

### Check Database Notifications
```bash
php artisan tinker
>>> User::first()->notifications
>>> User::first()->unreadNotifications
```

---

## 📧 Email Configuration

Ensure `.env` has correct SMTP settings:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=jonathanotimong@gmail.com
MAIL_PASSWORD=pkdhtitgrczijidu
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="jonathanotimong@gmail.com"
MAIL_FROM_NAME="UPTREND LMS"
```

---

## 💻 Usage Examples

### Customer Registration (Account Created)
```php
// Automatically sent in Customer\AuthController@register
ComprehensiveNotificationService::notifyAccountCreated($user);
```

### Loan Application Submitted (Staff Alert)
```php
// Automatically sent in Customer\LoanController@store
ComprehensiveNotificationService::notifyStaffNewApplication($application);
```

### Loan Approved (Customer)
```php
// Automatically sent in ApplicationController@approve
ComprehensiveNotificationService::notifyLoanPendingFinalApproval($application);
```

### Loan Rejected (Customer)
```php
// Automatically sent in ApplicationController@reject
ComprehensiveNotificationService::notifyLoanRejected($application, $reason);
```

### Repayment Overdue (Both Staff & Customer)
```php
ComprehensiveNotificationService::handleOverdueRepayment(
    $application,
    $installmentNumber,
    $amount,
    $lateFee,
    $daysOverdue
);
```

### Loan Rescheduling Request (Staff)
```php
ComprehensiveNotificationService::notifyStaffLoanRescheduling(
    $application,
    'Reason for rescheduling',
    '2026-06-01' // proposed date
);
```

### Indulgence Request (Staff)
```php
ComprehensiveNotificationService::notifyStaffIndulgenceRequest(
    $application,
    'Reason for indulgence',
    30 // extension days
);
```

### Complaint (Staff)
```php
ComprehensiveNotificationService::notifyStaffComplaint(
    $customer,
    'Complaint subject',
    'Complaint description',
    $loanId, // optional
    'high' // priority: normal, high, urgent
);
```

### New Product (All Customers)
```php
ComprehensiveNotificationService::notifyNewLoanProduct($product);
```

---

## 📊 Notification Matrix

| Event | Trigger | Staff | Customer | Auto-Sent |
|-------|---------|-------|----------|-----------|
| Account Created | Registration | ❌ | ✅ | ✅ Yes |
| Loan Submitted | Application submit | ✅ | ❌ | ✅ Yes |
| Loan Approved | Staff approval | ❌ | ✅ | ✅ Yes |
| Loan Rejected | Staff rejection | ❌ | ✅ | ✅ Yes |
| Loan Disbursed | Disbursement | ❌ | ✅ | ⚠️ Manual |
| Repayment Overdue | Cron/Manual | ✅ | ✅ | ⚠️ Manual |
| Rescheduling Request | Customer request | ✅ | ❌ | ⚠️ Manual |
| Indulgence Request | Customer request | ✅ | ❌ | ⚠️ Manual |
| Complaint | Customer complaint | ✅ | ❌ | ⚠️ Manual |
| New Product | Product creation | ❌ | ✅ | ⚠️ Manual |

**Legend:**
- ✅ Yes = Automatically sent
- ⚠️ Manual = Needs to be triggered manually (implement in controllers)

---

## 🔧 Next Steps (Optional Enhancements)

### 1. Implement Scheduled Overdue Checks
Create a scheduled command to check for overdue payments daily:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('loans:check-overdue')->daily();
}
```

### 2. Add Notification Preferences
Allow users to choose which notifications they want to receive.

### 3. SMS Notifications
Integrate SMS gateway for critical notifications.

### 4. Push Notifications
Add browser push notifications for real-time alerts.

### 5. Notification History
Create a UI to view notification history in the dashboard.

---

## ✅ Verification Checklist

- [x] All notification classes created
- [x] ComprehensiveNotificationService implemented
- [x] Controllers updated to send notifications
- [x] Test command created
- [x] Documentation complete
- [ ] Email configuration tested
- [ ] All notifications tested end-to-end
- [ ] Queue workers configured (optional)

---

**Status**: ✅ All notifications implemented and ready for testing  
**Last Updated**: 2026-05-12
