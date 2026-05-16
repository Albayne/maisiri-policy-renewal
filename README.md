# Maisiri Policy Renewal System

A lightweight, secure web application for managing insurance policy renewals with role-based access control. Built with PHP 8, MySQL, and modern OOP principles.

## Quick Start

### Prerequisites
- PHP 8.0+
- MySQL 5.7+ or MariaDB 10.2+
- Apache with `mod_rewrite` enabled
- Git (for cloning)

### Installation

1. Clone repository:
   ```bash
   git clone https://github.com/Albayne/maisiri-policy-renewal.git
   cd maisiri-policy-renewal
   ```

2. Setup environment:
   ```bash
   cp .env.example .env
   # Edit .env with your database credentials
   ```

3. Create database:
   ```bash
   mysql -u root -p < policy_renewal.sql
   ```

4. Configure web server to point to `public/` directory

5. Access application at `http://localhost/public/`
   - Admin: `admin` / `admin123`
   - Officer: `officer1` / `officer123`

## Features

✅ Role-Based Access Control (Admin, Policy Officer, Viewer)  
✅ Insurance Policy Management  
✅ Document Upload & Management (JPG, PNG, PDF - 5MB max)  
✅ Dashboard Analytics  
✅ Secure Authentication with Password Hashing  
✅ SQL Injection Prevention (Prepared Statements)  
✅ Responsive Bootstrap UI  
✅ Environment-based Configuration  

## Project Structure

```
├── config/          # Database configuration
├── classes/         # Database singleton class
├── middleware/      # Authentication middleware
├── models/          # User, Policy, Document models
├── controllers/     # Request handlers
├── views/           # PHP templates
├── public/          # Web-accessible entry point
├── uploads/         # User-uploaded documents
└── policy_renewal.sql # Database schema
```

## Documentation

For detailed setup instructions, architecture overview, and role design, see the full README in the repository.

## Security

- Never commit `.env` file (already in `.gitignore`)
- Use strong database passwords
- All queries use prepared statements
- Passwords hashed with `password_hash()`

## Author

Created by Blessing I Maisiri (ZIMNAT Software Developer Internship)

AI Assistance: Anthropic's Claude AI was used for architecture design, code optimization, and security review.
