<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement Titre Agricole</title>
</head>
<body>
    <h2>Enregistrer votre Titre Agricole ?</h2>
    <form action="client.php" method="post">
        <label for="nature">Nature :</label>
        <input type="text" name="nature" id="nature" required><br><br>
        <label for="numero">Numero :</label>
        <input type="text" name="numero" id="numero" required><br><br>
        <label for="indice">Indice :</label>
        <input type="text" name="indice" id="indice" required><br><br>
        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
