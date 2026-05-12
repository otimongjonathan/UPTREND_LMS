# 📧 Email & Notification System - UPTREND LMS

## Overview
Comprehensive email and notification system for customers and staff covering all major loan operations.

## 🔔 Notification Types

### 1. **Loan Application Notifications**

#### For Staff: `LoanApplicationSubmitted`
- **Trigger**: When customer submits new loan application
- **Recipients**: Staff member (loan product provider)
- **Channels**: Email + Database
- **Content**:
  - Applicant name
  - Loan amount
  - Loan purpose
  - Link to review application

#### For Customer: `LoanApplicationApproved`
- **Trigger**: When staff approves loan application
- **Recipients**: Customer (applicant)
- **Channels**: Email + Database
- **Content**:
  - Approved amount
  - Interest rate
  - Loan term
  - Link to view loan details

#### For Customer: `LoanApplicationRejected`
- **Trigger**: When staff rejects loan application
- **Recipients**: Customer (applicant)
- **Channels**: Email + Database
- **Content**:
  - Rejection reason (optional)
  - Guidance for reapplication
  - Link to view application

### 2. **Loan Disbursement Notifications**

#### For Customer: `LoanDisbursed`
- **Trigger**: When loan is successfully disbursed
- **Recipients**: Customer (borrower)
- **Channels**: Email + Database
- **Content**:
  - Disbursed amount
  - Disbursement method
  - Disbursement date
  - Transaction reference
  - Link to repayment schedule

### 3. **Repayment Notifications**

#### For Customer: `RepaymentDueReminder`
- **Trigger**: Automated reminders (7, 3, and 1 day before due date)
- **Recipients**: Customer (borrower)
- **Channels**: Email + Database
- **Content**:
  - Loan ID
  - Installment number
  - Amount due
  - Due date
  - Days until due
  - Link to payment page

#### For Customer: `RepaymentOverdue`
- **Trigger**: When payment becomes overdue
- **Recipients**: Customer (borrower)
- **Channels**: Email + Database
- **Content**:
  - Loan ID
  - Installment number
  - Original amount
  - Late fee
  - Total due
  - Days overdue
  - Urgent payment link

#### For Customer: `RepaymentReceived`
- **Trigger**: When payment is successfully recorded
- **Recipients**: Customer (borrower)
- **Channels**: Email + Database
- **Content**:
  - Loan ID
  - Installment number
  - Amount paid
  - Payment date
  - Remaining balance
  - Link to loan details

### 4. **Loan Product Notifications**

#### For All Customers: `NewLoanProductAvailable`
- **Trigger**: When staff creates new active loan product
- **Recipients**: All customers in system
- **Channels**: Email + Database
- **Content**:
  - Product name
  - Interest rate
  - Maximum amount
  - Maximum term
  - Product description
  - Link to apply

## 📁 File Structure

```
app/
├── Notifications/
│   ├── LoanApplicationSubmitted.php
│   ├── LoanApplicationApproved.php
│   ├── LoanApplicationRejected.php
│   ├── LoanDisbursed.php
│   ├── RepaymentDueReminder.php
│   ├── RepaymentOverdue.php
│   ├── RepaymentReceived.php
│   └── NewLoanProductAvailable.php
├── Console/Commands/
│   └── SendRepaymentReminders.php
└── Http/Controllers/
    ├── ApplicationController.php (updated)
    ├── LoanDisbursementController.php (updated)
    ├── RepaymentScheduleController.php (updated)
    ├── LoanProductController.php (updated)
    └── Customer/LoanController.php (updated)
```

## 🚀 Implementation Details

### Controllers Updated

1. **ApplicationController**
   - `approve()`: Sends `LoanApplicationApproved` to customer
   - `reject()`: Sends `LoanApplicationRejected` to customer with optional reason

2. **Customer\LoanController**
   - `store()`: Sends `LoanApplicationSubmitted` to staff (product provider)

3. **LoanDisbursementController**
   - `store()`: Sends `LoanDisbursed` to customer after successful disbursement

4. **RepaymentScheduleController**
   - `recordPayment()`: Sends `RepaymentReceived` to customer after payment

5. **LoanProductController**
   - `store()`: Sends `NewLoanProductAvailable` to all customers when active product created

### Automated Commands

#### `SendRepaymentReminders`
**Command**: `php artisan repayments:send-reminders`

**Schedule**: Should run daily (add to `app/Console/Kernel.php`)

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('repayments:send-reminders')->daily();
}
```

**Functionality**:
- Sends reminders 7, 3, and 1 day before due date
- Sends overdue notices for late payments
- Processes all active loan repayment schedules
- Logs all notifications sent

## 📊 Database Schema

### Notifications Table
```sql
CREATE TABLE notifications (
    id CHAR(36) PRIMARY KEY,
    type VARCHAR(255),
    notifiable_type VARCHAR(255),
    notifiable_id BIGINT UNSIGNED,
    data TEXT,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (notifiable_type, notifiable_id)
);
```

## 🔧 Configuration

### Email Setup (.env)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@uptrendlms.com
MAIL_FROM_NAME="UPTREND LMS"
```

### Queue Configuration (Optional but Recommended)
```env
QUEUE_CONNECTION=database
```

Run queue worker:
```bash
php artisan queue:work
```

## 📝 Usage Examples

### Manual Notification
```php
use App\Notifications\RepaymentDueReminder;

$user->notify(new RepaymentDueReminder(
    loanId: 123,
    installmentNumber: 5,
    amount: 50000,
    dueDate: '2024-06-15',
    daysUntilDue: 3
));
```

### Check Unread Notifications
```php
// Get unread notifications
$unreadNotifications = auth()->user()->unreadNotifications;

// Mark as read
auth()->user()->unreadNotifications->markAsRead();

// Get specific notification
$notification = auth()->user()->notifications()->find($id);
```

## 🎯 Notification Channels

### Email Channel
- Professional HTML emails
- Branded with UPTREND LMS
- Includes action buttons
- Mobile-responsive design

### Database Channel
- Stored in notifications table
- Accessible via user dashboard
- Real-time notification bell
- Mark as read functionality

## 🔄 Workflow Integration

### Application Flow
1. Customer submits application → Staff notified
2. Staff approves → Customer notified
3. Staff rejects → Customer notified with reason

### Disbursement Flow
1. Loan disbursed → Customer notified
2. Repayment schedule generated → Included in notification

### Repayment Flow
1. 7 days before due → Reminder sent
2. 3 days before due → Reminder sent
3. 1 day before due → Urgent reminder sent
4. Payment overdue → Overdue notice sent
5. Payment received → Confirmation sent

### Product Flow
1. New product created → All customers notified
2. Product activated → Notification sent

## 🛠️ Customization

### Modify Email Templates
Each notification class has a `toMail()` method that can be customized:

```php
public function toMail($notifiable): MailMessage
{
    return (new MailMessage)
        ->subject('Custom Subject')
        ->greeting('Hello!')
        ->line('Your custom message')
        ->action('Action Button', url('/'))
        ->line('Thank you!');
}
```

### Add New Notification Types
1. Create notification class: `php artisan make:notification YourNotification`
2. Implement `via()`, `toMail()`, and `toDatabase()` methods
3. Send notification: `$user->notify(new YourNotification())`

## 📈 Monitoring

### Check Notification Status
```bash
# View sent notifications
php artisan tinker
>>> \App\Models\User::find(1)->notifications

# Check failed jobs
php artisan queue:failed
```

### Logs
All notifications are logged in `storage/logs/laravel.log`

## ✅ Testing

### Test Email Configuration
```bash
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

### Test Notifications
```bash
php artisan tinker
>>> $user = \App\Models\User::first();
>>> $user->notify(new \App\Notifications\RepaymentDueReminder(1, 1, 50000, '2024-06-15', 3));
```

### Run Reminder Command
```bash
php artisan repayments:send-reminders
```

## 🎨 Best Practices

1. **Queue Notifications**: Use queues for better performance
2. **Rate Limiting**: Prevent spam by limiting notification frequency
3. **Personalization**: Use customer names and specific details
4. **Clear CTAs**: Include clear action buttons in emails
5. **Mobile-Friendly**: Ensure emails render well on mobile devices
6. **Logging**: Log all notification attempts for debugging
7. **Error Handling**: Gracefully handle notification failures
8. **Testing**: Test notifications in staging before production

## 🔐 Security

- Never include sensitive data in notifications
- Use secure links with tokens for actions
- Validate user permissions before sending
- Sanitize all user input in notifications
- Use HTTPS for all notification links

## 📞 Support

For issues or questions:
- Check logs: `storage/logs/laravel.log`
- Review queue: `php artisan queue:failed`
- Test email config: `php artisan tinker`
- Contact: support@uptrendlms.com

---

**Built with ❤️ for UPTREND LMS**
*Making loan management simple, efficient, and connected.*
