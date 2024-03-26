<?php

session_start();
if (isset($_POST['submit'])) {


    $action = $_GET['action'] ?? null; // si action présent sinon null
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT); //index du produit : doit etre int

    switch ($action) {
        case 'add': //ajout produit
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $qtt = filter_input(INPUT_POST, 'qtt', FILTER_VALIDATE_INT);
            addProduct($name, $qtt, $price);
            header("Location: index.php"); //on reste sur accueil
            break;
        case 'delete': //suppr produit (tous les produits d'une sorte même si 5)
            deleteProduct($id);
            GotoRecap();
            break;
        case 'clear':
            clearProducts(); //suppr tous les produits
            GotoRecap();
            break;
        case 'up-qtt': // ajout d'une quantité
            updateQtt($id, 1);
            GotoRecap();
            break;
        case 'down-qtt': // retrait d'une quantité
            updateQtt($id, -1);
            GotoRecap();
            break;
        default:
            header("Location: index.php");
            break;
    }
}

function addProduct($name, $qtt, $price)
{
    if ($name && $price && $qtt && preg_match("/^[a-zA-Z0-9\s\-_\.]+$/", $name)) {

        $imageName = null;
        $extensions = ['jpg', 'png', 'jpeg', 'gif'];
        $maxSize = 400000; //bytes

        if (isset($_FILES['file'])) { //s'il y a bien une image et pas d'erreur
            $tmpName = $_FILES['file']['tmp_name'];
            $originalName = $_FILES['file']['name'];
            $size = $_FILES['file']['size']; //bytes
            $error = $_FILES['file']['error'];

            $tabExtension = explode('.', $originalName); // slpit le nom de l'image en tableau
            $extension = strtolower(end($tabExtension)); // dernier element du tableau (end) en minuscule(Jpg,JPG) . end(array)prend un tableau en param

            // nom unique pour éviter les conflits et problèmes de cache
            $imageName = uniqid('', true) . "-" . basename($originalName); //uniqid = id unique basé sur le temps actuel en ms + nom de l'image sans path
            $uploadDir = "upload/"; //répertoire doit exister + accés écriture
            $uploadFile = $uploadDir . $imageName;

            //déplace fichier dans le répertoire de dest
            if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {
                // erreur si fichier pas déplacé
                if (!move_uploaded_file($tmpName, $uploadFile)) {
                    $imageName = null;
                    message("Erreur lors du telechargement de l'image.");
                }
            }
        }

        $product = [
            'name' => $name,
            'qtt' => $qtt,
            'price' => $price,
            'total' => $price * $qtt,
            'imageName' => isset($imageName) ? $imageName : null,
        ];

        $_SESSION['products'][] = $product; //rajoute le produit au tableau
        message('Produit ajouté avec succès !');
    } else
        message("Erreur lors de l'ajout du produit !");
}

function deleteProduct($id)
{
    if (isset($id) && isset($_SESSION['products'][$id])) {
        unset($_SESSION['products'][$id]);
        message('Produit supprimé avec succès !');
    } else
        message('Produit introuvable !');
}

function clearProducts()
{
    if (isset($_SESSION['products'])) {
        unset($_SESSION['products']);
        message('Tous les produits ont été supprimés !');
    }
}

function updateQtt($id, $delta)
{
    if (isset($_SESSION['products']) && isset($id)) {
        $product = &$_SESSION['products'][$id]; // & = pointeur ref

        if ($product['qtt'] + $delta > 0) { // produit doit etre un supérieur à zéro
            $product['qtt'] += $delta;
            $product['total'] = $product['price'] * $product['qtt'];
            message($delta > 0 ? 'La quantité a été augmentée.' : 'La quantité a été diminuée.');
        } else
            message('La quantité ne peut pas être réduite davantage.');
    } else
        message('Produit introuvable.');
}

function GoToRecap()
{
    header("Location: recap.php");
    exit();
}

function message($msg)
{
    $_SESSION['message'] = $msg;
}
