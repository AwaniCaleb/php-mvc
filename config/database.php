<?php
/**
 * Database Connection Class
 * 
 * Provides secure database connectivity using PDO
 * Implements singleton pattern to prevent multiple connections
 * Includes error handling and connection management
 */
class Database {
    // Singleton instance
    private static $instance = null;
    
    // Database connection
    private $connection;
    
    // Connection status
    private $connected = false;
    
    /**
     * Private constructor to enforce singleton pattern
     * Establishes database connection using PDO
     */
    private function __construct() {
        try {
            // Get database credentials from config
            $host = DB_HOST;
            $dbname = DB_NAME;
            $username = DB_USER;
            $password = DB_PASS;
            
            // Set PDO options for security and error handling
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions for errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Default fetch as associative array
                PDO::ATTR_EMULATE_PREPARES => false, // Use real prepared statements
                PDO::ATTR_PERSISTENT => false // Non-persistent connections
            ];
            
            // Create PDO connection
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                $options
            );
            
            $this->connected = true;
            
        } catch (PDOException $e) {
            // Log the error (but don't display sensitive information)
            error_log('Database Connection Error: ' . $e->getMessage());
            
            // Generic error message for security
            throw new Exception('Database connection failed. Please try again later.');
        }
    }
    
    /**
     * Get database instance (Singleton implementation)
     * 
     * @return Database The database instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get the PDO connection object
     * 
     * @return PDO The PDO connection
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Execute a query with parameters
     * 
     * @param string $sql SQL query with placeholders
     * @param array $params Parameters to bind to the query
     * @return PDOStatement The executed statement
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log('Query Error: ' . $e->getMessage() . ' in query: ' . $sql);
            throw new Exception('Database query failed.');
        }
    }
    
    /**
     * Fetch a single row from the database
     * 
     * @param string $sql SQL query
     * @param array $params Parameters to bind
     * @return array|false Single row result or false if no result
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Fetch all rows from the database
     * 
     * @param string $sql SQL query
     * @param array $params Parameters to bind
     * @return array Array of rows
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Insert data into a table
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @return int|false The last insert ID or false on failure
     */
    public function insert($table, $data) {
        // Build the query
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array_values($data));
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            error_log('Insert Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update data in a table
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @param string $where WHERE clause
     * @param array $params Parameters for WHERE clause
     * @return int Number of affected rows
     */
    public function update($table, $data, $where, $params = []) {
        // Build SET part of query
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = ?";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        
        // Combine data values with where params
        $allParams = array_merge(array_values($data), $params);
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($allParams);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log('Update Error: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Delete data from a table
     * 
     * @param string $table Table name
     * @param string $where WHERE clause
     * @param array $params Parameters for WHERE clause
     * @return int Number of affected rows
     */
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log('Delete Error: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Begin a transaction
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Commit a transaction
     */
    public function commit() {
        return $this->connection->commit();
    }
    
    /**
     * Roll back a transaction
     */
    public function rollBack() {
        return $this->connection->rollBack();
    }
    
    /**
     * Prevent cloning of the instance (part of singleton pattern)
     */
    private function __clone() {}
    
    /**
     * Close the database connection when object is destroyed
     */
    public function __destruct() {
        $this->connection = null;
    }
}

// Example usage:

// Get database instance
// $db = Database::getInstance();

// Simple query example
// $users = $db->fetchAll("SELECT * FROM users WHERE role = ?", ['admin']);

// Insert example
// $userId = $db->insert('users', [
//     'username' => 'newuser',
//     'email' => 'user@example.com',
//     'password' => password_hash('password123', PASSWORD_DEFAULT)
// ]);

// Update example
// $affected = $db->update('users', 
//     ['status' => 'active'], 
//     'user_id = ?', 
//     [5]
// );

// Delete example
// $deleted = $db->delete('users', 'user_id = ?', [5]);

// Transaction example
// try {
//     $db->beginTransaction();
//     // Multiple operations here
//     $db->insert('orders', ['user_id' => 1, 'total' => 99.99]);
//     $orderId = $db->getConnection()->lastInsertId();
//     $db->insert('order_items', ['order_id' => $orderId, 'product_id' => 123]);
//     $db->commit();
// } catch (Exception $e) {
//     $db->rollBack();
//     // Handle error
// }
?>