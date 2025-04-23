<?php
require './bd.php';
$message = "";
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $description = trim($_POST['description'] ??"");
  if(empty($description)){
    $message = "Erreur: La description est obligatoire!!!!";
  } else{
    try{
      $sql = "INSERT INTO produit (description) VALUES (:description)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['description' => $description]);

      $message = "Produit ajouté avec succès!!";
      $description = "";
    } catch(PDOException $e){
      die("erreur" . $e->getMessage());
    }
  }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>creer</title>
</head>
<body>
  <h2>Ajouter un nouveau produit</h2>

  <?php if($message): ?>
    <div class="message <?php echo strpos($message, 'Erreur') === false ? 'Succes': 'erreur';?>">
<?php echo htmlspecialchars($message); ?>
    </div>
    <?php endif; ?>
    <a href="./index.php">Retour à la liste des produits</a>
    <form action="" method="post">
      <div class="form">
        <label for="">
          Description du produit :
        </label>
        <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($description);?>">
      </div>
      <button type="submit">Ajouter le produit</button>
    </form>
</body>
</html>