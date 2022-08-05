<?php
// on démarre une session
session_start();

// Est-ce que l'id existe et n'est pas vide dans l'url
if(isset($_GET['id']) && !empty($_GET['id'])){
    
    require_once('connect.php');

    $id = strip_tags($_GET['id']);    
    $req = "SELECT prenom,nom, examens.matiere, examens.note FROM etudiants INNER JOIN examens ON etudiants.id_etudiant = examens.id_etudiant WHERE etudiants.id_etudiant = :id" ;
    $request = $db->prepare($req);
    $request->bindValue(':id', $id, PDO::PARAM_INT);
    $request->execute();
    $ficheAll = $request->fetchAll();
    // var_dump($ficheAll);
}else{
    $_SESSION['erreur'] = "URL invalide";
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>détail étudiant</title>
</head>
<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <h1>Bulletin de notes</h1>
                <table class="table">
                    <h3>Eleve: <?= $ficheAll[0]['prenom']," ". $ficheAll[0]['nom']?></h3>
                    <thead>
                        <th>Matiere</th>
                        <th>Notes</th>
                    </thead>
                    <tbody>
                           <tr>
                               <?php foreach($ficheAll as $el){ ?>
                                    <td><?= $el['matiere'] ?></td>
                                    <td><?= $el['note'] ?></td>

                            </tr>
                            <?php 
                            } 
                            ?>
                    </tbody>
                </table>
                <a href="index.php">Retour</a>
            </section>
        </div>
    </main>
</body>
</html>