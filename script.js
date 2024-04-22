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
        inputNumeroBorne.name = 'pointInput numeroBorneInput[]';
        inputNumeroBorne.className = 'pointInput numeroBorneInput';
        container.appendChild(inputNumeroBorne);

        // Adding input for X coordinate
        var labelX = document.createElement('label');
        labelX.textContent = 'X:';
        container.appendChild(labelX);
        var inputX = document.createElement('input');
        inputX.type = 'text';
        inputX.name = 'latitudeInput[]';
        inputX.className = 'latitudeInput';
        container.appendChild(inputX);

        // Adding input for Y coordinate
        var labelY = document.createElement('label');
        labelY.textContent = 'Y:';
        container.appendChild(labelY);
        var inputY = document.createElement('input');
        inputY.type = 'text';
        inputY.name = 'longitudeInput[]';
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
    var polygon = L.polygon(latLngs, {color: 'blue'}).addTo(map);
    var centroidMarker = L.marker(centroid).addTo(map);
    var titleNumber = document.getElementById('titleNumber').value; // Récupérer la valeur du numéro de titre
    centroidMarker.bindPopup("Centre de votre propriété: " + titleNumber).openPopup(); // Mettre à jour le contenu du popup
    var icon = getCultureIcon(selectedCulture);
    L.polygon(latLngs, {color: 'blue'}).addTo(map);
    L.marker(centroid, {icon: icon}).addTo(map);
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
specificCultures.forEach(function(culture) {
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
specificElvages.forEach(function(elvage) {
    var option = document.createElement('option');
    option.value = elvage;
    option.textContent = elvage;
    specificElvageSelect.appendChild(option);
});
}
function getSpecificElvages(elvageType) {
switch (elvageType) {
    case 'Élevage bovin':
        return ['Bovins laitiers', 'Bovins de boucherie',  'Bovins mixtes'];
    case 'Élevage ovin':
        return ['Ovins viande',  'Ovins laitiers',  'Ovins à laine'];
    case 'Élevage caprin':
        return ['Caprins laitiers',  'Caprins viande',  'Caprins mixtes'];
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
        return ['Lapins de chair', 'Lapins à fourrure',  'Lapins de compagnie'];
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
    specificIrrigations.forEach(function(irrigation) {
        var option = document.createElement('option');
        option.value = irrigation;
        option.textContent = irrigation;
        specificIrrigationSelect.appendChild(option);
    });
} 
function getSpecificIrrigations(irrigationType) {
    switch (irrigationType) {
        case 'Irrigation et drainage':
            return ['Irrigation par Aspersion', 'Irrigation Goutte-à-Goutte',  'Irrigation par Pivot Central', 'Irrigation par Submersion ou Inondation'];
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