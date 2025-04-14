<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "worknet");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../log_in/login.html");
    exit;
}

$message_id = $_GET['id'];
$current_user_id = $_SESSION['user_id'];

// Get original message
$stmt = $mysqli->prepare("SELECT m.*, u.email as sender_email FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.id = ?");
$stmt->bind_param("i", $message_id);
$stmt->execute();
$result = $stmt->get_result();
$message = $result->fetch_assoc();

if (!$message) {
    echo "Message not found.";
    exit;
}

// Get replies (if any)
$task_id = $message['task_id'];
$other_user_id = $message['sender_id'];

$repliesStmt = $mysqli->prepare("
    SELECT m.*, u.email as sender_email 
    FROM messages m 
    JOIN users u ON m.sender_id = u.id 
    WHERE m.task_id = ? AND 
    ((m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?))
    ORDER BY m.sent_at ASC
");

if (!$repliesStmt) {
    die("Prepare failed: " . $mysqli->error);
}

$repliesStmt->bind_param("iiiii", $task_id, $current_user_id, $other_user_id, $other_user_id, $current_user_id);
$repliesStmt->execute();
$replies = $repliesStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Message Thread</title>
    <link rel="stylesheet" href="message_thread.css">
</head>
<body>
    <h2>Chat with <?php echo htmlspecialchars($message['sender_email']); ?></h2>

    <div class="message-container" style="display: flex; flex-direction: column;">
    <div class="message-thread">
        <?php while ($row = $replies->fetch_assoc()): ?>
            <div class="message <?php echo $row['sender_id'] == $current_user_id ? 'sent' : 'received'; ?>">
                <strong><?php echo htmlspecialchars($row['sender_email']); ?></strong>
                <p><?php echo htmlspecialchars($row['message']); ?></p>
                <small><?php echo $row['sent_at']; ?></small>
            </div>
        <?php endwhile; ?>
    </div>
    </div>
    
    <form method="POST" action="send_reply.php">
        <input type="hidden" name="receiver_id" value="<?php echo $other_user_id; ?>">
        <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
        <textarea name="message" required placeholder="Type your reply..."></textarea>
        <button type="submit">Send</button>
    </form>
</body>
</html>
