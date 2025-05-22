<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php'; 

$message = ''; 
$playlist_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


if (!$playlist_id) {
    $_SESSION['message'] = "Error: No playlist ID specified for editing."; 
    header("Location: playlists.php");
    exit;
}


if (!function_exists('update_playlist_meta')) { 
    function update_playlist_meta($pdo_conn, $playlist_id_to_update) {
        try {
            $stmt_duration = $pdo_conn->prepare("
                SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(p.CzasTrwania))) AS TotalDuration
                FROM piosenka p
                JOIN playlista_piosenka pp ON p.ID = pp.PiosenkaID
                WHERE pp.PlaylistaID = ?
            ");
            $stmt_duration->execute([$playlist_id_to_update]);
            $total_duration_row = $stmt_duration->fetch();
            $total_duration = $total_duration_row['TotalDuration'] ?? '00:00:00';


            $stmt_count = $pdo_conn->prepare("SELECT COUNT(*) as SongCount FROM playlista_piosenka WHERE PlaylistaID = ?");
            $stmt_count->execute([$playlist_id_to_update]);
            $song_count_row = $stmt_count->fetch();
            $song_count = $song_count_row['SongCount'] ?? 0;


            $stmt_update = $pdo_conn->prepare("UPDATE playlista SET CzasTrwania = ?, LiczbaUtworow = ? WHERE ID = ?");
            $stmt_update->execute([$total_duration, $song_count, $playlist_id_to_update]);
            return true;
        } catch (PDOException $e) {
            error_log("Error updating playlist meta for ID $playlist_id_to_update: " . $e->getMessage());
            return false;
        }
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_playlist'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM playlista WHERE ID = ?");
        $stmt->execute([$playlist_id]);
        $_SESSION['message'] = "Playlist deleted successfully.";
        header("Location: playlists.php"); 
        exit;
    } catch (PDOException $e) {

        $message = "<p class='message error'>Error deleting playlist: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_playlist_name'])) {
    $new_playlist_name = trim($_POST['playlist_name']);
    if (!empty($new_playlist_name)) {
        try {
            $stmt = $pdo->prepare("UPDATE playlista SET Nazwa = ? WHERE ID = ?");
            $stmt->execute([$new_playlist_name, $playlist_id]);
            $message = "<p class='message success'>Playlist name updated successfully.</p>";
        } catch (PDOException $e) {
            $message = "<p class='message error'>Error updating name: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        $message = "<p class='message error'>Playlist name cannot be empty.</p>";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_song'])) {
    $song_id_to_add = (int)$_POST['song_id'];
    if ($song_id_to_add > 0) {
        try {
            $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM playlista_piosenka WHERE PlaylistaID = ? AND PiosenkaID = ?");
            $check_stmt->execute([$playlist_id, $song_id_to_add]);
            if ($check_stmt->fetchColumn() == 0) {
                $stmt = $pdo->prepare("INSERT INTO playlista_piosenka (PlaylistaID, PiosenkaID) VALUES (?, ?)");
                $stmt->execute([$playlist_id, $song_id_to_add]);
                update_playlist_meta($pdo, $playlist_id);
                $message = "<p class='message success'>Song added to playlist.</p>";
            } else {
                $message = "<p class='message info'>Song is already in this playlist.</p>";
            }
        } catch (PDOException $e) {
            $message = "<p class='message error'>Error adding song: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_song'])) {
    $song_id_to_remove = (int)$_POST['song_id'];
    if ($song_id_to_remove > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM playlista_piosenka WHERE PlaylistaID = ? AND PiosenkaID = ?");
            $stmt->execute([$playlist_id, $song_id_to_remove]);
            update_playlist_meta($pdo, $playlist_id);
            $message = "<p class='message success'>Song removed from playlist.</p>";
        } catch (PDOException $e) {
            $message = "<p class='message error'>Error removing song: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}


$stmt_playlist = $pdo->prepare("SELECT * FROM playlista WHERE ID = ?");
$stmt_playlist->execute([$playlist_id]);
$playlist = $stmt_playlist->fetch();


if (!$playlist) {
    
    if (!isset($_SESSION['message'])) { 
         $_SESSION['message'] = "Error: Playlist not found after processing.";
    }
    header("Location: playlists.php");
    exit;
}

// --- Fetch Songs Currently in Playlist ---
$stmt_current_songs = $pdo->prepare("
    SELECT p.ID, p.Tytul, p.CzasTrwania, al.Tytul as AlbumTytul, ar.Nazwa as ArtistNazwa
    FROM piosenka p
    JOIN playlista_piosenka pp ON p.ID = pp.PiosenkaID
    LEFT JOIN album al ON p.AlbumID = al.ID
    LEFT JOIN wykonawca ar ON al.WykonawcaID = ar.ID
    WHERE pp.PlaylistaID = ?
    ORDER BY p.Tytul
");
$stmt_current_songs->execute([$playlist_id]);
$current_songs = $stmt_current_songs->fetchAll();

// --- Fetch All Available Songs (not already in this playlist) ---
$search_term = isset($_GET['search_songs']) ? trim($_GET['search_songs']) : '';
$sql_available_songs = "
    SELECT p.ID, p.Tytul, p.CzasTrwania, al.Tytul as AlbumTytul, ar.Nazwa as ArtistNazwa
    FROM piosenka p
    LEFT JOIN album al ON p.AlbumID = al.ID
    LEFT JOIN wykonawca ar ON al.WykonawcaID = ar.ID
    WHERE p.ID NOT IN (SELECT PiosenkaID FROM playlista_piosenka WHERE PlaylistaID = :playlistid)
";
$params_available_songs = [':playlistid' => $playlist_id];

if (!empty($search_term)) {
    $sql_available_songs .= " AND (p.Tytul LIKE :searchterm OR ar.Nazwa LIKE :searchterm OR al.Tytul LIKE :searchterm)";
    $params_available_songs[':searchterm'] = '%' . $search_term . '%';
}
$sql_available_songs .= " ORDER BY p.Tytul LIMIT 50";

$stmt_available_songs = $pdo->prepare($sql_available_songs);
$stmt_available_songs->execute($params_available_songs);
$available_songs = $stmt_available_songs->fetchAll();

// Check for specific message from create_playlist redirect
if (isset($_GET['created'])) {
    $message = "<p class='message success'>Playlist created! Now add some songs.</p>";
}
// PHP LOGIC BLOCK - END
?>
<?php include 'includes/header.php'; // HTML OUTPUT STARTS HERE ?>

<h1>Edit Playlist: <?= htmlspecialchars($playlist['Nazwa']) ?></h1>

<?php if ($message) echo $message; // Display any messages set by the logic block ?>

<div class="edit-playlist-grid">
    <div class="form-section">
        <h3>Playlist Details</h3>
        <form action="edit_playlist.php?id=<?= $playlist_id ?>" method="post" class="form-styled">
            <div>
                <label for="playlist_name">Playlist Name:</label>
                <input type="text" name="playlist_name" id="playlist_name" value="<?= htmlspecialchars($playlist['Nazwa']) ?>" required>
            </div>
            <button type="submit" name="update_playlist_name" class="button">Update Name</button>
        </form>
        <hr style="margin: 20px 0; border-color: #333;">
        <form action="edit_playlist.php?id=<?= $playlist_id ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this playlist? This action cannot be undone.');">
            <button type="submit" name="delete_playlist" class="button-danger">Delete Playlist</button>
        </form>
    </div>

    <div class="form-section">
        <h3>Songs in this Playlist (<?= count($current_songs) ?>)</h3>
        <?php if ($current_songs): ?>
            <table class="track-list">
                <thead><tr><th>Title</th><th>Artist</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach ($current_songs as $song): ?>
                    <tr>
                        <td><?= htmlspecialchars($song['Tytul']) ?></td>
                        <td><?= htmlspecialchars($song['ArtistNazwa'] ?? 'N/A') ?></td>
                        <td>
                            <form action="edit_playlist.php?id=<?= $playlist_id ?>" method="post" style="display:inline;">
                                <input type="hidden" name="song_id" value="<?= $song['ID'] ?>">
                                <button type="submit" name="remove_song" class="action-button remove-button">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No songs in this playlist yet.</p>
        <?php endif; ?>
    </div>
</div>

<div class="form-section" style="margin-top: 30px;">
    <h3>Available Songs to Add</h3>
    <form action="edit_playlist.php?id=<?= $playlist_id ?>" method="get" class="search-songs">
        <input type="hidden" name="id" value="<?= $playlist_id ?>">
        <input type="text" name="search_songs" placeholder="Search songs, artists, albums..." value="<?= htmlspecialchars($search_term) ?>">
        <button type="submit" class="button-secondary">Search</button>
        <?php if(!empty($search_term)): ?>
            <a href="edit_playlist.php?id=<?= $playlist_id ?>" class="button-secondary" style="margin-left:10px;">Clear Search</a>
        <?php endif; ?>
    </form>

    <?php if ($available_songs): ?>
        <table class="track-list">
            <thead><tr><th>Title</th><th>Artist</th><th>Album</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($available_songs as $song): ?>
                <tr>
                    <td><?= htmlspecialchars($song['Tytul']) ?></td>
                    <td><?= htmlspecialchars($song['ArtistNazwa'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($song['AlbumTytul'] ?? 'N/A') ?></td>
                    <td>
                        <form action="edit_playlist.php?id=<?= $playlist_id ?>" method="post" style="display:inline;">
                            <input type="hidden" name="song_id" value="<?= $song['ID'] ?>">
                            <button type="submit" name="add_song" class="action-button add-button">Add</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif(!empty($search_term)): ?>
        <p>No songs found matching your search criteria that are not already in the playlist.</p>
    <?php else: ?>
        <p>All available songs are already in this playlist or there are no songs in the library.</p>
    <?php endif; ?>
</div>
<p style="margin-top:30px;"><a href="view_playlist.php?id=<?= $playlist_id ?>" class="button-secondary">← Back to View Playlist</a></p>

<?php
// Make sure duration formatter is available (if you also defined it in functions.php, this check isn't strictly needed here)
if (!function_exists('format_duration_from_time_type')) {
    // This function should ideally be in includes/functions.php and included by header.php
    function format_duration_from_time_type($time_str) { /* ... */ }
}
include 'includes/footer.php';
?>