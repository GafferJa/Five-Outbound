<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$host = 'localhost';
$dbname = 'five_outbound';
$username = 'root';  // Change this to your database username
$password = '';      // Change this to your database password

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if request is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate and sanitize input data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Validation
    if (empty($name)) {
        throw new Exception('Name is required');
    }

    if (empty($email)) {
        throw new Exception('Email is required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    if (empty($description)) {
        throw new Exception('Project description is required');
    }

    // Additional validation
    if (strlen($name) > 100) {
        throw new Exception('Name is too long (maximum 100 characters)');
    }

    if (strlen($email) > 255) {
        throw new Exception('Email is too long (maximum 255 characters)');
    }

    if (strlen($phone) > 20) {
        throw new Exception('Phone number is too long (maximum 20 characters)');
    }

    if (strlen($description) > 2000) {
        throw new Exception('Description is too long (maximum 2000 characters)');
    }

    // Prepare SQL statement
    $sql = "INSERT INTO inquiries (name, email, phone, description, status, created_at) VALUES (?, ?, ?, ?, 'new', NOW())";
    $stmt = $pdo->prepare($sql);

    // Execute the statement
    $result = $stmt->execute([$name, $email, $phone, $description]);

    if ($result) {
        // Send email notification (optional)
        sendEmailNotification($name, $email, $phone, $description);

        header("Location: ../index.php?success=1");
        exit;

    } else {
        throw new Exception('Failed to save inquiry');
    }

} catch (PDOException $e) {
    // Database error
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed. Please try again later.'
    ]);
} catch (Exception $e) {
    // General error
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Function to send email notification (optional)
function sendEmailNotification($name, $email, $phone, $description)
{
    $to = 'info@fiveoutbound.com'; // Change this to your email
    $subject = 'New Inquiry from ' . $name;

    $message = "You have received a new inquiry from your website.\n\n";
    $message .= "Name: " . $name . "\n";
    $message .= "Email: " . $email . "\n";
    $message .= "Phone: " . ($phone ?: 'Not provided') . "\n";
    $message .= "Description:\n" . $description . "\n\n";
    $message .= "Submitted on: " . date('Y-m-d H:i:s') . "\n";

    $headers = "From: noreply@fiveoutbound.com\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Uncomment the line below to enable email notifications
    // mail($to, $subject, $message, $headers);
}

// Function to create database and table (run this once)
function createDatabase()
{
    $host = 'localhost';
    $username = 'root';
    $password = '';

    try {
        // Connect without specifying database
        $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS five_outbound");
        $pdo->exec("USE five_outbound");

        // Create inquiries table
        $sql = "CREATE TABLE IF NOT EXISTS inquiries (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            description TEXT NOT NULL,
            status ENUM('new', 'read', 'replied') DEFAULT 'new',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        $pdo->exec($sql);
        echo "Database and table created successfully!";

    } catch (PDOException $e) {
        echo "Error creating database: " . $e->getMessage();
    }
}

// Uncomment the line below to create database and table (run once)
// createDatabase();
?>