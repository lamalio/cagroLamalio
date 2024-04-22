<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agricole";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Vérifier si des données sont envoyées
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du tableau
    $donnees = json_decode($_POST["donnees"], true);

    // Préparer et exécuter les requêtes d'insertion
    foreach ($donnees as $borne) {
        $sql = "INSERT INTO votre_table (Num_Borne, X_borne, Y_borne) VALUES ('" . $borne["Num_Borne"] . "', '" . $borne["X_borne"] . "', '" . $borne["Y_borne"] . "')";

        if ($conn->query($sql) !== TRUE) {
            echo "Erreur lors de l'insertion des données : " . $conn->error;
        }
    }

    echo "Données insérées avec succès.";
}

$conn->close();
?>
