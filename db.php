<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'Titres');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}