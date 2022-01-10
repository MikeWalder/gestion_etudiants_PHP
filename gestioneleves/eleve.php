<?php
require 'header.php';
?>

<h1 class="pb-5">Gestion des étudiants</h1>

<div class="container">
    <div class="row">
        <div class="col-md-1">
        </div>

        <div class="col-md-10 bg-light shadow rounded opacitor">

            <h2 class="pb-5 pt-3">Ajouter un étudiant</h2>

            <form action="" method="POST">
                <div class="form-group pl-5">
                    <label for="nom" class="font-weight-bold">Nom : </label>
                    <input type="text" class="form-control shadow col-md-10" name="nom">
                </div>
                <div class="form-group pl-5">
                    <label for="prenom" class="font-weight-bold">Prénom : </label>
                    <input type="text" class="form-control shadow col-md-10" name="prenom">
                </div>
                <div class="form-group pl-5">
                    <label for="notes" class="font-weight-bold">Notes : </label>
                    <input type="hidden" name="confirm_form">
                    <input type="text" class="form-control shadow col-md-10" name="notes" placeholder="Exemple : 12,13.5,14.5,8,7.5">
                </div>
                <div class="text-center pl-5">
                    <button type="submit" class="btn btn-success shadow btn-lg mt-5 mb-3 col-md-3 text-center">Ajouter</button>
                </div>
            </form>

            <?php
            //Traitement des données du formulaire
            if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['notes'])) {
                $nom = htmlspecialchars($_POST['nom']);
                $prenom = htmlspecialchars($_POST['prenom']);
                $tab_notes = explode(',', $_POST['notes']);

                //On récupère chaque élément de $tab_notes (au format string) et on le convertit en int 
                for ($i = 0; $i < count($tab_notes); $i++) {
                    $tab[$i] = (float)$tab_notes[$i];
                }

                //Calcul de la moyenne du tableau $tab et arrondi au 10ème
                $moyenne = array_sum($tab) / count($tab);
                $moyenne = round($moyenne, 1);

                //Ajout des informations dans la table 'classse_eleves'
                $request = $pdo->prepare('INSERT INTO classse_eleves (nom, prenom, moyenne) VALUES (:nom, :prenom, :moyenne)');
                $request->execute(array(
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'moyenne' => $moyenne
                ));

            ?>
                <div class="alert alert-success text-center">Enregistré avec succès !</div>
            <?php
            } else if (empty($_POST['nom']) && empty($_POST['prenom']) && empty($_POST['notes']) && isset($_POST['confirm_form'])) {
            ?>
                <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                    Tous les champs sont vides !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
            } else if ((empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['notes'])) && isset($_POST['confirm_form'])) {
            ?>
                <div class="alert alert-warning text-center">Merci de remplir tous les champs</div>
            <?php
            }
            ?>
        </div>

        <div class="col-md-1">
        </div>
    </div>
</div>

<div class="container">

    <div class="row">

        <div class="col-md-1">
        </div>

        <div class="col-md-10 mt-5 bg-light shadow rounded opacitor">
            <h2 class="pb-5 pt-3">Récapitulatif des étudiants ajoutés</h2>

            <table class="table table-light table-bordered mt-3 p-3">
                <thead class="thead-light text-center">
                    <tr>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">Moyenne</th>
                        <th scope="col">Modifier</th>
                        <th scope="col">Supprimer</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
                    //On lance une requête pour afficher les champs nom, prénom et moyenne dans un tableau
                    $answer = $pdo->query("SELECT id, nom, prenom, moyenne FROM classse_eleves ORDER BY nom ASC");
                    while ($q = $answer->fetch()) {
                    ?>
                        <tr>
                            <td class="table-success"><?= $q['nom'] ?></td>
                            <td class="table-info"><?= $q['prenom'] ?></td>
                            <td class="table-primary font-weight-bold"><?= $q['moyenne'] ?></td>
                            <td class="font-weight-bold table-warning"><i class="fas fa-redo-alt redo_color">
                                    <a onclick="return confirm('Voulez-vous modifier ce champ ?')" href="eleve.php?modify=<?= $q['id'] ?>" class="a_modify"></i>Modifier</a></td>
                            <td class="font-weight-bold table-danger"><i class="fas fa-trash-alt trash_color">
                                    <a onclick="return confirm('Voulez-vous supprimer ce champ ?')" href="eleve.php?delete=<?= $q['id'] ?>" class="a_delete"></i>Supprimer</a></td>
                        </tr>
                    <?php
                    }

                    if (isset($_GET['delete'])) {
                        $del = (int)$_GET['delete'];
                        var_dump($del);
                        $suppr = $pdo->prepare("DELETE FROM classse_eleves WHERE id = :id");
                        $suppr->execute(array(
                            'id' => $del
                        ));
                    ?>
                        <div class="alert alert-success text-center">Le champ a bien été supprimé !</div>
                    <?php
                    }
                    ?>

                    <?php
                    if (isset($_GET['modify'])) {
                    ?>
                        <form action="" method="POST" class="pb-3 pt-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" class="form-control shadow" name="nom" value="">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control shadow" name="prenom" value="">
                                </div>
                                <div class="col-md-3">
                                    <input type="hidden" name="confirmation">
                                    <input type="text" class="form-control shadow" name="moyenne" value="">
                                </div>
                                <button type="submit" class="btn btn-warning shadow">Modifier</button>
                            </div>
                        </form>
                        <?php
                        if (isset($_POST['confirmation'])) {

                            $modif = (int)$_GET['modify'];
                            $nom = htmlspecialchars($_POST['nom']);
                            $prenom = htmlspecialchars($_POST['prenom']);
                            $moyenne = (float)$_POST['moyenne'];

                            $query = $pdo->prepare("UPDATE classse_eleves SET nom = :nom, prenom = :prenom, moyenne = :moyenne WHERE id = :id");
                            $m = $query->execute(array(
                                'id' => $modif,
                                'nom' => $nom,
                                'prenom' => $prenom,
                                'moyenne' => $moyenne
                            ));
                        ?>
                            <div class="alert alert-info text-center">Le champ a bien été modifié !</div>
                    <?php
                        } else {
                        }
                    }
                    ?>
                </tbody>
            </table>

        </div>

        <div class="col-md-1">
        </div>

    </div>

</div>

<?php
require 'footer.php';
?>