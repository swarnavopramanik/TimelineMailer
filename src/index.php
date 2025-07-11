<?php
require_once __DIR__ . '/functions.php';

// TODO: Implement the form and logic for email registration and verification

session_start();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['unsubscribe_email'])) {
        $email = $_POST['unsubscribe_email'];
        $code = generateVerificationCode();
        $_SESSION['verification_code'] = $code;
        $_SESSION['unsubscribe_email'] = $email;
        sendVerificationEmail($email, $code);
        $message = "Email Verification code sent to $email";
    }

    if (isset($_POST['verification_code'])) {
        if ($_POST['verification_code'] == $_SESSION['verification_code']) {
            unsubscribeEmail($_SESSION['unsubscribe_email']);
            $message = "Your Code Verification successful.";
        } else {
           $message = "Invalid code.";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            text-align: center;
            width: 350px;
        }
        h3 {
            margin-top: 20px;
            color: #333;
        }
        input[type="email"], input[type="text"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            background: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background:rgb(174, 31, 59);
        }
        .popup {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        .popup.show {
            opacity: 1;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Email Verificatione</h3>
    <form method="post">
        <input type="email" name="unsubscribe_email" required placeholder="Enter your email">
        <button id="submit-unsubscribe">Register</button>
    </form>

    <h3>Enter the Code</h3>
    <form method="post">
        <input type="text" name="verification_code" placeholder="Enter code">
        <button id="verify-unsubscribe">Verify</button>
    </form>
</div>

<?php if (!empty($message)) : ?>
<div class="popup" id="popup"><?php echo $message; ?></div>
<script>
    const popup = document.getElementById('popup');
    popup.classList.add('show');
    setTimeout(() => {
        popup.classList.remove('show');
    }, 3000); // Hide after 3 seconds
</script>
<?php endif; ?>

</body>
</html>