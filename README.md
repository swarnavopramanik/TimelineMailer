
This project is a PHP-based email verification system where users register using their email, receive a verification code, and subscribe to GitHub timeline updates. A CRON job fetches the latest GitHub timeline every 5 minutes and sends updates to registered users via email.

---

![email](https://github.com/user-attachments/assets/bf16f347-49a4-4bd9-9977-bf8041cbaad3)

## Project Demo 

- [Video](https://vimeo.com/1097250835)

## 🚀 Task


✅ Implement all required functions in `functions.php`.  

✅ Implement a form in `index.php` to take email input and verify via code.  

✅ Implement a CRON job to send GitHub timeline updates every 5 minutes.  

✅ Implement an unsubscribe feature where users can opt out via email verification.

✅ Implement `unsubscribe.php` to handle email unsubscription.

---

## 📌 Implement all Features 

### 1️⃣ **Email Verification**
- Users enter their email in a form.
- A **6-digit numeric code** is generated and emailed to them.
- Users enter the code in the form to verify and register.
- Store the verified email in `registered_emails.txt`.

### 2️⃣ **Unsubscribe Mechanism**
- Emails should include an **unsubscribe link**.
- Clicking it will take user to the unsubscribe page.
- Users enter their email in a form.
- A **6-digit numeric code** is generated and emailed to them.
- Users enter the code to confirm unsubscription.

### 3️⃣ **GitHub Timeline Fetch**
- Every 5 minutes, a CRON job should:
- Fetch data from `https://www.github.com/timeline`
- Format it as **HTML (not JSON)**.
- Send it via email to all registered users.

---
