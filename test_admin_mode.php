<?php

// Script untuk menguji halaman admin dengan login
$loginUrl = 'http://localhost:8000/login';
$adminUrl = 'http://localhost:8000/admin';

// Inisialisasi cURL dengan cookie jar
$cookieJar = tempnam(sys_get_temp_dir(), 'cookies');

// Step 1: Get login page untuk mendapatkan CSRF token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
$loginPage = curl_exec($ch);

// Extract CSRF token
preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginPage, $matches);
$csrfToken = $matches[1] ?? '';

echo "CSRF Token: $csrfToken\n";

// Step 2: Login dengan credentials
$postData = [
    '_token' => $csrfToken,
    'email' => 'test@example.com',
    'password' => 'password'
];

curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$loginResult = curl_exec($ch);

echo "Login completed\n";

// Step 3: Access admin page
curl_setopt($ch, CURLOPT_URL, $adminUrl);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, '');
$adminPage = curl_exec($ch);

curl_close($ch);

// Check if we can find the current mode display
if (strpos($adminPage, 'Mode Aktif Saat Ini') !== false) {
    echo "Successfully accessed admin page\n";
    
    // Extract current mode display
    if (preg_match('/Mode Aktif Saat Ini:[^<]*<[^>]*>([^<]+)/', $adminPage, $matches)) {
        echo "Current Mode Display: " . trim($matches[1]) . "\n";
    }
    
    // Check if JavaScript is present
    if (strpos($adminPage, 'fetch(\'/api/current-display-mode\')') !== false) {
        echo "JavaScript API fetch code is present\n";
    } else {
        echo "JavaScript API fetch code is NOT present\n";
    }
} else {
    echo "Failed to access admin page or page structure changed\n";
    echo "Page content preview: " . substr($adminPage, 0, 500) . "...\n";
}

// Cleanup
unlink($cookieJar);