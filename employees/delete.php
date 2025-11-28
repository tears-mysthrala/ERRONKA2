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
$stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
$stmt->execute([$id]);
header('Location: list.php');
exit;
