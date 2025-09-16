<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow CORS for local testing

try {
    // Include database configuration
    include 'config.php';

    // Check if PDO connection is established
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }

    // Fetch data from pc_status_archive table
    $stmt = $pdo->query("SELECT pc_id, lab_id, status, timestamp FROM pc_status_archive ORDER BY timestamp DESC LIMIT 100");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode($results);

    // Create index on timestamp for faster queries (if not already created)
    try {
        $pdo->exec("CREATE INDEX idx_timestamp ON pc_status_archive (timestamp)");
    } catch (PDOException $e) {
        // Ignore if index already exists
    }

} catch (Exception $e) {
    // Return error as JSON
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>