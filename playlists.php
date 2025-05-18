<?php
ini_set('display_errors', 1); error_reporting(E_ALL);
include 'includes/header.php';

// Define the function at the top
if (!function_exists('format_duration_from_time_type')) {
    function format_duration_from_time_type($time_str) {
        if (empty($time_str) || $time_str == '00:00:00') return '0:00'; // Or 'N/A' or however you want to display zero
        list($h, $m, $s) = explode(':', $time_str);
        $h = (int)$h;
        $m = (int)$m;
        $s = (int)$s;
        if ($h > 0) {
            return sprintf('%d:%02d:%02d', $h, $m, $s);
        } else {
            return sprintf('%d:%02d', $m, $s);
        }
    }
}

// Check for messages from other pages (e.g., after deletion)
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after displaying
}
?>
<h1>All Playlists</h1>

<?php if ($message): ?>
    <div class="message <?= strpos(strtolower($message), 'error') !== false || strpos(strtolower($message), 'failed') !== false ? 'error' : 'success' ?>">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<p><a href="create_playlist.php" class="button">Create New Playlist</a></p>

<div class="grid-container">
    <?php
    $stmt = $pdo->query("
        SELECT ID, Nazwa, DataUtworzenia, LiczbaUtworow, CzasTrwania
        FROM playlista
        ORDER BY Nazwa
    ");
    $playlists = $stmt->fetchAll();

    if ($playlists):
        foreach ($playlists as $playlist_item):
    ?>
        <div class="card">
            <h3><a href="view_playlist.php?id=<?= $playlist_item['ID'] ?>"><?= htmlspecialchars($playlist_item['Nazwa']) ?></a></h3>
            <p><?= htmlspecialchars($playlist_item['LiczbaUtworow'] ?? 0) ?> songs</p>
            <!-- This is line 37 (or around it) where the error occurred -->
            <p class="card-meta">Duration: <?= htmlspecialchars(format_duration_from_time_type($playlist_item['CzasTrwania'] ?? '00:00:00')) ?></p>
            <p class="card-meta">Created: <?= htmlspecialchars(date("M j, Y", strtotime($playlist_item['DataUtworzenia']))) ?></p>
            <div class="actions">
                <a href="edit_playlist.php?id=<?= $playlist_item['ID'] ?>" class="button-secondary">Edit</a>
            </div>
        </div>
    <?php
        endforeach;
    else:
        echo "<p>No playlists found yet. Why not create one?</p>";
    endif;
    ?>
</div>

<?php
// The function definition can be removed from here now
include 'includes/footer.php';
?>