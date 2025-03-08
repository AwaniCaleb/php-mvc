<?php

/**
 * HomeController.php
 * Controller for the home page and related functions
 */
class HomeController extends BaseController {
    /**
     * Display the home page
     * 
     * @return void
     */
    public function index() {
        // Prepare data for the view
        $featuredItems = $this->getFeaturedItems();
        $stats = $this->getWebsiteStats();
        
        // Pass data to the view
        $this->view('home', [
            'title' => 'Welcome to ' . APP_NAME,
            'featured_items' => $featuredItems,
            'stats' => $stats,
            'current_year' => date('Y')
        ]);
    }
    
    /**
     * Handle newsletter subscription (POST)
     * 
     * @return void
     */
    public function subscribe() {
        // Only process POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }
        
        // Verify CSRF token
        if (!$this->verifyCsrfToken()) {
            $this->redirect('/');
            return;
        }
        
        // Get and sanitize email
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError("Please provide a valid email address.");
            $this->redirect('/');
            return;
        }
        
        // Add subscriber to database (example)
        try {
            $stmt = $this->db->prepare("INSERT INTO subscribers (email, created_at) VALUES (?, NOW())");
            $stmt->execute([$email]);
            
            $this->addMessage("Thank you for subscribing to our newsletter!");
            $this->log("New subscriber: {$email}", 'info');
        } catch (PDOException $e) {
            // Check for duplicate entry
            if ($e->errorInfo[1] === 1062) { // MySQL duplicate entry error code
                $this->addError("You're already subscribed to our newsletter.");
            } else {
                $this->addError("An error occurred. Please try again later.");
                $this->log("Subscribe error: " . $e->getMessage());
            }
        }
        
        // Redirect back to home
        $this->redirect('/');
    }
    
    /**
     * Get featured items for the homepage
     * 
     * @return array Featured items
     */
    private function getFeaturedItems() {
        // Get this from the database
        // For this example, we'll return dummy data
        return [
            [
                'id' => 1,
                'title' => 'Getting Started with PHP',
                'excerpt' => 'Learn the basics of PHP programming',
                'image' => 'assets/images/php-basics.jpg'
            ],
            [
                'id' => 2,
                'title' => 'Understanding MVC',
                'excerpt' => 'Model-View-Controller explained',
                'image' => 'assets/images/mvc.jpg'
            ],
            [
                'id' => 3,
                'title' => 'Secure PHP Practices',
                'excerpt' => 'Protect your website from vulnerabilities',
                'image' => 'assets/images/security.jpg'
            ]
        ];
    }
    
    /**
     * Get website statistics
     * 
     * @return array Website statistics
     */
    private function getWebsiteStats() {
        // Calculate these from the database
        // For this example, we'll return dummy data
        return [
            'users' => 1250,
            'articles' => 87,
            'comments' => 3421
        ];
    }
}