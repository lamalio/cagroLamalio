<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'Titres');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupérer les données du formulaire
$num_tit = $nb_br = $num_br = $x_br = $y_br = $type_cult = $spc_cult = $type_elv = $spc_elv = $type_irr = "";
if(isset($_POST['titleNumber'], $_POST['numPoints'], $_POST['numeroBorne'], $_POST['latitude'], $_POST['longitude'], $_POST['cultureType'], $_POST['specificCulture'], $_POST['elvageType'], $_POST['specificElvage'], $_POST['irrigationType'])) {
    $num_tit = $_POST['titleNumber'];
    $nb_br = $_POST['numPoints'];
    $num_br = $_POST['numeroBorne'];
    $x_br = $_POST['latitude'];
    $y_br = $_POST['longitude'];
    $type_cult = $_POST['cultureType'];
    $spc_cult = $_POST['specificCulture'];
    $type_elv = $_POST['elvageType'];
    $spc_elv = $_POST['specificElvage'];
    $type_irr = $_POST['irrigationType'];

    // Requête d'insertion préparée pour insérer les valeurs dans la table "agricoles"
    $stmt = $conn->prepare("INSERT INTO agricoles (Num_tit, Nb_Br, Num_Br, X_Br, Y_Br, Type_Cult, Spc_Cult, Type_Elv, Spc_ELv, Type_Irr) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $num_tit, $nb_br, $num_br, $x_br, $y_br, $type_cult, $spc_cult, $type_elv, $spc_elv, $type_irr);
    
    // Exécuter la requête d'insertion
    if ($stmt->execute()) {
        echo "Données insérées avec succès dans la table agricoles.";
    } else {
        echo "Erreur lors de l'insertion des données dans la table agricoles : " . $conn->error;
    }

    // Fermer la requête
    $stmt->close();
} else {
    echo "Tous les champs du formulaire doivent être remplis.";
}
$conn->close();
?>