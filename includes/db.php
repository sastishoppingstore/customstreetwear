<?php
/**
 * Custom Streetwear - Database Connection
 * PDO MySQL with prepared statements
 */

require_once __DIR__ . '/../config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $initCommand = 1002;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        $initCommand => "SET NAMES " . DB_CHARSET . " COLLATE utf8mb4_unicode_ci"
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

/**
 * Get PDO instance
 */
function getDB() {
    global $pdo;
    return $pdo;
}

/**
 * Execute a prepared query and return statement
 */
function dbQuery($sql, $params = []) {
    $db = getDB();
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Fetch single row
 */
function dbFetchOne($sql, $params = []) {
    return dbQuery($sql, $params)->fetch();
}

/**
 * Fetch all rows
 */
function dbFetchAll($sql, $params = []) {
    return dbQuery($sql, $params)->fetchAll();
}

/**
 * Insert and get last ID
 */
function dbInsert($sql, $params = []) {
    $db = getDB();
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $db->lastInsertId();
}

/**
 * Update/Delete - return affected rows
 */
function dbExecute($sql, $params = []) {
    $stmt = dbQuery($sql, $params);
    return $stmt->rowCount();
}

/**
 * Count rows
 */
function dbCount($table, $where = '', $params = []) {
    $sql = "SELECT COUNT(*) FROM " . $table;
    if ($where) {
        $sql .= " WHERE " . $where;
    }
    return dbQuery($sql, $params)->fetchColumn();
}
