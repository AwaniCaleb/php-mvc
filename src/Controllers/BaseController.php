<?php
/**
 * BaseController.php
 * Base controller that all other controllers extend
 * Provides common functionality for all controllers
 */
class BaseController {
    protected $db;
    protected $currentUser = null;
    protected $errors = [];
    protected $messages = [];
    
    /**
     * Constructor - set up database connection and session
     */
    public function __construct() {
        // Create database connection
        require_once '../config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_strict_mode', 1);
            session_start();
        }
        
        // Set CSRF token if not set
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        
        // Check if user is logged in
        $this->checkAuth();
    }
    
    /**
     * Render a view with data
     * 
     * @param string $viewName Name of the view file (without .view.php)
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function view($viewName, $data = []) {
        // Add common data to all views
        $data['csrf_token'] = $_SESSION[CSRF_TOKEN_NAME];
        $data['current_user'] = $this->currentUser;
        $data['errors'] = $this->errors;
        $data['messages'] = $this->messages;
        $data['app_name'] = APP_NAME;
        
        // Extract data to make accessible as variables in view
        extract($data);
        
        // Include header
        require_once '../src/includes/header.php';
        
        // Include specific view
        $viewPath = "../src/Views/{$viewName}.view.php";
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            $this->log("View not found: {$viewName}");
            die("View {$viewName} not found");
        }
        
        // Include footer
        require_once '../src/includes/footer.php';
    }
    
    /**
     * Verify CSRF token from form submission
     * 
     * @return bool True if token is valid, false otherwise
     */
    protected function verifyCsrfToken() {
        $token = $_POST[CSRF_TOKEN_NAME] ?? '';
        if (empty($token) || !hash_equals($_SESSION[CSRF_TOKEN_NAME], $token)) {
            $this->errors[] = "Security validation failed";
            $this->log("CSRF token validation failed");
            return false;
        }
        return true;
    }
    
    /**
     * Sanitize input data
     * 
     * @param string $data The input to sanitize
     * @return string Sanitized input
     */
    protected function sanitize($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    /**
     * Redirect to another page
     * 
     * @param string $url URL to redirect to
     * @return void
     */
    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Add error message
     * 
     * @param string $message Error message to add
     * @return void
     */
    protected function addError($message) {
        $this->errors[] = $message;
    }
    
    /**
     * Add success/info message
     * 
     * @param string $message Message to add
     * @return void
     */
    protected function addMessage($message) {
        $this->messages[] = $message;
    }
    
    /**
     * Log an error or informational message
     * 
     * @param string $message Message to log
     * @param string $level Log level (error, info, warning)
     * @return void
     */
    protected function log($message, $level = 'error') {
        $logFile = "../logs/{$level}.log";
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;
        
        error_log($logMessage, 3, $logFile);
    }
    
    /**
     * Check if user is authenticated
     * 
     * @return void
     */
    protected function checkAuth() {
        // Check if user is logged in
        if (isset($_SESSION['user_id'])) {
            // Here you would typically query the database
            // to get user details using $_SESSION['user_id']
            // For example purposes, we'll just set a dummy user
            $this->currentUser = [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'] ?? 'Guest',
                'role' => $_SESSION['role'] ?? 'user'
            ];
        }
    }
    
    /**
     * Require authentication to access page
     * 
     * @param string $redirectUrl URL to redirect if not authenticated
     * @return void
     */
    protected function requireAuth($redirectUrl = '/login') {
        if (!$this->currentUser) {
            // Store intended URL for after login
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
            
            // Add message
            $this->addError("Please log in to access this page");
            
            // Redirect to login
            $this->redirect($redirectUrl);
        }
    }
}
