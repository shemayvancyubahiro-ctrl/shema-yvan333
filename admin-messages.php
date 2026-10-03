<?php
session_start();

$PASSWORD = "kigeme@2026";   // <-- change this to your own password

$error = "";
if (isset($_POST['password'])) {
    if ($_POST['password'] === $PASSWORD) {
        $_SESSION['admin'] = true;
    } else {
        $error = "Wrong password.";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin-messages.php");
    exit;
}

if (empty($_SESSION['admin'])) {
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <style>
    body { font-family: Arial, sans-serif; background: #eef7f0; display: flex; justify-content: center; padding-top: 80px; }
    form { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    input { padding: 10px; width: 220px; margin: 10px 0; }
    button { background: #1b7a3d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    .error { color: red; }
  </style>
</head>
<body>
  <form method="POST">
    <h2>Admin Login</h2>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
    <p class="error"><?php echo $error; ?></p>
  </form>
</body>
</html>
<?php
    exit;
}

$conn = new mysqli("localhost", "root", "", "kigeme_db");
$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Messages</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; background: #f7f7f7; }
    header { background: #1b7a3d; color: white; padding: 15px 30px; display: flex; justify-content: space-between; }
    header a { color: white; }
    .wrap { padding: 30px; overflow-x: auto; }
    table { border-collapse: collapse; width: 100%; background: white; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: top; }
    th { background: #0f4d26; color: white; }
  </style>
</head>
<body>
  <header>
    <h2>GS Kigeme A - Messages</h2>
    <a href="?logout=1">Logout</a>
  </header>
  <div class="wrap">
    <p>Total messages: <?php echo $result->num_rows; ?></p>
    <table>
      <tr><th>#</th><th>Name</th><th>Email</th><th>Message</th><th>Date</th></tr>
      <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
        <td><?php echo $row['created_at']; ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
</body>
</html>