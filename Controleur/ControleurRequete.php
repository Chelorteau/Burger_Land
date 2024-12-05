<?php
// Démarrage de la session pour pouvoir utiliser les variables de session.
session_start();

// Inclusion des fichiers nécessaires.
require_once '../Modele/tbs_class.php'; // Bibliothèque TinyButStrong pour le template
require_once '../Modele/connexionBD.php'; // Connexion à la base de données
require_once '../Modele/Requetes.php'; // Fichier contenant des requêtes spécifiques
require_once '../Modele/fonctions.php'; // Fichier contenant des fonctions utilisées dans le script

// Initialisation de TinyButStrong pour la gestion des templates.
$tbs = new clsTinyButStrong;
$cible = $_SERVER["PHP_SELF"]; // L'URL actuelle du script en cours d'exécution.
$gabAcceuil = "../Vue/accueil.tpl.html"; // Chemin du template d'accueil.

// Chargement et affichage des burgers.
$reqburger = "SELECT * FROM burger"; // Requête SQL pour récupérer tous les burgers.
$gabMenu = "../Vue/menu2.tpl.html"; // Chemin du template de menu de burgers.

$requeteBurger = new RecupBugers($connexion, $tbs, $gabMenu, $reqburger);
$requeteBurger->executerAll(); // Exécution de la requête et affichage des résultats.

// Chargement et affichage des crudités.
$reqCrudite = "SELECT * FROM crudite"; // Requête SQL pour récupérer toutes les crudités.
$gabCrudite = "../Vue/crudites.tpl.html"; // Chemin du template de crudités.

$requeteCrudite = new RecupCrudite($connexion, $tbs, $gabCrudite, $reqCrudite);
$requeteCrudite->executerAll(); // Exécution de la requête et affichage des résultats.

// Chargement et affichage des sauces.
$reqSauce = "SELECT * FROM sauce"; // Requête SQL pour récupérer toutes les sauces.
$gabSauce = "../Vue/sauces.tpl.html"; // Chemin du template de sauces.

$requeteSauce = new RecupSauce($connexion, $tbs, $gabSauce, $reqSauce);
$requeteSauce->executerAll(); // Exécution de la requête et affichage des résultats.

// Chargement et affichage des boissons.
$reqBoisson = "SELECT * FROM boisson"; // Requête SQL pour récupérer toutes les boissons.
$gabBoisson = "../Vue/boisson.tpl.html"; // Chemin du template de boissons.

$requeteBoisson = new RecupBoisson($connexion, $tbs, $gabBoisson, $reqBoisson);
$requeteBoisson->executerAll(); // Exécution de la requête et affichage des résultats.


if (!isset($_GET["page"])) {
    // Si $_GET["page"] n'existe pas, redirige l'utilisateur vers l'accueil
    $tbs->LoadTemplate($gabAcceuil);
} else {
    // Si $_GET["page"] existe, commence à traiter les différentes pages
    switch ($_GET["page"]) {
        case "menu":
            // Affiche le menu des burgers
            $requeteBurger->afficher();
            break;
        case "crudite":
            // Affiche les choix de crudités pour un burger sélectionné
            if (isset($_POST["BURGER_ID"])) {
                // Si un burger est sélectionné, traite les choix de crudités
                $burgerIdUnique = $_POST["BURGER_ID"] . '_' . uniqid();
                $burgerID = $burgerIdUnique;

                // Initialise les tableaux de sélection des composants du burger dans la session
                if (!isset($_SESSION["BURGER_SELECTIONNER"])) {
                    $_SESSION["BURGER_SELECTIONNER"] = array();
                }

                // Ajoute le burger sélectionné à la session
                if (!in_array($burgerIdUnique, $_SESSION["BURGER_SELECTIONNER"])) {
                    $_SESSION["BURGER_SELECTIONNER"][] = $burgerIdUnique;
                    $_SESSION["CRUDITES_SELECTIONNER"][$burgerIdUnique] = array();
                    $_SESSION["SAUCES_SELECTIONNER"][$burgerIdUnique] = array();
                    $_SESSION["BOISSONS_SELECTIONNER"][$burgerIdUnique] = array();
                    $_SESSION["VALIDATION"][$burgerIdUnique] = False;
                }
            } else {
                // Si aucun burger n'est sélectionné, redirige vers l'accueil
                $tbs->LoadTemplate($gabAcceuil);
            }
            // Affiche les choix de crudités disponibles
            $requeteCrudite->afficher();
            break;
        case "sauce":
            // Affiche les choix de sauces pour un burger sélectionné
            if (isset($_POST["BURGER_ID"])) {
                $burgerIdUnique = $_POST["BURGER_ID"];
                $burgerID = $burgerIdUnique;
                if (!isset($_SESSION["CRUDITES_SELECTIONNER"][$burgerIdUnique])) {
                    $_SESSION["CRUDITES_SELECTIONNER"][$burgerIdUnique] = array();
                }

                // Initialise le tableau des crudites sélectionnées
                $cruditesSelectionnees = array();
                foreach ($_POST as $key => $value) {
                    if ($key !== "BURGER_ID" && $value === "on") {
                        $cruditesSelectionnees[] = intval($key);
                    }
                }

                // Ajoute les sauces sélectionnées à la session
                foreach ($requeteCrudite->getData() as $crudite) {
                    $cruditeID = $crudite['CRUDITE_ID'];
                    if (in_array($cruditeID, $cruditesSelectionnees) && !in_array($cruditeID, $_SESSION["CRUDITES_SELECTIONNER"][$burgerIdUnique])) {
                        $_SESSION["CRUDITES_SELECTIONNER"][$burgerIdUnique][] = $cruditeID;
                    }
                }
            }

            // Affiche les choix de sauces disponibles
            $requeteSauce->afficher();
            break;
        case "boisson":
            // Affiche les choix de boissons pour un burger sélectionné
            if (isset($_POST["BURGER_ID"])) {
                $burgerIdUnique = $_POST["BURGER_ID"];
                $burgerID = $burgerIdUnique;

                // Initialise le tableau des boissons sélectionnées
                if (!isset($_SESSION["SAUCES_SELECTIONNER"][$burgerIdUnique])) {
                    $_SESSION["SAUCES_SELECTIONNER"][$burgerIdUnique] = array();
                }

                // Parcourt les données POST pour récupérer les sauces sélectionnées
                $saucesSelectionnees = array();
                foreach ($_POST as $key => $value) {
                    if ($key !== "BURGER_ID" && $value === "on") {
                        $saucesSelectionnees[] = intval($key);
                    }
                }
                // Ajoute les sauces sélectionnées à la session
                foreach ($requeteSauce->getData() as $sauce) {
                    $sauceID = $sauce['SAUCE_ID'];
                    if (in_array($sauceID, $saucesSelectionnees) && !in_array($sauceID, $_SESSION["SAUCES_SELECTIONNER"][$burgerIdUnique])) {
                        $_SESSION["SAUCES_SELECTIONNER"][$burgerIdUnique][] = $sauceID;
                    }
                }
            }
            // Affiche les choix de boissons disponibles
            $requeteBoisson->afficher();
            break;
        case "traitementCommande":
            // Traite la commande du burger sélectionné
            if (isset($_POST["BURGER_ID"])) {
                $burgerIdUnique = $_POST["BURGER_ID"];

                // Initialise le tableau des boissons sélectionnées
                if (!isset($_SESSION["BOISSONS_SELECTIONNER"][$burgerIdUnique])) {
                    $_SESSION["BOISSONS_SELECTIONNER"][$burgerIdUnique] = array();
                }

                // Parcourt les données POST pour récupérer les boissons sélectionnées
                $boissonsSelectionnees = array();
                foreach ($_POST as $key => $value) {
                    if ($key !== "BURGER_ID" && $value === "on") {
                        $boissonsSelectionnees[] = intval($key);
                    }
                }

                // Ajoute les boissons sélectionnées à la session
                foreach ($requeteBoisson->getData() as $boisson) {
                    $boissonID = $boisson['BOISSON_ID'];
                    if (in_array($boissonID, $boissonsSelectionnees) && !in_array($boissonID, $_SESSION["BOISSONS_SELECTIONNER"][$burgerIdUnique])) {
                        $_SESSION["BOISSONS_SELECTIONNER"][$burgerIdUnique][] = $boissonID;
                    }
                }
                // Redirige vers la page du panier après le traitement de la commandes
                header("Location: ControleurRequete.php?page=panier&add=yes&burgerId={$burgerIdUnique}");
            }
        case "panier":
            // Affiche le contenu du panier
            $gabPanier = "../Vue/panier.tpl.html";

            // Si un burger a été ajouté au panier et l'utilisateur a validé sa commande
            if (isset($_GET["add"]) and isset($_GET["burgerId"])) {
                if ($_GET["add"] == "yes") {
                    $_SESSION["VALIDATION"][$_GET["burgerId"]] = True;
                }
            }

            // Récupère les sélections de l'utilisateur dans la session
            $burgersSelectionnes = $_SESSION["BURGER_SELECTIONNER"];
            $cruditesSelectionnees = $_SESSION["CRUDITES_SELECTIONNER"];
            $saucessSelectionnees = $_SESSION["SAUCES_SELECTIONNER"];
            $boissonsSelectionnees = $_SESSION["BOISSONS_SELECTIONNER"];

            // Initialise un tableau associatif pour stocker les détails des burgers dans le panier
            $tableauAssociatifBurger = array();

            // Parcourt les burgers sélectionnés pour les afficher dans le panier
            foreach ($burgersSelectionnes as $burger) {
                // Récupère les détails du burger
                $validation = $_SESSION["VALIDATION"][$burger];
                $prix_total = 0;
                $cruditesElementNom = "";
                $saucesElementNom = "";
                $boissonsElementNom = "";
                $burgerIdPost = $burger;
                $idBurger = explode("_", $burger)[0];
                $burgerElement = getBurgerById($connexion, $idBurger);
                $prix_total += $burgerElement["BURGER_PRIX"];
                $idsCrudite = recupIdSessionByBurgersId("CRUDITES_SELECTIONNER", $burger);
                $idsSauce = recupIdSessionByBurgersId("SAUCES_SELECTIONNER", $burger);
                $idsBoisson = recupIdSessionByBurgersId("BOISSONS_SELECTIONNER", $burger);

                // Parcourt les crudités sélectionnées pour le burger
                foreach ($idsCrudite as $idCrudite) {
                    $cruditeElement = getCruditeById($connexion, $idCrudite)["CRUDITE_NOM"];
                    $prix_total += getCruditeById($connexion, $idCrudite)["CRUDITE_PRIX"];
                    $cruditesElementNom .= $cruditeElement . ', ';
                }

                // Parcourt les sauces sélectionnées pour le burger
                foreach ($idsSauce as $idSauce) {
                    $sauceElement = getSauceById($connexion, $idSauce)["SAUCE_NOM"];
                    $saucesElementNom .= $sauceElement . ', ';
                }

                // Parcourt les boissons sélectionnées pour le burger
                foreach ($idsBoisson as $idBoisson) {
                    $boissonElement = getBoissonById($connexion, $idBoisson)["BOISSON_NOM"];
                    $prix_total += getBoissonById($connexion, $idBoisson)["BOISSON_PRIX"];
                    $boissonsElementNom .= $boissonElement . ', ';
                }

                // Supprime la virgule en trop à la fin de chaque liste
                $cruditesElementNom = rtrim($cruditesElementNom, ', ');
                $saucesElementNom = rtrim($saucesElementNom, ', ');
                $boissonsElementNom = rtrim($boissonsElementNom, ', ');

                // Vérifie si la commande est validée
                if ($validation) {
                    $tableauAssociatifBurger[] = array(
                        'burgerNom' => $burgerElement["BURGER_NOM"],
                        'burgerImg' => $burgerElement["BURGER_IMG"],
                        'crudites' => $cruditesElementNom,
                        'sauces' => $saucesElementNom,
                        'boissons' => $boissonsElementNom,
                        'prix' => $prix_total,
                        'id' => $burgerIdPost
                    );
                }
            }

            // Calcule le prix final du panier
            $prixFinal = 0.0;
            foreach ($tableauAssociatifBurger as $burger) {
                $prixFinal += $burger['prix'];
            }
            $_SESSION['tableau_burger'] = $tableauAssociatifBurger;
            $_SESSION['prix_final'] = $prixFinal;

            $tbs->LoadTemplate($gabPanier);

            $tbs->MergeBlock('panier', $tableauAssociatifBurger);
            break;
        case "supprimer_burger":
            // Supprime un burger du panier
            if (isset($_POST['burgerId'])) {
                $burgerId = $_POST['burgerId'];
                if (in_array($burgerId, $_SESSION['BURGER_SELECTIONNER'])) {
                    $index = array_search($burgerId, $_SESSION['BURGER_SELECTIONNER']);

                    // Supprime les éléments du burger sélectionné de la session
                    unset($_SESSION['BURGER_SELECTIONNER'][$index]);
                    unset($_SESSION['CRUDITES_SELECTIONNER'][$index]);
                    unset($_SESSION['SAUCES_SELECTIONNER'][$index]);
                    unset($_SESSION['BOISSONS_SELECTIONNER'][$index]);

                    // Redirige vers la page du panier
                    header('Location: ControleurRequete.php?page=panier');
                    exit();
                }
            }
            break;
        case "validation_panier":

            // Affiche la page de validation du panier
            $gabValidation = "../Vue/validation.tpl.html";

            // Vérifie si le panier n'est pas vide
            if (!empty($_SESSION["BURGER_SELECTIONNER"])) {
                $tbs->LoadTemplate($gabValidation);
            } else {
                // Redirige vers la page du panier si le panier est vide
                header('Location: ControleurRequete.php?page=panier');
                exit();
            }
            break;
        case  "traitement_validation_panier":
            // Traite la validation du panier
            if (isset($_POST['nom'])) {
                // Génère un code de commande aléatoire
                $code = mt_rand();
                // Récupère les données de l'utilisateur
                $nom = $_POST["nom"];
                $prenom = $_POST["prenom"];
                $num = $_POST["numero"];
                $adresse_mail = $_POST["adresseMail"];
                $adresse = $_POST["adresseLivraison"];
                $mode_de_paiement = $_POST["modePaiement"];

                // Ajoute l'utilisateur dans la base de données
                addUtilisateur($connexion, $nom, $prenom, $num, $adresse, $adresse_mail, $code, $mode_de_paiement);
                $user = getUserByCode($connexion, $code);

                // Ajoute la commande dans la base de données
                $admin_id = 1;
                $commande_prix = $_SESSION['prix_final'];
                addCommande($connexion, $user["UTI_KEY"], $admin_id, $commande_prix);
                $commande = getCommandeByUserId($connexion, $user["UTI_ID"]);

                // Ajoute les lignes de commande dans la base de données
                if (isset($_SESSION['tableau_burger'])) {
                    $reqCrudName = "SELECT CRUDITE_ID FROM crudite WHERE CRUDITE_NOM = :nom";
                    $reqSauceName = "SELECT SAUCE_ID FROM sauce WHERE SAUCE_NOM = :nom";
                    $reqBoissonName = "SELECT BOISSON_ID FROM boisson WHERE BOISSON_NOM = :nom";

                    foreach ($_SESSION['tableau_burger'] as $burger) {
                        $idBurgerEncode = $burger['id'];
                        $idBurgerDecode = explode("_", $idBurgerEncode)[0];
                        $id_lgn_cmd = addLigneCommande($connexion, $commande["CMD_ID"], intval($idBurgerDecode), $burger['prix']);
                        $cruditesArray = explode(", ", $burger["crudites"]);
                        $saucesArray = explode(", ", $burger["sauces"]);
                        $boissonsArray = explode(", ", $burger["boissons"]);


                        // Ajoute les crudités sélectionnées à la ligne de commande
                        foreach ($cruditesArray as $crudite) {
                            if (!empty($crudite)) {
                                $cruditeIdArray = getReqIdByName($connexion, $reqCrudName, $crudite);
                                $cruditeId = $cruditeIdArray["CRUDITE_ID"];
                                addChoixCrudite($connexion, intval($id_lgn_cmd), $cruditeId);
                            }
                        }

                        // Ajoute les sauces sélectionnées à la ligne de commande
                        foreach ($saucesArray as $sauce) {
                            if (!empty($sauce)) {
                                $sauceIdArray = getReqIdByName($connexion, $reqSauceName, $sauce);
                                $sauceId = $sauceIdArray["SAUCE_ID"];
                                addChoixSauce($connexion, intval($id_lgn_cmd), $sauceId);
                            }
                        }

                        // Ajoute les boissons sélectionnées à la ligne de commande
                        foreach ($boissonsArray as $boisson) {
                            if (!empty($boisson)) {
                                $boissonIdArray = getReqIdByName($connexion, $reqBoissonName, $boisson);
                                $boissonId = $boissonIdArray["BOISSON_ID"];
                                addChoixBoisson($connexion, intval($id_lgn_cmd), $boissonId);
                            }
                        }
                    }
                }
                // Détruit la session
                session_destroy();
                // Redirige vers la page de confirmation
                header('Location: ControleurRequete.php?page=confirmation');
                exit();
            }
            break;
        case "confirmation":
            // Affiche la page de confirmation de commande
            $bagConfirmation = "../Vue/confirmation.tpl.html";
            $tbs->LoadTemplate($bagConfirmation);
            break;
        case "connexion_adm":
            $bagAdmin = "../Vue/admin.tpl.html";
            $bagConnexion_adm = "../Vue/connexion_admin.tpl.html";
            $error = "";
            $authenticated = false;
            $admins = getAllAdmin($connexion);

            // Vérifie les informations de connexion de l'administrateur
            foreach ($admins as $admin) {
                if (($_SESSION["CONNEXION_ADMIN"]["username"] == $admin['ADM_USERNAME']) and ($_SESSION["CONNEXION_ADMIN"]["password"]) == $admin['ADM_PASSWORD']) {
                    $authenticated = true;
                }
            }

            // Vérifie si l'administrateur est authentifié
            if (!$authenticated) {
                // Vérifie s'il y a une erreur de connexion
                if (isset($_GET["error"])) {
                    $error = "Pseudo ou mot de passe incorrect";
                }
                // Charge le template de connexion de l'administrateur
                $prix = filter_input(INPUT_POST, "prix");
                $tbs->LoadTemplate($bagConnexion_adm);
            } else {
                // Charge le template de l'interface administrateur
                $tbs->LoadTemplate($bagAdmin);
            }
            break;
        case "admin":
            // Affiche l'interface administrateur
            $bagAdmin = "../Vue/admin.tpl.html";

            // Traite la connexion de l'administrateur
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["username"])) {
                $usernamePost = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $passwordPost = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $admins = getAllAdmin($connexion);

                $authenticated = false;

                foreach ($admins as $admin) {
                    if (hash('sha256', $passwordPost) === $admin['ADM_PASSWORD']) {
                        $authenticated = true;
                        $_SESSION["CONNEXION_ADMIN"]["username"] = $admin['ADM_USERNAME'];
                        $_SESSION["CONNEXION_ADMIN"]["password"] = $admin['ADM_PASSWORD'];
                    }
                }

                // Vérifie si l'administrateur est authentifié
                if ($authenticated) {
                    $tbs->LoadTemplate($bagAdmin);
                } else {
                    // Redirige vers la page de connexion avec une erreur
                    header('Location: ControleurRequete.php?page=connexion_adm&error=!passAdmin');
                    exit();
                }
            } else {
                // Vérifie si l'administrateur est connecté
                if (!isset($_SESSION["CONNEXION_ADMIN"])) {
                    // Charge le template de connexion de l'administrateur
                    $tbs->LoadTemplate("../Vue/connexion_admin.tpl.html");
                } else {
                    $authenticated = false;
                    $admins = getAllAdmin($connexion);

                    foreach ($admins as $admin) {
                        if (($_SESSION["CONNEXION_ADMIN"]["username"] == $admin['ADM_USERNAME']) and ($_SESSION["CONNEXION_ADMIN"]["password"]) == $admin['ADM_PASSWORD']) {
                            $authenticated = true;
                        }
                    }
                    // Vérifie si l'administrateur est authentifié
                    if ($authenticated) {
                        // Vérifie s'il y a une page spécifique demandée
                        if (!isset($_GET["pageAdmin"]) and $_GET["page"] != "admin") {
                            // Charge le template de l'interface administrateur
                            $tbs->LoadTemplate($bagAdmin);
                        } else {
                            // Traite les différentes pages demandées dans l'interface administrateur
                            switch ($_GET["pageAdmin"]) {
                                case "commandes":
                                    // Affiche la liste des commandes non livrées
                                    $bagCommandes = "../Vue/commande.tpl.html";
                                    $tbs->LoadTemplate($bagCommandes);
                                    $idsCommandeNonLivre = getUnDeliveredCommandeIds($connexion, False);
                                    $tableauAssociatifCommande = [];

                                    // Parcourt les commandes non livrées pour les afficher
                                    foreach ($idsCommandeNonLivre as $commandeId) {
                                        $prixTotal = 0;
                                        $commandeDetails = getCommandeDetailsByCommandeId($connexion, $commandeId);

                                        // Temporaire pour stocker les détails des lignes de commande
                                        $lignesCommandeDetails = [];

                                        // Parcourt les lignes de commande pour récupérer les détails
                                        foreach ($commandeDetails as $ligne) {
                                            $ligneDetails = getLigneCommandeDetailsById($connexion, $ligne["LGN_CMD_ID"]);
                                            $prixTotal += $ligneDetails["LGN_CMD_PRIX"];
                                            $lignesCommandeDetails[] = $ligneDetails;
                                        }

                                        // Ajoute les détails de la commande au tableau associatif
                                        $tableauAssociatifCommande[] = [
                                            'utilisateurId' => $commandeDetails[0]["UTI_ID"],
                                            'utilisateurNom' => $commandeDetails[0]["UTI_NOM"],
                                            'utilisateurPrenom' => $commandeDetails[0]["UTI_PRENOM"],
                                            'utilisateurAdresse' => $commandeDetails[0]["UTI_ADR"],
                                            'utilisateurNum' => $commandeDetails[0]["UTI_NUM"],
                                            'utilisateurMdp' => $commandeDetails[0]["UTI_MODE_PAYMENT"],
                                            'commandeDate' => $commandeDetails[0]["CMD_DATE_FORMATTED"],
                                            'commandeId' => $commandeDetails[0]["CMD_ID"],
                                            'lignesCommande' => $lignesCommandeDetails,
                                            'prixTotal' => $prixTotal,
                                        ];
                                    }
                                    $tbs->MergeBlock('commande', $tableauAssociatifCommande);
                                    break;
                                case "commandesLivre":

                                    $bagCommandesLivre = "../Vue/commandesLivre.tpl.html";
                                    $tbs->LoadTemplate($bagCommandesLivre);
                                    $idsCommandeNonLivre = getUnDeliveredCommandeIds($connexion, True);
                                    $tableauAssociatifCommande = [];

                                    foreach ($idsCommandeNonLivre as $commandeId) {
                                        $prixTotal = 0;
                                        $commandeDetails = getCommandeDetailsByCommandeId($connexion, $commandeId);

                                        // Temporaire pour stocker les détails des lignes de commande
                                        $lignesCommandeDetails = [];
                                        foreach ($commandeDetails as $ligne) {
                                            $ligneDetails = getLigneCommandeDetailsById($connexion, $ligne["LGN_CMD_ID"]);
                                            $prixTotal += $ligneDetails["LGN_CMD_PRIX"];
                                            $lignesCommandeDetails[] = $ligneDetails;
                                        }
                                        // Ajouter les détails de la commande avec les lignes de commande associées
                                        $tableauAssociatifCommande[] = [
                                            'utilisateurId' => $commandeDetails[0]["UTI_ID"],
                                            'utilisateurNom' => $commandeDetails[0]["UTI_NOM"],
                                            'utilisateurPrenom' => $commandeDetails[0]["UTI_PRENOM"],
                                            'utilisateurAdresse' => $commandeDetails[0]["UTI_ADR"],
                                            'utilisateurNum' => $commandeDetails[0]["UTI_NUM"],
                                            'utilisateurMdp' => $commandeDetails[0]["UTI_MODE_PAYMENT"],
                                            'commandeDate' => $commandeDetails[0]["CMD_DATE_FORMATTED"],
                                            'commandeId' => $commandeDetails[0]["CMD_ID"],
                                            'lignesCommande' => $lignesCommandeDetails,
                                            'prixTotal' => $prixTotal,
                                        ];
                                    }
                                    $tbs->MergeBlock('commande', $tableauAssociatifCommande);
                                    break;
                                case "confirmerLivre":
                                    // Livre une commande
                                    if (isset($_GET["cmd_id"]) && $_GET["cmd_id"] > 0) {
                                        $cmd_id = intval($_GET["cmd_id"]);
                                        marquerCommandeCommeLivree($connexion, $cmd_id);
                                    }
                                    // Redirige vers la page des livraisons avec succès
                                    header('Location: ControleurRequete.php?page=admin&pageAdmin=commandes');
                                default:
                                    // Redirige vers l'accueil si la page demandée n'existe pas
                                    $bag404 = "../Vue/404.tpl.html";
                                    $tbs->LoadTemplate($bag404);
                            }
                        }
                    } else {
                        header('Location: ControleurRequete.php?page=connexion_adm&error=!passAdmin');
                        exit();
                    }
                }
            }
            break;
        case "decoAdmin":
            unset($_SESSION["CONNEXION_ADMIN"]);
            header('Location: ControleurRequete.php');
            exit();
        default:
            $bag404 = "../Vue/404.tpl.html";
            $tbs->LoadTemplate($bag404);
    }
}

$tbs->Show();
