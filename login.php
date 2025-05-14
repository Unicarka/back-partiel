<?php
// login.php
session_start();
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Connexion à Azure SQL (via Key Vault si configuré)
$connString = getenv('DB_CONN'); // Référence Key Vault (à configurer dans App Service)

try {
    $pdo = new PDO($connString);
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['username'] = $username;
        echo "Connexion réussie !";
    } else {
        echo "Identifiants invalides.";
    }
} catch (Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
if ($row && password_verify($password, $row['password'])) {
    $_SESSION['username'] = $username;
    header("Location: /success.html");
    exit;
} else {
    echo "Identifiants invalides.";
}
?>
