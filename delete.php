<?php require "./bd.php";
$id = isset($_GET['id']) ? (int)$_GET['id']: 0;

$message = '';

if ($id > 0){
  try{
    $sql = "DELETE FROM produit WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $message = "Produit supprimé!! ";
  } catch(PDOException $e){
    $message = "erreur";
  }
} else {
  $message = "Erreur";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>supprimer</title>
</head>
<body>
  <h2>Supprimer un produit</h2>
  <p><?php echo htmlspecialchars($message); ?></p>
  <a href="index.php">Retour a la liste des produits!!</a>
</body>
</html>