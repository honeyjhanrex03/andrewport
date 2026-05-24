// Main JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Portfolio initialized');
    
    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Update active nav link based on current path
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        // Handle root link specifically to prevent it from matching everything
        if (href === '/' || href === '/andrew/') {
            if (currentPath === href || currentPath === href + 'index.php') {
                link.classList.add('active');
            }
        } else if (currentPath.includes(href)) {
            link.classList.add('active');
        }
    });
});

