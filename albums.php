<?php
ini_set('display_errors', 1); error_reporting(E_ALL);
include 'includes/header.php';

if (!function_exists('format_duration_from_time_type')) {
    function format_duration_from_time_type($time_str) {
        if (empty($time_str) || $time_str == '00:00:00') return 'N/A';
        list($h, $m, $s) = explode(':', $time_str);
        $h = (int)$h; $m = (int)$m; $s = (int)$s;
        if ($h > 0) return sprintf('%d:%02d:%02d', $h, $m, $s);
        else return sprintf('%d:%02d', $m, $s);
    }
}

if (isset($_GET['id']) && !empty($_GET['id'])):
    $album_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("
        SELECT a.Tytul AS AlbumTytul, a.IloscPiosenek, w.ID AS WykonawcaID, w.Nazwa AS WykonawcaNazwa
        FROM album a
        JOIN wykonawca w ON a.WykonawcaID = w.ID
        WHERE a.ID = ?
    ");
    $stmt->execute([$album_id]);
    $album_detail = $stmt->fetch();

    if ($album_detail):
?>
    <div class="detail-header">
        <div class="detail-info">
            <h1><?= htmlspecialchars($album_detail['AlbumTytul']) ?></h1>
            <p>By: <a href="artists.php?id=<?= $album_detail['WykonawcaID'] ?>"><?= htmlspecialchars($album_detail['WykonawcaNazwa']) ?></a></p>
            <p><?= htmlspecialchars($album_detail['IloscPiosenek'] ?? 0) ?> songs</p>
        </div>
    </div>

    <h2>Tracklist</h2>
    <?php
    $stmt_tracks = $pdo->prepare("
        SELECT ID, Tytul, CzasTrwania, Gatunek
        FROM piosenka
        WHERE AlbumID = ?
        ORDER BY Tytul
    ");
    $stmt_tracks->execute([$album_id]);
    $tracks = $stmt_tracks->fetchAll();
    if ($tracks):
    ?>
    <table class="track-list">
        <thead><tr><th>#</th><th>Title</th><th>Duration</th><th>Genre</th></tr></thead>
        <tbody>
            <?php $track_num = 1; foreach ($tracks as $track): ?>
            <tr>
                <td><?= $track_num++ ?></td>
                <td class="track-title"><?= htmlspecialchars($track['Tytul']) ?></td>
                <td class="track-duration"><?= htmlspecialchars(format_duration_from_time_type($track['CzasTrwania'])) ?></td>
                <td><?= htmlspecialchars($track['Gatunek'] ?? 'N/A') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No tracks found for this album yet.</p>
    <?php endif; ?>
    <p style="margin-top:2rem;"><a href="albums.php" class="button-secondary">← Back to All Albums</a></p>
<?php
    else:
        echo "<h1>Album Not Found</h1><p>The album you are looking for does not exist.</p>";
    endif;
else:
?>
    <h1>All Albums</h1>
    <div class="grid-container">
    <?php
    $stmt_all_albums = $pdo->query("
        SELECT a.ID, a.Tytul, a.IloscPiosenek, w.Nazwa AS WykonawcaNazwa, w.ID AS WykonawcaID
        FROM album a
        JOIN wykonawca w ON a.WykonawcaID = w.ID
        ORDER BY a.Tytul
    ");
    $albums_list = $stmt_all_albums->fetchAll();
    if ($albums_list):
        foreach ($albums_list as $album_item):
    ?>
        <div class="card">
            <h3><a href="albums.php?id=<?= $album_item['ID'] ?>"><?= htmlspecialchars($album_item['Tytul']) ?></a></h3>
            <p>By: <a href="artists.php?id=<?= $album_item['WykonawcaID'] ?>"><?= htmlspecialchars($album_item['WykonawcaNazwa']) ?></a></p>
            <p class="card-meta"><?= htmlspecialchars($album_item['IloscPiosenek'] ?? 0) ?> songs</p>
        </div>
    <?php
        endforeach;
    else:
        echo "<p>No albums found in the database.</p>";
    endif;
    ?>
    </div>
<?php
endif;
include 'includes/footer.php';
?>