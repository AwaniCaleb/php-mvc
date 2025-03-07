# PHP MVC Template

## Overview
This is a lightweight, secure PHP website template using a simple MVC (Model-View-Controller) architecture. Designed for ease of use and extensibility.

## Features
- Simple routing mechanism
- MVC architectural pattern
- Security-focused design
- Easy configuration
- Minimal dependencies
- Straightforward project structure

## Requirements
- PHP 7.4+
- Apache Web Server
- MySQL (optional, but recommended)
- Composer (recommended for dependency management)

## Project Structure
```
php-mvc/
├── config/           # Configuration files
├── public/           # Web entry point
├── src/              # Application source code
│   ├── Controllers/  # Page logic
│   ├── Models/       # Database interactions
│   └── Views/        # Page templates
└── logs/             # Error logs
```

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/awanicaleb/php-mvc.git
cd php-mvc
```

### 2. Configure Database
1. Open `config/config.php`
2. Update database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'mywebsite');
```

### 3. Web Server Configuration
#### Apache (.htaccess)
- Ensure `mod_rewrite` is enabled
- Place `.htaccess` in `public/` directory
- Allow `.htaccess` overrides in your Apache configuration

#### Nginx (alternative)
Add to your server block:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 4. Permissions
```bash
# Set proper permissions
chmod -R 755 .
chmod -R 777 logs/
```

## Adding New Pages

### 1. Create Controller
In `src/Controllers/`, create a new controller:
```php
<?php
class YourPageController extends BaseController {
    public function index() {
        $this->view('your-page', [
            'title' => 'Your Page Title'
        ]);
    }
}
```

### 2. Create View
In `src/Views/`, create `your-page.view.php`:
```php
<div class="container">
    <h1><?= htmlspecialchars($title) ?></h1>
    <!-- Page content -->
</div>
```

### 3. Update Router
In `public/index.php`, add to routes:
```php
private $routes = [
    // ... existing routes
    'your-page' => 'YourPageController@index'
];
```

## Security Considerations
- Always sanitize and validate user inputs
- Use prepared statements for database queries
- Keep PHP and dependencies updated
- Use HTTPS in production
- Implement proper authentication for protected routes

## Deployment Checklist
- Set `APP_ENV` to `production` in `config/config.php`
- Disable error reporting
- Use environment variables for sensitive data
- Implement SSL/TLS
- Set up proper file permissions

## Troubleshooting
- Check `logs/error.log` for any issues
- Ensure all file paths are correct
- Verify web server configuration
- Check PHP version compatibility

## Contributing
1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License
Distributed under the MIT License. See `LICENSE` for more information.

## Contact
<!-- Your Name - your.email@example.com -->

Project Link: [https://github.com/awanicaleb/php-mvc](https://github.com/awanicaleb/php-mvc)
```

## Support
If you encounter any problems, please open an issue on GitHub with:
- Detailed description
- Error logs
- Steps to reproduce

## Recommended Tools
- [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [PHPUnit](https://phpunit.de/)
- [Composer](https://getcomposer.org/)