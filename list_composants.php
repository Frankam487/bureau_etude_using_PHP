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
    $sql = "SELECT id, description, cout_unitaire FROM Composant ORDER BY id ASC";
    $stmt = $pdo->query($sql);
    $composants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $composants = [];
    $message = "Erreur lors de la récupération des composants : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Liste des liscomposants</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gestion composants</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="create_composant.php">Ajouter un composant</a>
        </nav>
    </header>

    <main>
        <h2>Liste des composants</h2>

        <?php if (isset($message)): ?>
            <div class="message error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (empty($composants)): ?>
            <p>Aucun composant trouvé.</p>
        <?php else: ?>
            <div class="table-container">
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Coût unitaire (FCFA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($composants as $composant): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($composant['id']); ?></td>
                                <td><?php echo htmlspecialchars($composant['description']); ?></td>
                                <td><?php echo htmlspecialchars($composant['cout_unitaire']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>