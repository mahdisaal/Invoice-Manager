<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$dsn = 'mysql:host=localhost;dbname=invoice_manager';
$username = "root";
$password = "root";

try {
  $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
  $error = $e->getMessage();
  echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8');
  exit();
}

if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) !== 'login.php' && basename($_SERVER['PHP_SELF']) !== 'signup.php') {
  header("Location: login.php");
  exit();
}

$result = $db->query("SELECT * FROM statuses");
$statuses = $result->fetchAll(PDO::FETCH_COLUMN, 1);



// Create tables if they do not exist
try {
  $db->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    );

    CREATE TABLE IF NOT EXISTS invoices (
        id INT AUTO_INCREMENT PRIMARY KEY,
        number VARCHAR(10) UNIQUE NOT NULL,
        client VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        amount INT NOT NULL,
        status_id INT NOT NULL,
        FOREIGN KEY (status_id) REFERENCES statuses(id)
    );
  ");
} catch (PDOException $e) {
  $error = $e->getMessage();
  echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8');
  exit();
}
