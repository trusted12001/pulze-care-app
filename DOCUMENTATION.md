# Pulze Care Management System - Comprehensive Documentation

## Table of Contents
1. [Overview](#overview)
2. [Architecture](#architecture)
3. [User Roles & Permissions](#user-roles--permissions)
4. [Core Features](#core-features)
5. [Data Models](#data-models)
6. [API Endpoints](#api-endpoints)
7. [User Interface](#user-interface)
8. [Setup & Installation](#setup--installation)
9. [Configuration](#configuration)
10. [Security](#security)
11. [Development Guidelines](#development-guidelines)

## Overview

**Pulze Care Management System** is a comprehensive SaaS platform built with Laravel 11, designed specifically for care home management, supported living, domiciliary care, and residential centers. The system provides GPS-verified staff actions, shift management, care tracking, and insightful reporting.

### Key Features
- Multi-tenant architecture for multiple care organizations
- Comprehensive staff management and HR records
- Service user profiles and care planning
- Risk assessment and management
- Shift scheduling and attendance tracking
- Document management and compliance tracking
- Role-based access control

## Architecture

### Technology Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Breeze with role-based permissions
- **Permissions**: Spatie Laravel Permission package
- **Build Tools**: Vite for asset compilation

### Application Structure
```
app/
├── Console/Commands/          # Artisan commands
├── Enums/                    # Application enums
├── Http/Controllers/         # Route controllers
├── Models/                   # Eloquent models
├── Notifications/           # Email notifications
└── Providers/               # Service providers
```

## User Roles & Permissions

### Role Hierarchy
1. **Super Admin** - System-wide access across all tenants
2. **Admin** - Organization-level administration
3. **Carer** - Frontend care staff interface

### Permission System
- Uses Spatie Laravel Permission package
- Role-based access control (RBAC)
- Middleware-protected routes
- Tenant-scoped data access

## Core Features

### 1. Multi-Tenant Management

#### Tenant Management (Super Admin)
- Create and manage multiple care organizations
- Tenant-specific data isolation
- Subscription status management
- Contact information and billing details

**Key Models:**
- `Tenant` - Organization information
- `User` - System users with tenant association

### 2. User Management

#### User Administration
- Create and manage system users
- Assign roles and permissions
- Soft delete functionality
- User profile management

**Features:**
- User creation and editing
- Role assignment
- Account status management
- Password reset functionality

### 3. Staff Management

#### Staff Profiles
Comprehensive staff record management including:

**Core Information:**
- Personal details and contact information
- Employment status and type
- Job titles and reporting structure
- Work location assignments

**HR Records:**
- Employment contracts
- Professional registrations (NMC, GPHC)
- Employment checks and background verification
- Visa and right-to-work documentation
- Training records and certifications
- Qualifications and professional development
- Occupational health clearances
- Immunization records
- Payroll information
- Bank account details
- Leave entitlements and records
- Availability preferences
- Emergency contacts
- Equality and diversity data
- Disciplinary records
- Document management

**Key Models:**
- `StaffProfile` - Main staff record
- `StaffContract` - Employment contracts
- `StaffRegistration` - Professional registrations
- `StaffEmploymentCheck` - Background checks
- `StaffTrainingRecord` - Training history
- `StaffQualification` - Qualifications
- `StaffPayroll` - Payroll information
- `StaffLeaveRecord` - Leave management

### 4. Service User Management

#### Service User Profiles
Comprehensive resident/client management including:

**Personal Information:**
- Identity and demographic data
- Contact details and addresses
- Medical information and diagnoses
- Allergies and dietary requirements
- Mobility and communication needs

**Care Planning:**
- Admission and discharge dates
- Care plan development and management
- Risk assessments
- Medical history and conditions

**Legal & Consent:**
- Mental capacity assessments
- DNACPR status
- Deprivation of Liberty Safeguards (DoLS)
- Lasting Power of Attorney (LPA)
- Advanced decisions

**Key Models:**
- `ServiceUser` - Main resident/client record
- `CarePlan` - Care planning documents
- `RiskAssessment` - Risk management

### 5. Care Planning System

#### Care Plan Management
- Create and manage individual care plans
- Section-based organization
- Goal setting and intervention tracking
- Review and sign-off processes
- Version control and audit trails

**Care Plan Structure:**
- **Sections**: Identity & Inclusion, Health, Nutrition, Medication, Mobility, Communication, Personal Care, Emotional Wellbeing, Risk Management, Preferences
- **Goals**: Specific objectives within each section
- **Interventions**: Actions to achieve goals
- **Reviews**: Regular assessment and updates
- **Sign-offs**: Approval workflows

**Key Models:**
- `CarePlan` - Main care plan document
- `CarePlanSection` - Plan sections
- `CarePlanGoal` - Specific goals
- `CarePlanIntervention` - Actions and interventions
- `CarePlanReview` - Review records
- `CarePlanSignoff` - Approval records
- `CarePlanVersion` - Version history

**Status Enums:**
- `CarePlanStatus`: Draft, Active, Archived

### 6. Risk Assessment & Management

#### Risk Management System
- Comprehensive risk assessment creation
- Risk scoring and banding (Low, Medium, High)
- Risk control measures
- Review scheduling and tracking
- Risk insights and reporting

**Risk Assessment Features:**
- Likelihood and severity scoring (1-5 scale)
- Automatic risk score calculation
- Risk band assignment (Low/Medium/High)
- Context and description fields
- Review frequency management
- Approval workflows

**Risk Controls:**
- Control measure definition
- Implementation tracking
- Effectiveness monitoring

**Key Models:**
- `RiskAssessment` - Main risk assessment
- `RiskType` - Risk categories
- `RiskControl` - Control measures
- `RiskReview` - Review records

**Enums:**
- `RiskBand`: Low, Medium, High
- `RiskStatus`: Active, Inactive, etc.

### 7. Shift Management & Scheduling

#### Shift Planning System
- Shift template creation
- Rota period management
- Automatic shift generation
- Staff assignment
- Attendance tracking

**Shift Features:**
- Template-based shift creation
- Recurring shift patterns
- Staff skill matching
- Break time management
- Status tracking (Draft, Published, Locked)

**Attendance System:**
- GPS-verified check-in/out
- Assignment-based attendance
- Mobile-friendly interface
- Real-time tracking

**Key Models:**
- `ShiftTemplate` - Reusable shift patterns
- `RotaPeriod` - Scheduling periods
- `Shift` - Individual shifts
- `ShiftAssignment` - Staff assignments
- `Attendance` - Check-in/out records

**Enums:**
- `ShiftStatus`: Draft, Published, Locked
- `AssignmentStatus`: Assigned, Accepted, Swapped, Cancelled

### 8. Document Management

#### Document System
- File upload and storage
- Document categorization
- Access control
- Version management
- Expiry tracking and alerts

**Features:**
- Polymorphic document relationships
- Secure file storage
- Document expiry monitoring
- Automatic alert generation

**Key Models:**
- `Document` - File management
- `ExpiryAlert` - Expiry notifications

### 9. Location Management

#### Location System
- Care facility management
- Room and area tracking
- Location-based assignments
- Multi-location support

**Key Models:**
- `Location` - Facility locations

## Data Models

### Core Entity Relationships

```
Tenant (1) ──→ (Many) User
User (1) ──→ (1) StaffProfile
StaffProfile (1) ──→ (Many) StaffContract
StaffProfile (1) ──→ (Many) StaffTrainingRecord
StaffProfile (1) ──→ (Many) StaffQualification

ServiceUser (1) ──→ (Many) CarePlan
ServiceUser (1) ──→ (Many) RiskAssessment
CarePlan (1) ──→ (Many) CarePlanSection
CarePlanSection (1) ──→ (Many) CarePlanGoal
CarePlanGoal (1) ──→ (Many) CarePlanIntervention

ShiftTemplate (1) ──→ (Many) Shift
Shift (1) ──→ (Many) ShiftAssignment
ShiftAssignment (1) ──→ (Many) Attendance
```

### Key Model Attributes

#### User Model
- `first_name`, `last_name`, `other_names`
- `email`, `password`
- `tenant_id` (multi-tenant)
- `status` (active/inactive)
- Soft deletes enabled

#### ServiceUser Model
- Comprehensive personal information
- Medical and care details
- Legal and consent information
- Address and contact details
- Risk flags and assessments
- Placement and funding information

#### StaffProfile Model
- Employment details
- Professional information
- Contact and location data
- Line manager relationships
- Comprehensive HR record links

## API Endpoints

### Authentication Routes
```
POST /login                    # User authentication
POST /logout                   # User logout
POST /register                 # User registration
GET  /verify-email/{id}/{hash} # Email verification
POST /forgot-password          # Password reset request
POST /reset-password           # Password reset
```

### Super Admin Routes
```
GET    /backend/super-admin/users              # User management
POST   /backend/super-admin/users              # Create user
PUT    /backend/super-admin/users/{user}       # Update user
DELETE /backend/super-admin/users/{user}       # Delete user

GET    /backend/super-admin/tenants            # Tenant management
POST   /backend/super-admin/tenants            # Create tenant
PUT    /backend/super-admin/tenants/{tenant}   # Update tenant
DELETE /backend/super-admin/tenants/{tenant}   # Delete tenant
```

### Admin Routes
```
# Staff Management
GET    /backend/admin/staff-profiles           # Staff listing
POST   /backend/admin/staff-profiles           # Create staff
PUT    /backend/admin/staff-profiles/{staff}   # Update staff

# Service User Management
GET    /backend/admin/service-users            # Service users
POST   /backend/admin/service-users            # Create service user
PUT    /backend/admin/service-users/{user}     # Update service user

# Care Planning
GET    /backend/admin/care-plans               # Care plans
POST   /backend/admin/care-plans               # Create care plan
PUT    /backend/admin/care-plans/{plan}        # Update care plan

# Risk Assessment
GET    /backend/admin/risk-assessments         # Risk assessments
POST   /backend/admin/risk-assessments         # Create assessment
PUT    /backend/admin/risk-assessments/{risk}  # Update assessment

# Shift Management
GET    /backend/admin/shift-templates          # Shift templates
POST   /backend/admin/shift-templates          # Create template
GET    /backend/admin/rota-periods             # Rota periods
POST   /backend/admin/rota-periods/{period}/generate # Generate shifts
```

### Carer Routes
```
GET    /frontend/carer                         # Carer dashboard
POST   /frontend/assignments/{assignment}/check-in  # Check in
POST   /frontend/assignments/{assignment}/check-out # Check out
GET    /frontend/handovers/{service_user}/top-risks # Risk overview
```

## User Interface

### Admin Interface
- Comprehensive backend management system
- Tabbed navigation for different modules
- Form-based data entry
- Search and filtering capabilities
- Bulk operations support
- Export functionality

### Carer Interface
- Mobile-friendly frontend
- Simplified care-focused interface
- Check-in/out functionality
- Risk information display
- Assignment management

### Key UI Components
- Responsive design with Tailwind CSS
- Form validation and error handling
- Data tables with pagination
- Modal dialogs for quick actions
- Status badges and indicators
- Print-friendly layouts

## Setup & Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL/PostgreSQL database
- Web server (Apache/Nginx)

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/pulze-care-app.git
   cd pulze-care-app
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate --seed
   ```

5. **Build assets**
   ```bash
   npm run dev
   ```

6. **Start development server**
   ```bash
   php artisan serve
   ```

### Development Commands
```bash
# Run all services
composer run dev

# Run tests
composer run test

# Code formatting
./vendor/bin/pint

# Queue processing
php artisan queue:listen
```

## Configuration

### Environment Variables
```env
APP_NAME="Pulze Care Management"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pulze_care
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
```

### Key Configuration Files
- `config/app.php` - Application settings
- `config/database.php` - Database configuration
- `config/permission.php` - Permission settings
- `config/mail.php` - Email configuration

## Security

### Authentication & Authorization
- Laravel Breeze authentication system
- Role-based access control (RBAC)
- Middleware protection on routes
- CSRF protection on forms
- Password hashing with bcrypt

### Data Protection
- Soft deletes for data retention
- Multi-tenant data isolation
- Input validation and sanitization
- SQL injection prevention
- XSS protection

### File Security
- Secure file upload handling
- File type validation
- Storage path protection
- Access control on documents

## Development Guidelines

### Code Standards
- PSR-12 coding standards
- Laravel Pint for code formatting
- Comprehensive model relationships
- Proper validation rules
- Error handling and logging

### Database Design
- Proper foreign key relationships
- Indexed columns for performance
- Soft deletes for audit trails
- Timestamps for tracking
- JSON columns for flexible data

### Testing
- PHPUnit for unit and feature tests
- Database factories for test data
- Authentication and authorization tests
- Form validation tests

### Documentation
- Comprehensive model documentation
- API endpoint documentation
- User guide and training materials
- Technical documentation for developers

---

## Support & Maintenance

### Regular Maintenance Tasks
- Database optimization
- Log file rotation
- Backup verification
- Security updates
- Performance monitoring

### Monitoring & Alerts
- Application error tracking
- Database performance monitoring
- User activity logging
- System health checks

### Future Enhancements
- Mobile application development
- Advanced reporting and analytics
- Integration with external systems
- Enhanced security features
- Performance optimizations

---

*This documentation is maintained alongside the application codebase and should be updated as new features are added or existing functionality is modified.*
