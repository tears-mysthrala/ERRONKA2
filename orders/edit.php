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
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();
if (!$order) {
    header('Location: list.php');
    exit;
}

$clients = $pdo->query("SELECT id, name FROM clients ORDER BY name")->fetchAll();
$products = $pdo->query("SELECT id, name FROM products ORDER BY name")->fetchAll();
$employees = $pdo->query("SELECT id, name FROM employees ORDER BY name")->fetchAll();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = (int)($_POST['client_id'] ?? 0);
    $product_id = (int)($_POST['product_id'] ?? 0);
    $employee_id = $_POST['employee_id'] !== '' ? (int)$_POST['employee_id'] : null;
    $quantity = (int)($_POST['quantity'] ?? 1);
    $order_date = trim($_POST['order_date'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if ($client_id === 0 || $product_id === 0 || $order_date === '') {
        $error = 'Bezeroa, produktu eta data derrigorrezkoak dira.';
    } else {
        $stmt = $pdo->prepare("UPDATE orders SET client_id=?, product_id=?, employee_id=?, quantity=?, order_date=?, status=?, notes=? WHERE id=?");
        $stmt->execute([$client_id, $product_id, $employee_id, $quantity, $order_date, $status, $notes, $id]);
        header('Location: list.php');
        exit;
    }
}
include '../templates/header.php';
?>
<h2>Eskaera editatu</h2>
<?php if ($error): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
<form method="post" class="row g-3 col-md-8">
  <div class="col-md-4">
    <label class="form-label">Bezeroa</label>
    <select name="client_id" class="form-select" required>
      <option value="">Hautatu...</option>
      <?php foreach ($clients as $c): ?>
        <option value="<?=$c['id']?>" <?=$c['id']==$order['client_id']?'selected':''?>><?=htmlspecialchars($c['name'])?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Produktua</label>
    <select name="product_id" class="form-select" required>
      <option value="">Hautatu...</option>
      <?php foreach ($products as $p): ?>
        <option value="<?=$p['id']?>" <?=$p['id']==$order['product_id']?'selected':''?>><?=htmlspecialchars($p['name'])?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Langilea (aukerakoa)</label>
    <select name="employee_id" class="form-select">
      <option value="">(Inor ez)</option>
      <?php foreach ($employees as $e): ?>
        <option value="<?=$e['id']?>" <?=$order['employee_id']==$e['id']?'selected':''?>><?=htmlspecialchars($e['name'])?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Kopurua</label>
    <input type="number" name="quantity" class="form-control" value="<?=htmlspecialchars($order['quantity'])?>" min="1">
  </div>
  <div class="col-md-3">
    <label class="form-label">Data</label>
    <input type="date" name="order_date" class="form-control" value="<?=htmlspecialchars($order['order_date'])?>" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Egoera</label>
    <input type="text" name="status" class="form-control" value="<?=htmlspecialchars($order['status'])?>">
  </div>
  <div class="col-12">
    <label class="form-label">Oharra</label>
    <textarea name="notes" class="form-control" rows="3"><?=htmlspecialchars($order['notes'])?></textarea>
  </div>
  <div class="col-12">
    <button class="btn btn-primary">Aldaketak gorde</button>
    <a href="list.php" class="btn btn-secondary">Utzi</a>
  </div>
</form>
<?php include '../templates/footer.php'; ?>
