<?php
require_once "connect.php";

// on démarre une session
session_start();

if($_POST){
    if(isset($_POST['id']) && !empty($_POST['id'])
    && isset($_POST['nom']) && !empty($_POST['nom'])
    && isset($_POST['prenom']) && !empty($_POST['prenom'])){
        
        // on inclut la connexion a la base 
        require_once('connect.php');

        // on nettoie les données envoyer
        $id = strip_tags($_POST['id']);
        $nom = strip_tags($_POST['nom']);
        $prenom = strip_tags($_POST['prenom']);

        $sql = 'UPDATE `etudiants` SET `nom`= :nom, `prenom`= :prenom WHERE `id_etudiant`=:id;';

        $query = $db->prepare($sql);

        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':nom', $nom, PDO::PARAM_STR);
        $query->bindValue(':prenom', $prenom, PDO::PARAM_STR);

        $query->execute();

        $_SESSION['message'] = "Eleve modifié";
        require_once('close.php');
        header('Location: index.php');
    }else{
        $_SESSION['erreur'] = "le formulaire est incomplet";
    }
}

// Est-ce que l'id existe et n'est pas vide dans l'url
if(isset($_GET['id']) && !empty($_GET['id'])){
    
    $id = strip_tags($_GET['id']);    
    $req = "SELECT * FROM etudiants  WHERE etudiants.id_etudiant = :id" ;
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
    <title>Modifier un éleve</title>
</head>
<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <?php 
                    if(!empty($_SESSION['erreur'])){
                        echo '<div class="alert alert-danger" role="alert">
                        '.$_SESSION['erreur'].' </div>';
                        $_SESSION['erreur'] = "";
                    }
                ?>
                <?php 
                    if(!empty($_SESSION['message'])){
                        echo '<div class="alert alert-success" role="alert">
                        '.$_SESSION['message'].' </div>';
                        $_SESSION['message'] = "";
                    }
                ?>
                <h1>Modifier un éleve</h1>
                <form  method="post">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" class="form-control" value="<?= $ficheAll[0]['nom']?>">
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" value="<?= $ficheAll[0]['prenom']?>">
                    </div>
                    <input type="hidden" name="id" value="<?= $ficheAll[0]['id_etudiant']?>">
                    <button class="btn btn-primary">Modifié</button>
                </form>
            </section>
        </div>
    </main>
</body>
</html>