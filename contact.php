<?php 
if(session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/database.php';
// Load secure API keys
if(file_exists('config/secrets.php')) require_once 'config/secrets.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
    
    if($name && $email && $message) {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);
        
        // --- BREVO EMAIL API INTEGRATION ---
        $apiKey = defined('BREVO_API_KEY') ? BREVO_API_KEY : 'YOUR_BREVO_API_KEY_HERE';
        $apiUrl = 'https://api.brevo.com/v3/smtp/email';
        
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . $apiKey,
            'content-type: application/json'
        ]);
        
        // 1. Email to Admin (Rovic)
        $adminHtml = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #e0e0e0;'>
            <div style='background-color: #1e212b; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;'>
                <h2 style='color: #ff6b57; margin: 0;'>New Portfolio Message</h2>
            </div>
            <div style='background-color: #ffffff; padding: 30px; border-radius: 0 0 8px 8px;'>
                <p style='font-size: 16px; color: #333;'>You have received a new message from your portfolio contact form.</p>
                <table style='width: 100%; margin-top: 20px; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 10px; border-bottom: 1px solid #eee; width: 30%; color: #666; font-weight: bold;'>Name:</td>
                        <td style='padding: 10px; border-bottom: 1px solid #eee; color: #333;'>" . htmlspecialchars($name) . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border-bottom: 1px solid #eee; color: #666; font-weight: bold;'>Email:</td>
                        <td style='padding: 10px; border-bottom: 1px solid #eee; color: #333;'><a href='mailto:" . htmlspecialchars($email) . "' style='color: #0dcaf0; text-decoration: none;'>" . htmlspecialchars($email) . "</a></td>
                    </tr>
                </table>
                <h4 style='color: #1e212b; margin-top: 30px; margin-bottom: 10px;'>Message:</h4>
                <div style='background-color: #f4f6f9; padding: 15px; border-radius: 6px; border-left: 4px solid #ff6b57; font-size: 15px; color: #444; line-height: 1.6;'>
                    " . nl2br(htmlspecialchars($message)) . "
                </div>
            </div>
        </div>";

        $adminData = [
            'sender' => ['name' => 'Portfolio Contact Form', 'email' => 'rovicrj96@gmail.com'],
            'to' => [['email' => 'rovicrj96@gmail.com', 'name' => 'Rovic Andrew']],
            'replyTo' => ['email' => $email, 'name' => $name],
            'subject' => 'New Portfolio Message from ' . $name,
            'htmlContent' => $adminHtml
        ];
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($adminData));
        curl_exec($ch);
        
        // 2. Auto-reply to Visitor
        $visitorHtml = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px 20px; border-top: 5px solid #ff6b57;'>
            <h2 style='color: #1e212b; margin-top: 0;'>Thank you for reaching out, " . htmlspecialchars($name) . "!</h2>
            <p style='font-size: 16px; color: #555; line-height: 1.6;'>
                This is an automated message to confirm that I have successfully received your inquiry via my portfolio website.
            </p>
            <p style='font-size: 16px; color: #555; line-height: 1.6;'>
                I review all messages personally and will get back to you as soon as possible regarding your project or inquiry.
            </p>
            <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
            <div style='display: flex; align-items: center;'>
                <div>
                    <h4 style='margin: 0; color: #1e212b;'>Rovic Andrew V. Bungalan</h4>
                    <p style='margin: 5px 0 0 0; color: #888; font-size: 14px;'>Information Systems Student</p>
                </div>
            </div>
        </div>";

        $userData = [
            'sender' => ['name' => 'Rovic Andrew', 'email' => 'rovicrj96@gmail.com'],
            'to' => [['email' => $email, 'name' => $name]],
            'subject' => 'Inquiry Received - Rovic Andrew',
            'htmlContent' => $visitorHtml
        ];
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
        curl_exec($ch);
        
        curl_close($ch);
        
        // PRG Pattern: Store success in session and redirect to avoid form resubmission
        $_SESSION['sweet_success'] = "Your message has been sent successfully. I will get back to you soon!";
        header("Location: contact.php");
        exit;
    }
}

require_once 'includes/header.php'; 

// Extract session success message if available (from PRG pattern)
if (isset($_SESSION['sweet_success'])) {
    $sweet_success = $_SESSION['sweet_success'];
    unset($_SESSION['sweet_success']);
}
?>

<style>
/* Ensure inputs are highly readable in dark mode */
.form-control {
    background-color: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: #ffffff !important;
    font-weight: 500 !important;
}
.form-control:focus {
    background-color: rgba(255, 255, 255, 0.1) !important;
    border-color: var(--accent-color) !important;
    color: #ffffff !important;
    box-shadow: 0 0 0 0.25rem rgba(255, 107, 87, 0.25) !important;
}
.form-control::placeholder {
    color: #a0a4b8 !important;
    opacity: 1 !important;
}
</style>

<section class="py-5 mt-5">
    <div class="container">
        <h2 class="section-title">Contact Us</h2>
        
        <div class="row justify-content-center mt-5">
            <div class="col-lg-10">
                <div class="row g-5">
                    <div class="col-md-5">
                        <h4 class="text-white mb-4 d-flex align-items-center gap-2"><i class="fa-solid fa-address-card text-accent" style="color: var(--accent-color);"></i> Get In Touch</h4>
                        <p class="mb-5" style="line-height: 1.8;">I am available for freelance work, internships, and entry-level positions. Connect with me via email, phone, or LinkedIn.</p>
                        
                        <div class="d-flex align-items-center mb-4 p-3 rounded" style="background-color: var(--secondary-bg); border: 1px solid var(--border-color);">
                            <div class="service-icon me-4" style="font-size: 1.5rem; width: 30px; text-align: center;">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <h6 class="text-white mb-1" style="font-size: 0.9rem;">Phone</h6>
                                <p class="mb-0 text-white font-weight-bold"><?= htmlspecialchars($profile['phone']) ?></p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-4 p-3 rounded" style="background-color: var(--secondary-bg); border: 1px solid var(--border-color);">
                            <div class="service-icon me-4" style="font-size: 1.5rem; width: 30px; text-align: center;">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <h6 class="text-white mb-1" style="font-size: 0.9rem;">Email</h6>
                                <p class="mb-0 text-white"><?= htmlspecialchars($profile['email']) ?></p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-4 p-3 rounded" style="background-color: var(--secondary-bg); border: 1px solid var(--border-color);">
                            <div class="service-icon me-4" style="font-size: 1.5rem; width: 30px; text-align: center;">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <h6 class="text-white mb-1" style="font-size: 0.9rem;">Address</h6>
                                <p class="mb-0 text-white"><?= htmlspecialchars($profile['address']) ?></p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center p-3 rounded" style="background-color: var(--secondary-bg); border: 1px solid var(--border-color);">
                            <div class="service-icon me-4" style="font-size: 1.5rem; width: 30px; text-align: center;">
                                <i class="fa-brands fa-linkedin"></i>
                            </div>
                            <div>
                                <h6 class="text-white mb-1" style="font-size: 0.9rem;">LinkedIn</h6>
                                <a href="<?= htmlspecialchars($profile['linkedin']) ?>" class="text-white text-decoration-none" target="_blank">View Profile <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size: 0.7rem;"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-7">
                        <div class="service-card h-100 d-block p-4 p-md-5">
                            <h4 class="text-white mb-4 d-flex align-items-center gap-2"><i class="fa-solid fa-paper-plane text-accent" style="color: var(--accent-color);"></i> Send a Message</h4>

                            <form action="" method="POST">
                                <div class="mb-4">
                                    <label class="form-label text-white">Your Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-white">Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-white">Message</label>
                                    <textarea name="message" class="form-control" rows="5" placeholder="Tell me about your project..." required></textarea>
                                </div>
                                <button type="submit" class="btn-accent w-100 text-center" style="padding: 15px 30px; font-size: 1.1rem;"><i class="fa-solid fa-paper-plane me-2"></i> Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
