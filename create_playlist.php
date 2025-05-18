<?php
// Start PHP block at the very top
ini_set('display_errors', 1);
error_reporting(E_ALL);

// It's good practice to start session if you plan to use flash messages later
// and it MUST be before any output if you redirect based on session values
// or if header.php itself starts a session.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php'; // Need DB connection for the insert

$message = '';
$playlist_name_input = ''; // Use a different variable for the input to avoid conflict if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_playlist'])) {
    $playlist_name_input = trim($_POST['playlist_name']);

    if (empty($playlist_name_input)) {
        // We can't set $message here directly if we redirect. Use session for messages on redirect.
        $_SESSION['form_message'] = "<p class='message error'>Playlist name cannot be empty.</p>";
        // No redirect here, let the form re-display with the error
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO playlista (Nazwa, DataUtworzenia, CzasTrwania, LiczbaUtworow) VALUES (?, CURDATE(), '00:00:00', 0)");
            if ($stmt->execute([$playlist_name_input])) {
                $new_playlist_id = $pdo->lastInsertId();
                $_SESSION['message'] = "Playlist '" . htmlspecialchars($playlist_name_input) . "' created successfully! Now add some songs.";
                // This is line 19 (or around it) - THIS MUST HAPPEN BEFORE ANY HTML OUTPUT
                header("Location: edit_playlist.php?id=" . $new_playlist_id);
                exit; // ALWAYS call exit after a header redirect
            } else {
                // Can't set $message directly here.
                $_SESSION['form_message'] = "<p class='message error'>Failed to create playlist.</p>";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                 $_SESSION['form_message'] = "<p class='message error'>A playlist with this name might already exist.</p>";
            } else {
                $_SESSION['form_message'] = "<p class='message error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
    }
    // If there was an error and no redirect, redirect back to the form itself to show the message
    if (isset($_SESSION['form_message'])) {
        header("Location: create_playlist.php"); // Redirect to show the message on the form page
        exit;
    }
}

// Now include the header, AFTER all potential header() calls
include 'includes/header.php';

// Retrieve message from session if it was set for form display
if (isset($_SESSION['form_message'])) {
    $message = $_SESSION['form_message'];
    unset($_SESSION['form_message']);
}

?>

<h1>Create New Playlist</h1>

<?php if ($message) echo $message; // Display error messages set in session or directly ?>

<form action="create_playlist.php" method="post" class="form-styled">
    <div>
        <label for="playlist_name">Playlist Name:</label>
        <input type="text" name="playlist_name" id="playlist_name" value="<?= htmlspecialchars($playlist_name_input) ?>" required>
    </div>
    <button type="submit" name="create_playlist" class="button">Create Playlist</button>
</form>
<p style="margin-top:20px;"><a href="playlists.php" class="button-secondary">← Back to All Playlists</a></p>

<?php include 'includes/footer.php'; ?>