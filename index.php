<?php
session_start();
require 'secure_db.php';
include 'templates/header.php';
?>
<header class="pt-2 pb-2 bg-light">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h1 class="display-4" style="margin-top:0; margin-bottom:0.25rem;">Ongi etorri Guenaga Farmazeutikara</h1>
        <p class="lead" style="margin-bottom:0.5rem;">Osasuna zaintzen dugu kalitateko produktuekin eta profesionalen aholkuarekin.</p>
        <ul class="list-unstyled">
          <li>• Botika eta parafarmazia produktuak</li>
          <li>• Aholkularitza profesionala eta zerbitzu pertsonalizatuak</li>
          <li>• Entrega azkarra eta kalitate-kontrol zorrotza</li>
        </ul>
        <p class="mt-3">
          <?php if (empty($_SESSION['user_id'])): ?>
            <a class="btn btn-primary btn-lg me-2" href="auth/login.php">Sartu</a>
            <a class="btn btn-outline-secondary btn-lg" href="auth/register.php">Erregistratu</a>
          <?php else: ?>
            <a class="btn btn-primary btn-lg" href="admin/dashboard.php">Joan administrazio panelera</a>
          <?php endif; ?>
        </p>
      </div>
      <div class="col-lg-6 text-center">
        <img src="/WEB/ERRONKA2/img/logofarm.png" alt="Guenaga Farmazeutika" style="max-width:60%; height:auto;">
      </div>
    </div>
  </div>
</header>

<section class="pt-2 pb-4">
  <div class="container">
    <h2 class="mb-4">Gure zerbitzuak</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">Farmazia aholkuak</h5>
            <p class="card-text">Medikazioari, elkarreraginari eta zaintzari buruzko aholkularitza pertsonalizatua.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">Formulazio eta kalitate-kontrola</h5>
            <p class="card-text">Produktuek kalitate estandarrak betetzen dituztela ziurtatzen dugu.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">Bidalketak eta logistika</h5>
            <p class="card-text">Azkar entregatzen dugu eta eskaeren jarraipena eskaintzen dugu.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>




<?php include 'templates/footer.php'; ?>