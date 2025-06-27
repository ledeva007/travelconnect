<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$admins = [
    'arezki' => 'Resto1997',
    'lyes' => 'lyes1234',
    'salem' => 'salem1234',
    'massi' => 'massi1234',
];

$servername = 'localhost';
$db_username = 'root';
$db_password = '';
$dbname = 'hotel_reservations';

if (!isset($_SESSION['admin'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        if (isset($admins[$user]) && $admins[$user] === $pass) {
            $_SESSION['admin'] = $user;
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }

    if (!isset($_SESSION['admin'])) {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Connexion Admin</title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: #f5f6fa;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                }
                .login-box {
                    background: #fff;
                    padding: 40px;
                    border-radius: 10px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    width: 300px;
                }
                h2 {
                    text-align: center;
                    margin-bottom: 20px;
                }
                input[type="text"], input[type="password"] {
                    width: 100%;
                    padding: 10px;
                    margin: 10px 0;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                }
                input[type="submit"] {
                    width: 100%;
                    padding: 10px;
                    background-color: #2c3e50;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                input[type="submit"]:hover {
                    background-color: #34495e;
                }
                .error {
                    color: red;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class="login-box">
                <h2>Connexion Admin</h2>
                <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
                <form method="post" action="admin.php">
                    <input type="text" name="username" placeholder="Nom d'utilisateur" required>
                    <input type="password" name="password" placeholder="Mot de passe" required>
                    <input type="submit" value="Se connecter">
                </form>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query("SELECT * FROM reservations ORDER BY date_reservation DESC");
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Réservations</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            padding: 30px;
        }
        h2, h3 {
            color: #2c3e50;
        }
        .logout {
            float: right;
            text-decoration: none;
            color: #e74c3c;
            font-weight: bold;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h2>Bienvenue <?= htmlspecialchars($_SESSION['admin']) ?> !</h2>
    <a href="logout.php" class="logout">Déconnexion</a>
    <h3>Liste des Réservations</h3>
    <table>
        <tr>
            <th>ID</th><th>Nom Et Prenom</th><th>Email</th><th>Telephone</th><th>Arrivée</th>
            <th>Départ</th><th>Message</th><th>Date réservation</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nom']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= $row['telephone'] ?></td>
            <td><?= $row['date_arrivee'] ?></td>
            <td><?= $row['date_depart'] ?></td>
            <td><?= htmlspecialchars($row['message']) ?></td>
            <td><?= $row['date_reservation'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
