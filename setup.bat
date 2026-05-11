@echo off
REM UPTREND LMS - Windows Automated Setup Script
REM This script sets up the complete UPTREND LMS system on Windows

echo 🚀 UPTREND LMS - Automated Setup Starting...
echo ==============================================

REM Check if we're in the right directory
if not exist "artisan" (
    echo ❌ Error: Please run this script from the UPTREND LMS root directory
    pause
    exit /b 1
)

REM Step 1: Install PHP Dependencies
echo 📦 Installing PHP dependencies...
where composer >nul 2>nul
if %errorlevel% == 0 (
    composer install --optimize-autoloader
    echo ✅ PHP dependencies installed
) else (
    echo ❌ Composer not found. Please install Composer first.
    pause
    exit /b 1
)

REM Step 2: Install Node Dependencies
echo 📦 Installing Node.js dependencies...
where npm >nul 2>nul
if %errorlevel% == 0 (
    npm install
    echo ✅ Node.js dependencies installed
) else (
    echo ❌ npm not found. Please install Node.js first.
    pause
    exit /b 1
)

REM Step 3: Environment Setup
echo ⚙️ Setting up environment...
if not exist ".env" (
    copy ".env.example" ".env"
    echo ✅ Environment file created
) else (
    echo ℹ️ Environment file already exists
)

REM Generate application key
php artisan key:generate
echo ✅ Application key generated

REM Step 4: Database Setup
echo 🗄️ Setting up database...

REM Create SQLite database if it doesn't exist
if not exist "database\database.sqlite" (
    type nul > "database\database.sqlite"
    echo ✅ SQLite database created
)

REM Run migrations
php artisan migrate --force
echo ✅ Database migrations completed

REM Run seeders
php artisan db:seed --class=LoanProductSeeder --force
echo ✅ Database seeded with loan products

REM Step 5: Storage Setup
echo 📁 Setting up storage...
php artisan storage:link
echo ✅ Storage linked

REM Create necessary directories
if not exist "storage\app\public\loan-documents" mkdir "storage\app\public\loan-documents"
if not exist "storage\app\public\profile-photos" mkdir "storage\app\public\profile-photos"
echo ✅ Storage directories created

REM Step 6: Cache and Optimization
echo ⚡ Optimizing application...
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo ✅ Application optimized

REM Step 7: Build Assets
echo 🎨 Building frontend assets...
npm run build
echo ✅ Assets built successfully

REM Step 8: Create default admin user
echo 👤 Creating default admin user...
php artisan tinker --execute="$user = new App\Models\User(); $user->name = 'Admin User'; $user->email = 'admin@uptrendlms.com'; $user->password = bcrypt('password123'); $user->role = 'staff'; $user->business_name = 'UPTREND Financial Services'; $user->business_type = 'Financial Institution'; $user->email_verified_at = now(); $user->save(); echo 'Admin user created successfully';"

echo.
echo 🎉 UPTREND LMS Setup Complete!
echo ==============================================
echo.
echo 📋 Setup Summary:
echo   ✅ Dependencies installed
echo   ✅ Environment configured
echo   ✅ Database setup complete
echo   ✅ Storage configured
echo   ✅ Assets built
echo   ✅ Application optimized
echo.
echo 🌐 Access Information:
echo   Staff Portal: http://localhost:8000/login
echo   Customer Portal: http://localhost:8000/customer/login
echo.
echo 🔑 Default Admin Credentials:
echo   Email: admin@uptrendlms.com
echo   Password: password123
echo.
echo 🚀 To start the development server:
echo   php artisan serve
echo.
echo 📚 For more information, check:
echo   - SYNC_PLAN.md
echo   - README.md
echo   - QUICK_START_GUIDE.md
echo.
echo Happy coding! 🎯
pause