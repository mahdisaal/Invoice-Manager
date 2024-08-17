<?php
require "data.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

  $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
  $stmt = $db->prepare($sql);
  if ($stmt->execute([':username' => $username, ':password' => $password])) {
    header("Location: login.php");
  } else {
    $error = "Error in signing up.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
</head>

<body>
  <h1>Sign Up</h1>
  <?php if (isset($error)) : ?>
    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
  <?php endif; ?>
  <form method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" required>
    <label for="password">Password:</label>
    <input type="password" name="password" required>
    <button type="submit">Sign Up</button>
  </form>
</body>

</html>