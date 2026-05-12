# 🏦 UPTREND LMS - Loan Management System

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-3.x-38B2AC.svg)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A comprehensive, modern Loan Management System built with Laravel 11, featuring dual interfaces for staff and customers with beautiful UI/UX design and complete loan lifecycle management.

## 🌟 Features

### 🏢 Staff Portal
- **Dashboard**: Animated statistics cards with performance metrics
- **Application Management**: Review, approve, and reject loan applications
- **Loan Management**: Track issued loans and pending disbursements
- **Product Management**: Create and manage loan products with provider tracking
- **Borrower Management**: Comprehensive customer profiles and history
- **Repayment Tracking**: Monitor payments and overdue accounts
- **Credit Scoring**: Automated credit assessment system
- **Reports & Analytics**: Performance insights and data visualization

### 👥 Customer Portal
- **Modern Dashboard**: Beautiful interface with burnt orange theme
- **Loan Products**: Browse available loan offerings from different providers
- **Application System**: Submit loan applications with document uploads
- **Status Tracking**: Real-time application status with filters
- **Profile Management**: Update personal and business information
- **Loan History**: View past and current loan details

### 🎨 Design System
- **Consistent Theme**: Burnt orange (#d96a2b) primary color
- **Animations**: Smooth transitions, hover effects, and micro-interactions
- **Responsive Design**: Mobile-first approach with tablet and desktop optimization
- **Modern UI**: Clean, professional interface with gradient cards and shadows

## 🚀 Quick Start

### Prerequisites
- PHP 8.3 or higher
- Composer
- Node.js & npm
- SQLite (development) or MySQL (production)

### Automated Setup

#### Windows
```bash
# Run the automated setup script
setup.bat
```

#### Linux/Mac
```bash
# Make script executable and run
chmod +x setup.sh
./setup.sh
```

### Manual Setup

1. **Clone and Install Dependencies**
```bash
composer install
npm install
```

2. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Setup**
```bash
touch database/database.sqlite  # For SQLite
php artisan migrate
php artisan db:seed --class=LoanProductSeeder
```

4. **Storage and Assets**
```bash
php artisan storage:link
npm run build
```

5. **Start Development Server**
```bash
php artisan serve
```

## 🌐 Access Points

### Staff Portal
- **URL**: `http://localhost:8000/login`
- **Default Admin**: 
  - Email: `admin@uptrendlms.com`
  - Password: `password123`

### Customer Portal
- **URL**: `http://localhost:8000/customer/login`
- **Registration**: Available at `/customer/register`

## 📊 System Architecture

### Database Schema
```
users (staff & customers)
├── loan_applications (customer requests)
├── loan_products (staff offerings)
├── repayments (payment tracking)
├── loan_disbursements (fund distribution)
├── credit_scores (assessment data)
├── collaterals (asset management)
└── loan_guarantors (guarantor info)
```

### Key Models & Relationships
- **User**: HasMany LoanApplications, LoanProducts
- **LoanApplication**: BelongsTo User, HasMany Repayments
- **LoanProduct**: BelongsTo User (provider), HasMany LoanApplications
- **Repayment**: BelongsTo LoanApplication
- **CreditScore**: BelongsTo User

## 🔧 Configuration

### Environment Variables
```env
# Application
APP_NAME="UPTREND LMS"
APP_ENV=local
APP_DEBUG=true

# Database
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Currency
DEFAULT_CURRENCY=UGX

# File Uploads
MAX_FILE_SIZE=5120  # 5MB
ALLOWED_FILE_TYPES=pdf,jpg,jpeg,png
```

### Loan Configuration
```env
DEFAULT_LOAN_TERM_MONTHS=12
MAX_LOAN_AMOUNT=1000000  # UGX 1M
MIN_LOAN_AMOUNT=50000    # UGX 50K
DEFAULT_INTEREST_RATE=15.0
```

## 🎯 Core Workflows

### Customer Journey
1. **Registration** → Create customer account
2. **Browse Products** → View available loan offerings
3. **Apply** → Submit application with documents
4. **Track Status** → Monitor application progress
5. **Manage Loans** → View approved loans and repayments

### Staff Workflow
1. **Dashboard** → Overview of system metrics
2. **Review Applications** → Approve/reject customer requests
3. **Manage Products** → Create and configure loan offerings
4. **Process Disbursements** → Release approved funds
5. **Monitor Repayments** → Track payment schedules

## 🛡️ Security Features

- **Role-Based Access Control**: Staff and customer separation
- **CSRF Protection**: All forms protected
- **Input Validation**: Comprehensive request validation
- **File Upload Security**: Type and size restrictions
- **Password Hashing**: Bcrypt encryption
- **Session Management**: Secure session handling

## 📱 Responsive Design

### Breakpoints
- **Mobile**: 320px - 768px
- **Tablet**: 768px - 1024px
- **Desktop**: 1024px+

### Features
- Touch-friendly interfaces
- Optimized navigation for mobile
- Responsive tables and cards
- Adaptive typography

## 🧪 Testing

### Run Tests
```bash
# All tests
php artisan test

# Specific test suite
php artisan test --testsuite=Feature

# With coverage
php artisan test --coverage
```

### Test Structure
```
tests/
├── Feature/           # Integration tests
│   ├── Auth/         # Authentication tests
│   ├── LoanTest.php  # Loan functionality
│   └── ...
└── Unit/             # Unit tests
    └── ...
```

## 📈 Performance Optimization

### Caching
```bash
# Optimize for production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database
- Indexed foreign keys
- Optimized queries with relationships
- Pagination for large datasets

### Assets
- Vite for fast builds
- CSS/JS minification
- Image optimization ready

## 🚀 Deployment

### Production Setup
1. **Server Requirements**
   - PHP 8.3+ with extensions
   - MySQL 8.0+ or PostgreSQL
   - Redis (recommended)
   - SSL certificate

2. **Environment**
```bash
cp .env.production .env
# Configure production values
```

3. **Optimization**
```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan optimize
```

### Docker Support
```dockerfile
# Dockerfile included for containerization
docker build -t uptrend-lms .
docker run -p 8000:8000 uptrend-lms
```

## 📚 Documentation

- **[Sync Plan](SYNC_PLAN.md)**: Complete synchronization guide
- **[Quick Start](QUICK_START_GUIDE.md)**: Fast setup instructions
- **[Features Guide](LMS_FEATURES_GUIDE.md)**: Detailed feature documentation
- **[API Documentation](docs/api.md)**: API endpoints and usage

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation
- Use conventional commits

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

### Getting Help
- **Documentation**: Check the docs/ directory
- **Issues**: Create GitHub issue with details
- **Email**: support@uptrendlms.com

### Common Issues
1. **Permission Errors**: Check storage/ and bootstrap/cache/ permissions
2. **Database Issues**: Verify .env database configuration
3. **Asset Problems**: Run `npm run build` and clear cache

## 🎯 Roadmap

### Version 1.1
- [ ] Mobile app (React Native)
- [ ] Advanced reporting dashboard
- [ ] SMS notifications
- [ ] Payment gateway integration

### Version 1.2
- [ ] Multi-language support
- [ ] Advanced credit scoring
- [ ] Document OCR processing
- [ ] API for third-party integrations

## 👥 Team

- **Lead Developer**: AI Assistant
- **UI/UX Design**: Modern responsive design
- **Backend Architecture**: Laravel best practices
- **Database Design**: Optimized relational structure

---

**Built with ❤️ using Laravel 11 and modern web technologies**

*UPTREND LMS - Making loan management simple, efficient, and beautiful.*