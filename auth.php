
<?php

// Cookie security settings (before session_start)
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);   
    ini_set('session.use_strict_mode', 1);  

    session_start(); // Start the session

// Security headers (expanded from your current ones)
    header('X-Frame-Options: DENY'); // Prevent clickjacking
    header('X-XSS-Protection: 1; mode=block'); // Enable XSS protection
    header("X-Content-Type-Options: nosniff"); // Prevent MIME type sniffing
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains"); // Force HTTPS

    header("Content-Security-Policy: default-src 'self' https://cdnjs.cloudflare.com https://code.jquery.com; 
        script-src 'self' 'unsafe-inline' 'unsafe-eval' 
            https://cdnjs.cloudflare.com 
            https://code.jquery.com 
            https://cdn.jsdelivr.net; 
        style-src 'self' 'unsafe-inline' 
            https://cdnjs.cloudflare.com 
            assets/css/ 
            assets/libs/; 
        img-src 'self' data: /api/placeholder/ assets/images/; 
        font-src 'self' data: https://cdnjs.cloudflare.com");

    header("Referrer-Policy: strict-origin-when-cross-origin"); // Control referrer information
    header("Permissions-Policy: camera=(), microphone=(), geolocation=()"); // Restrict browser features

// Session timeout after 12 hours of inactivity
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 43200)) {
        // last request was more than 12 hours ago
        session_unset();     // unset $_SESSION variable for the run-time 
        session_destroy();   // destroy session data in storage
        header('Location: occur_login.php'); // redirect to login page
        exit();
    }
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity timestamp



// Check if the user is logged in
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        // Redirect to the login page if not logged in
        header('Location: occur_login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }

// Retrieve the email from the session
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
    } else {
        // Log the issue if the email is not set
        error_log('Email not set in session for user: ' . session_id());
        $email = 'Email not set'; // Fallback if email is not set
    }

// Prevent session fixation
    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } else if (time() - $_SESSION['CREATED'] > 43200) {
        // session started more than 12 hours ago
        session_regenerate_id(true);    // change session ID for the current session
        $_SESSION['CREATED'] = time();  // update creation time
    }
    
?>




