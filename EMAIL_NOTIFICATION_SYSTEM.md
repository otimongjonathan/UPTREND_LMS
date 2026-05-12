# Email & Notification System Documentation

## Overview
UPTREND LMS implements a comprehensive notification system that sends both email and database notifications to staff and customers for various loan management events.

## Notification Channels
- **Email**: Sent via SMTP (configured in .env)
- **Database**: Stored in notifications table for in-app viewing

---

## STAFF NOTIFICATIONS

### 1. New Loan Application Submitted
**Trigger**: When a customer submits a new loan application  
**Recipients**: All staff members  
**Notification Class**: `LoanApplicationSubmitted`

**Email Content**:
- Subject: "New Loan Application Submitted - #[ID]"
- Applicant name
- Loan amount
- Loan purpose
- Action button: "Review Application"

**When Sent**:
```php
// In Customer\LoanController@store
ComprehensiveNotificationService::notifyStaffNewApplication($application);
```

---

### 2. Repayment Overdue (Staff Alert)
**Trigger**: When a loan repayment becomes overdue  
**Recipients**: All staff members  
**Notification Class**: `RepaymentOverdueStaff`

**Email Content**:
- Subject: "ALERT: Overdue Loan Repayment - Loan #[ID]"
- Customer name
- Loan ID
- Installment number
- Original amount
- Late fee
- Total due
- Days overdue
- Action button: "View Loan Details"

**When Sent**:
```php
ComprehensiveNotificationService::notifyStaffRepaymentOverdue(
    $application,
    $installmentNumber,
    $amount,
    $lateFee,
    $daysOverdue
);
```

---

### 3. Loan Rescheduling Request
**Trigger**: When a customer requests to reschedule their loan  
**Recipients**: All staff members  
**Notification Class**: `LoanReschedulingRequest`

**Email Content**:
- Subject: "Loan Rescheduling Request - Loan #[ID]"
- Customer name
- Loan ID
- Original amount
- Reason for rescheduling
- Proposed new date (if provided)
- Action button: "Review Request"

**When Sent**:
```php
ComprehensiveNotificationService::notifyStaffLoanRescheduling(
    $application,
    $reason,
    $proposedDate
);
```

---

### 4. Request for Indulgence
**Trigger**: When a customer requests indulgence (payment extension)  
**Recipients**: All staff members  
**Notification Class**: `IndulgenceRequest`

**Email Content**:
- Subject: "Request for Indulgence - Loan #[ID]"
- Customer name
- Loan ID
- Loan amount
- Reason for indulgence
- Requested extension days (if provided)
- Action button: "Review Request"

**When Sent**:
```php
ComprehensiveNotificationService::notifyStaffIndulgenceRequest(
    $application,
    $reason,
    $extensionDays
);
```

---

### 5. Customer Complaint
**Trigger**: When a customer submits a complaint  
**Recipients**: All staff members  
**Notification Class**: `ComplaintSubmitted`

**Email Content**:
- Subject: "New Customer Complaint - [PRIORITY] Priority"
- Customer name
- Customer email
- Subject
- Priority level
- Related loan ID (if applicable)
- Description
- Action button: "View Complaint"

**When Sent**:
```php
ComprehensiveNotificationService::notifyStaffComplaint(
    $customer,
    $subject,
    $description,
    $loanId,
    $priority
);
```

---

## CUSTOMER NOTIFICATIONS

### 1. Account Creation
**Trigger**: When a new customer account is created  
**Recipients**: The new customer  
**Notification Class**: `AccountCreated`

**Email Content**:
- Subject: "Welcome to UPTREND LMS - Account Created Successfully"
- Welcome message
- Account details (name, email, business, account type)
- Action button: "Login to Your Account"

**When Sent**:
```php
// In Customer\AuthController@register
ComprehensiveNotificationService::notifyAccountCreated($user);
```

---

### 2. Loan Application Approved - Pending Final Approval
**Trigger**: When staff approves a loan application  
**Recipients**: The loan applicant  
**Notification Class**: `LoanApplicationPendingFinalApproval`

**Email Content**:
- Subject: "Loan Approved - Come for Final Approval - Application #[ID]"
- Congratulations message
- Next steps (visit office for final approval)
- Approved loan details (ID, amount, term)
- Required documents list
- Contact information
- Action button: "View Application Details"

**When Sent**:
```php
// In ApplicationController@approve
ComprehensiveNotificationService::notifyLoanPendingFinalApproval($application);
```

---

### 3. Loan Application Rejected
**Trigger**: When staff rejects a loan application  
**Recipients**: The loan applicant  
**Notification Class**: `LoanApplicationRejected`

**Email Content**:
- Subject: "Loan Application Update - Application #[ID]"
- Application details
- Rejection reason
- Encouragement to reapply

**When Sent**:
```php
// In ApplicationController@reject
ComprehensiveNotificationService::notifyLoanRejected($application, $reason);
```

---

### 4. Loan Disbursement
**Trigger**: When a loan is disbursed to customer  
**Recipients**: The loan recipient  
**Notification Class**: `LoanDisbursed`

**Email Content**:
- Subject: "Loan Disbursed - UGX [AMOUNT]"
- Disbursement confirmation
- Loan amount
- Disbursement date
- Repayment schedule information
- Action button: "View Loan Details"

**When Sent**:
```php
ComprehensiveNotificationService::notifyLoanDisbursed($application, $amount);
```

---

### 5. Loan Repayment Overdue
**Trigger**: When a customer's repayment becomes overdue  
**Recipients**: The customer with overdue payment  
**Notification Class**: `RepaymentOverdue`

**Email Content**:
- Subject: "URGENT: Overdue Loan Repayment - Installment #[NUMBER]"
- Overdue payment details
- Loan ID
- Installment number
- Original amount
- Late fee
- Total due
- Days overdue
- Action button: "Make Payment Now"

**When Sent**:
```php
ComprehensiveNotificationService::notifyCustomerLoanOverdue(
    $application,
    $installmentNumber,
    $amount,
    $lateFee,
    $daysOverdue
);
```

---

### 6. New Loan Product Available
**Trigger**: When a new loan product is created  
**Recipients**: All customers  
**Notification Class**: `NewLoanProductAvailable`

**Email Content**:
- Subject: "New Loan Product Available - [PRODUCT NAME]"
- Product name
- Product description
- Interest rate
- Amount range
- Term range
- Action button: "View Product Details"

**When Sent**:
```php
ComprehensiveNotificationService::notifyNewLoanProduct($product);
```

---

## Implementation Guide

### 1. Email Configuration
Configure SMTP settings in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="UPTREND LMS"
```

### 2. Using the Notification Service

```php
use App\Services\ComprehensiveNotificationService;

// Customer notifications
ComprehensiveNotificationService::notifyAccountCreated($user);
ComprehensiveNotificationService::notifyLoanPendingFinalApproval($application);
ComprehensiveNotificationService::notifyLoanDisbursed($application, $amount);

// Staff notifications
ComprehensiveNotificationService::notifyStaffNewApplication($application);
ComprehensiveNotificationService::notifyStaffRepaymentOverdue($application, ...);
ComprehensiveNotificationService::notifyStaffComplaint($customer, ...);

// Combined notifications (both customer and staff)
ComprehensiveNotificationService::handleOverdueRepayment($application, ...);
```

### 3. Queue Configuration (Recommended)
For better performance, configure queue workers:

```env
QUEUE_CONNECTION=database
```

Run queue worker:
```bash
php artisan queue:work
```

### 4. Testing Notifications

```bash
# Test email configuration
php artisan tinker
>>> Notification::route('mail', 'test@example.com')
    ->notify(new \App\Notifications\AccountCreated($user));
```

---

## Notification Summary Table

| Event | Staff | Customer | Notification Class |
|-------|-------|----------|-------------------|
| Account Created | ❌ | ✅ | AccountCreated |
| New Loan Application | ✅ | ❌ | LoanApplicationSubmitted |
| Loan Approved | ❌ | ✅ | LoanApplicationPendingFinalApproval |
| Loan Rejected | ❌ | ✅ | LoanApplicationRejected |
| Loan Disbursed | ❌ | ✅ | LoanDisbursed |
| Repayment Overdue | ✅ | ✅ | RepaymentOverdue / RepaymentOverdueStaff |
| Loan Rescheduling Request | ✅ | ❌ | LoanReschedulingRequest |
| Indulgence Request | ✅ | ❌ | IndulgenceRequest |
| Complaint Submitted | ✅ | ❌ | ComplaintSubmitted |
| New Loan Product | ❌ | ✅ | NewLoanProductAvailable |

---

## Database Notifications

All notifications are also stored in the `notifications` table for in-app viewing.

### Viewing Notifications

```php
// Get unread notifications
$notifications = auth()->user()->unreadNotifications;

// Mark as read
auth()->user()->unreadNotifications->markAsRead();

// Get all notifications
$notifications = auth()->user()->notifications;
```

### Notification Structure
```php
[
    'id' => 'uuid',
    'type' => 'App\\Notifications\\LoanApplicationSubmitted',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => 1,
    'data' => [
        'application_id' => 123,
        'message' => 'New loan application submitted',
        'type' => 'loan_application',
        // ... other data
    ],
    'read_at' => null,
    'created_at' => '2026-05-12 10:00:00',
]
```

---

## Customization

### Customizing Email Templates
Email templates use Laravel's markdown mail templates. Customize them in:
```
resources/views/vendor/notifications/email.blade.php
```

### Adding New Notifications

1. Create notification class:
```bash
php artisan make:notification YourNotification
```

2. Implement the notification:
```php
public function via($notifiable): array
{
    return ['mail', 'database'];
}

public function toMail($notifiable): MailMessage
{
    return (new MailMessage)
        ->subject('Your Subject')
        ->line('Your message')
        ->action('Action Button', url('/'))
        ->line('Thank you!');
}

public function toDatabase($notifiable): array
{
    return [
        'message' => 'Your message',
        'type' => 'your_type',
    ];
}
```

3. Add to ComprehensiveNotificationService:
```php
public static function notifyYourEvent($params)
{
    $recipients->notify(new YourNotification($params));
}
```

---

## Troubleshooting

### Emails Not Sending
1. Check `.env` mail configuration
2. Verify SMTP credentials
3. Check `storage/logs/laravel.log` for errors
4. Test with `php artisan tinker`

### Notifications Not Appearing
1. Verify `notifications` table exists
2. Check User model has `Notifiable` trait
3. Verify notification channels in `via()` method

### Queue Not Processing
1. Ensure queue worker is running: `php artisan queue:work`
2. Check `jobs` table for failed jobs
3. Review `failed_jobs` table

---

**Last Updated**: 2026-05-12  
**Status**: ✅ All notifications implemented and tested
