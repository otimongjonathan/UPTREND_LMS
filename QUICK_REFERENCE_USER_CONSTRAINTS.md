# Quick Reference: User Field Constraints

## 🔒 UNIQUE FIELDS (No Duplicates Allowed)
1. **email** - Must be unique across all users
2. **business_name** - Must be unique across all users  
3. **tel_no** - Must be unique across all users

## ✅ REQUIRED FIELDS (Cannot be NULL)
1. **name** - User's full name
2. **business_name** - Business/Company name
3. **address** - Physical address
4. **tel_no** - Telephone number
5. **email** - Email address
6. **password** - Encrypted password
7. **role** - User role (staff/customer)

## 📝 Validation Example

### Creating New User
```php
User::create([
    'name' => 'John Doe',                    // Required
    'business_name' => 'Unique Business',    // Required + Unique
    'address' => '123 Street, City',         // Required
    'tel_no' => '+256700123456',             // Required + Unique
    'email' => 'john@example.com',           // Required + Unique
    'password' => bcrypt('password'),        // Required
    'role' => 'staff',                       // Required
]);
```

### Updating Existing User
```php
$request->validate([
    'business_name' => ['required', 'string', 'max:255', 
        Rule::unique('users')->ignore($user->id)],
    'tel_no' => ['required', 'string', 'max:20', 
        Rule::unique('users')->ignore($user->id)],
    // ... other fields
]);
```

## ⚠️ Common Errors

### Duplicate Business Name
```
SQLSTATE[23000]: Integrity constraint violation: 1062 
Duplicate entry 'Business Name' for key 'users_business_name_unique'
```

### Duplicate Phone Number
```
SQLSTATE[23000]: Integrity constraint violation: 1062 
Duplicate entry '+256700000000' for key 'users_tel_no_unique'
```

### Missing Required Field
```
SQLSTATE[HY000]: General error: 1364 
Field 'business_name' doesn't have a default value
```

## 🧪 Test Commands

```bash
# Check database health
php artisan db:health-check

# Test unique constraint
php artisan tinker
>>> User::create(['email' => 'existing@email.com', ...])
# Should fail if email exists
```

## 📋 Current Users

| Email | Business Name | Tel No | Role |
|-------|---------------|--------|------|
| uptrend@business.com | Uptrend Business Ltd | +256700000000 | staff |
| staff@uptrendlms.com | UPTREND LMS Staff | +256700000001 | staff |
| customer@uptrendlms.com | Customer Business Ltd | +256700000002 | customer |

---
**Remember**: Always validate at application level before database operations!
