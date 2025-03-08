<?php
/**
 * home.view.php
 * Home page template with complete structure
 */
?>

<div class="hero-section">
    <div class="container">
        <h1><?= htmlspecialchars($title) ?></h1>
        <p class="lead">Welcome to our PHP website with secure routing.</p>
        
        <!-- Call to action button -->
        <a href="/register" class="btn btn-primary">Get Started</a>
    </div>
</div>

<!-- Display any error messages -->
<?php if (!empty($errors)): ?>
    <div class="container mt-4">
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<!-- Display any success messages -->
<?php if (!empty($messages)): ?>
    <div class="container mt-4">
        <div class="alert alert-success">
            <ul class="mb-0">
                <?php foreach ($messages as $message): ?>
                    <li><?= htmlspecialchars($message) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<!-- Featured Content Section -->
<section class="featured-section">
    <div class="container">
        <h2>Featured Content</h2>
        
        <div class="row">
            <?php foreach ($featured_items as $item): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($item['excerpt']) ?></p>
                            <a href="/article/<?= $item['id'] ?>" class="btn btn-outline-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">Our Community</h2>
        
        <div class="row text-center">
            <div class="col-md-4">
                <div class="stat-box">
                    <h3><?= number_format($stats['users']) ?></h3>
                    <p>Registered Users</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <h3><?= number_format($stats['articles']) ?></h3>
                    <p>Published Articles</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <h3><?= number_format($stats['comments']) ?></h3>
                    <p>Community Comments</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Subscription -->
<section class="newsletter-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Subscribe to Our Newsletter</h3>
                        <p class="card-text">Stay updated with our latest articles and news.</p>
                        
                        <form action="/subscribe" method="POST" class="d-flex">
                            <!-- CSRF Protection -->
                            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                            
                            <input type="email" name="email" class="form-control me-2" placeholder="Your Email Address" required>
                            <button type="submit" class="btn btn-primary">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>