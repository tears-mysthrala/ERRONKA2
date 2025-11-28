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

function generate_captcha() {
    $num1 = rand(1, 9);
    $num2 = rand(1, 9);
    $_SESSION['captcha_num1'] = $num1;
    $_SESSION['captcha_num2'] = $num2;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $captcha  = trim($_POST['captcha'] ?? '');

    // Comprobamos que todos los campos estén rellenos
    if ($username === '' || $password === '' || $captcha === '') {
        $error = 'Rellena todos los campos.';
    } else {
        // Comprobamos que existe un captcha generado
        if (!isset($_SESSION['captcha_num1'], $_SESSION['captcha_num2'])) {
            $error = 'Captcha expirado. Vuelve a intentarlo.';
        } else {
            $expected = (int)$_SESSION['captcha_num1'] + (int)$_SESSION['captcha_num2'];

            // Validamos el captcha
            if (!ctype_digit($captcha) || (int)$captcha !== $expected) {
                $error = 'Captcha incorrecto.';
            } else {
                // Si el captcha es correcto, comprobamos usuario y contraseña
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password_hash'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    // Limpiamos el captcha al hacer login correcto
                    unset($_SESSION['captcha_num1'], $_SESSION['captcha_num2']);
                    header('Location: ../admin/dashboard.php');
                    exit;
                } else {
                    $error = 'Usuario o contraseña incorrectos.';
                }
            }
        }
    }
    // Generamos siempre un nuevo captcha para el siguiente intento
    generate_captcha();
} else {
    // Primera carga de la página: generamos captcha
    generate_captcha();
}

include '../templates/header.php';
?>
<h2>Login</h2>
<?php if ($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
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
    <label class="form-label">Captcha</label>
    <div class="input-group">
      <span class="input-group-text">
        <?= (int)($_SESSION['captcha_num1'] ?? 0) ?> + <?= (int)($_SESSION['captcha_num2'] ?? 0) ?> =
      </span>
      <input type="text" name="captcha" class="form-control" required>
    </div>
    <div class="form-text">Idatzi emaitza zenbakiz (adib. 7).</div>
  </div>
  <div class="col-12">
    <button class="btn btn-primary">Entrar</button>
  </div>
</form>
<?php include '../templates/footer.php'; ?>
