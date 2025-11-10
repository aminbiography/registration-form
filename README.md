Live URL:

https://aminbiography.github.io/registration-form/
  
------------------------------------------------------

Here’s a developer-friendly guide you can drop into a `README.md` (or your team wiki). It’s opinionated, pragmatic, and includes production notes, security must-dos, and test recipes.

---

# Registration Form + PHP Mail Backend — Developer Guide

## 1) What this is

A minimal registration pipeline composed of:

* **`registration_form.html`** — collects user data and does light client-side validation.
* **`process_registration.php`** — receives `POST`, validates/sanitizes, and emails the submission (or persists it, if you add a DB).

> Works with PHP’s `mail()` for quick demos. For production, use SMTP (PHPMailer) and environment variables.

---

## 2) Project layout

```
/
├─ public/
│  ├─ registration_form.html
│  ├─ process_registration.php
│  ├─ thanks.html                 # optional success page
│  └─ assets/...                  # css/js if you split files
├─ vendor/                        # if using Composer + PHPMailer
├─ .env                           # SMTP creds (never commit)
└─ README.md
```

---

## 3) HTML form (client)

**Fields captured**

* First/Last Name, Nickname, Gender
* Graduation Course, Passing Year
* Address, Date of Birth
* Email, Phone/WhatsApp

**Behavior**

* Method: `POST`
* Action: `process_registration.php` (or a Formspree endpoint during prototyping)
* CSS for layout/responsiveness; gradient styles for header/button
* JS validates required fields before submit (basic “presence” checks)

**Example (essential bits):**

```html
<form method="POST" action="/process_registration.php" id="regForm" novalidate>
  <input name="first_name" required />
  <input name="last_name" required />
  <input name="nickname" />
  <select name="gender" required>
    <option value="">Select…</option>
    <option>Male</option><option>Female</option><option>Other</option>
  </select>
  <input name="course" required />
  <input name="passing_year" type="number" min="1900" max="2100" required />
  <textarea name="address"></textarea>
  <input name="dob" type="date" required />
  <input name="email" type="email" required />
  <input name="phone" required />
  <button type="submit">Submit</button>
</form>
<script>
document.getElementById('regForm').addEventListener('submit', (e) => {
  const required = ['first_name','last_name','gender','course','passing_year','dob','email','phone'];
  const missing = required.filter(n => !document.querySelector(`[name="${n}"]`).value.trim());
  if (missing.length) { e.preventDefault(); alert('Please complete all required fields.'); }
});
</script>
```

---

## 4) PHP endpoint (server)

**Responsibilities**

* Accept **POST only**
* Validate + sanitize inputs
* Construct a plain-text (or HTML) email with the registration details
* Send via `mail()` (demo) or SMTP (recommended)
* Return success/failure message or redirect to `thanks.html`

**Secure baseline (no dependencies):**

```php
<?php
// process_registration.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405); exit('Method Not Allowed');
}

function f($key, $filter = FILTER_SANITIZE_SPECIAL_CHARS) {
  return trim((string)filter_input(INPUT_POST, $key, $filter));
}

// Collect
$first = f('first_name');
$last  = f('last_name');
$nick  = f('nickname');
$gender= f('gender');
$course= f('course');
$year  = f('passing_year', FILTER_SANITIZE_NUMBER_INT);
$addr  = f('address');
$dob   = f('dob');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$phone = f('phone');

// Validate
$errors = [];
foreach (['first','last','gender','course','year','dob','phone'] as $k) { if (!$$k) $errors[] = "$k required"; }
if (!$email) $errors[] = 'Valid email required';
if ($year && ($year < 1900 || $year > 2100)) $errors[] = 'Year out of range';

if ($errors) {
  http_response_code(400);
  echo 'Validation failed: ' . implode(', ', $errors);
  exit;
}

// Compose
$to   = 'recipient@example.com';
$subj = "New Registration: $first $last";
$body = <<<TXT
New registration received:

Name: $first $last
Nickname: $nick
Gender: $gender
Course: $course
Passing Year: $year
Address: $addr
DOB: $dob
Email: $email
Phone: $phone
TXT;

$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
// Prevent header injection by never interpolating raw user input into headers:
$headers[] = 'From: noreply@example.com';
$headers[] = 'Reply-To: ' . $email; // safe: validated email
$headersStr = implode("\r\n", $headers);

// Send
$ok = mail($to, $subj, $body, $headersStr);

// Respond
if ($ok) {
  header('Location: /thanks.html'); // or echo a message
  exit;
} else {
  http_response_code(502);
  echo 'Failed to send email. Please try again later.';
}
```

---

## 5) SMTP (production-ready)

Use **PHPMailer** with SMTP for reliable delivery, DKIM, and TLS.

**Install**

```bash
composer require phpmailer/phpmailer vlucas/phpdotenv
```

**.env**

```
SMTP_HOST=smtp.yourprovider.com
SMTP_PORT=587
SMTP_USER=postmaster@example.com
SMTP_PASS=********
SMTP_FROM=noreply@example.com
SMTP_FROM_NAME="Registration Bot"
SMTP_TO=recipient@example.com
```

**PHP (snippet)**

```php
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); $dotenv->load();

$mail = new PHPMailer\PHPMailer\PHPMailer(true);
$mail->isSMTP();
$mail->Host = $_ENV['SMTP_HOST'];
$mail->SMTPAuth = true;
$mail->Username = $_ENV['SMTP_USER'];
$mail->Password = $_ENV['SMTP_PASS'];
$mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = (int)$_ENV['SMTP_PORT'];

$mail->setFrom($_ENV['SMTP_FROM'], $_ENV['SMTP_FROM_NAME']);
$mail->addAddress($_ENV['SMTP_TO']);
$mail->addReplyTo($email); // validated earlier
$mail->Subject = $subj;
$mail->Body    = $body;
$mail->send();
```

---

## 6) Testing checklist

* **Local run**: Serve `public/` via PHP’s server:

  ```bash
  php -S 127.0.0.1:8080 -t public
  ```
* **Happy path**: Submit with all required fields → redirected to `thanks.html`.
* **Validation**: Remove required field → 400 with error list.
* **Email delivery**: Check SMTP logs / inbox (watch spam).
* **cURL smoke test**:

  ```bash
  curl -X POST http://127.0.0.1:8080/process_registration.php \
    -d first_name=Jane -d last_name=Doe -d gender=Female \
    -d course="BSc CS" -d passing_year=2023 -d dob=2000-01-01 \
    -d email=jane@example.com -d phone=+8801712345678
  ```

---

## 7) Security essentials (do these)

* **Input validation + sanitization** (server-side is authoritative).
* **Email header injection**: never inject raw user strings into `From:`; use `Reply-To` and validate email with `FILTER_VALIDATE_EMAIL`.
* **CSRF**: include a CSRF token in the form and verify it in PHP if this is authenticated/privileged.
* **Rate limiting**: throttle by IP (e.g., store attempts + `X-RateLimit`), or use a reverse proxy/WAF.
* **Spam mitigation**: add a honeypot field, time-to-submit check, or reCAPTCHA.
* **HTTPS only**: force TLS for all endpoints.
* **Secrets management**: use `.env` (never hardcode creds); don’t commit `.env`.

---

## 8) Enhancements (nice to have)

* **Persist to DB** (MySQL/Postgres): store submissions and email them asynchronously via a queue/cron.
* **Thank-you page** with summary + reference ID.
* **Email templates** (HTML + text) with your branding.
* **User confirmation**: send a copy to the registrant; double-opt-in if required.
* **Admin dashboard**: view/export CSV of submissions.
* **i18n**: translate labels and error messages.

---

## 9) Troubleshooting

| Symptom                | Likely cause              | Fix                                                             |
| ---------------------- | ------------------------- | --------------------------------------------------------------- |
| Email not received     | `mail()` blocked / no MTA | Switch to SMTP (PHPMailer); verify SPF/DKIM/DMARC               |
| 500 error              | PHP fatal                 | Check server logs (`error_log`); enable `display_errors` in dev |
| Always goes to spam    | Missing sender auth       | Setup SPF/DKIM/DMARC with your domain                           |
| “Valid email required” | Bad email format          | Confirm client & server validation; no newlines allowed         |
| No PHP execution       | Server not configured     | Ensure PHP-FPM/Apache mod_php enabled; correct `DocumentRoot`   |

---

## 10) Deployment notes

* **Apache**: ensure `.php` is handled by PHP; set `DirectoryIndex index.php index.html`.
* **Nginx + PHP-FPM**: route `location ~ \.php$` to PHP-FPM.
* **Permissions**: web user should not write to code directories (except logs/uploads if used).

---

## 11) Compliance

If collecting PII:

* Provide a privacy notice.
* Store only what you need; set retention.
* Encrypt at rest if stored; always encrypt in transit (HTTPS).
* Follow local regulations (e.g., GDPR).

---

That’s it—drop this in your repo and you’ve got a solid developer story for the registration form + PHP backend.

---

## Author

**Developed by [Mohammad Aminul Islam (Amein)](https://github.com/aminbiography)**
*Web Developer | Cyber Threat Intelligence Associate*

---

## License

This project is licensed under the **MIT License**.
You are free to use, modify, and share it — attribution is appreciated.

---
