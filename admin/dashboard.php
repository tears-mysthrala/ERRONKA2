<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

function val_str($v){ if(!$v) return false;
    return preg_match("/^[a-zA-Z0-9\s\-]{2,50}$/",$v); }

function val_user($v){ return preg_match("/^[a-zA-Z0-9]{4,20}$/",$v); }
function val_pass($v){ return preg_match("/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,32}$/",$v); }

$clean=[];
foreach($_POST as $k=>$v){
    $clean[$k]=filter_var($v,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

$errores=[];
foreach($clean as $k=>$v){
    if(strpos($k,"email")!==false && !filter_var($v,FILTER_VALIDATE_EMAIL)) $errores[]="Email inválido";
    if(strpos($k,"precio")!==false && !filter_var($v,FILTER_VALIDATE_FLOAT)) $errores[]="Precio inválido";
    if(strpos($k,"cantidad")!==false && !filter_var($v,FILTER_VALIDATE_INT)) $errores[]="Cantidad inválida";
    if(in_array($k,["nombre","name","client","product"]) && !val_str($v)) $errores[]="$k inválido";
}

if(!empty($errores)){
    foreach($errores as $e){ echo "<div class='error'>$e</div>"; }
    exit;
}

$_POST = $clean;
}
?>
<?php
require '../auth/require_admin.php';
require '../secure_db.php';
include '../templates/header.php';

$clients_count = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$employees_count = $pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();
$products_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$orders_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
?>
<h2>Panel de administración</h2>
<div class="row">
  <div class="col-md-3">
    <div class="card text-bg-light mb-3">
      <div class="card-body">
        <h5 class="card-title">Bezeroak</h5>
        <p class="card-text display-6"><?=$clients_count?></p>
        <a href="../clients/list.php" class="btn btn-sm btn-primary">Bezeroak ikusi</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-bg-light mb-3">
      <div class="card-body">
        <h5 class="card-title">Langileak</h5>
        <p class="card-text display-6"><?=$employees_count?></p>
        <a href="../employees/list.php" class="btn btn-sm btn-primary">Langileak ikusi</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-bg-light mb-3">
      <div class="card-body">
        <h5 class="card-title">Produktuak</h5>
        <p class="card-text display-6"><?=$products_count?></p>
        <a href="../products/list.php" class="btn btn-sm btn-primary">Produktuak ikusi</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-bg-light mb-3">
      <div class="card-body">
        <h5 class="card-title">Aginduak</h5>
        <p class="card-text display-6"><?=$orders_count?></p>
        <a href="../orders/list.php" class="btn btn-sm btn-primary">Aginduak ikusi</a>
      </div>
    </div>
  </div>
</div>
<?php include '../templates/footer.php'; ?>
