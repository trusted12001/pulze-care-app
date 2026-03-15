# Pulze Care Management System

Before working on Pulze, read: docs/ReadMeFirst.md

<div align="center">

![Pulze Care](https://img.shields.io/badge/Pulze-Care%20Management-blue?style=for-the-badge&logo=heart)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**A comprehensive SaaS platform for care home management, supported living, domiciliary care, and residential centers.**

[Features](#-features) • [Installation](#-installation) • [Documentation](#-documentation) • [Support](#-support)

</div>

---

## 🏥 Overview

Pulze Care Management System is a modern, multi-tenant SaaS platform designed specifically for care organizations. It provides comprehensive tools for managing staff, residents, care planning, risk assessments, and shift scheduling with GPS-verified attendance tracking.

### 🎯 Target Users

- **Care Homes** - Residential care facilities
- **Supported Living** - Independent living with support
- **Domiciliary Care** - Home-based care services
- **Day Centers** - Community-based care services

---

## ✨ Features

### 👥 **Multi-Tenant Architecture**

- Organization-level data isolation
- Scalable for multiple care providers
- Subscription management and billing

### 👨‍⚕️ **Staff Management**

- Comprehensive HR records and documentation
- Professional registrations (NMC, GPHC)
- Training records and compliance tracking
- Payroll and leave management
- Document management with expiry alerts

### 🏠 **Service User Management**

- Detailed resident/client profiles
- Medical history and care needs tracking
- Legal and consent management
- Admission and discharge processes

### 📋 **Care Planning System**

- Structured care plan development
- Goal setting and intervention tracking
- Review scheduling and approval workflows
- Version control and audit trails

### ⚠️ **Risk Management**

- Comprehensive risk assessments
- Risk scoring and banding (Low/Medium/High)
- Control measures and monitoring
- Review scheduling and tracking

### 📅 **Shift Management**

- Template-based shift creation
- Automatic rota generation
- Staff assignment and skill matching
- GPS-verified attendance tracking

### 📊 **Reporting & Analytics**

- Risk insights and trends
- Staff performance metrics
- Compliance reporting
- Custom dashboards

---

## 🛠️ Technology Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Breeze with Spatie Permissions
- **Build Tools**: Vite
- **Testing**: PHPUnit

---

## 🚀 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 16+ and npm
- MySQL 8.0+ or PostgreSQL 13+
- Web server (Apache/Nginx)

### Quick Start

1. **Clone the repository**

    ```bash
    git clone https://github.com/yourusername/pulze-care-app.git
    cd pulze-care-app
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install Node dependencies**

    ```bash
    npm install
    ```

4. **Environment setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Configure database**

    ```bash
    # Update .env with your database credentials
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=pulze_care
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

6. **Run migrations and seeders**

    ```bash
    php artisan migrate --seed
    ```

7. **Build assets**

    ```bash
    npm run dev
    ```

8. **Start the development server**
    ```bash
    php artisan serve
    ```

Visit `http://localhost:8000` to access the application.

### Development Commands

```bash
# Run all development services
composer run dev

# Run tests
composer run test

# Code formatting
./vendor/bin/pint

# Queue processing
php artisan queue:listen
```

---

## 👥 User Roles

### 🔑 Super Admin

- System-wide access across all tenants
- User and tenant management
- Global system configuration

### 🛠️ Admin

- Organization-level administration
- Staff and service user management
- Care planning and risk assessment
- Shift scheduling and reporting

### 👨‍⚕️ Carer

- Mobile-friendly interface
- GPS-verified check-in/out
- Care task management
- Risk information access

---

## 📚 Documentation

- **[Complete Documentation](./DOCUMENTATION.md)** - Comprehensive feature documentation
- **[API Documentation](./docs/api.md)** - API endpoint reference
- **[User Guide](./docs/user-guide.md)** - End-user documentation
- **[Developer Guide](./docs/developer-guide.md)** - Technical documentation

---

## 🔒 Security Features

- **Multi-tenant data isolation**
- **Role-based access control (RBAC)**
- **CSRF protection**
- **SQL injection prevention**
- **XSS protection**
- **Secure file upload handling**
- **Password hashing with bcrypt**

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage
php artisan test --coverage
```

---

## 📈 Performance

- **Optimized database queries** with proper indexing
- **Eager loading** to prevent N+1 queries
- **Caching** for improved response times
- **Queue processing** for background tasks
- **Asset optimization** with Vite

---

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guidelines](./CONTRIBUTING.md) for details.

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🆘 Support

### Getting Help

- 📖 **Documentation**: Check our [comprehensive documentation](./DOCUMENTATION.md)
- 🐛 **Bug Reports**: [Open an issue](https://github.com/yourusername/pulze-care-app/issues)
- 💡 **Feature Requests**: [Request a feature](https://github.com/yourusername/pulze-care-app/issues)
- 💬 **Discussions**: [Join our discussions](https://github.com/yourusername/pulze-care-app/discussions)

### Commercial Support

For enterprise support, custom development, or consulting services, please contact us at support@pulze.care.

---

## 🏆 Acknowledgments

- Built with [Laravel](https://laravel.com/) - The PHP Framework for Web Artisans
- UI components with [Tailwind CSS](https://tailwindcss.com/)
- Permissions by [Spatie](https://spatie.be/)
- Icons by [Heroicons](https://heroicons.com/)

---

<div align="center">

**Made with ❤️ for the care community**

[Website](https://pulze.care) • [Documentation](./DOCUMENTATION.md) • [Support](mailto:support@pulze.care)

</div>
