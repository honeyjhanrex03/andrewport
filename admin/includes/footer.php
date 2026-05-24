            </div>
        </div>
    </div>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    
    <script>
    // Mobile Sidebar Toggle
    document.getElementById('menu-toggle')?.addEventListener('click', function() {
        document.getElementById('wrapper').classList.toggle('toggled');
    });
    document.getElementById('mobile-overlay')?.addEventListener('click', function() {
        document.getElementById('wrapper').classList.remove('toggled');
    });
    
    // Universal SweetAlert for forms that use .delete-form
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    });
    
    // Check URL parameters for success messages
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('success')) {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: urlParams.get('success'),
            timer: 2500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
        // Remove param from URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    </script>
</body>
</html>