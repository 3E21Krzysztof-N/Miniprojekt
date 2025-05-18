<?php include 'includes/header.php'; ?>

<h1>Welcome to PlaylistHub</h1>

<section id="featured-playlists">
    <h2>Featured Playlists</h2>
    <div class="grid-container">
        <?php
        $stmt = $pdo->query("SELECT ID, Nazwa, DataUtworzenia, LiczbaUtworow FROM playlista ORDER BY DataUtworzenia DESC LIMIT 3");
        $playlists = $stmt->fetchAll();
        if ($playlists):
            foreach ($playlists as $playlist):
        ?>
            <div class="card">
                <h3><a href="view_playlist.php?id=<?= $playlist['ID'] ?>"><?= htmlspecialchars($playlist['Nazwa']) ?></a></h3>
                <p><?= htmlspecialchars($playlist['LiczbaUtworow'] ?? 0) ?> songs</p>
                <p class="card-meta">Created: <?= htmlspecialchars(date("M j, Y", strtotime($playlist['DataUtworzenia']))) ?></p>
                <div class="actions">
                    <a href="edit_playlist.php?id=<?= $playlist['ID'] ?>" class="button-secondary">Edit</a>
                </div>
            </div>
        <?php
            endforeach;
        else:
            echo "<p>No playlists found yet. <a href='create_playlist.php'>Create one!</a></p>";
        endif;
        ?>
    </div>
</section>

<section id="browse-artists">
    <h2>Browse Artists</h2>
    <div class="grid-container">
        <?php
        $stmt = $pdo->query("SELECT ID, Nazwa FROM wykonawca ORDER BY Nazwa LIMIT 4");
        $artists = $stmt->fetchAll();
        if ($artists):
            foreach ($artists as $artist):
        ?>
            <div class="card">
                <h3><a href="artists.php?id=<?= $artist['ID'] ?>"><?= htmlspecialchars($artist['Nazwa']) ?></a></h3>
                <!-- You can add artist album count here if desired -->
            </div>
        <?php
            endforeach;
        else:
            echo "<p>No artists found yet.</p>";
        endif;
        ?>
    </div>
    <p style="text-align: center; margin-top: 20px;"><a href="artists.php" class="button">View All Artists →</a></p>
</section>

<section id="recent-albums">
    <h2>Recent Albums</h2>
    <div class="grid-container">
        <?php
        $stmt = $pdo->query("
            SELECT a.ID, a.Tytul, w.Nazwa as WykonawcaNazwa, w.ID as WykonawcaID
            FROM album a
            JOIN wykonawca w ON a.WykonawcaID = w.ID
            ORDER BY a.ID DESC LIMIT 4
        ");
        $albums = $stmt->fetchAll();
        if ($albums):
            foreach ($albums as $album):
        ?>
            <div class="card">
                <h3><a href="albums.php?id=<?= $album['ID'] ?>"><?= htmlspecialchars($album['Tytul']) ?></a></h3>
                <p>By: <a href="artists.php?id=<?= $album['WykonawcaID'] ?>"><?= htmlspecialchars($album['WykonawcaNazwa']) ?></a></p>
            </div>
        <?php
            endforeach;
        else:
            echo "<p>No albums found yet.</p>";
        endif;
        ?>
    </div>
     <p style="text-align: center; margin-top: 20px;"><a href="albums.php" class="button">View All Albums →</a></p>
</section>

<?php include 'includes/footer.php'; ?>