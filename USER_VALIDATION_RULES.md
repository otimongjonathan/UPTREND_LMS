# User Field Validation Rules

## Database Constraints

### Required Fields (NOT NULL)
All user fields are **REQUIRED** and cannot be null:

1. **name** - User's full name
2. **business_name** - Business/Company name
3. **address** - Physical address
4. **tel_no** - Telephone number
5. **email** - Email address
6. **password** - Encrypted password
7. **role** - User role (staff/customer)

### Unique Fields
The following fields must be **UNIQUE** across all users:

1. **email** - No two users can have the same email
2. **business_name** - No two users can have the same business name
3. **tel_no** - No two users can have the same telephone number

### Optional Fields
- **financial_compliance_statement** - File path (nullable)
- **email_verified_at** - Timestamp (nullable)
- **remember_token** - Session token (nullable)

## Validation Rules

### Staff Registration
```php
[
    'name' => ['required', 'string', 'max:255'],
    'business_name' => ['required', 'string', 'max:255', 'unique:users'],
    'address' => ['required', 'string', 'max:500'],
    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    'tel_no' => ['required', 'string', 'max:20', 'unique:users'],
    'financial_compliance_statement' => ['required', 'file', 'mimes:pdf', 'max:5120'],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
]
```

### Customer Registration
```php
[
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'max:255', 'unique:users'],
    'business_name' => ['required', 'string', 'max:255', 'unique:users'],
    'address' => ['required', 'string', 'max:500'],
    'tel_no' => ['required', 'string', 'max:20', 'unique:users'],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
]
```

### Profile Update
```php
[
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
    'business_name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
    'tel_no' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($userId)],
    'address' => ['required', 'string', 'max:500'],
]
```

## Error Messages

### Unique Constraint Violations
When attempting to create/update a user with duplicate values:

- **Email**: "The email has already been taken."
- **Business Name**: "The business name has already been taken."
- **Tel No**: "The tel no has already been taken."

### Required Field Violations
When attempting to create a user without required fields:

- "The [field] field is required."

### Database Level Errors
If validation is bypassed and database constraints are violated:

- **Duplicate Email**: `SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '[email]' for key 'users_email_unique'`
- **Duplicate Business Name**: `SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '[name]' for key 'users_business_name_unique'`
- **Duplicate Tel No**: `SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '[tel]' for key 'users_tel_no_unique'`
- **Missing Required Field**: `SQLSTATE[HY000]: General error: 1364 Field '[field]' doesn't have a default value`

## Testing Unique Constraints

### Test Business Name Uniqueness
```bash
php artisan tinker
>>> User::create([
    'name' => 'Test',
    'business_name' => 'Uptrend Business Ltd', // Existing business name
    'address' => 'Test',
    'tel_no' => '+256700000099',
    'email' => 'unique@test.com',
    'password' => bcrypt('password'),
    'role' => 'customer'
]);
# Should throw: Integrity constraint violation
```

### Test Tel No Uniqueness
```bash
php artisan tinker
>>> User::create([
    'name' => 'Test',
    'business_name' => 'Unique Business',
    'address' => 'Test',
    'tel_no' => '+256700000000', // Existing phone
    'email' => 'unique2@test.com',
    'password' => bcrypt('password'),
    'role' => 'customer'
]);
# Should throw: Integrity constraint violation
```

### Test Email Uniqueness
```bash
php artisan tinker
>>> User::create([
    'name' => 'Test',
    'business_name' => 'Another Business',
    'address' => 'Test',
    'tel_no' => '+256700000098',
    'email' => 'uptrend@business.com', // Existing email
    'password' => bcrypt('password'),
    'role' => 'customer'
]);
# Should throw: Integrity constraint violation
```

## Migration History

### Applied Migrations
1. **2026_05_12_092232_fix_required_fields_make_nullable.php**
   - Made business_name, address, tel_no nullable (REVERTED)

2. **2026_05_12_093020_enforce_required_and_unique_fields_on_users.php**
   - Made business_name, address, tel_no NOT NULL (required)
   - Added UNIQUE constraint on business_name
   - Added UNIQUE constraint on tel_no
   - Email already had UNIQUE constraint from initial migration

## Controllers Updated

### Registration Controllers
- ✅ `App\Http\Controllers\Auth\RegisteredUserController` (Staff)
- ✅ `App\Http\Controllers\Customer\AuthController` (Customer)

### Profile Controllers
- ✅ `App\Http\Controllers\ProfileController` (Staff)
- ✅ `App\Http\Controllers\Customer\ProfileController` (Customer)

### Form Requests
- ✅ `App\Http\Requests\ProfileUpdateRequest`

## Database Schema

```sql
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `business_name` varchar(255) NOT NULL UNIQUE,
  `address` text NOT NULL,
  `tel_no` varchar(255) NOT NULL UNIQUE,
  `financial_compliance_statement` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `role` varchar(255) NOT NULL DEFAULT 'staff',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_business_name_unique` (`business_name`),
  UNIQUE KEY `users_tel_no_unique` (`tel_no`)
);
```

## Best Practices

1. **Always validate at application level** before database operations
2. **Use Rule::unique()->ignore($id)** when updating existing records
3. **Provide clear error messages** to users about uniqueness requirements
4. **Test constraints** after migrations to ensure they work correctly
5. **Document unique fields** in user-facing forms and API documentation

## Sample Valid User Data

### Staff User
```php
[
    'name' => 'John Doe',
    'business_name' => 'Doe Financial Services Ltd',
    'address' => '123 Main Street, Kampala, Uganda',
    'tel_no' => '+256700123456',
    'email' => 'john.doe@doeservices.com',
    'password' => 'SecurePassword123!',
    'role' => 'staff',
]
```

### Customer User
```php
[
    'name' => 'Jane Smith',
    'business_name' => 'Smith Trading Company',
    'address' => '456 Commerce Avenue, Entebbe, Uganda',
    'tel_no' => '+256700654321',
    'email' => 'jane.smith@smithtrading.com',
    'password' => 'SecurePassword456!',
    'role' => 'customer',
]
```

---

**Last Updated**: 2026-05-12
**Status**: ✅ All constraints active and tested
