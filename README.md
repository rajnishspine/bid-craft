# BidCraft - Template Management Platform

A modern Laravel application for creating, managing, and organizing text templates. Perfect for emails, documents, contracts, and any text-based content that needs variable placeholders and easy management.

## 🚀 Features

- ✅ **Laravel 10** with PHP 8.1+
- ✅ **Laravel Breeze Authentication** with stunning Bootstrap 5 UI
- ✅ **Template Editor** with real-time validation and AJAX interface
- ✅ **Variable Placeholders** - Use `[VARIABLE_NAME]` syntax for dynamic content
- ✅ **File Management** - Create, edit, load, and delete templates
- ✅ **Responsive Design** - works perfectly on desktop and mobile
- ✅ **Professional Interface** - Bootstrap 5 cards and components
- ✅ **Real-time Statistics** - Track template count and system status
- ✅ **User Authentication** - Secure access with user accounts
- ✅ **AJAX Processing** - no page refreshes, smooth UX
- ✅ **File Storage** - Templates saved as .txt files for easy access

## 📋 System Requirements

- **PHP 8.1** or higher
- **MySQL 5.7** or higher (or MariaDB 10.3+)
- **Composer** (latest version)
- **Apache** or **Nginx** web server
- **File system write permissions** for template storage

## ⚡ Easy Installation Guide

Follow these simple steps to get BidCraft running on your system:

### Step 1: Download the Project
```bash
# Clone the repository
git clone <repository-url> bid-craft
cd bid-craft
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies with Composer
composer install
```

### Step 3: Setup Environment
```bash
# Copy the example environment file
cp .env.example .env

# Generate application encryption key
php artisan key:generate
```

### Step 4: Configure Database
Edit the `.env` file with your database information:
```env
# Application Settings
APP_NAME="BidCraft"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Settings
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bidcraft
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 5: Create Database
```bash
# Create the database tables
php artisan migrate
```

### Step 6: Set File Permissions
```bash
# Make storage directories writable
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chmod -R 755 public/uploads/

# For Apache/Nginx servers:
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
sudo chown -R www-data:www-data public/uploads/
```

### Step 7: Start the Application
```bash
# Start the development server
php artisan serve
```

### Step 8: Access Your Application
- Open your browser and go to: `http://localhost:8000`
- Click **"Register"** to create your account
- Start creating templates!

## 📝 Template Editor Guide

### How to Use Templates

#### 1. Access the Template Editor
- Navigate to `/templates` or click **"Template Editor"** in the navigation menu
- You must be logged in to access this feature

#### 2. Create a New Template
1. Enter a file name (only letters, numbers, hyphens, and underscores allowed)
2. Type or paste your template content in the large textarea
3. Click **"Save Template"** to save the file
4. The `.txt` extension is automatically added

#### 3. Edit an Existing Template
1. Select a template from the dropdown list
2. Click **"Load Template"** to load its content
3. Modify the content as needed
4. Click **"Save Template"** to update the file

#### 4. Delete a Template
1. Select a template from the dropdown list
2. Click **"Delete Template"**
3. Confirm the deletion when prompted

### Template Variables
You can use placeholder variables in your templates:
- `[COMPANY_NAME]` - Company or organization name
- `[CLIENT_NAME]` - Client's name
- `[PROJECT_NAME]` - Project identifier
- `[SENDER_NAME]` - Sender's name
- `[DATE]` - Current date
- `[STATUS]` - Project status
- `[EMAIL]` - Email address
- And any custom variables you define

### Sample Templates Included
1. **welcome_template.txt** - Basic welcome message with variables
2. **project_update.txt** - Project status update email template

## 🎯 Getting Started

### 1. Register Your Account
1. Open your browser and go to your BidCraft URL
2. Click **"Register"** in the top navigation
3. Fill in your details (name, email, password)
4. Click **"Register"** button
5. You'll be automatically logged in

### 2. Create Your First Template
1. Click **"Template Editor"** in the navigation menu
2. Enter a template name (e.g., "welcome_email")
3. Add your content with variables like `[CLIENT_NAME]`
4. Click **"Save Template"**

### 3. Manage Your Templates
- Use the dropdown to select existing templates
- Click **"Load Template"** to edit
- Click **"Delete Template"** to remove unwanted templates
- View your template statistics on the dashboard

## 📁 Project Structure

```
bid-craft/
├── app/
│   ├── Http/Controllers/
│   │   ├── TemplateController.php         # Template management controller
│   │   └── ProfileController.php          # User profile management
│   └── Models/
│       └── User.php                       # User authentication model
├── database/migrations/                   # Database schema
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php                 # Bootstrap 5 main layout
│   │   └── navigation.blade.php          # Professional navbar
│   ├── templates/
│   │   └── index.blade.php               # Template editor interface
│   └── dashboard.blade.php               # Template-focused dashboard
├── routes/
│   ├── web.php                           # Template routes with auth
│   └── api.php                           # Basic API routes
├── public/uploads/                       # Template storage directory
│   ├── welcome_template.txt              # Sample welcome template
│   └── project_update.txt                # Sample project template
└── config/
    └── app.php                           # Application configuration
```

## 🔑 Key Features

### 🎨 Modern User Interface
- **Professional Bootstrap 5** design with card-based layouts
- **Responsive** mobile-friendly interface
- **Real-time validation** for template names and content
- **AJAX-powered** operations with smooth user experience
- **Font Awesome** icons throughout the interface
- **Success/error alerts** with auto-hide functionality

### 📝 Template Management
- **Create templates** with custom names and content
- **Edit existing templates** with easy load functionality
- **Variable placeholders** using `[VARIABLE_NAME]` syntax
- **File validation** to ensure secure template names
- **Dropdown selection** for quick template access
- **Delete confirmation** to prevent accidental removal

### 💾 File Storage System
- **Text file storage** in `public/uploads/` directory
- **Automatic file extensions** (.txt added automatically)
- **File permission management** for secure access
- **Template counter** showing total available templates
- **Storage status monitoring** for system health

### 🔐 Security & Authentication
- **Laravel Breeze** authentication system
- **Route protection** with auth middleware
- **File name validation** (alphanumeric, hyphens, underscores only)
- **CSRF protection** on all forms
- **Secure file operations** with proper error handling

### 📊 Dashboard Features
- **Template statistics** showing count and storage status
- **Quick access cards** for creating and managing templates
- **System status indicators** for monitoring health
- **Professional layout** with clear navigation

## 🔗 Template Routes

The application provides web routes for template management:

### Web Routes
- `GET /dashboard` - Main dashboard with template statistics
- `GET /templates` - Template editor interface
- `POST /templates/save` - Save template content to file
- `POST /templates/load` - Load template content from file
- `DELETE /templates/delete` - Delete template file
- `GET /templates/list` - List all available templates

### Authentication Required
All template routes require user authentication via Laravel Breeze middleware.

## 🛠️ Development & Maintenance

### Development Mode
```bash
# Enable debug mode in .env file
APP_DEBUG=true
APP_ENV=local

# Start development server
php artisan serve
```

### View Application Logs
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check web server logs (if applicable)
tail -f /var/log/apache2/error.log  # Apache
tail -f /var/log/nginx/error.log    # Nginx
```

### Clear Application Cache
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Clear compiled views
rm -rf storage/framework/views/*
```

### Production Optimization
```bash
# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Set proper permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/uploads/
```

## 🚨 Troubleshooting

### Common Issues & Solutions

#### 1. Permission Errors
```bash
# Fix storage and uploads permissions
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/
sudo chmod -R 755 public/uploads/

# Set proper ownership (for Apache/Nginx)
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
sudo chown -R www-data:www-data public/uploads/
```

#### 2. Composer/Class Not Found
```bash
# Regenerate autoloader
composer dump-autoload
composer install --optimize-autoloader

# Clear compiled files
php artisan clear-compiled
```

#### 3. Database Connection Issues
```bash
# Test database connection
php artisan migrate:status

# Check database configuration
php artisan config:show database

# Test connection manually
php artisan tinker
> DB::connection()->getPdo();
```

#### 4. Template File Issues
```bash
# Check uploads directory exists
ls -la public/uploads/

# Create uploads directory if missing
mkdir -p public/uploads/
chmod 755 public/uploads/

# Test file write permissions
touch public/uploads/test.txt
rm public/uploads/test.txt
```

#### 5. Environment Configuration
```bash
# Verify .env file exists
ls -la .env

# Check app key is generated
php artisan key:generate

# Verify environment configuration
php artisan config:show
```

### Debug Steps
1. **Check Laravel logs**: `tail -f storage/logs/laravel.log`
2. **Verify environment**: `php artisan config:show`
3. **Test database**: `php artisan migrate:status`
4. **Check file permissions**: `ls -la public/uploads/`
5. **Test web server**: Check server error logs

## 🎯 Production Deployment

### Web Server Configuration

#### Apache Setup
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/bid-craft/public
    
    <Directory /path/to/bid-craft/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/bidcraft-error.log
    CustomLog ${APACHE_LOG_DIR}/bidcraft-access.log combined
</VirtualHost>
```

#### Nginx Setup
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/bid-craft/public;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### SSL Certificate (Recommended)
```bash
# Using Let's Encrypt (free SSL)
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d your-domain.com
```

### Backup Strategy
```bash
# Database backup
mysqldump -u username -p bidcraft > backup.sql

# Template files backup
tar -czf templates_backup.tar.gz public/uploads/
```

## 📝 Technical Details

### Controller Features
- **TemplateController** - Handles all template CRUD operations
- **File validation** - Secure filename and content validation
- **AJAX responses** - JSON responses for smooth user experience
- **Error handling** - Comprehensive error handling and logging

### JavaScript Features
- **Real-time validation** - Form validation without page refresh
- **AJAX operations** - Save, load, and delete without reloading
- **Success/error alerts** - User feedback with auto-hide
- **Dropdown management** - Dynamic template selection
- **Confirmation dialogs** - Prevent accidental deletions

### Future Enhancements
- Template categories/folders
- Template preview functionality
- Variable replacement preview
- Template versioning
- Bulk operations
- Import/export functionality

## 📝 License

This project is open-sourced software licensed under the MIT license.

---

## 🎉 Ready to Create Templates!

Your BidCraft template management platform is ready to use!

### ✅ **What You Have:**
- 📝 **Template Editor** - Create and manage text templates
- 🎨 **Professional UI** - Modern Bootstrap 5 interface  
- 📱 **Responsive Design** - Works on all devices
- 🔐 **Secure Authentication** - User registration & login
- 💾 **File Storage** - Templates saved as .txt files
- 🔄 **AJAX Interface** - Smooth user experience
- 📊 **Statistics** - Template count and system status

### 🚀 **Start Creating Templates Now:**
1. Register your user account
2. Navigate to Template Editor
3. Create your first template with variables!

**Your professional template management platform is live!** 🎊✨

## **📦 NPM Dependencies Analysis for BidCraft**

Based on my analysis of your bid-craft project, here's what I found:

### **🔍 Current Setup:**

#### **NPM Dependencies Configured:**
Your `package.json` includes:
- ✅ **TailwindCSS** (with forms plugin)
- ✅ **AlpineJS** 
- ✅ **Vite** (build tool)
- ✅ **Laravel Vite plugin**
- ✅ **PostCSS** and Autoprefixer

#### **Resource Files Exist:**
- ✅ `resources/css/app.css` - Contains TailwindCSS directives
- ✅ `resources/js/app.js` - Imports AlpineJS
- ✅ `vite.config.js` - Configured to build these assets

### **❌ BUT Currently NOT Used:**

Your application **currently works WITHOUT npm** because:
- ❌ **No @vite directives** in layout files
- ❌ **Bootstrap 5 loaded from CDN** instead of TailwindCSS
- ❌ **Font Awesome loaded from CDN**
- ❌ **All styling is inline/CDN-based**

---

### **🎯 RECOMMENDATION:**

You have **TWO OPTIONS**:

#### **Option 1: Keep It Simple (Recommended)**
**NO npm needed** - Continue using CDN approach:
- ✅ **Faster development** - no build steps
- ✅ **Easier deployment** - no asset compilation
- ✅ **Already working** - Bootstrap + Font Awesome from CDN
- ✅ **Template editor works perfectly** without compiled assets

#### **Option 2: Use Build Tools**
**YES npm needed** - Switch to compiled assets:
```bash
# Install dependencies
npm install

# Build for development
npm run dev

# Build for production  
npm run build
```

Then add to your layout:
```blade
<code_block_to_apply_changes_from>
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

---

### **💡 My Recommendation:**

**Keep the current CDN approach** for your BidCraft template editor because:

1. **✅ No npm complexity** - Simpler setup for users
2. **✅ Faster installation** - No additional build steps
3. **✅ Already working perfectly** - Template functionality is complete
4. **✅ Easy maintenance** - No asset compilation needed
5. **✅ Server resource friendly** - No node_modules folder

### **📝 Update Installation Steps:**

Your installation guide should remain as-is since **npm is NOT required** for the current implementation:

```bash
# These are sufficient:
git clone <repo> bid-craft
cd bid-craft
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

---

### **🎯 Final Answer:**

**NO, you don't need `npm install` or `npm run dev`** for your current BidCraft implementation. The application uses CDN resources and works perfectly without compiled assets! 🎉
