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

try {
    $sql = "SELECT id_composant, description, cout_unitaire FROM Composants ORDER BY id_composant ASC";
    $stmt = $pdo->query($sql);
    $composants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $composants = [];
    $error = "Erreur lors de la récupération des composants : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des composants</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Liste des composants</h2>

    <a href="create_composant.php">Ajouter un nouveau composant</a>
    <br><br>

    <?php if (isset($error)): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>


    <?php if (empty($composants)): ?>
        <p>Aucun composant trouvé.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Coût unitaire (€)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($composants as $composant): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($composant['id_composant']); ?></td>
                        <td><?php echo htmlspecialchars($composant['description']); ?></td>
                        <td><?php echo htmlspecialchars($composant['cout_unitaire']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>

    
    <br>
    <a href="index.php">Retour à la liste des produits</a>
</body>
</html>