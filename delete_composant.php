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

$id_composant = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';

if ($id_composant > 0) {
    try {
        
        $sql_check = "SELECT COUNT(*) FROM Nomenclature WHERE id_composant = :id_composant";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute(['id_composant' => $id_composant]);
        $count = $stmt_check->fetchColumn();

        if ($count > 0) {
            $message = "Erreur : Ce composant est utilisé par un ou plusieurs produits dans la nomenclature.";
        } else {
          
            $sql_delete = "DELETE FROM Composants WHERE id_composant = :id_composant";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->execute(['id_composant' => $id_composant]);
            $message = "Composant supprimé avec succès !";
        }
    } catch (PDOException $e) {
        $message = "Erreur : " . $e->getMessage();
    }
} else {
    $message = "Erreur : ID de composant invalide.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Supprimer un composant</title>
</head>
<body>
    <h2>Supprimer un composant</h2>
    <p><?php echo htmlspecialchars($message); ?></p>
    <a href="index_composant.php">Retour à la liste des composants</a>
</body>
</html>