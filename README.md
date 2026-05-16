# Maisiri Policy Renewal System

A lightweight, secure web application for managing insurance policy renewals with role-based access control. Built with PHP 8, MySQL, and modern OOP principles.

## Table of Contents

- [Quick Start](#quick-start)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Project Structure](#project-structure)
- [Architecture](#architecture-overview)
- [Role-Based Access Control](#role-based-access-control)
- [Key Features](#key-features)
- [Business Logic](#business-logic--assumptions)
- [Environment Configuration](#environment-configuration)
- [Security](#security)
- [Development](#development)
- [Usage](#usage)
- [Troubleshooting](#troubleshooting)
- [API Routes](#api-routes)
- [Database Schema](#database-schema)
- [AI Usage Disclosure](#ai-usage-disclosure)

---

## Quick Start

### Prerequisites

- **PHP** 8.0 or higher
- **MySQL** 5.7+ or **MariaDB** 10.2+
- **Apache** with `mod_rewrite` enabled (optional for clean URLs)
- **Git** (for cloning)

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Albayne/maisiri-policy-renewal.git
   cd maisiri-policy-renewal
   ```

2. **Configure environment variables**:
   ```bash
   cp .env.example .env
   nano .env  # Edit with your database credentials
   ```

3. **Create the database**:
   ```bash
   mysql -u root -p < policy_renewal.sql
   ```

4. **Set permissions**:
   ```bash
   chmod 755 uploads/
   chmod 644 public/.htaccess
   ```

5. **Access the application**:
   - URL: `http://localhost/public/`
   - Admin: `admin` / `admin123`
   - Officer: `officer1` / `officer123`
   - ⚠️ **Change default passwords immediately**

---

## Prerequisites

### System Requirements

| Requirement | Minimum | Recommended |
|-------------|---------|-------------|
| PHP | 8.0 | 8.1+ |
| MySQL | 5.7 | 8.0+ |
| Apache | 2.4 | 2.4+ |
| Disk Space | 50 MB | 100 MB |

### PHP Extensions
- `pdo` - PHP Data Objects
- `pdo_mysql` - MySQL driver
- `sessions` - Session support

---

## Installation

### Step-by-Step Setup

#### 1. Clone Repository
```bash
git clone https://github.com/Albayne/maisiri-policy-renewal.git policy-renewal
cd policy-renewal
```

#### 2. Environment Configuration
```bash
cp .env.example .env
# Edit .env with your database credentials
```

Example `.env`:
```env
DB_HOST=localhost
DB_NAME=policy_renewal
DB_USERNAME=root
DB_PASSWORD=your_secure_password
DB_CHARSET=utf8mb4
```

#### 3. Database Setup
```bash
# Via command line
mysql -u root -p < policy_renewal.sql

# Or via phpMyAdmin:
# 1. Create database 'policy_renewal'
# 2. Import policy_renewal.sql
# 3. Set collation to utf8mb4_general_ci
```

#### 4. File Permissions
```bash
chmod 755 uploads/
chmod 644 public/.htaccess
```

#### 5. Web Server Configuration

**Apache - Virtual Host**:
```apache
<VirtualHost *:80>
    ServerName policy-renewal.local
    DocumentRoot /var/www/html/policy-renewal/public
    
    <Directory /var/www/html/policy-renewal/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Nginx**:
```nginx
server {
    listen 80;
    server_name policy-renewal.local;
    root /var/www/html/policy-renewal/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

---

## Project Structure

```
maisiri-policy-renewal/
├── .env                          # Environment variables (DO NOT COMMIT) ⚠️
├── .env.example                  # Template for .env
├── .gitignore                    # Git ignore rules
├── README.md                     # Documentation
│
├── config/
│   └── database.php              # Database configuration
│
├── classes/
│   └── Database.php              # Singleton PDO class
│
├── middleware/
│   └── Auth.php                  # Authentication middleware
│
├── models/
│   ├── User.php                  # User CRUD
│   ├── Policy.php                # Policy CRUD
│   └── Document.php              # Document management
│
├── controllers/
│   ├── AuthController.php        # Login/logout
│   ├── DashboardController.php   # Dashboard
│   ├── PolicyController.php      # Policy operations
│   └── UserController.php        # User management
│
├── views/
│   ├── layout/
│   │   ├── header.php            # Header with navigation
│   │   └── footer.php            # Footer
│   ├── auth/
│   │   └── login.php             # Login form
│   ├── dashboard/
│   │   └── index.php             # Dashboard
│   ├── policies/
│   │   ├── list.php              # Policies list
│   │   ├── add.php               # Add policy
│   │   ├── edit.php              # Edit policy
│   │   └── view.php              # Policy details
│   ├── users/
│   │   ├── list.php              # Users list
│   │   ├── add.php               # Add user
│   │   └── edit.php              # Edit user
│   └── error/
│       ├── 403.php               # Forbidden
│       └── 404.php               # Not found
│
├── public/
│   ├── index.php                 # Front controller
│   ├── .htaccess                 # Apache rewrite
│   └── assets/
│       ├── css/style.css         # Stylesheet
│       └── logo/logo.png         # Logo
│
├── uploads/                      # User documents (git-ignored)
├── policy_renewal.sql            # Database schema
└── seed_users.sql                # Test data
```

---

## Architecture Overview

### MVC Design Pattern

```
HTTP Request → public/index.php (Front Controller)
             ↓
         Controller (request handling, authorization)
             ↓
         Model (database operations)
             ↓
         View (PHP templates, Bootstrap UI)
             ↓
         HTTP Response
```

### Key Components

**Models** - Database operations using PDO prepared statements
- User authentication and CRUD
- Policy management (create, read, update, delete)
- Document file management
- Data validation and business logic

**Controllers** - Request handling and authorization
- Process HTTP requests
- Enforce role-based access control
- Call appropriate models
- Load views with data

**Views** - PHP templates with Bootstrap 5
- Responsive UI components
- Form rendering and validation
- Conditional content based on user role
- Error and success messages

**Middleware** - Authentication and authorization
- Session management
- User role verification
- Access control enforcement
- Permission checking

**Database Layer** - Singleton PDO connection
- Centralized database access
- Environment-based configuration
- Connection pooling
- Error handling

---

## Role-Based Access Control

### Three-Tier System

#### 1. Admin (Full Access)
| Feature | Access |
|---------|--------|
| Dashboard | ✅ Full |
| Policies | ✅ CRUD |
| Documents | ✅ Manage |
| Users | ✅ Full Control |
| System Config | ✅ All |

- Create and manage all users
- View all policies and documents
- Access complete statistics
- Deactivate/activate accounts

#### 2. Policy Officer (Core Operations)
| Feature | Access |
|---------|--------|
| Dashboard | ✅ Limited |
| Policies | ✅ CRUD |
| Documents | ✅ Manage |
| Users | ❌ None |

- Add, edit, delete policies
- Upload and manage documents
- View dashboard statistics
- No user management access

#### 3. Viewer (Read-Only)
| Feature | Access |
|---------|--------|
| Dashboard | ✅ View |
| Policies | ✅ Read |
| Documents | ✅ Download |
| Modifications | ❌ None |

- View policies and details
- Download documents
- See dashboard summaries
- No modification capabilities

### Access Control Enforcement

**Navigation Level** - Menu items based on role:
```php
<?php if (Auth::isAdmin()): ?>
    <a href="?action=users">Manage Users</a>
<?php endif; ?>
```

**Controller Level** - Authorization checks:
```php
Auth::requireRole('admin');  // Only admins can access
Auth::requireAuth();         // Any logged-in user
```

---

## Key Features

### 🔐 Security
✅ Password hashing (BCRYPT)  
✅ SQL injection prevention (prepared statements)  
✅ Session-based authentication  
✅ Environment-based configuration  
✅ Role-based access control  

### 📋 Policy Management
✅ Full CRUD operations  
✅ Policy status tracking  
✅ Renewal date management  
✅ Premium amount tracking  
✅ Policy search and filtering  

### 📄 Document Management
✅ File upload (JPG, PNG, PDF)  
✅ 5MB file size limit  
✅ Document linking to policies  
✅ Download capability  
✅ Automatic file cleanup  

### 📊 Dashboard & Analytics
✅ Real-time statistics  
✅ Total policies count  
✅ Active/expired tracking  
✅ Renewal status overview  
✅ 30-day renewal alerts  

### 👥 User Management
✅ User account creation  
✅ Role assignment  
✅ Account deactivation  
✅ User status tracking  

### 🎨 User Interface
✅ Bootstrap 5 responsive design  
✅ Mobile-friendly layout  
✅ Clean navigation  
✅ Intuitive forms  
✅ Form validation  

---

## Business Logic & Assumptions

### Policy Management
- **Status**: Manually set by officers/admins (not automatic)
- **Renewal Alerts**: Policies within 30 days of renewal shown in dashboard
- **Expiration**: Determined by status field, not solely by date
- **Calculations**: Dynamic on each dashboard load

### User Management
- **Self-Deletion**: Prevented for data integrity
- **Deactivation**: Admins can deactivate instead of delete
- **Default Passwords**: Must be changed on first login
- **Recovery**: Admins can reactivate deactivated accounts

### Document Handling
- **Multiple Files**: Allowed per policy
- **Format Restrictions**: JPG, PNG, PDF only
- **Size Limit**: 5MB per file
- **Upload Method**: One file at a time
- **File Deletion**: Original file removed when document deleted

### Authentication
- **Session-Based**: Uses PHP sessions for login state
- **No "Remember Me"**: Must login on each browser/device
- **Password Hashing**: BCRYPT algorithm
- **Verification**: `password_verify()` for authentication

---

## Environment Configuration

### .env File
```env
DB_HOST=localhost              # Database host
DB_NAME=policy_renewal         # Database name
DB_USERNAME=root               # Database user
DB_PASSWORD=your_password      # Database password
DB_CHARSET=utf8mb4            # Character set
```

### Configuration Levels

**Development**:
```env
DB_HOST=localhost
DB_NAME=policy_renewal_dev
```

**Staging**:
```env
DB_HOST=staging.example.com
DB_NAME=policy_renewal_staging
```

**Production**:
```env
DB_HOST=prod.example.com
DB_NAME=policy_renewal_prod
DB_PASSWORD=strong_secure_password
```

---

## Security

### Critical Practices

**1. Environment Variables**
- Store credentials in `.env` file
- Never commit `.env` to version control
- Different credentials per environment

**2. Password Security**
- Hashed with `password_hash()` + BCRYPT
- Never store plain-text passwords
- Verified with `password_verify()`
- Change default passwords

**3. SQL Injection Prevention**
- Use PDO prepared statements
- Parameterized queries only
- Never concatenate user input

**4. Access Control**
- Check authentication on all routes
- Verify roles before actions
- Unauthorized = 403 error
- Hide menu items by role

**5. File Upload Security**
- Whitelist allowed file types
- Limit file size (5MB max)
- Validate MIME types
- Generate random filenames

---

## Development

### Adding New Features

**1. Create Model**:
```php
class NewFeature {
    public function getAll() { /* ... */ }
}
```

**2. Create Controller**:
```php
class NewFeatureController {
    public function index() {
        Auth::requireRole('admin');
        $model = new NewFeature();
        require '../views/view.php';
    }
}
```

**3. Create Views** in `views/` directory

**4. Add Route** in `public/index.php`:
```php
case 'feature':
    (new NewFeatureController())->index();
    break;
```

---

## Usage

### First Login
1. Navigate to `http://localhost/public/`
2. Login: `admin` / `admin123`
3. **Change password immediately**

### Creating a Policy
1. Click "Policies" menu
2. Click "Add New Policy"
3. Fill details (policy number, client, dates, etc.)
4. Click "Save"

### Uploading Documents
1. View policy details
2. Scroll to "Documents"
3. Click "Upload Document"
4. Select file (JPG, PNG, PDF)
5. Click "Upload"

### Managing Users (Admin)
1. Click "Users" menu (admin only)
2. Click "Add User"
3. Enter username, password, role
4. Click "Create"

---

## Troubleshooting

### Common Issues

**Database Connection Error**
- Check MySQL is running
- Verify `.env` credentials
- Ensure database created: `mysql -u root -p < policy_renewal.sql`

**404 Errors**
- Verify `.htaccess` enabled
- Check `mod_rewrite` loaded
- Restart Apache

**Permission Denied (403)**
- Check user role permissions
- Verify logged in status
- Try different user account

**File Upload Fails**
- Check `uploads/` writable: `chmod 755 uploads/`
- File < 5MB
- File type: JPG, PNG, PDF
- Disk space available

**Blank Page**
- Check PHP error log
- Verify database connection
- Check all files exist

---

## API Routes

### Authentication
| Action | Method | Purpose |
|--------|--------|---------|
| `login` | GET | Show login form |
| `login_post` | POST | Process login |
| `logout` | GET | Logout user |

### Dashboard
| Action | Method | Purpose |
|--------|--------|---------|
| `dashboard` | GET | Show dashboard |

### Policies
| Action | Method | Purpose |
|--------|--------|---------|
| `policies` | GET | List policies |
| `view_policy` | GET | View details |
| `add_policy` | GET | Show add form |
| `add_policy_post` | POST | Create policy |
| `edit_policy` | GET | Show edit form |
| `edit_policy_post` | POST | Update policy |
| `delete_policy` | POST | Delete policy |

### Documents
| Action | Method | Purpose |
|--------|--------|---------|
| `upload_document` | POST | Upload file |
| `download_document` | GET | Download file |

### Users (Admin)
| Action | Method | Purpose |
|--------|--------|---------|
| `users` | GET | List users |
| `add_user` | GET | Show add form |
| `add_user_post` | POST | Create user |
| `edit_user` | GET | Show edit form |
| `edit_user_post` | POST | Update user |
| `deactivate_user` | POST | Deactivate user |

---

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'officer', 'viewer') NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Policies Table
```sql
CREATE TABLE policies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    policy_number VARCHAR(50) NOT NULL UNIQUE,
    client_name VARCHAR(100) NOT NULL,
    insurance_type VARCHAR(100) NOT NULL,
    premium_amount DECIMAL(10,2) NOT NULL,
    start_date DATE NOT NULL,
    renewal_date DATE NOT NULL,
    status ENUM('Active', 'Expired', 'Pending Renewal') DEFAULT 'Active',
    created_by INT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

### Documents Table
```sql
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    policy_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_type VARCHAR(20) NOT NULL,
    uploaded_by INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (policy_id) REFERENCES policies(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);
```

---

## AI Usage Disclosure

This system was created by **Blessing I Maisiri** as part of the **ZIMNAT Software Developer Internship** assignment.

### AI Assistance

Anthropic's **Claude AI** was utilized for:

✅ **Architecture & Design** - MVC pattern, database design, class structure  
✅ **Code Optimization** - Performance, clarity, maintainability  
✅ **UI/UX Design** - Bootstrap integration, responsive layout  
✅ **Code Review** - Debugging, security, correctness  
✅ **Documentation** - README, API docs, comments  

---

## Support

For issues or questions:
1. Check [Troubleshooting](#troubleshooting) section
2. Review [Usage](#usage) guide
3. Consult source code comments
4. Contact development team

---

**Version**: 1.0  
**Last Updated**: May 2026  
**Author**: Blessing I Maisiri  
**AI Assistant**: Anthropic's Claude AI
