<?php

/**
 * footer.php
 * Common footer for all pages
 */
?>

<!-- Footer -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5><?= htmlspecialchars(APP_NAME) ?></h5>
                <p>A secure and modern PHP website template with routing.</p>
            </div>

            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="/" class="text-white">Home</a></li>
                    <li><a href="/about" class="text-white">About Us</a></li>
                    <li><a href="/contact" class="text-white">Contact</a></li>
                    <li><a href="/privacy" class="text-white">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5>Connect With Us</h5>
                <div class="social-links">
                    <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-2"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white me-2"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <div class="text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> <?= htmlspecialchars(APP_NAME) ?>. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script src="/assets/js/main.js"></script>
</body>

</html>