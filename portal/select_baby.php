<?php
session_start();
require_once('../config/database.php'); 

if (!isset($_SESSION['staff_id'])) { 
    header('Location: ../login.php'); 
    exit; 
}

$conn = new mysqli('localhost', 'root', '', 'barangay_health_db');
$parent_id = $_SESSION['staff_id'];

$query = "SELECT id, first_name, last_name, gender FROM patients WHERE parent_id = '$parent_id'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Profile — BrgyHealth</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'DM Sans', sans-serif; 
            background: #fdfcf0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
            position: relative;
            overflow: hidden; /* Prevents scrollbars from particles */
        }

        /* The Baby Pattern Container */
        #baby-particle-container {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            opacity: 0.8; /* High visibility as requested */
            font-size: 1.5rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));
            animation: gentle-float 8s infinite ease-in-out;
        }

        @keyframes gentle-float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(10px, -15px) rotate(5deg); }
            66% { transform: translate(-10px, 10px) rotate(-5deg); }
        }

        /* Main Content UI */
        .container { position: relative; z-index: 10; width: 100%; max-width: 600px; text-align: center; padding: 20px; }
        h1 { font-family: 'Outfit'; color: #0f2744; margin-bottom: 30px; font-size: 2.2rem; }
        
        .baby-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .baby-card { 
            background: white; padding: 30px; border-radius: 28px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.08); 
            text-decoration: none; transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            border: 2px solid transparent; display: flex; flex-direction: column; 
            align-items: center; justify-content: center; 
        }
        .baby-card:hover { border-color: #fbbf24; transform: scale(1.05); z-index: 20; }
        .baby-icon { font-size: 3.5rem; margin-bottom: 15px; }
        .baby-name { font-weight: 700; color: #1e293b; font-size: 1.2rem; }
        
        .exit-container { position: fixed; bottom: 30px; left: 30px; z-index: 100; }
        .exit-btn {
            display: flex; align-items: center; gap: 10px; background: white; padding: 12px 20px; border-radius: 12px;
            text-decoration: none; color: #475569 !important; font-weight: 600; font-size: 0.9rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

<div id="baby-particle-container"></div>

<div class="exit-container">
    <a href="/health_monitoring/logout.php" class="exit-btn">
        <span>🚪</span> Logout
    </a>
</div>

<div class="container">
    <h1>Who are you checking today?</h1>
    
    <div class="baby-grid">
        <?php while($patient = $result->fetch_assoc()): ?>
            <a href="index.php?baby_id=<?= $patient['id'] ?>" class="baby-card">
                <span class="baby-icon"><?= ($patient['gender'] == 'Male') ? '👶' : '👧' ?></span>
                <span class="baby-name"><?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></span>
            </a>
        <?php endwhile; ?>
        
        <a href="add_baby.php" class="baby-card" style="border: 2px dashed #cbd5e1; background: #f8fafc;">
            <span class="baby-icon">➕</span>
            <span class="baby-name" style="color: #64748b;">Link New Baby</span>
        </a>
    </div>
</div>

<script>
    // Config: Add more icons here if you want
    const icons = ['🍼', '✨', '🧸', '🎈', '☁️', '🍭', '🎨'];
    const container = document.getElementById('baby-particle-container');
    const particleCount = 40; // Increase this number for more particles

    for (let i = 0; i < particleCount; i++) {
        const span = document.createElement('span');
        span.className = 'particle';
        span.innerText = icons[Math.floor(Math.random() * icons.length)];
        
        // Random Position
        span.style.top = Math.random() * 100 + 'vh';
        span.style.left = Math.random() * 100 + 'vw';
        
        // Random size and animation speed
        const size = (Math.random() * 1 + 1) + 'rem';
        span.style.fontSize = size;
        span.style.animationDuration = (Math.random() * 5 + 5) + 's';
        span.style.animationDelay = (Math.random() * 5) + 's';
        
        container.appendChild(span);
    }
</script>

</body>
</html>