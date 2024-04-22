<?php
require "./db.php";

if(isset($_POST['submit2'])) {
    $Numtits = $_POST['titleNumber'];
    $Tele = $_POST['Tele'];
    $latit = $_POST['latitude'];
    $longit = $_POST['longitude'];
    //$CIN = $_POST['CIN'];
    //$Bons = $_POST['Bons'];

    if (isset($_POST['submit'])) {
        $num_tit = $_POST['titleNumber'];
        $nb_br = $_POST['numPoints']; titleNumber, Tele, latitude, longitude
        $num_br = $_POST['num_br'];
        $x_br = $_POST['X_br'];
        $y_br = $_POST['Y_br'];
        $type_cult = $_POST['cultureType'];
        $spc_cult = $_POST['specificCulture'];
        $type_elv = $_POST['elvageType'];
        $spc_elv = $_POST['specificElvage'];
        $type_irr = $_POST['irrigationType'];
    
        // Loop through each color and insert into database
        for ($i = 0; $i < $nb_br; $i++) {
    
    
            // Requête d'insertion préparée pour insérer les valeurs dans la table "agricoles"
            $stmt = $conn->prepare("INSERT INTO agricoles (titleNumber, Tele, latitude, longitude) VALUES ( ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $num_tit, $nb_br, $num_br, $x_br[$i], $y_br[$i], $type_cult, $spc_cult, $type_elv, $spc_elv, $type_irr);
    
    
            echo "teeeeeeest";
    
            if ($stmt->execute()) {
                echo "Données insérées avec succès dans la table agricoles.";
            } else {
                echo "Erreur lors de l'insertion des données dans la table agricoles : " . $conn->error;
            }
    
        }
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