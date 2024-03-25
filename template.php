<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?= $pageTitle; ?></title>
</head>

<body>
    <div class="container">
        <header>
            <nav>
                <ul>
                    <li>
                        <a href="<?= $link; ?>"><?= $linkText; ?></a>
                    </li>
                </ul>
                <i class="panier"><?= array_sum(array_column(isset($_SESSION['products']) ? $_SESSION['products'] : [], 'qtt')); ?></i>
            </nav>
        </header>

        <div id="content">
            <?= $content ?>
        </div>

    </div>
</body>

</html>