<?php
require "./bd.php";



try{
  $sql = "SELECT id, description FROM produit ORDER BY id ASC";
  $stmt = $pdo->query($sql);
  $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // header("Location: index.php");
} catch(PDOException $e){
  $produits = [];
  $error = "Erreur lors de la recuperation des produits" . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h2>Liste des produits</h2>
  <?php if(isset($error)): ?>
    <div class="error"><?php echo htmlspecialchars($error) ?></div>
    <?php endif;?>
<?php
    if(empty($produits)): ?>
    <p class="pasDeDonnee">Aucun produit trouvé.</p>
    <?php else :?>
<div class="creer">
  <a href="./create.php">Creer un produit</a>
  <a href="./create_composant.php">Ajouter un nouveau composant</a> <br>
  
</div>
    <div class="table-container">
      <a href="">Ajout de composant</a>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($produits as $produit):?>
            <tr>
              <td><?php echo htmlspecialchars($produit['id']);?></td>
              <td><?php echo htmlspecialchars($produit['description']);?></td>
              <td><a href="update.php?id=<?php echo $produit['id']?>">modifier</a></td>
              <td><a href="delete.php?id=<?php echo $produit['id']?>" onclick="return confirm('effacer?')">effacer</a></td>
            </tr>
            <?php endforeach;?>
        </tbody>
      </table>
      <?php endif;?>
    </div>
</body>
</html>