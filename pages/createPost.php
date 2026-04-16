<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post | SocialMedia</title>
    <link rel="stylesheet" href="../styles/navbar.styles.css">
    <link rel="stylesheet" href="../styles/createPostPage.styles.css">
</head>
<body>

<!--replace the # with the file names once pages are made-->
    <nav class="navbar">
        <div class="nav-container">
            <a href="#" class="logo">Wahly.com</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="createPost.php" class="active">Post</a></li>
            </ul>
        </div>
    </nav>

    <main class="content-wrapper">
        <div class="post-card">
            <h1>Create New Post</h1>
            <form id="create-post-form" action="../processing/form_processing.php" method="post" enctype="multipart/form-data">
                
                <div class="input-group">
                    <label for="post-image">Upload Photo</label>
                    <input type="file" name="photo" id="post-image" accept="image/*">
                </div>

                <div class="input-group">
                    <label for="post-title">Title</label>
                    <input type="text" name="title" id="post-title" placeholder="Give your post a title..." required>
                </div>

                <div class="input-group">
                    <label for="post-description">Description</label>
                    <textarea type="text" name="description" id="post-description" rows="3" placeholder="What's on your mind?"></textarea>
                </div>

                <button type="submit" class="submit-btn">Share Post</button>
            </form>
        </div>
    </main>

</body>
</html>