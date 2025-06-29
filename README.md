# pulze-care-app

# Pulze Care Management App

A Laravel 11-based care home management system tailored for supported living, domiciliary care, and residential centers. GPS-verified staff actions, shift management, care tracking, and insightful reporting.

## 🔧 Setup

```bash
git clone https://github.com/yourusername/pulze-care-app.git
cd pulze-care-app
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
