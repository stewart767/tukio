# MyCard - Digital Invitation Management System

## Overview

MyCard is a comprehensive digital invitation management system built with Laravel and Tailwind CSS. It allows users to create, customize, and manage digital invitations for various events including weddings, kitchen parties, send-offs, and corporate events.

## Features

### 🎯 Core Features
- **Multi-Category Events**: Wedding, Kitchen Party, Send-off, Corporate Events
- **User Management**: Admin-created users with category-specific access
- **Event Setup**: Customizable event details per category
- **Guest Management**: Import and manage guest lists with invitation types
- **Template System**: Admin-designed templates for each event category
- **Dual Invitation System**: SMS with codes + WhatsApp with QR codes
- **Real-time Reporting**: Delivery status, attendance tracking
- **Invoice Management**: Automated billing system (3000 TSH per guest)
- **Staff Management**: QR code scanning authority for event staff

### 👥 User Roles

#### Super Admin
- Create and manage users
- Design invitation templates
- Generate invoices automatically
- View comprehensive reports

#### Event Admin (User)
- Setup event details
- Manage guest lists
- Send invitations in whatsapp by using the card templates
- View event reports

#### Staff
- Scan QR codes at events
- Mark attendance
- View scanning history

## Technology Stack

- **Backend**: Laravel 12.x
- **Frontend**: Tailwind CSS 4.x
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Auth
- **Icons**: Heroicons
- **Fonts**: Inter, Poppins

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL or SQLite

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd mycard
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   # For SQLite (default)
   touch database/database.sqlite
   
   # For MySQL, update .env with your database credentials
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## Demo Accounts

The system comes with pre-seeded demo accounts:

- **Super Admin**: admin@mycard.com / password
- **Event Admin**: host@mycard.com / password  
- **Staff**: staff@mycard.com / password

## Usage

### For Super Admins
1. Login with admin credentials
2. Create and manage users
3. Design invitation templates
4. View system reports

### For Event Admins
1. Login with host credentials
2. Create events
3. Add guests to events
4. Send invitations
5. Track attendance

### For Staff
1. Login with staff credentials
2. Use QR scanner to check in guests
3. View attendance records

## Project Structure

```
mycard/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── Host/           # Event admin controllers
│   │   └── Staff/          # Staff controllers
│   ├── Models/             # Eloquent models
│   └── Policies/           # Authorization policies
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   ├── views/             # Blade templates
│   │   ├── admin/         # Admin views
│   │   ├── host/          # Event admin views
│   │   ├── staff/         # Staff views
│   │   └── layouts/       # Layout templates
│   ├── css/               # Tailwind CSS
│   └── js/                # JavaScript files
└── routes/
    └── web.php            # Web routes
```

## Key Features Implemented

✅ **Authentication System** - Role-based access control
✅ **Public Website** - Modern marketing site with Tailwind CSS
✅ **Admin Dashboard** - User management and template design
✅ **Host Dashboard** - Event creation and management
✅ **Staff Interface** - QR code scanning and attendance tracking
✅ **Database Models** - Complete data structure
✅ **Responsive Design** - Mobile-friendly interface
✅ **Template System** - Pre-built invitation templates

## Development

### Running in Development Mode
```bash
# Start Laravel server
php artisan serve

# Start Vite for hot reloading
npm run dev
```

### Building for Production
```bash
npm run build
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).