<?php
// Basic sanitization helper
function clean($value) {
  return htmlspecialchars(trim($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// Collect fields
$fullName = clean($_POST['fullName'] ?? '');
$email    = clean($_POST['email'] ?? '');
$phone    = clean($_POST['phone'] ?? '');
$dob      = clean($_POST['dob'] ?? '');
$gender   = clean($_POST['gender'] ?? '');
$program  = clean($_POST['program'] ?? '');
$address  = clean($_POST['address'] ?? '');
$statement = clean($_POST['statement'] ?? '');
$skills    = $_POST['skills'] ?? []; // array

// Simple server-side validation (must match client rules)
$errors = [];
if (strlen($fullName) < 3) $errors[] = "Full name must be at least 3 characters.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address.";
if (!preg_match('/^\d{10}$/', $phone)) $errors[] = "Phone must be a 10-digit number.";
if ($dob === '') $errors[] = "Date of birth is required.";
if ($gender === '') $errors[] = "Gender is required.";
if ($program === '') $errors[] = "Program is required.";
if (strlen($address) < 10) $errors[] = "Address must be more detailed.";
if (strlen($statement) < 10) $errors[] = "Personal statement is too short.";

// If errors, show them; otherwise show a formatted summary
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Application Summary</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .summary-card { margin-top: 12px; padding: 18px; border: 1px solid var(--border); border-radius: 12px; background: #0b1324; }
    .summary-grid { display: grid; grid-template-columns: 180px 1fr; gap: 10px 16px; }
    .badge { display: inline-block; padding: 6px 10px; border-radius: 999px; background: rgba(34, 197, 94, 0.18); color: var(--text); border: 1px solid var(--accent); margin-right: 8px; margin-bottom: 6px; }
    .error-list { color: var(--error); margin: 12px 0; }
    .back-link { display:inline-block; margin-top: 16px; color: var(--accent); text-decoration: none; font-weight: 700; }
    .back-link:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="container">
    <h1><?php echo empty($errors) ? "Application submitted successfully" : "Please fix the following issues"; ?></h1>
    <p class="subtitle">
      <?php if (empty($errors)) { ?>
        Your application details are shown below with formatting.
      <?php } else { ?>
        Submission failed due to validation errors.
      <?php } ?>
    </p>

    <?php if (!empty($errors)) { ?>
      <div class="summary-card">
        <ul class="error-list">
          <?php foreach ($errors as $err) { echo "<li>" . htmlspecialchars($err, ENT_QUOTES, 'UTF-8') . "</li>"; } ?>
        </ul>
        <a class="back-link" href="index.html">← Go back to the form</a>
      </div>
    <?php } else { ?>
      <div class="summary-card">
        <div class="summary-grid">
          <strong>Full name</strong><div><?php echo $fullName; ?></div>
          <strong>Email</strong><div><?php echo $email; ?></div>
          <strong>Phone</strong><div><?php echo $phone; ?></div>
          <strong>Date of birth</strong><div><?php echo $dob; ?></div>
          <strong>Gender</strong><div><?php echo $gender; ?></div>
          <strong>Program</strong><div><?php echo $program; ?></div>
          <strong>Address</strong><div style="white-space: pre-wrap;"><?php echo $address; ?></div>
          <strong>Personal statement</strong><div style="white-space: pre-wrap;"><?php echo $statement; ?></div>
          <strong>Skills</strong>
          <div>
            <?php
              if (is_array($skills) && count($skills) > 0) {
                foreach ($skills as $s) {
                  echo '<span class="badge">' . htmlspecialchars($s, ENT_QUOTES, 'UTF-8') . '</span>';
                }
              } else {
                echo '<span class="badge" style="opacity:0.7">No skills selected</span>';
              }
            ?>
          </div>
        </div>
      </div>
      <a class="back-link" href="index.html">← Submit another response</a>
    <?php } ?>
  </div>
</body>
</html>