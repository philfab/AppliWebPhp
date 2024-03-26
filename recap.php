<?php
session_start();
$pageTitle = "Récapitulatif des produits";
$link = "index.php";
$linkText = "Accueil";
ob_start();

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $isError = strpos($message, 'Erreur') !== false;
    unset($_SESSION['message']);
} else
    $message = '';
?>

<body>
    <div class="container">


        <?php
        if (!isset($_SESSION['products']) || empty($_SESSION['products'])) {
            echo "<p>Votre panier est vide</p>";
        } else {
            echo
            "<table>",
            "<thead>",
            "<tr>",
            "<th>#</th>",
            "<th>Nom</th>",
            "<th>Prix</th>",
            "<th>Quantité</th>",
            "<th>Total</th>",
            "</tr>",
            "</thead>",
            "<tbody>";

            $totalGeneral = 0;

            foreach ($_SESSION['products'] as $index => $product) {
                echo "<tr>",
                "<td>" . $index . "</td>";

                if (!empty($product['imageName'])) {
                    echo "<td><img src='upload/" . $product['imageName']  . $product['name'] . "</td>";
                } else {
                    echo "<td>" . $product['name'] . "</td>";
                }

                echo "<td>" . number_format($product['price'], 2, ",", "&nbsp;") . "&nbsp;€</td>",
                "<td>" . $product['qtt'] .
                    "<form class='form-qtt' action='traitement.php?action=down-qtt' method='post'>
                            <input type='hidden' name='id' value='" . $index . "'>
                            <button class='button-qtt' type='submit' name='submit'>-</button>
                        </form>
                        <form class='form-qtt' action='traitement.php?action=up-qtt' method='post'>
                            <input type='hidden' name='id' value='" . $index . "'>
                            <button class='button-qtt' type='submit' name='submit'>+</button>
                        </form>
                     </td>",
                "<td>" . number_format($product['total'], 2, ",", "&nbsp;") . "&nbsp;€</td>",
                "<td>
                        <form action='traitement.php?action=delete' method='post'>
                            <input type='hidden' name='id' value='" . $index . "'>
                            <button class='bt-suppr' type='submit' name='submit'>Supprimer</button>
                        </form>
                     </td>",
                "</tr>";
                $totalGeneral += $product['total'];
            }

            echo
            "<tr>",

            "<td class='td-clear' colspan=4> Total général : </td>",
            "<td><strong>" . number_format($totalGeneral, 2, ",", "&nbsp;") . "&nbsp;€</strong></td>",
            "<td>
             <form action='traitement.php?action=clear' method='post'>
                <button class='bt-suppr' type='submit' name='submit'>Tout Supprimer</button>
              </form>
            </td>",
            "</tr>",
            "</tbody>",
            "</table>";
        }
        ?>
        <?php if (!empty($message)) : ?>
            <div class="<?php echo ($isError === true)  ? 'message message-error' : 'message'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
$content = ob_get_clean();
include 'template.php';
?>