<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Guenaga Farmazeutika</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS propio -->
    <link href="/WEB/ERRONKA2/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-0">
    <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center p-0" href="/WEB/ERRONKA2/index.php" style="padding:0; margin:0;">
      <img src="/WEB/ERRONKA2/img/logofarm.png" alt="Guenaga Farmazeutika" style="height:64px; object-fit:contain; display:block;">
      <span class="ms-2" style="font-size:1.25rem; line-height:1; margin-left:8px;">Guenaga Farmazeutika</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if (!empty($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="/WEB/ERRONKA2/admin/dashboard.php">Panela</a></li>
          <li class="nav-item"><a class="nav-link" href="/WEB/ERRONKA2/clients/list.php">Bezeroak</a></li>
          <li class="nav-item"><a class="nav-link" href="/WEB/ERRONKA2/employees/list.php">Langileak</a></li>
          <li class="nav-item"><a class="nav-link" href="/WEB/ERRONKA2/products/list.php">Produktuak</a></li>
          <li class="nav-item"><a class="nav-link" href="/WEB/ERRONKA2/orders/list.php">Aginduak</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (!empty($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <span class="navbar-text me-2">Kaixo, <?=htmlspecialchars($_SESSION['username'])?></span>
          </li>
          <li class="nav-item"><a class="nav-link" href="/WEB/ERRONKA2/auth/logout.php">Irten</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/WEB/ERRONKA2/auth/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="/WEB/ERRONKA2/auth/register.php">Erregistroa</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
