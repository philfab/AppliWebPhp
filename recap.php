<?php
session_start();
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $isError = strpos($message, 'Erreur') !== false;
    unset($_SESSION['message']);
} else
    $message = '';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Récapitulatif des produits</title>
</head>

<body>
    <div class="container">

        <header>
            <nav>
                <ul>
                    <li>
                        <a href="index.php">Accueil</a>
                    </li>
                </ul>
                <i class="panier"><?php echo array_sum(array_column(isset($_SESSION['products']) ? $_SESSION['products'] : [], 'qtt')); ?></i>
            </nav>

        </header>
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
                echo
                "<tr>",
                "<td>" . $index . "</td>",
                "<td>" . $product['name'] . "</td>",
                "<td>" . number_format($product['price'], 2, ",", "&nbsp;") . "&nbsp;€", "</td>",
                "<td>" . $product['qtt'] . "</td>",
                "<td>" . number_format($product['total'], 2, ",", "&nbsp;") . "&nbsp;€", "</td>",
                "<td>
                <form action='traitement.php?action=delete' method='post'>
                  <input type='hidden' name='id' value='" . $index . "'>
                  <button type='submit' name='submit'>Supprimer</button>
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
                <button type='submit' name='submit'>Tout Supprimer</button>
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