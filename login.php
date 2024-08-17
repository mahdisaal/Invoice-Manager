<?php
session_start();

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

// test: admin' OR '1' = '1
// test: <script>alert('test')</script>
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);


  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    header("Location: index.php");
    exit();
  } else {
    $error = "Invalid username or password";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #5cb85c;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #4cae4c;
    }

    .error {
      color: red;
      text-align: center;
    }

    .signup {
      text-align: center;
      margin-top: 10px;
    }

    .signup a {
      color: #007bff;
      text-decoration: none;
    }

    .signup a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Login</h1>
    <form action="login.php" method="post">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
      <button type="submit">Login</button>
    </form>
    <?php if (isset($error)) : ?>
      <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <p class="signup">Don't have an account? <a href="signup.php">Sign up here</a></p>
  </div>
</body>

</html>