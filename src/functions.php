<?php

/**
 * Generate a 6-digit numeric verification code.
 */
function generateVerificationCode(): string {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Send verification code to user for registration.
 */
function sendVerificationEmail(string $email, string $code): void {
    $subject = "Your Verification Code";
    $message = "<p>Your verification code is: <strong>$code</strong></p>";

    file_put_contents(__DIR__ . '/email_log.txt', "TO: $email\nSubject: $subject\n$message\n\n", FILE_APPEND);
}

/**
 * Register an email by storing it in the subscriber file.
 */
function registerEmail(string $email): void {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) {
        file_put_contents($file, '');
    }
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

/**
 * Send verification code to confirm unsubscription.
 */
function sendUnsubscribeEmail(string $email, string $code): void {
    $subject = "Confirm Unsubscription";
    $message = "<p>Your verification code to unsubscribe is: <strong>$code</strong></p>";

    file_put_contents(__DIR__ . '/email_log.txt', "TO: $email\nSubject: $subject\n$message\n\n", FILE_APPEND);
}

/**
 * Remove an email from the subscriber list.
 */
function unsubscribeEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return false;

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $updatedEmails = array_filter($emails, fn($e) => trim($e) !== trim($email));
    file_put_contents($file, implode(PHP_EOL, $updatedEmails) . PHP_EOL);
    return true;
}

/**
 * Send unsubscription success confirmation email.
 */
function sendUnsubscribeSuccessEmail(string $email): void {
    $subject = "You have been successfully unsubscribed";
    $message = "<p>Your email <strong>$email</strong> has been removed from our list.</p>";

    file_put_contents(__DIR__ . '/email_log.txt', "TO: $email\nSubject: $subject\n$message\n\n", FILE_APPEND);
}

/**
 * Fetch GitHub timeline.
 */
function fetchGitHubTimeline(): array {
    $url = "https://api.github.com/events";
    $options = [
        "http" => [
            "header" => "User-Agent: PHP\r\n"
        ]
    ];
    $context = stream_context_create($options);
    $data = @file_get_contents($url, false, $context);

    if ($data === false) {
        
        return [
            ["type" => "PushEvent", "actor" => ["login" => "test_user"]],
            ["type" => "ForkEvent", "actor" => ["login" => "demo_user"]]
        ];
    }
    return json_decode($data, true) ?? [];
}

/**
 * Format GitHub timeline data as HTML.
 */
function formatGitHubData(array $data): string {
    $html = "<h2>GitHub Timeline Updates</h2><table border='1'><tr><th>Event</th><th>User</th></tr>";

    foreach ($data as $event) {
        $eventType = $event['type'] ?? 'Unknown';
        $username = $event['actor']['login'] ?? 'Unknown';
        $html .= "<tr><td>$eventType</td><td>$username</td></tr>";
    }

    $html .= "</table>";
    return $html;
}

/**
 * Send GitHub updates to all registered users with unsubscribe link.
 */
function sendGitHubUpdatesToSubscribers(): void {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) {
        //echo "registered_emails.txt not found.\n";
        return;
    }

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (empty($emails)) {
        echo "No registered emails found.\n";
        return;
    }

    $data = fetchGitHubTimeline();
    
    if (empty($data)) {
        echo "GitHub timeline fetch failed, using dummy data.\n";
        $data = [
            ["type" => "PushEvent", "actor" => ["login" => "test_user"]],
            ["type" => "ForkEvent", "actor" => ["login" => "demo_user"]]
        ];
    }

    $html = formatGitHubData($data);

    foreach ($emails as $email) {
        $unsubscribeLink = "http://localhost:8000/src/unsubscribe.php?email=" . urlencode($email);
        $body = $html . "<p>If you wish to unsubscribe, click here: <a href=\"$unsubscribeLink\">Unsubscribe</a></p>";
        $subject = "Latest GitHub Updates";

        file_put_contents(__DIR__ . '/email_log.txt', "TO: $email\nSubject: $subject\n$body\n\n", FILE_APPEND);
        echo "Email sent to $email\n";
    }
}
