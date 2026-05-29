<?php
session_start();

if (isset($_SESSION['staff_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'barangay_health_db');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        $username   = $conn->real_escape_string(trim($_POST['username']));
        $fullname   = $conn->real_escape_string(trim($_POST['fullname']));
        $position   = $conn->real_escape_string(trim($_POST['position']));
        $contact    = $conn->real_escape_string(trim($_POST['contactnumber']));
        $password   = $_POST['password'];
        $confirm    = $_POST['confirm_password'];
        $role       = $_POST['role'];
        $staff_code = $_POST['staff_code'] ?? '';

        // --- SECURITY CHECK FOR STAFF ---
        $SECRET_KEY = "BRGY2026"; 

        // --- NEW: FORCE CLIENT POSITION TO PATIENT ---
        if ($role === 'client') {
            $position = 'Patient';
        }

        if (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters long.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match. Please try again.';
        } elseif ($role === 'staff' && $staff_code !== $SECRET_KEY) {
            $error = 'Invalid Staff Verification Code. Access denied.';
        } else {
            $check = $conn->query("SELECT staffid FROM staff WHERE username = '$username'");
            if ($check && $check->num_rows > 0) {
                $error = 'Username already exists. Please choose another.';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = "INSERT INTO staff (username, password, fullname, position, contactnumber, role) 
                         VALUES ('$username','$hashed','$fullname','$position','$contact', '$role')";
                
                if ($conn->query($stmt)) {
                    $success = 'Account created! You can now log in.';
                } else {
                    $error = 'Registration failed: ' . $conn->error;
                }
            }
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — Barangay Health Center</title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --teal:#0d9488;--teal-d:#0f766e;--teal-lt:#ccfbf1;
  --navy:#0f2744;--navy-m:#1e3a5f;
  --bg:#f0fafa;--white:#fff;
  --border:#d1faf4;--muted:#64748b;
  --red:#ef4444;--green:#22c55e;
  --shadow-lg:0 12px 40px rgba(13,148,136,.18);
  --r:14px;--r-s:8px;
}
body{font-family:'DM Sans',sans-serif;background:var(--bg);min-height:100vh;display:flex;align-items:stretch;}

.auth-side{
  width:42%;background:linear-gradient(150deg,var(--navy) 0%,var(--teal-d) 100%);
  display:flex;flex-direction:column;justify-content:center;padding:4rem 3.5rem;
  position:relative;overflow:hidden;
}
.side-logo{width:56px;height:56px;background:rgba(255,255,255,.12);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:2rem;border:1.5px solid rgba(255,255,255,.2);}
.auth-side h1{font-family:'Outfit',sans-serif;font-size:1.9rem;font-weight:800;color:#fff;margin-bottom:1rem;}
.auth-side p{color:rgba(255,255,255,.65);font-size:.9rem;line-height:1.7;max-width:300px;}

.auth-main{flex:1;display:flex;align-items:center;justify-content:center;padding:2.5rem 2rem;overflow-y:auto;}
.auth-box{width:100%;max-width:460px;}
.auth-box h2{font-family:'Outfit',sans-serif;font-size:1.6rem;font-weight:800;color:var(--navy);margin-bottom:.4rem;}
.auth-box .sub{color:var(--muted);font-size:.88rem;margin-bottom:1.75rem;}
.auth-card{background:var(--white);border-radius:var(--r);box-shadow:var(--shadow-lg);padding:2rem;border:1px solid var(--border);}

.alert-err{padding:.9rem;border-radius:var(--r-s);margin-bottom:1.25rem;font-size:.88rem;background:#fff1f2;color:#be123c;border-left:4px solid #f43f5e;}
.alert-ok{padding:.9rem;border-radius:var(--r-s);margin-bottom:1.25rem;font-size:.88rem;background:#f0fdf4;color:#15803d;border-left:4px solid #22c55e;}

label{display:block;margin-bottom:.4rem;font-size:.84rem;font-weight:600;color:var(--navy);}
.star{color:var(--red);}
.input-wrap{position:relative;}
.input-wrap .ico{position:absolute;left:.9rem;top:50%;transform:translateY(-50%);font-size:.9rem;}
input,select{width:100%;padding:.7rem 1rem .7rem 2.55rem;border:1.5px solid var(--border);border-radius:var(--r-s);font-size:.9rem;color:var(--navy);background:#fafffe;}
select{padding-left:1rem; cursor:pointer;}
.fg{margin-bottom:1rem;}
.row-2{display:grid;grid-template-columns:1fr 1fr;gap:.85rem;}
.btn{width:100%;padding:.78rem;border:none;border-radius:var(--r-s);font-weight:700;background:var(--teal);color:#fff;cursor:pointer;margin-top:.5rem;}
.btn:hover{background:var(--teal-d);}
.auth-link{text-align:center;margin-top:1.5rem;font-size:.88rem;color:var(--muted);}
.auth-link a{color:var(--teal);font-weight:700;text-decoration:none;}
</style>
</head>
<body>

<div class="auth-side">
  <div class="side-logo">🏥</div>
  <h1>Welcome to the Center</h1>
  <p>Create an account to access health services or manage patient records efficiently.</p>
</div>

<div class="auth-main">
  <div class="auth-box">
    <h2>Create Account ✨</h2>
    <p class="sub">Please select your role and fill in your details</p>

    <?php if ($error): ?>
      <div class="alert-err">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="alert-ok">✅ <?= htmlspecialchars($success) ?> <a href="login.php">Login now →</a></div>
    <?php endif; ?>

    <div class="auth-card">
      <form method="POST">
        
        <div class="fg">
          <label>Register as: <span class="star">*</span></label>
          <select name="role" id="roleSelect" onchange="toggleStaffCode()" required>
            <option value="client">Client (Patient)</option>
            <option value="staff">Staff Member</option>
          </select>
        </div>

        <div class="fg" id="staffCodeGroup" style="display: none;">
          <label style="color: var(--teal);">Staff Verification Code <span class="star">*</span></label>
          <div class="input-wrap">
            <span class="ico">🛡️</span>
            <input type="text" name="staff_code" placeholder="Enter secret code">
          </div>
        </div>

        <div class="fg">
          <label>Full Name <span class="star">*</span></label>
          <div class="input-wrap">
            <span class="ico">👤</span>
            <input type="text" name="fullname" placeholder="e.g. Maria Santos" required>
          </div>
        </div>

        <div class="row-2">
          <div class="fg">
            <label>Username <span class="star">*</span></label>
            <div class="input-wrap">
              <span class="ico">@</span>
              <input type="text" name="username" placeholder="Username" required style="padding-left:2.4rem;">
            </div>
          </div>
          <div class="fg" id="positionGroup" style="display: none;">
            <label>Position</label>
            <select name="position">
              <option value="Doctor">Doctor</option>
              <option value="Nurse">Nurse</option>
              <option value="BHW">BHW</option>
            </select>
          </div>
        </div>

        <div class="fg">
          <label>Contact Number</label>
          <div class="input-wrap">
            <span class="ico">📞</span>
            <input type="text" name="contactnumber" placeholder="09xx-xxx-xxxx">
          </div>
        </div>

        <div class="row-2">
          <div class="fg">
            <label>Password <span class="star">*</span></label>
            <div class="input-wrap">
              <span class="ico">🔒</span>
              <input type="password" name="password" required>
            </div>
          </div>
          <div class="fg">
            <label>Confirm Password <span class="star">*</span></label>
            <div class="input-wrap">
              <span class="ico">🔑</span>
              <input type="password" name="confirm_password" required>
            </div>
          </div>
        </div>

        <button type="submit" class="btn">Create Account →</button>
      </form>
    </div>

    <div class="auth-link">Already have an account? <a href="login.php">Sign in</a></div>
  </div>
</div>

<script>
function toggleStaffCode() {
    var role = document.getElementById("roleSelect").value;
    var codeGroup = document.getElementById("staffCodeGroup");
    var posGroup = document.getElementById("positionGroup");
    
    if (role === "staff") {
        codeGroup.style.display = "block";
        posGroup.style.display = "block";
    } else {
        codeGroup.style.display = "none";
        posGroup.style.display = "none";
    }
}
</script>

</body>
</html> 