<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hotel_reservations";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Nettoyage des données
        $nom = htmlspecialchars($_POST['nom']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $telephone = preg_replace('/[^0-9]/', '', $_POST['telephone']);
        $date_arrivee = $_POST['date_arrivee'];
        $date_depart = $_POST['date_depart'];
        $type_chambre = htmlspecialchars($_POST['type_chambre']);
        $nombre_personnes = (int)$_POST['nombre_personnes'];
        $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : null;

        // Validation des données
        if (empty($nom) || empty($email) || empty($telephone)) {
            throw new Exception("Tous les champs obligatoires doivent être remplis");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'adresse email n'est pas valide");
        }

        if (strlen($telephone) < 10) {
            throw new Exception("Le numéro de téléphone doit contenir au moins 10 chiffres");
        }

        if (strtotime($date_depart) <= strtotime($date_arrivee)) {
            throw new Exception("La date de départ doit être postérieure à la date d'arrivée");
        }

        // Préparation de la requête
        $stmt = $conn->prepare("INSERT INTO reservations 
                              (nom, email, telephone, date_arrivee, date_depart, type_chambre, nombre_personnes, message) 
                              VALUES 
                              (:nom, :email, :telephone, :date_arrivee, :date_depart, :type_chambre, :nombre_personnes, :message)");
        
        // Exécution de la requête
        $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':date_arrivee' => $date_arrivee,
            ':date_depart' => $date_depart,
            ':type_chambre' => $type_chambre,
            ':nombre_personnes' => $nombre_personnes,
            ':message' => $message
        ]);

        // Redirection seulement si tout s'est bien passé
        header("Location: confirmations.php?nom=$nom&email=$email");
        exit();

    } catch(PDOException $e) {
        // Journalisation de l'erreur (à adapter selon votre environnement)
        error_log("Erreur base de données: " . $e->getMessage());
        header("Location: erreur.html");
        exit();
    } catch(Exception $e) {
        // Redirection vers une page d'erreur avec le message
        header("Location: erreur.html?message=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Si quelqu'un essaie d'accéder directement au script
    header("Location: index.html");
    exit();
}