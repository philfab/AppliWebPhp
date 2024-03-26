<?php
session_start();
$pageTitle = "Ajout Produit";
$link = "recap.php";
$linkText = "Panier";
ob_start(); //demarre la temporisation de sortie


if (isset($_SESSION['message'])) {
    // stock le message dans une variable
    $message = $_SESSION['message'];
    $isError = strpos($message, 'Erreur') !== false; //contient le mot Erreur
    // supprime le message de la session pour éviter qu'il réapparaisse
    unset($_SESSION['message']);
} else
    $message = '';

?>


<body>
    <div class="container">

        <input class="btAjouter" type="submit" name="submit" form="product-form" value="Ajouter le produit">



        <h1>Ajouter un produit</h1>
        <form id="product-form" action="traitement.php?action=add" method="post" enctype="multipart/form-data">
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
            <div>
                <label>Image produit :</label>
                <input type="file" name="file">
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

<?php
$content = ob_get_clean();
require_once "template.php";
?>