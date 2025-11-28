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
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE name LIKE ? OR email LIKE ? ORDER BY id DESC");
    $like = "%".$q."%";
    $stmt->execute([$like, $like]);
    $rows = $stmt->fetchAll();
} else {
    $rows = $pdo->query("SELECT * FROM clients ORDER BY id DESC")->fetchAll();
}
include '../templates/header.php';
?>
<h2>Bezeroak</h2>
<form class="row g-2 mb-3" method="get">
  <div class="col-md-4">
    <input type="text" name="q" value="<?=htmlspecialchars($q)?>" class="form-control" placeholder="Izena edo emaila bilatu">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary">Bilatu</button>
  </div>
  <div class="col-md-3">
    <a href="new.php" class="btn btn-primary">Bezero berria</a>
  </div>
</form>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Izena</th><th>Emaila</th><th>Telefonoa</th><th>Oharra</th><th class="text-end">Ekintzak</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><?=htmlspecialchars($r['name'])?></td>
      <td><?=htmlspecialchars($r['email'])?></td>
      <td><?=htmlspecialchars($r['phone'])?></td>
      <td><?=nl2br(htmlspecialchars($r['notes']))?></td>
      <td class="text-end table-actions">
        <a class="btn btn-sm btn-secondary" href="edit.php?id=<?=$r['id']?>">Editatu</a>
        <a class="btn btn-sm btn-danger" href="delete.php?id=<?=$r['id']?>" onclick="return confirm('¿Seguro que quieres borrar este cliente?');">Borrar</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php include '../templates/footer.php'; ?>
