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

$q = trim($_GET['q'] ?? '');
$sql = "SELECT o.*, c.name AS client_name, p.name AS product_name
        FROM orders o
        JOIN clients c ON o.client_id = c.id
        JOIN products p ON o.product_id = p.id";
$params = [];
if ($q !== '') {
    $sql .= " WHERE c.name LIKE ? OR p.name LIKE ? OR o.status LIKE ?";
    $like = "%".$q."%";
    $params = [$like, $like, $like];
}
$sql .= " ORDER BY o.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

include '../templates/header.php';
?>
<h2>Eskaerak</h2>
<form class="row g-2 mb-3" method="get">
  <div class="col-md-4">
    <input type="text" name="q" value="<?=htmlspecialchars($q)?>" class="form-control" placeholder="Buscar por cliente, producto o estado">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary">Bilatu</button>
  </div>
  <div class="col-md-3">
    <a href="new.php" class="btn btn-primary">Eskaera berria</a>
  </div>
</form>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Bezeroa</th><th>Produktua</th><th>Kopurua</th><th>Data</th><th>Egoera</th><th class="text-end">Ekintzak</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><?=htmlspecialchars($r['client_name'])?></td>
      <td><?=htmlspecialchars($r['product_name'])?></td>
      <td><?=htmlspecialchars($r['quantity'])?></td>
      <td><?=htmlspecialchars($r['order_date'])?></td>
      <td><?=htmlspecialchars($r['status'])?></td>
      <td class="text-end table-actions">
        <a class="btn btn-sm btn-secondary" href="edit.php?id=<?=$r['id']?>">Editatu</a>
        <a class="btn btn-sm btn-danger" href="delete.php?id=<?=$r['id']?>" onclick="return confirm('Ziur zaude eskaera hau ezabatu nahi duzula?');">Ezabatu</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php include '../templates/footer.php'; ?>
