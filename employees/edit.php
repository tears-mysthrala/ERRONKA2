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

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$id]);
$emp = $stmt->fetch();
if (!$emp) {
    header('Location: list.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = trim($_POST['role'] ?? '');

    if ($name === '') {
        $error = 'Izena derrigorrezkoa da.';
    } else {
        $stmt = $pdo->prepare("UPDATE employees SET name=?, email=?, phone=?, role=? WHERE id=?");
        $stmt->execute([$name, $email, $phone, $role, $id]);
        header('Location: list.php');
        exit;
    }
}
include '../templates/header.php';
?>
<h2>Langilea editatu</h2>
<?php if ($error): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
<form method="post" class="row g-3 col-md-6">
  <div class="col-12">
    <label class="form-label">Izena</label>
    <input type="text" name="name" class="form-control" value="<?=htmlspecialchars($emp['name'])?>" required>
  </div>
  <div class="col-12">
    <label class="form-label">Emaila</label>
    <input type="email" name="email" class="form-control" value="<?=htmlspecialchars($emp['email'])?>">
  </div>
  <div class="col-12">
    <label class="form-label">Telefonoa</label>
    <input type="text" name="phone" class="form-control" value="<?=htmlspecialchars($emp['phone'])?>">
  </div>
  <div class="col-12">
    <label class="form-label">Rola</label>
    <input type="text" name="role" class="form-control" value="<?=htmlspecialchars($emp['role'])?>">
  </div>
  <div class="col-12">
    <button class="btn btn-primary">Gorde aldaketak</button>
    <a href="list.php" class="btn btn-secondary">Utzi</a>
  </div>
</form>
<?php include '../templates/footer.php'; ?>
