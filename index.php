<?php
session_start();
require_once __DIR__ . '/config/database.php';

// Rediriger si déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: /admin/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("SELECT id, username, password, nom FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && $password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['login_time'] = time();

                header('Location: /admin/dashboard.php');
                exit;
            } else {
                $error = 'Nom d\'utilisateur ou mot de passe incorrect';
            }
        } catch (PDOException $e) {
            $error = 'Erreur de connexion à la base de données';
        }
    } else {
        $error = 'Veuillez remplir tous les champs';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion Location Voitures</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="logo-container">
                <img src="/assets/images/car1_anime.gif" alt="Logo" class="logo-animated">
            </div>
            <h1>Location Voitures</h1>
            <h2>Connexion Administrateur</h2>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" value="admin" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" value="admin123" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
            </form>

            <div class="login-info">
                <p><small>Identifiants par défaut : <strong>admin</strong> / <strong>admin123</strong></small></p>
            </div>
        </div>
    </div>
</body>

</html>