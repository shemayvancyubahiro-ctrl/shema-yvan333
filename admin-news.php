<?php
session_start();

if (empty($_SESSION['admin'])) {
    header("Location: admin-messages.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "kigeme_db");
$notice = "";

// Add news
if (isset($_POST['add'])) {
    $title    = trim($_POST['title'] ?? '');
    $content  = trim($_POST['content'] ?? '');
    $category = ($_POST['category'] ?? 'News') === 'Event' ? 'Event' : 'News';
    $date     = $_POST['event_date'] ?? '';
    $date     = ($date === '') ? null : $date;

    if ($title === '' || $content === '') {
        $notice = "Please fill in the title and content.";
    } else {
        $stmt = $conn->prepare("INSERT INTO news (title, content, category, event_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $content, $category, $date);
        $stmt->execute();
        $stmt->close();
        $notice = "News posted!";
    }
}

// Delete news
if (isset($_POST['delete'])) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $notice = "News deleted.";
}

$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage News</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; background: #f7f7f7; }
    header { background: #1b7a3d; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
    header a { color: white; margin-left: 15px; }
    .wrap { max-width: 900px; margin: 0 auto; padding: 30px; }
    .box { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
    input, textarea, select { width: 100%; padding: 10px; margin: 8px 0 15px; box-sizing: border-box; font-family: inherit; }
    button { background: #1b7a3d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    button.del { background: #c0392b; padding: 6px 12px; }
    .notice { color: #1b7a3d; font-weight: bold; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: top; }
    th { background: #0f4d26; color: white; }
  </style>
</head>
<body>
  <header>
    <h2>GS Kigeme A - Manage News</h2>
    <div>
      <a href="admin-messages.php">Messages</a>
      <a href="admin-messages.php?logout=1">Logout</a>
    </div>
  </header>

  <div class="wrap">
    <div class="box">
      <h3>Post new news or event</h3>
      <?php if ($notice) { echo '<p class="notice">' . htmlspecialchars($notice) . '</p>'; } ?>
      <form method="POST">
        <label>Title</label>
        <input type="text" name="title" required>

        <label>Type</label>
        <select name="category">
          <option value="News">News</option>
          <option value="Event">Event</option>
        </select>

        <label>Event date (only for events, optional)</label>
        <input type="date" name="event_date">

        <label>Content</label>
        <textarea name="content" rows="5" required></textarea>

        <button type="submit" name="add">Post</button>
      </form>
    </div>

    <div class="box">
      <h3>All posts (<?php echo $result->num_rows; ?>)</h3>
      <table>
        <tr><th>Title</th><th>Type</th><th>Date</th><th>Action</th></tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><?php echo htmlspecialchars($row['title']); ?></td>
          <td><?php echo htmlspecialchars($row['category']); ?></td>
          <td><?php echo $row['event_date'] ? $row['event_date'] : substr($row['created_at'], 0, 10); ?></td>
          <td>
            <form method="POST" onsubmit="return confirm('Delete this post?');">
              <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
              <button class="del" type="submit" name="delete">Delete</button>
            </form>
          </td>
        </tr>
        <?php } ?>
      </table>
    </div>
  </div>
</body>
</html>