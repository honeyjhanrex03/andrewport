    <!-- Footer -->
    <footer class="mt-5 pt-5 pb-3" style="background-color: var(--secondary-bg); border-top: 1px solid var(--border-color);">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6 pr-lg-5">
                    <a href="<?= BASE_URL ?>" class="d-inline-block mb-4">
                        <img src="<?= BASE_URL ?>assets/images/logo.png" alt="<?= htmlspecialchars($profile['name'] ?? 'Logo') ?>" height="40" style="object-fit: contain; border-radius: 4px;">
                    </a>
                    <p class="mb-4" style="color: var(--text-color); line-height: 1.8;">
                        <?= htmlspecialchars(isset($profile['objective']) ? substr($profile['objective'], 0, 120) . '...' : 'A passionate Information Systems Student specializing in UI Design and Data Encoding.') ?>
                    </p>
                    <div class="d-flex gap-3">
                        <a href="<?= htmlspecialchars($profile['linkedin'] ?? '#') ?>" target="_blank" class="text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.05); border-radius: 50%; transition: 0.3s;" onmouseover="this.style.backgroundColor='var(--accent-color)'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.05)'">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                        <a href="mailto:<?= htmlspecialchars($profile['email'] ?? '') ?>" class="text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.05); border-radius: 50%; transition: 0.3s;" onmouseover="this.style.backgroundColor='var(--accent-color)'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.05)'">
                            <i class="fa-solid fa-envelope"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h5 class="text-white mb-4">Quick Links</h5>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                        <li><a href="<?= BASE_URL ?>" class="text-decoration-none" style="color: var(--text-color); transition: 0.3s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-color)'">Home</a></li>
                        <li><a href="<?= BASE_URL ?>about" class="text-decoration-none" style="color: var(--text-color); transition: 0.3s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-color)'">About Us</a></li>
                        <li><a href="<?= BASE_URL ?>services" class="text-decoration-none" style="color: var(--text-color); transition: 0.3s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-color)'">Services</a></li>
                        <li><a href="<?= BASE_URL ?>portfolio" class="text-decoration-none" style="color: var(--text-color); transition: 0.3s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-color)'">Portfolio</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Services</h5>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                        <li><a href="<?= BASE_URL ?>services" class="text-decoration-none" style="color: var(--text-color); transition: 0.3s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-color)'">UI/UX Design</a></li>
                        <li><a href="<?= BASE_URL ?>services" class="text-decoration-none" style="color: var(--text-color); transition: 0.3s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-color)'">Data Encoding</a></li>
                        <li><a href="<?= BASE_URL ?>services" class="text-decoration-none" style="color: var(--text-color); transition: 0.3s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-color)'">Administrative Support</a></li>
                        <li><a href="<?= BASE_URL ?>services" class="text-decoration-none" style="color: var(--text-color); transition: 0.3s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-color)'">System Analysis</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Contact Info</h5>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                        <li class="d-flex align-items-start gap-3">
                            <i class="fa-solid fa-location-dot mt-1" style="color: var(--accent-color);"></i>
                            <span style="color: var(--text-color);"><?= htmlspecialchars($profile['address'] ?? 'Davao City, Philippines') ?></span>
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-phone" style="color: var(--accent-color);"></i>
                            <span style="color: var(--text-color);"><?= htmlspecialchars($profile['phone'] ?? '+63 000 000 0000') ?></span>
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-envelope" style="color: var(--accent-color);"></i>
                            <span style="color: var(--text-color);"><?= htmlspecialchars($profile['email'] ?? 'email@example.com') ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-top pt-4 mt-4 text-center d-flex flex-column flex-md-row justify-content-between align-items-center" style="border-color: rgba(255,255,255,0.05) !important;">
                <p class="mb-2 mb-md-0" style="color: var(--text-color); font-size: 0.9rem;">
                    &copy; <?= date('Y'); ?> <?= htmlspecialchars($profile['name'] ?? 'Rovic Andrew V. Bungalan') ?>. All Rights Reserved.
                </p>
                <div class="d-flex gap-4" style="font-size: 0.9rem;">
                    <a href="#" class="text-decoration-none" style="color: var(--text-color); transition: 0.3s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-color)'">Privacy Policy</a>
                    <a href="#" class="text-decoration-none" style="color: var(--text-color); transition: 0.3s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-color)'">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= BASE_URL ?>assets/js/main.js?v=<?= time() ?>"></script>
    
    <script>
    <?php if(isset($sweet_success)): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?= addslashes($sweet_success) ?>',
            confirmButtonColor: '#ff6b57',
            background: '#1e212b',
            color: '#fff'
        });
    <?php endif; ?>
    <?php if(isset($sweet_error)): ?>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '<?= addslashes($sweet_error) ?>',
            confirmButtonColor: '#ff6b57',
            background: '#1e212b',
            color: '#fff'
        });
    <?php endif; ?>
    </script>
</body>
</html>

