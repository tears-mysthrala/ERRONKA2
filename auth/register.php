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
session_start();
require '../secure_db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($username === '' || $password === '' || $password2 === '') {
        $error = 'Bete guztiak.';
    } elseif ($password !== $password2) {
        $error = 'Pasahitzak ez datoz bat.';
    } else {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users(username, password_hash, role) VALUES(?,?,?)");
            $stmt->execute([$username, $hash, 'user']);
            $success = 'Erabiltzailea ondo sortu da. Orain saioa hasi dezakezu.';
        } catch (PDOException $e) {
            $error = 'Ezin izan da erabiltzailea sortu (agian dagoeneko existitzen da).';
        }
    }
}

include '../templates/header.php';
?>
<h2>Registro</h2>
<?php if ($error): ?>
<div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
<?php endif; ?>
<?php if ($success): ?>
<div class="alert alert-success"><?=htmlspecialchars($success)?></div>
<?php endif; ?>
<form method="post" class="row g-3 col-md-6">
  <div class="col-12">
    <label class="form-label">Erabiltzailea</label>
    <input type="text" name="username" class="form-control" required>
  </div>
  <div class="col-12">
    <label class="form-label">Pasahitza</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <div class="col-12">
    <label class="form-label">Errepikatu pasahitza</label>
    <input type="password" name="password2" class="form-control" required>
  </div>
  <div class="col-12">
    <button class="btn btn-primary">Erregistratu</button>
  </div>
</form>
<?php include '../templates/footer.php'; ?>
