<?php

$host = 'localhost';
$dbname = 'bureau_etude';
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}


$description = '';
$cout_unitaire = '';
$message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description'] ?? '');
    $cout_unitaire = trim($_POST['cout_unitaire'] ?? '');

   
    if (empty($description)) {
        $message = "Erreur : La description est obligatoire.";
    } elseif (!is_numeric($cout_unitaire) || $cout_unitaire <= 0) {
        $message = "Erreur : Le coût unitaire doit être un nombre positif.";
    } else {
        try {
            
            $sql = "INSERT INTO Composant (description, cout_unitaire) VALUES (:description, :cout_unitaire)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'description' => $description,
                'cout_unitaire' => $cout_unitaire
            ]);

            $message = "Composant ajouté avec succès !";
            $description = '';
            $cout_unitaire = '';
            header("Location: list_composants.php");
        } catch (PDOException $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Ajouter un composant</title>
</head>
<body>
    <h2>Ajouter un nouveau composant</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    
    <form method="POST" action="create_composant.php">
        <label for="description">Description :</label>
        <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($description); ?>" required>
        <br>
        <label for="cout_unitaire">Coût unitaire (CFA) :</label>
        <input type="number" id="cout_unitaire" name="cout_unitaire" step="0.01" value="<?php echo htmlspecialchars($cout_unitaire); ?>" required>
        <br>
        <button type="submit">Ajouter le composant</button>
    </form>

    <a href="index.php">Retour à la liste des produits</a>
</body>
</html>