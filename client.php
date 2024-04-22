<?php
require "./db.php";
/*$date = date("dm Y");
echo "La date d'aujourd'hui est : " . str_replace(' ', '', $date);
echo "La date d'aujourd'hui est : " . strtotime($date . ' +15 days');*/
// Récupérer les données du formulaire
$nature = $numero = $indice = "";
if (isset($_POST['nature'], $_POST['numero'], $_POST['indice'])) {
    $nature = $_POST['nature'];
    $numero = $_POST['numero'];
    $indice = $_POST['indice'];

    // Utiliser des requêtes préparées pour éviter les injections SQL
    $stmt = $conn->prepare("SELECT * FROM Bornes WHERE Num_titre = ? AND Nature_titre = ?");
    $stmt->bind_param("ss", $numero, $nature);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si des données existent
    if ($result->num_rows > 0) {
        // Afficher les données de bornes dans un tableau
        echo "<h2>Données de Bornes :</h2>";
        echo "<table border='1'>
                <tr>
                    <th>Nature</th>
                    <th>Numero</th>
                    <th>Indice</th>
                    <th>Num_bornes</th>
                    <th>X</th>
                    <th>Y</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $nature . "</td>
                    <td>" . $numero . "</td>
                    <td>" . $indice . "</td>
                    <td>" . ($row['Num_borne'] ?? '') . "</td>
                    <td>" . ($row['X'] ?? '') . "</td>
                    <td>" . ($row['Y'] ?? '') . "</td> 
                  </tr>";
        }

        echo "</table>";

        // Utiliser une requête préparée pour récupérer les données de Titres
        $stmt_titres = $conn->prepare("SELECT * FROM titres WHERE Num = ? AND Nature = ?");
        $stmt_titres->bind_param("ss", $numero, $nature);
        $stmt_titres->execute();
        $result_titres = $stmt_titres->get_result();

        // Vérifier si des données existent dans Titres
        if ($result_titres->num_rows > 0) {
            // Afficher les données de Titres dans un tableau
            echo "<h2>Données de Titres :</h2>";
            echo "<table border='1'>
                    <tr>
                        <th>Nature</th>
                        <th>Numero</th>
                        <th>Indice</th>
                        <th>Surf_Adop</th>
                        <th>Consistance</th>
                    </tr>";

            while ($row_titres = $result_titres->fetch_assoc()) {
                echo "<tr>
                        <td>" . $nature . "</td>
                        <td>" . $numero . "</td>
                        <td>" . $indice . "</td>
                        <td>" . ($row_titres['Surf_Adop'] ?? '') . "</td>
                        <td>" . ($row_titres['Consistance'] ?? '') . "</td>
                      </tr>";
            }

            echo "</table>";
        } else {
            echo "Aucune donnée de Titres trouvée pour ces valeurs.";
        }
    } else {
        echo "Aucune donnée de Bornes trouvée pour ces valeurs.";
    }
}

// Récupérer les données du formulaire
if (isset($_POST['submit'])) {
    $num_tit = $_POST['titleNumber'];
    $nb_br = $_POST['numPoints'];
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
        $stmt = $conn->prepare("INSERT INTO agricoles (num_tit, nb_br, num_br, x_br, y_br, type_cult, spc_cult, type_elv, spc_elv, type_irr) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $num_tit, $nb_br, $num_br, $x_br[$i], $y_br[$i], $type_cult, $spc_cult, $type_elv, $spc_elv, $type_irr);


        echo "teeeeeeest";

        if ($stmt->execute()) {
            echo "Données insérées avec succès dans la table agricoles.";
        } else {
            echo "Erreur lors de l'insertion des données dans la table agricoles : " . $conn->error;
        }

    }

    // Close the prepared statement
    $stmt->close();
}

// Fermer la connexion
$conn->close();
?>
<h2>
    <a href="index.html">Cliquer ici pour voyez votre parcelle(s) dans la mappe :</a>
</h2>
<h2>Formulaire d'Enregistrement des Terres Agricoles :</h2>
<!-- Formulaire -->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Géoréférencée</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style src="style.css"></style>
</head>

<body>
    <div class=" flex space-x-4">     
           
        <div class="w-[30%]">
            <form class="w-full border border-blue-500" action="" method="post">
                <div>
                    <form action="">
                        <label for="titleNumber">Numéro de titre :</label>
                        <input type="text" id="titleNumber" name="titleNumber" required>
                        <br>
                        <label for="email">Email :</label>
                        <input class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"></label>
                        <!--<input type="text" id="first_name"
class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block mx-auto p-2.5 "
                            type="email" id="email" name="email" required>-->
                        <br>
                            <label for="phone">Phone :</label>
                            <input type="tel" id="phone" name="phone" required>
                        <br>
                            <label for="Nom et Prénom">Nom et Prénom :</label>
                            <input type="text" id="Nom et Prénom" name="Nom et Prénom" required>
                        <br>
                            <label for="latitude">Latitude :</label>
                            <input type="text" id="latitude" name="latitude" required>
                        <br>
                            <label for="longitude">Longitude :</label>
                            <input type="text" id="longitude" name="longitude" required>
                        <br>
                            <button type="submit" id ="submit2" name="submit2" onclick="updateMarker()">Mettre à jour le marqueur</button><br>
                    </form>
                    </div>
                <br>
                <div>
                    <form action="">
                        
                        <br>
                        <label for="numPoints">Nombre de points :</label>
                        <input type="number" id="numPoints" name="numPoints" min="3" max="200" placeholder="3 ou plus" required>
                        <button type="button" onclick="createPointInputs()">Créer des points</button><br>
                        <div id="pointInputs"></div><br>
                        <label for="cultureType">Type de culture :</label>
                        <select id="cultureType" name="cultureType" onchange="updateSpecificCultures()" required>
                            <option value="cereals">Cultures céréalières</option>
                            <option value="vegetables">Cultures maraîchères</option>
                            <option value="fruits">Cultures fruitières</option>
                            <option value="oleaginous">Cultures oléagineuses</option>
                            <option value="industrial">Cultures industrielles</option>
                            <option value="forage">Cultures fourragères</option>
                            <option value="special">Cultures spéciales</option>
                            <option value="Pas de Culture">Pas de culture</option>
                        </select>
                        <label for="specificCulture">Culture spécifique :</label>
                        <select id="specificCulture" name="specificCulture" required>
                            <!-- Les options spécifiques seront ajoutées dynamiquement -->
                        </select>
                        <br></br>
                        <label for="elvageType">Type d'élevage :</label>
                        <select id="elvageType" name="elvageType" onchange="updateSpecificElvages()" required>
                            <option value="Élevage bovin">Élevage bovin</option>
                            <option value="Élevage ovin">Élevage ovin</option>
                            <option value="Élevage caprin">Élevage caprin</option>
                            <option value="Élevage porcin">Élevage porcin</option>
                            <option value="Aviculture">Aviculture</option>
                            <option value="Apiculture">Apiculture</option>
                            <option value="Aquaculture">Aquaculture</option>
                            <option value="Élevage équin">Élevage équin</option>
                            <option value="Élevage de volailles d'ornement">Élevage de volailles d'ornement</option>
                            <option value="Élevage de lapins">Élevage de lapins</option>
                            <option value="Pas d_Élevage">Pas d'élevage</option>
                        </select>
                        <label for="specificElvage">Élevage spécifique :</label>
                        <select id="specificElvage" name="specificElvage" required>
                            <!-- Les options spécifiques seront ajoutées dynamiquement -->
                        </select>
                        <br></br>
                        <label for="irrigationType">Irrigation et drainage :</label>
                        <select id="irrigationType" name="irrigationType" onchange="updateSpecificIrrigations()" required>
                            <option value="Irrigation par Aspersion">Irrigation par Aspersion</option>
                            <option value="Irrigation Goutte-à-Goutte">Irrigation Goutte-à-Goutte</option>
                            <option value="Irrigation par Pivot Central">Irrigation par Pivot Central</option>
                            <option value="Irrigation par Submersion ou Inondation">Irrigation par Submersion ou Inondation
                            </option>
                            <option value="Pas d_Irrigation">Pas d'irrigation</option>
                        </select>
                        <br>
                        <button type="button" onclick="drawPolygon()">Dessiner un polygone</button>
                        <button name="submit" type="submit" onclick="TerresAgricole()">Me enregistrer</button>
                    </form>
                </div>
                <div>
                    <form action="">
                        <h2>Télécharger votre CIN</h2>
                                <label for="file">Sélectionnez un fichier (jpg ou document, max. 3 Mo) :</label><br>
                                <input type="file" name="CIN" id="file" accept=".jpg,.jpeg,.doc,.docx,.pdf" required><br>
                                <!--<input type="" value="Télécharger">-->
                                <h2>Télécharger votre Bons d'achat</h2>
                                <label for="file">Sélectionnez un fichier (jpg ou document, max. 3 Mo) :</label><br>
                                <input type="file" name="Bons" id="file" accept=".jpg,.jpeg,.doc,.docx,.pdf" required><br>
                                <!--<input type="" value="Télécharger">-->
                    </form>
                
            </form>
            
        </div>
        </div>
                <div class="w-[70%]">
                <div id="surfaceOutput"></div>
                <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                <div id="map" style="width: 100%; height: 100%;"></div>
                <div id="map"></div>
            </div>
    </div>

    <script>
        var map = L.map('map').setView([31.7917, -7.0926], 6); // Réglage de la vue sur le Maroc

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker = L.marker([31.7917, -7.0926]).addTo(map); // Placement du marqueur au centre du Maroc
        marker.bindPopup("<b>Bonjour tout le monde !</b><br>Je suis ici !!.").openPopup();

        function updateMarker() {
            var latitude = parseFloat(document.getElementById('latitude').value);
            var longitude = parseFloat(document.getElementById('longitude').value);

            if (!isNaN(latitude) && !isNaN(longitude)) {
                marker.setLatLng([latitude, longitude]);
                map.setView([latitude, longitude], 13); // Centrer la carte sur les nouvelles coordonnées
            } else {
                alert("Veuillez entrer des coordonnées valides.");
            }
        }
        function createPointInputs() {
            var numPoints = parseInt(document.getElementById('numPoints').value);
            var pointInputsDiv = document.getElementById('pointInputs');
            pointInputsDiv.innerHTML = ''; // Clear previous inputs

            if (numPoints >= 3) {
                for (var i = 0; i < numPoints; i++) {
                    var container = document.createElement('div');
                    container.className = 'inputContainer';

                    // Adding input for numero de borne
                    var labelNumeroBorne = document.createElement('label');
                    labelNumeroBorne.textContent = 'Numero de Borne:';
                    container.appendChild(labelNumeroBorne);
                    var inputNumeroBorne = document.createElement('input');
                    inputNumeroBorne.type = 'text';
                    inputNumeroBorne.name = 'num_br[]';
                    inputNumeroBorne.className = 'pointInput numeroBorneInput';
                    container.appendChild(inputNumeroBorne);

                    // Adding input for X coordinate
                    var labelX = document.createElement('label');
                    labelX.textContent = 'X:';
                    container.appendChild(labelX);
                    var inputX = document.createElement('input');
                    inputX.type = 'text';
                    inputX.name = 'X_br[]';
                    inputX.className = 'latitudeInput';
                    container.appendChild(inputX);

                    // Adding input for Y coordinate
                    var labelY = document.createElement('label');
                    labelY.textContent = 'Y:';
                    container.appendChild(labelY);
                    var inputY = document.createElement('input');
                    inputY.type = 'text';
                    inputY.name = 'Y_br[]';
                    inputY.className = 'longitudeInput';
                    container.appendChild(inputY);

                    pointInputsDiv.appendChild(container);
                }
            } else {
                alert("Veuillez sélectionner au moins 3 points.");
            }

            // Add button to calculate surface after inserting inputs
            var calculateButton = document.createElement('button');
            calculateButton.textContent = 'Calculer la surface';
            calculateButton.addEventListener('click', calculateSurface);
            pointInputsDiv.appendChild(calculateButton);
        }

        function drawPolygon() {
            var latLngs = [];

            var latitudeInputs = document.getElementsByClassName('latitudeInput');
            var longitudeInputs = document.getElementsByClassName('longitudeInput');

            for (var i = 0; i < latitudeInputs.length; i++) {
                var lat = parseFloat(latitudeInputs[i].value.trim());
                var lng = parseFloat(longitudeInputs[i].value.trim());

                if (!isNaN(lat) && !isNaN(lng)) {
                    latLngs.push([lat, lng]);
                } else {
                    alert("Invalid coordinate format for Point " + (i + 1) + ".");
                    return;
                }
            }

            if (latLngs.length < 3) {
                alert("A polygon must have at least 3 points.");
                return;
            }

            var selectedCulture = document.getElementById('specificCulture').value;
            var centroid = calculateCentroid(latLngs);
            var polygon = L.polygon(latLngs, { color: 'red' }).addTo(map);
            var centroidMarker = L.marker(centroid).addTo(map);
            var titleNumber = document.getElementById('titleNumber').value; // Récupérer la valeur du numéro de titre
            centroidMarker.bindPopup("Centre de votre propriété: " + titleNumber).openPopup(); // Mettre à jour le contenu du popup
            var icon = getCultureIcon(selectedCulture);
            L.polygon(latLngs, { color: 'red' }).addTo(map);
            L.marker(centroid, { icon: icon }).addTo(map);
            // Calculating the surface area of the polygon
            var area = calculateArea(latLngs);

            // Convertir de hectares en mètres carrés (1 hectare = 10000 mètres carrés)
            area *= 10000;

            // Adding an annotation to the centroid of the polygon
            var annotation = L.tooltip({
                permanent: true,
                direction: 'center',
                className: 'custom-tooltip'
            })

                .setContent("Area: " + area.toFixed(2) + " m2")
                .setLatLng(centroid)
                .addTo(map);
        }

        function calculateCentroid(latLngs) {
            var sumX = 0;
            var sumY = 0;
            var sumZ = 0;

            for (var i = 0; i < latLngs.length; i++) {
                var lat = latLngs[i][0] * Math.PI / 180;
                var lng = latLngs[i][1] * Math.PI / 180;

                sumX += Math.cos(lat) * Math.cos(lng);
                sumY += Math.cos(lat) * Math.sin(lng);
                sumZ += Math.sin(lat);
            }

            var avgX = sumX / latLngs.length;
            var avgY = sumY / latLngs.length;
            var avgZ = sumZ / latLngs.length;

            var lng = Math.atan2(avgY, avgX) * 180 / Math.PI;
            var hyp = Math.sqrt(avgX * avgX + avgY * avgY);
            var lat = Math.atan2(avgZ, hyp) * 180 / Math.PI;

            return [lat, lng];
        }

        function updateSpecificCultures() {
            var cultureType = document.getElementById('cultureType').value;
            var specificCultureSelect = document.getElementById('specificCulture');
            specificCultureSelect.innerHTML = ''; // Clear previous options

            var specificCultures = getSpecificCultures(cultureType);
            specificCultures.forEach(function (culture) {
                var option = document.createElement('option');
                option.value = culture;
                option.textContent = culture;
                specificCultureSelect.appendChild(option);
            });
        }
        function getSpecificCultures(cultureType) {
            switch (cultureType) {
                case 'cereals':
                    return ['Blé', 'Riz', 'Maïs', 'Orge', 'Millet'];
                case 'vegetables':
                    return ['Tomates', 'Carottes', 'Pommes de terre', 'Laitues', 'Fraises'];
                case 'fruits':
                    return ['Pommes', 'Oranges', 'Bananes', 'Raisins', 'Mangues'];
                case 'oleaginous':
                    return ['Soja', 'Tournesol', 'Colza', 'Olive', 'Arachide'];
                case 'industrial':
                    return ['Coton', 'Tabac', 'Jute'];
                case 'forage':
                    return ['Foin', 'Luzerne', 'Trèfle'];
                case 'special':
                    return ['Café', 'Thé', 'Cacao', 'Épices', 'Plantes médicinales'];
                case 'Pas de Culture':
                    return ['aucune'];
                default:
                    return [];
            }
        }

        function getCultureIcon(culture) {

            var iconUrl = '';
            switch (culture) {
                case 'Blé':
                case 'Riz':
                case 'Maïs':
                case 'Orge':
                case 'Millet':
                    iconUrl = '🌾';
                    break;
                case 'Tomates':
                case 'Carottes':
                case 'Pommes de terre':
                case 'Laitues':
                case 'Fraises':
                    iconUrl = '🥕';
                    break;
                case 'Pommes':
                case 'Oranges':
                case 'Bananes':
                case 'Raisins':
                case 'Mangues':
                    iconUrl = '🍎';
                    break;
                case 'Soja':
                case 'Tournesol':
                case 'Colza':
                case 'Olive':
                case 'Arachide':
                    iconUrl = '🌻';
                    break;
                case 'Coton':
                case 'Tabac':
                case 'Jute':
                    iconUrl = '🌱';
                    break;
                case 'Foin':
                case 'Luzerne':
                case 'Trèfle':
                    iconUrl = '🌿';
                    break;
                case 'Café':
                case 'Thé':
                case 'Cacao':
                case 'Épices':
                case 'Plantes médicinales':
                    iconUrl = '☕';
                    break;
                default:
                    iconUrl = '';
                    break;
            }
            return L.divIcon({ html: iconUrl, className: 'cult-icon' });
        }
        function updateSpecificElvages() {

            var elvageType = document.getElementById('elvageType').value;
            var specificElvageSelect = document.getElementById('specificElvage');
            specificElvageSelect.innerHTML = ''; // Effacer les options précédentes

            var specificElvages = getSpecificElvages(elvageType); // Assurez-vous que la fonction est correctement appelée
            specificElvages.forEach(function (elvage) {
                var option = document.createElement('option');
                option.value = elvage;
                option.textContent = elvage;
                specificElvageSelect.appendChild(option);
            });
        }
        function getSpecificElvages(elvageType) {
            switch (elvageType) {
                case 'Élevage bovin':
                    return ['Bovins laitiers', 'Bovins de boucherie', 'Bovins mixtes'];
                case 'Élevage ovin':
                    return ['Ovins viande', 'Ovins laitiers', 'Ovins à laine'];
                case 'Élevage caprin':
                    return ['Caprins laitiers', 'Caprins viande', 'Caprins mixtes'];
                case 'Élevage porcin':
                    return ['Porcs charcutiers', 'Porcs à l_engraissement', 'Porcs reproducteurs'];
                case 'Aviculture':
                    return ['Poules pondeuse', 'Poulets de chair', 'Autres volailles (dindes, canards, oies, etc.)'];
                case 'Apiculture':
                    return ['Production de miel', 'Pollinisation des cultures'];
                case 'Aquaculture':
                    return ['Pisciculture', 'Élevage de crustacés', 'Élevage de mollusques'];
                case 'Élevage équin':
                    return ['Chevaux de selle', 'Chevaux de course', 'Chevaux de trait'];
                case 'Élevage de volailles d_ornement':
                    return ['Paons', 'Faisans', 'Pigeons d_ornement'];
                case 'Élevage de lapins':
                    return ['Lapins de chair', 'Lapins à fourrure', 'Lapins de compagnie'];
                case 'Pas d_Élevage':
                    return ['aucun'];
                default:
                    return [];
            }
        }
        function getElvageIcon(elvage) {
            var iconUrl = '';
            switch (elvage) {
                case 'Bovins laitiers':
                case 'Bovins de boucherie':
                case 'Bovins mixtes':
                    iconUrl = '+Ébovin';
                    break;
                case 'Ovins viande':
                case 'Ovins laitiers':
                case 'Ovins à laine':
                    iconUrl = '+Éovin';
                    break;
                case 'Caprins laitiers':
                case 'Caprins viande':
                case 'Caprins mixtes':
                    iconUrl = '+Écaprin';
                    break;
                case 'Porcs charcutiers':
                case 'Porcs à l_engraissement':
                case 'Porcs reproducteurs':
                    iconUrl = '+Éorcin';
                    break;
                case 'Poules pondeuse':
                case 'Poulets de chair':
                case 'Autres volailles (dindes, canards, oies, etc.)':
                    iconUrl = '+Avic';
                    break;
                case 'Production de miel':
                case 'Pollinisation des cultures':
                    iconUrl = '+Apic';
                    break;
                case 'Pisciculture':
                case 'Élevage de crustacés':
                case 'Élevage de mollusques':
                    iconUrl = '+Aquac';
                    break;
                case 'Chevaux de selle':
                case 'Chevaux de course':
                case 'Chevaux de trait':
                    iconUrl = '+Éequin';
                    break;
                case 'Paons':
                case 'Faisans':
                case 'Pigeons d_ornement':
                    iconUrl = '+ÉVO';
                    break;
                case 'Lapins de chair':
                case 'Lapins à fourrure':
                case 'Lapins de compagnie':
                    iconUrl = '+Élapin';
                    break;
                default:
                    iconUrl = '';
                    break;
            }
            return L.divIcon({ html: iconUrl, className: 'cult-icon' });
        }

        function updateSpecificIrrigations() {
            var irrigationType = document.getElementById('irrigationType').value;
            var specificIrrigationSelect = document.getElementById('specificIrrigation');
            specificIrrigationSelect.innerHTML = ''; // Effacer les options précédentes
            var specificIrrigations = getSpecificIrrigations(irrigationType); // Fix parameter case
            specificIrrigations.forEach(function (irrigation) {
                var option = document.createElement('option');
                option.value = irrigation;
                option.textContent = irrigation;
                specificIrrigationSelect.appendChild(option);
            });
        }
        function getSpecificIrrigations(irrigationType) {
            switch (irrigationType) {
                case 'Irrigation et drainage':
                    return ['Irrigation par Aspersion', 'Irrigation Goutte-à-Goutte', 'Irrigation par Pivot Central', 'Irrigation par Submersion ou Inondation'];
                case 'Pas d_Irrigation':
                    return ['aucun'];
                default:
                    return [];
            }
        }
        function getIrrigationIcon(irrigation) {
            var iconUrl = '';
            switch (irrigation) {
                case 'Irrigation par Aspersion':
                case 'Irrigation par Submersion ou Inondation':
                case 'Irrigation Goutte-à-Goutte':
                case 'Irrigation Goutte-à-Goutte':
                case 'Irrigation par Pivot Central':
                    iconUrl = '+Irrig';
                    break;
                default:
                    iconUrl = '';
                    break;
            }
            return L.divIcon({ html: iconUrl, className: 'cult-icon' });
        }
        function calculateArea(latLngs) {
            var area = 0;
            if (latLngs.length > 2) {
                for (var i = 0; i < latLngs.length; i++) {
                    var j = (i + 1) % latLngs.length;
                    area += (latLngs[i][0] + latLngs[j][0]) * (latLngs[j][1] - latLngs[i][1]);
                }
                area = Math.abs(area) / 2;

                // Convertir de hectares en mètres carrés (1 hectare = 10000 mètres carrés)
                area *= 10000;
            }
            return area;
        }
    </script>
</body>

</html>