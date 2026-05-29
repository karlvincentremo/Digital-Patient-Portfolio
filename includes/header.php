<?php
session_start();

require_once __DIR__ . '/../config/database.php';

// Determine current page for active nav
$currentPage = basename($_SERVER['PHP_SELF']);
$currentDir  = basename(dirname($_SERVER['PHP_SELF']));

/**
 * FIXED NAV LOGIC
 */
function navActive($dir, $currentDir, $currentPage) {
    $currentDir = strtolower($currentDir);
    $dir = strtolower($dir);
    
    if ($dir === 'index' && ($currentDir === 'health_monitoring' || $currentDir === 'htdocs')) {
        return ($currentPage === 'index.php') ? 'active' : '';
    }
    if ($currentDir === $dir) return 'active';
    return '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle ?? 'Barangay Health Center'; ?></title>
  <link rel="stylesheet" href="/health_monitoring/css/style.css">
</head>
<body>

<nav class="navbar">
  <div class="nav-brand">
    <a href="/health_monitoring/index.php">
      Barangay Health Center
    </a>
  </div>

  <ul class="nav-links">
    <li>
        <a href="/health_monitoring/index.php" class="<?php echo ($currentPage == 'index.php' && $currentDir == 'health_monitoring') ? 'active' : ''; ?>">
            <span class="icon">📊</span> Dashboard
        </a>
    </li>
    <li>
        <a href="/health_monitoring/patients/index.php" class="<?php echo (strpos($_SERVER['REQUEST_URI'], '/patients/') !== false) ? 'active' : ''; ?>">
            <span class="icon">👥</span> Client
        </a>
    </li>

    <?php 
    /**
     * DIRECT URL CHECK
     */
    if (strpos($_SERVER['REQUEST_URI'], '/patients/') === false): 
    ?>
        <li>
            <a href="/health_monitoring/appointments/index.php" class="<?php echo ($currentDir == 'appointments') ? 'active' : ''; ?>">
                <span class="icon">📅</span> Appointments
            </a>
        </li>
        <li>
            <a href="/health_monitoring/health_records/index.php" class="<?php echo ($currentDir == 'health_records') ? 'active' : ''; ?>">
                <span class="icon">🩺</span> Health Records
            </a>
        </li> <?php endif; ?>
  </ul>

<div style="padding: 1rem 1.25rem; border-top: 1px solid rgba(255,255,255,.08); margin-top: auto;">
    <a href="/health_monitoring/logout.php" 
       style="display: flex; align-items: center; gap: .75rem; color: white !important; text-decoration: none; font-size: .85rem; font-weight: 500; opacity: 0.8; transition: opacity 0.2s;"
       onmouseover="this.style.opacity='1'; this.style.color='#f87171';"
       onmouseout="this.style.opacity='0.8'; this.style.color='white';">
        <span style="font-size: 1rem;">🚪</span>
        <span>Sign Out</span>
    </a>
</div>
</nav>

<main class="container">
  <?php
  if (function_exists('getAlert')) {
      $alert = getAlert();
      if ($alert): ?>
        <div class="alert alert-<?php echo $alert['type']; ?>">
          <?php echo $alert['type'] === 'success' ? '✅' : '⚠️'; ?>
          <?php echo $alert['message']; ?>
        </div>
      <?php endif; 
  } ?>