<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php'; 

$message = '';
$playlist_name_input = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_playlist'])) {
    $playlist_name_input = trim($_POST['playlist_name']);

    if (empty($playlist_name_input)) {
        
        $_SESSION['form_message'] = "<p class='message error'>Playlist name cannot be empty.</p>";
        
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO playlista (Nazwa, DataUtworzenia, CzasTrwania, LiczbaUtworow) VALUES (?, CURDATE(), '00:00:00', 0)");
            if ($stmt->execute([$playlist_name_input])) {
                $new_playlist_id = $pdo->lastInsertId();
                $_SESSION['message'] = "Playlist '" . htmlspecialchars($playlist_name_input) . "' created successfully! Now add some songs.";
                
                header("Location: edit_playlist.php?id=" . $new_playlist_id);
                exit; 
            } else {
                
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
    
    if (isset($_SESSION['form_message'])) {
        header("Location: create_playlist.php"); 
        exit;
    }
}


include 'includes/header.php';


if (isset($_SESSION['form_message'])) {
    $message = $_SESSION['form_message'];
    unset($_SESSION['form_message']);
}

?>

<h1>Create New Playlist</h1>

<?php if ($message) echo $message;?>

<form action="create_playlist.php" method="post" class="form-styled">
    <div>
        <label for="playlist_name">Playlist Name:</label>
        <input type="text" name="playlist_name" id="playlist_name" value="<?= htmlspecialchars($playlist_name_input) ?>" required>
    </div>
    <button type="submit" name="create_playlist" class="button">Create Playlist</button>
</form>
<p style="margin-top:20px;"><a href="playlists.php" class="button-secondary">← Back to All Playlists</a></p>

<?php include 'includes/footer.php'; ?>