<?php
ini_set('display_errors', 1); error_reporting(E_ALL);
include 'includes/header.php';


if (!function_exists('format_duration_from_time_type')) {
    function format_duration_from_time_type($time_str) {
        if (empty($time_str) || $time_str == '00:00:00') return '0:00';
        list($h, $m, $s) = explode(':', $time_str);
        $h = (int)$h; $m = (int)$m; $s = (int)$s;
        if ($h > 0) return sprintf('%d:%02d:%02d', $h, $m, $s);
        else return sprintf('%d:%02d', $m, $s);
    }
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: playlists.php"); 
    exit;
}
$playlist_id = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT Nazwa, DataUtworzenia, CzasTrwania AS PlaylistCzasTrwania, LiczbaUtworow
    FROM playlista
    WHERE ID = ?
");
$stmt->execute([$playlist_id]);
$playlist = $stmt->fetch();

if (!$playlist) {
    $_SESSION['message'] = "Error: Playlist not found.";
    header("Location: playlists.php");
    exit;
}
?>
    <div class="detail-header">
        <div class="detail-info">
            <h1><?= htmlspecialchars($playlist['Nazwa']) ?></h1>
            <p>Created: <?= htmlspecialchars(date("M j, Y", strtotime($playlist['DataUtworzenia']))) ?></p>
            <p><?= htmlspecialchars($playlist['LiczbaUtworow'] ?? 0) ?> songs</p>
            <?php if ($playlist['PlaylistCzasTrwania']): ?>
            <p>Total Duration: <?= htmlspecialchars(format_duration_from_time_type($playlist['PlaylistCzasTrwania'])) ?></p>
            <?php endif; ?>
            <p><a href="edit_playlist.php?id=<?= $playlist_id ?>" class="button">Edit This Playlist</a></p>
        </div>
    </div>

    <h2>Songs in this Playlist</h2>
    <?php
    $stmt_songs = $pdo->prepare("
        SELECT p.ID AS PiosenkaID, p.Tytul AS PiosenkaTytul, p.CzasTrwania AS PiosenkaCzasTrwania, p.Gatunek,
               a.Tytul AS AlbumTytul, a.ID AS AlbumID,
               w.Nazwa AS WykonawcaNazwa, w.ID AS WykonawcaID
        FROM piosenka p
        JOIN playlista_piosenka pp ON p.ID = pp.PiosenkaID
        LEFT JOIN album a ON p.AlbumID = a.ID
        LEFT JOIN wykonawca w ON a.WykonawcaID = w.ID -- Assuming album has WykonawcaID
        WHERE pp.PlaylistaID = ?
        ORDER BY p.Tytul
    ");
    $stmt_songs->execute([$playlist_id]);
    $songs = $stmt_songs->fetchAll();

    if ($songs):
    ?>
    <table class="track-list">
        <thead>
            <tr>
                <th>Title</th>
                <th>Artist</th>
                <th>Album</th>
                <th>Duration</th>
                <th>Genre</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($songs as $song): ?>
            <tr>
                <td class="track-title"><?= htmlspecialchars($song['PiosenkaTytul']) ?></td>
                <td>
                    <?php if ($song['WykonawcaID'] && $song['WykonawcaNazwa']): ?>
                        <a href="artists.php?id=<?= $song['WykonawcaID'] ?>"><?= htmlspecialchars($song['WykonawcaNazwa']) ?></a>
                    <?php else: echo "N/A"; endif; ?>
                </td>
                <td class="track-album">
                     <?php if ($song['AlbumID'] && $song['AlbumTytul']): ?>
                        <a href="albums.php?id=<?= $song['AlbumID'] ?>"><?= htmlspecialchars($song['AlbumTytul']) ?></a>
                    <?php else: echo "N/A"; endif; ?>
                </td>
                <td class="track-duration"><?= htmlspecialchars(format_duration_from_time_type($song['PiosenkaCzasTrwania'])) ?></td>
                <td><?= htmlspecialchars($song['Gatunek'] ?? 'N/A') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No songs found in this playlist yet. <a href="edit_playlist.php?id=<?= $playlist_id ?>">Add some!</a></p>
    <?php endif; ?>
     <p style="margin-top:2rem;"><a href="playlists.php" class="button-secondary">← Back to All Playlists</a></p>

<?php include 'includes/footer.php'; ?>