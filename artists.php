<?php
ini_set('display_errors', 1); error_reporting(E_ALL);
include 'includes/header.php';

if (isset($_GET['id']) && !empty($_GET['id'])):
    $artist_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("
        SELECT w.Nazwa AS WykonawcaNazwa, wt.Nazwa AS WytworniaNazwa
        FROM wykonawca w
        LEFT JOIN wytwornia wt ON w.WytworniaID = wt.ID
        WHERE w.ID = ?
    ");
    $stmt->execute([$artist_id]);
    $artist = $stmt->fetch();

    if ($artist):
?>
    <div class="detail-header">
        <div class="detail-info">
            <h1><?= htmlspecialchars($artist['WykonawcaNazwa']) ?></h1>
            <?php if ($artist['WytworniaNazwa']): ?>
                <p>Record Label: <?= htmlspecialchars($artist['WytworniaNazwa']) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <h2>Albums by <?= htmlspecialchars($artist['WykonawcaNazwa']) ?></h2>
    <?php
    $stmt_albums = $pdo->prepare("
        SELECT ID, Tytul, IloscPiosenek
        FROM album
        WHERE WykonawcaID = ?
        ORDER BY Tytul
    ");
    $stmt_albums->execute([$artist_id]);
    $albums = $stmt_albums->fetchAll();

    if ($albums):
    ?>
    <div class="grid-container">
        <?php foreach ($albums as $album_item): ?>
        <div class="card">
            <h3><a href="albums.php?id=<?= $album_item['ID'] ?>"><?= htmlspecialchars($album_item['Tytul']) ?></a></h3>
            <p><?= htmlspecialchars($album_item['IloscPiosenek'] ?? 0) ?> songs</p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <p>No albums found for this artist yet.</p>
    <?php endif; ?>
    <p style="margin-top:2rem;"><a href="artists.php" class="button-secondary">← Back to All Artists</a></p>
<?php
    else:
        echo "<h1>Artist Not Found</h1><p>The artist you are looking for does not exist.</p>";
    endif;
else:
?>
    <h1>All Artists</h1>
    <div class="grid-container">
    <?php
    $stmt_all_artists = $pdo->query("
        SELECT w.ID, w.Nazwa AS WykonawcaNazwa, wt.Nazwa AS WytworniaNazwa
        FROM wykonawca w
        LEFT JOIN wytwornia wt ON w.WytworniaID = wt.ID
        ORDER BY w.Nazwa
    ");
    $artists_list = $stmt_all_artists->fetchAll();
    if ($artists_list):
        foreach ($artists_list as $artist_item):
    ?>
        <div class="card">
            <h3><a href="artists.php?id=<?= $artist_item['ID'] ?>"><?= htmlspecialchars($artist_item['WykonawcaNazwa']) ?></a></h3>
            <?php if ($artist_item['WytworniaNazwa']): ?>
                <p class="card-meta">Label: <?= htmlspecialchars($artist_item['WytworniaNazwa']) ?></p>
            <?php endif; ?>
        </div>
    <?php
        endforeach;
    else:
        echo "<p>No artists found in the database.</p>";
    endif;
    ?>
    </div>
<?php
endif;
include 'includes/footer.php';
?>