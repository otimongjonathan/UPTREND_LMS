#!/bin/bash

# UPTREND LMS - Automated Deployment Script
# This script sets up the complete UPTREND LMS system

echo "🚀 UPTREND LMS - Automated Setup Starting..."
echo "=============================================="

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Please run this script from the UPTREND LMS root directory"
    exit 1
fi

# Step 1: Install PHP Dependencies
echo "📦 Installing PHP dependencies..."
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader
    echo "✅ PHP dependencies installed"
else
    echo "❌ Composer not found. Please install Composer first."
    exit 1
fi

# Step 2: Install Node Dependencies
echo "📦 Installing Node.js dependencies..."
if command -v npm &> /dev/null; then
    npm install
    echo "✅ Node.js dependencies installed"
else
    echo "❌ npm not found. Please install Node.js first."
    exit 1
fi

# Step 3: Environment Setup
echo "⚙️ Setting up environment..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "✅ Environment file created"
else
    echo "ℹ️ Environment file already exists"
fi

# Generate application key
php artisan key:generate
echo "✅ Application key generated"

# Step 4: Database Setup
echo "🗄️ Setting up database..."

# Create SQLite database if it doesn't exist
if [ ! -f "database/database.sqlite" ]; then
    touch database/database.sqlite
    echo "✅ SQLite database created"
fi

# Run migrations
php artisan migrate --force
echo "✅ Database migrations completed"

# Run seeders
php artisan db:seed --class=LoanProductSeeder --force
echo "✅ Database seeded with loan products"

# Step 5: Storage Setup
echo "📁 Setting up storage..."
php artisan storage:link
echo "✅ Storage linked"

# Create necessary directories
mkdir -p storage/app/public/loan-documents
mkdir -p storage/app/public/profile-photos
echo "✅ Storage directories created"

# Step 6: Cache and Optimization
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Application optimized"

# Step 7: Build Assets
echo "🎨 Building frontend assets..."
npm run build
echo "✅ Assets built successfully"

# Step 8: Set Permissions (Unix-like systems)
if [[ "$OSTYPE" == "linux-gnu"* ]] || [[ "$OSTYPE" == "darwin"* ]]; then
    echo "🔐 Setting permissions..."
    chmod -R 775 storage bootstrap/cache
    echo "✅ Permissions set"
fi

# Step 9: Create default admin user (optional)
echo "👤 Creating default admin user..."
php artisan tinker --execute="
\$user = new App\Models\User();
\$user->name = 'Admin User';
\$user->email = 'admin@uptrendlms.com';
\$user->password = bcrypt('password123');
\$user->role = 'staff';
\$user->business_name = 'UPTREND Financial Services';
\$user->business_type = 'Financial Institution';
\$user->email_verified_at = now();
\$user->save();
echo 'Admin user created successfully';
"

echo ""
echo "🎉 UPTREND LMS Setup Complete!"
echo "=============================================="
echo ""
echo "📋 Setup Summary:"
echo "  ✅ Dependencies installed"
echo "  ✅ Environment configured"
echo "  ✅ Database setup complete"
echo "  ✅ Storage configured"
echo "  ✅ Assets built"
echo "  ✅ Application optimized"
echo ""
echo "🌐 Access Information:"
echo "  Staff Portal: http://localhost:8000/login"
echo "  Customer Portal: http://localhost:8000/customer/login"
echo ""
echo "🔑 Default Admin Credentials:"
echo "  Email: admin@uptrendlms.com"
echo "  Password: password123"
echo ""
echo "🚀 To start the development server:"
echo "  php artisan serve"
echo ""
echo "📚 For more information, check:"
echo "  - SYNC_PLAN.md"
echo "  - README.md"
echo "  - QUICK_START_GUIDE.md"
echo ""
echo "Happy coding! 🎯"