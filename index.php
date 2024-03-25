<?php
session_start();

if (isset($_SESSION['message'])) {
    // stock le message dans une variable
    $message = $_SESSION['message'];
    $isError = strpos($message, 'Erreur') !== false; //contient le mot Erreur
    // supprime le message de la session pour éviter qu'il réapparaisse
    unset($_SESSION['message']);
} else
    $message = '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Ajout Produit</title>
</head>

<body>
    <div class="container">
        <header>
            <input class="btAjouter" type="submit" name="submit" form="product-form" value="Ajouter le produit">
            <nav>
                <ul>
                    <li>
                        <a href="recap.php">
                            Panier
                            <!--array_column est utilisé pour extraire toutes les valeurs d'une colonne (ici qtt)
                            array_sum prend le tableau (valeurs extraites par array_column) et retourne la somme des valeurs-->
                            <i><?php echo array_sum(array_column(isset($_SESSION['products']) ? $_SESSION['products'] : [], 'qtt')); ?></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </header>

        <h1>Ajouter un produit</h1>
        <form id="product-form" action="traitement.php?action=add" method="post">
            <div>
                <label> Nom du produit :</label>
                <input type="text" name="name">
            </div>
            <div>
                <label>Prix du produit en € :</label>
                <input type="number" step="any" min="0" name="price">
            </div>
            <div>
                <label>Quantité désirée : </label>
                <input type="number" name="qtt" min="1" value="1"><br>
            </div>

            <input class="btAjouter" type="submit" name="submit" value="Ajouter le produit">
            <?php if (!empty($message)) : ?>
                <div class="<?php echo ($isError === true)  ? 'message message-error' : 'message'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
        </form>
    </div>

</body>

</html>