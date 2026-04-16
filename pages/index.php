<?php require_once('../database/db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Wahly</title>
    <link rel="stylesheet" href="../styles/navbar.styles.css">
    <link rel="stylesheet" href="../styles/displayPage.styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">Wahly.com</a>
            <ul class="nav-links">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="createPost.php">Post</a></li>
            </ul>
        </div>
    </nav>

    <main class="feed-wrapper">
        <h1 class="feed-title">Recent Posts</h1>

        <div class="feed-container">
            <?php
                $result = $mysqli->query("SELECT * FROM posts ORDER BY id DESC");

                if ($result && $result->num_rows > 0):
                    while ($post = $result->fetch_assoc()):
                        $imagePath = htmlspecialchars($post['photo']);
                        $title     = htmlspecialchars($post['title']);
                        $desc      = htmlspecialchars($post['description']);
            ?>
            <article class="post-card">
                <?php if (!empty($imagePath)): ?>
                <div class="post-image-wrap">
                    <img src="<?= $imagePath ?>" alt="<?= $title ?>">
                </div>
                <?php endif; ?>
                <div class="post-body">
                    <div class="post-body-header">
                        <h2 class="post-title"><?= $title ?></h2>
                        <form action="../processing/delete_post.php" method="post" onsubmit="return confirm('Delete this post?')">
                            <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">
                            <input type="hidden" name="photo" value="<?= $imagePath ?>">
                            <button type="submit" class="delete-btn" title="Delete post">&#x1F5D1;</button>
                        </form>
                    </div>
                    <?php if (!empty($desc)): ?>
                    <p class="post-desc"><?= $desc ?></p>
                    <?php endif; ?>
                </div>
            </article>
            <?php
                    endwhile;
                else:
            ?>
            <div class="empty-state">
                <p>No posts yet. <a href="createPost.php">Be the first to share something!</a></p>
            </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
