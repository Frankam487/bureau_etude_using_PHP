<?php
require "./bd.php";

$id = isset($_GET['id']) ? (int)$_GET['id']: 0;
$description = '';
$message = '';
$produit_exist  = false;

if($id > 0){
  try{
    $sql = "SELECT description FROM produit WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $produit = $stmt->fetch((PDO::FETCH_ASSOC));

    if($produit){
      $description = $produit['description'];
      $produit_exist = true;
    } else {
      $message = "Erreur : produit non trouvé";
    }
  } catch(PDOException $e){
    $message = "Erreur : " . $e->getMessage();
    
  } 
}else {
  $message = "Erreur : ID de produit invalide!!";
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && $produit_exist){
  $description = trim($_POST['description'] ?? '');

  if(empty($description)){
    $message = "Erreur: La description est obligatoire";
  } else {
    try{
      $sql = "UPDATE produit SET description = :description WHERE id = :id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        'description' => $description,
        'id' => $id
      ]);
      $message = "Produit mis a jour avec succes";
      $description = '';
      header("Location: index.php");
    } catch(PDOException $e){
      $message = "Erreur". $e->getMessage();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>modifier</title>
</head>
<body>
  <?php if($message):?>

    <div class="message <?php echo strpos($message, 'Erreur') === false ? 'Succes' : 'Erreur';?>">
<?php echo htmlspecialchars($message); ?>
    </div>
    <?php endif;?>

    <?php if($produit_exist):?>

      <form action="update.php?id=<?php echo $id ?>" method="post">
        <div class="form">
          <label for="description">Description de produit : </label>
          <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($description)?>">
        </div>
        <button type="submit">Mettre a jour</button>
      </form>
      <?php endif;?>

      <a href="./index.php">Retour a la liste des produits</a>
</body>
</html>