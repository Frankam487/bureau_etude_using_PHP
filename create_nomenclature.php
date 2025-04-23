
<?php
require './bd.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}


$id_produit = '';
$id_composant = '';
$quantite = '';
$message = '';


try {
    $sql_produits = "SELECT id, description FROM Produit ORDER BY description ASC";
    $stmt_produits = $pdo->query($sql_produits);
    $produits = $stmt_produits->fetchAll(PDO::FETCH_ASSOC);

    $sql_composants = "SELECT id, description FROM Composant ORDER BY description ASC";
    $stmt_composants = $pdo->query($sql_composants);
    $composants = $stmt_composants->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $produits = [];
    $composants = [];
    $message = "Erreur lors de la récupération des données : " . $e->getMessage();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produit = trim($_POST['id_produit'] ?? '');
    $id_composant = trim($_POST['id_composant'] ?? '');
    $quantite = trim($_POST['quantite'] ?? '');

    if (empty($id_produit) || empty($id_composant)) {
        $message = "Erreur : Veuillez sélectionner un produit et un composant.";
    } elseif (!is_numeric($quantite) || $quantite <= 0) {
        $message = "Erreur : La quantité doit être un nombre positif.";
    } else {
        try {
           
            $sql_check = "SELECT COUNT(*) FROM Nomenclature WHERE id_produit = :id_produit AND id_composant = :id_composant";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute(['id_produit' => $id_produit, 'id_composant' => $id_composant]);
            $count = $stmt_check->fetchColumn();

            if ($count > 0) {
                $message = "Erreur : Cette combinaison produit-composant existe déjà.";
            } else {
                // Insertion dans Nomenclature
                $sql_insert = "INSERT INTO Nomenclature (id_produit, id_composant, quantite) VALUES (:id_produit, :id_composant, :quantite)";
                $stmt_insert = $pdo->prepare($sql_insert);
                $stmt_insert->execute([
                    'id_produit' => $id_produit,
                    'id_composant' => $id_composant,
                    'quantite' => $quantite
                ]);

                $message = "Entrée ajoutée à la nomenclature avec succès !";
                $id_produit = '';
                $id_composant = '';
                $quantite = '';
            }
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
    <title>Ajouter une entrée à la nomenclature</title>
</head>
<body>
    <h2>Ajouter une entrée à la nomenclature</h2>

    
    <p><?php echo htmlspecialchars($message); ?></p>

    
    <form method="POST" action="create_nomenclature.php">
        <label for="id_produit">Produit :</label>
        <select id="id_produit" name="id_produit" required>
            <option value="">Sélectionner un produit</option>
            <?php foreach ($produits as $produit): ?>
                <option value="<?php echo $produit['id_produit']; ?>" <?php echo $id_produit == $produit['id_produit'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($produit['description']); ?>


</option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="id_composant">Composant :</label>
        <select id="id_composant" name="id_composant" required>
            <option value="">Sélectionner un composant</option>
            <?php foreach ($composants as $composant): ?>
                <option value="<?php echo $composant['id_composant']; ?>" <?php echo $id_composant == $composant['id_composant'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($composant['description']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="quantite">Quantité :</label>
        <input type="number" id="quantite" name="quantite" min="1" value="<?php echo htmlspecialchars($quantite); ?>" required>
        <br>
        <button type="submit">Ajouter à la nomenclature</button>
    </form>

    <br>
    <a href="index.php">Retour à la liste des produits</a>
    <br>
    <a href="index_composant.php">Retour à la liste des composants</a>
</body>
</html>