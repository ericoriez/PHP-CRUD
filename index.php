<?php
// on démarre une session
session_start();

// on inclut la connexion à la base 
require_once('connect.php');

// PAGINATION
if(isset($_GET['page']) && !empty($_GET['page'])){
    $currentPage = (int) strip_tags($_GET['page']);
}else{
    $currentPage = 1;
}
$sql = "SELECT COUNT(*) AS nb_articles FROM `etudiants`; " ;
$query = $db->prepare($sql);
$query->execute();
$results = $query->fetch();

$nbArticles = (int) $results['nb_articles'];

$parPage = 6;

$pages = ceil($nbArticles / $parPage);

$premier = ($currentPage * $parPage) - $parPage;

// SELECTIONNER LES ETUDIANTS 
$sql = "SELECT * , AVG(note) moyenne FROM `etudiants` INNER JOIN `examens` ON `etudiants`.`id_etudiant` = `examens`.`id_etudiant` GROUP BY `etudiants`.`id_etudiant` LIMIT :premier, :parpage ;";
// on prépare la requête
$query = $db->prepare($sql);
// on exécute la requête

$query->bindValue(':premier', $premier, PDO::PARAM_INT);
$query->bindValue(':parpage', $parPage, PDO::PARAM_INT);

$query->execute();
// on récupere les données (fetch ou fetchAll)
$result = $query->fetchAll();
// var_dump($result)



// INPUT SEARCH
if (isset($_GET['q']) and !empty($_GET['q'])) {
    $q = htmlspecialchars($_GET['q']);
    $articles = $db->query('SELECT * , AVG(note) moyenne FROM etudiants INNER JOIN examens ON etudiants.id_etudiant = examens.id_etudiant WHERE nom LIKE "%' . $q . '%" OR prenom LIKE "%' . $q . '%" GROUP BY etudiants.id_etudiant');
    $result = $articles->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>Gestion</title>
</head>

<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <?php
                if (!empty($_SESSION['erreur'])) {
                    echo '<div class="alert alert-danger" role="alert">
                        ' . $_SESSION['erreur'] . ' </div>';
                    $_SESSION['erreur'] = "";
                }
                ?>
                <?php
                if (!empty($_SESSION['message'])) {
                    echo '<div class="alert alert-success" role="alert">
                        ' . $_SESSION['message'] . ' </div>';
                    $_SESSION['message'] = "";
                }
                ?>

                <h1>Liste des éleves</h1>

                <!-- input search -->
                <form method="$_GET">
                    <input type="search" name="q" placeholder="Recherche...">
                    <input type="submit" value="Valider">
                </form>

                <table class="table">
                    <thead>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Moyenne</th>
                        <th>Matiere</th>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($result as $etudiant) { ?>
                            <tr>
                                <td><?= $etudiant['prenom'] ?></td>
                                <td><?= $etudiant['nom'] ?></td>
                                <td><?= $etudiant['moyenne'] ?></td>
                                <td><a href="matiere.php?id=<?= $etudiant['id_etudiant'] ?>">Voir</a> <a href="update.php?id=<?= $etudiant['id_etudiant'] ?>">Modifier</a> <a href="delete.php?id=<?= $etudiant['id_etudiant'] ?>">Supprimer</a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                            <a href="?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                        </li>
                        <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                            <a href="?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                        </li>
                    </ul>
                </nav>
            </section>
        </div>
    </main>
</body>

</html>