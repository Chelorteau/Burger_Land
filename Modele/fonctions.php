<?php

/**
 * Récupère les détails d'un burger par son ID.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $burgerId ID du burger à récupérer.
 * @return array|null Détails du burger ou null si non trouvé.
 */
function getBurgerById($pdo, $burgerId)
{
    $reqRecupBurgerById = "SELECT * FROM burger WHERE BURGER_ID = :burger_id";
    $res = $pdo->prepare($reqRecupBurgerById);
    $res->bindParam(':burger_id', $burgerId, PDO::PARAM_INT);
    $res->execute();
    return $res->fetch(PDO::FETCH_ASSOC);
}

/**
 * Récupère les détails d'une crudité par son ID.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $cruditeId ID de la crudité à récupérer.
 * @return array|null Détails de la crudité ou null si non trouvée.
 */
function getCruditeById($pdo, $cruditeId)
{
    $reqRecupCruditeById = "SELECT * FROM crudite WHERE CRUDITE_ID = :crudite_id";
    $res = $pdo->prepare($reqRecupCruditeById);
    $res->bindParam(':crudite_id', $cruditeId, PDO::PARAM_INT);
    $res->execute();
    return $res->fetch(PDO::FETCH_ASSOC);
}

/**
 * Récupère les détails d'une sauce par son ID.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $saucerId ID de la sauce à récupérer.
 * @return array|null Détails de la sauce ou null si non trouvée.
 */
function getSauceById($pdo, $saucerId)
{
    $reqRecupSauceById = "SELECT * FROM sauce WHERE SAUCE_ID = :sauce_id";
    $res = $pdo->prepare($reqRecupSauceById);
    $res->bindParam(':sauce_id', $saucerId, PDO::PARAM_INT);
    $res->execute();
    return $res->fetch(PDO::FETCH_ASSOC);
}

/**
 * Récupère les détails d'une boisson par son ID.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $boissonId ID de la boisson à récupérer.
 * @return array|null Détails de la boisson ou null si non trouvée.
 */
function getBoissonById($pdo, $boissonId)
{
    $reqRecupBoissonById = "SELECT * FROM boisson WHERE BOISSON_ID = :boisson_id";
    $res = $pdo->prepare($reqRecupBoissonById);
    $res->bindParam(':boisson_id', $boissonId, PDO::PARAM_INT);
    $res->execute();
    return $res->fetch(PDO::FETCH_ASSOC);
}

/**
 * Récupère les détails d'un utilisateur par son code unique.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $code Code unique de l'utilisateur.
 * @return array|null Détails de l'utilisateur ou null si non trouvé.
 */
function getUserByCode($pdo, $code)
{
    $reqRecupUserByCode = "SELECT * FROM utilisateur WHERE UTI_KEY = :code";
    $res = $pdo->prepare($reqRecupUserByCode);
    $res->bindParam(':code', $code, PDO::PARAM_INT);
    $res->execute();
    return $res->fetch(PDO::FETCH_ASSOC);
}

/**
 * Récupère les détails d'une commande par l'ID de l'utilisateur.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $userId ID de l'utilisateur dont récupérer la commande.
 * @return array|null Détails de la commande ou null si non trouvée.
 */
function getCommandeByUserId($pdo, $userId)
{
    $reqRecupCommandeByUserId = "SELECT * FROM commande WHERE UTI_ID = :user_id";
    $res = $pdo->prepare($reqRecupCommandeByUserId);
    $res->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $res->execute();
    return $res->fetch(PDO::FETCH_ASSOC);
}


/**
 * Exécute une requête SQL préparée avec un paramètre 'nom' et retourne le premier résultat.
 * @param PDO $pdo Connexion à la base de données.
 * @param string $req La requête SQL préparée à exécuter.
 * @param string $nom Le nom à chercher dans la requête.
 * @return array|null Le résultat de la requête ou null si aucun résultat trouvé.
 */
function getReqIdByName($pdo, $req, $nom)
{
    $res = $pdo->prepare($req);
    $res->bindParam(':nom', $nom, PDO::PARAM_STR);
    $res->execute();

    return $res->fetch(PDO::FETCH_ASSOC);
}

/**
 * Récupère tous les administrateurs de la base de données.
 * @param PDO $pdo Connexion à la base de données.
 * @return array Un tableau contenant tous les administrateurs.
 */
function getAllAdmin($pdo)
{
    $req = "SELECT * FROM administrateur";
    $res = $pdo->prepare($req);
    $res->execute();
    return $res->fetchAll();
}

/**
 * Récupère une liste d'ID à partir d'une session pour des burgers spécifiques.
 * @param string $id_element Nom de la clé de session où les ID sont stockés.
 * @param int $burger_id ID du burger concerné.
 * @return array Un tableau des IDs récupérés.
 */
function recupIdSessionByBurgersId($id_element, $burger_id)
{
    $ids = array();
    if (isset($_SESSION[$id_element][$burger_id])) {
        foreach ($_SESSION[$id_element][$burger_id] as $crudite_id) {
            $ids[] = $crudite_id;
        }
    }
    return $ids;
}


/**
 * Ajoute un nouvel utilisateur à la base de données.
 * @param PDO $pdo Connexion à la base de données.
 * @param string $nom Nom de l'utilisateur.
 * @param string $prenom Prénom de l'utilisateur.
 * @param string $numero Numéro de téléphone de l'utilisateur.
 * @param string $adresse Adresse de l'utilisateur.
 * @param string $email Email de l'utilisateur.
 * @param int $key Clé unique de l'utilisateur.
 * @param string $modePayment Mode de paiement de l'utilisateur.
 * @return bool True si l'utilisateur est ajouté avec succès, False sinon.
 */
function addUtilisateur($pdo, $nom, $prenom, $numero, $adresse, $email, $key, $modePayment)
{
    $reqAjoutUtilisateur = "INSERT INTO utilisateur (UTI_NOM, UTI_PRENOM, UTI_NUM, UTI_ADR, UTI_EMAIL, UTI_KEY, UTI_MODE_PAYMENT) VALUES (:nom, :prenom, :numero, :adresse, :email, :key, :mode_payment)";
    $stmt = $pdo->prepare($reqAjoutUtilisateur);
    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $stmt->bindParam(':numero', $numero, PDO::PARAM_STR);
    $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':key', $key, PDO::PARAM_INT);
    $stmt->bindParam(':mode_payment', $modePayment, PDO::PARAM_STR);

    return $stmt->execute();
}

/**
 * Ajoute une nouvelle commande à la base de données en utilisant l'ID utilisateur récupéré par son code unique.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $codeUser Code unique de l'utilisateur.
 * @param int $admin_id ID de l'administrateur qui gère la commande.
 * @param string $cmd_prix Prix de la commande.
 * @return bool True si la commande est ajoutée avec succès, False sinon.
 */
function addCommande($pdo, $codeUser, $admin_id, $cmd_prix)
{
    $user = getUserByCode($pdo, $codeUser);
    if (!$user) {
        return false;
    }
    $user_id = $user['UTI_ID'];

    $cmd_date = date("Y-m-d H:i:s");
    $cmd_livre = false;

    $reqAddCommande = "INSERT INTO commande (UTI_ID, ADM_ID, CMD_DATE, CMD_PRIX, CMD_LIVRE) VALUES (:user_id, :admin_id, :cmd_date, :cmd_prix, :cmd_livre)";
    $stmt = $pdo->prepare($reqAddCommande);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->bindParam(':cmd_date', $cmd_date);
    $stmt->bindParam(':cmd_prix', $cmd_prix, PDO::PARAM_STR);
    $stmt->bindParam(':cmd_livre', $cmd_livre, PDO::PARAM_BOOL);

    return $stmt->execute();
}


/**
 * Ajoute une ligne de commande pour un burger spécifique avec son prix.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $cmd_id ID de la commande à laquelle ajouter la ligne.
 * @param int $burger_id ID du burger concerné.
 * @param string $lgn_cmd_prix Prix de la ligne de commande.
 * @return int|false L'ID de la nouvelle ligne de commande ajoutée ou false en cas d'échec.
 */
function addLigneCommande($pdo, $cmd_id, $burger_id, $lgn_cmd_prix)
{
    $reqAddLigneCommande = "INSERT INTO lignecommande (CMD_ID, BURGER_ID, LGN_CMD_PRIX) VALUES (:cmd_id, :burger_id, :lgn_cmd_prix)";
    $stmt = $pdo->prepare($reqAddLigneCommande);

    $stmt->bindParam(':cmd_id', $cmd_id, PDO::PARAM_INT);
    $stmt->bindParam(':burger_id', $burger_id, PDO::PARAM_INT);
    $stmt->bindParam(':lgn_cmd_prix', $lgn_cmd_prix, PDO::PARAM_STR);

    $success = $stmt->execute();
    if ($success) {
        return $pdo->lastInsertId();
    } else {
        return false;
    }
}

/**
 * Ajoute un choix de crudité pour une ligne de commande spécifique.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $lgn_cmd_id ID de la ligne de commande concernée.
 * @param int $crudite_id ID de la crudité choisie.
 * @return bool True si le choix est ajouté avec succès, False sinon.
 */
function addChoixCrudite($pdo, $lgn_cmd_id, $crudite_id)
{
    $reqAddChoixCrudite = "INSERT INTO `choixcrudites` (`LGN_CMD_ID`, `CRUDITE_ID`) VALUES (:lgn_cmd_id, :crudite_id)";
    $res = $pdo->prepare($reqAddChoixCrudite);

    $res->bindParam(':lgn_cmd_id', $lgn_cmd_id, PDO::PARAM_INT);
    $res->bindParam(':crudite_id', $crudite_id, PDO::PARAM_INT);

    return $res->execute();
}

/**
 * Ajoute un choix de sauce pour une ligne de commande spécifique.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $lgn_cmd_id ID de la ligne de commande concernée.
 * @param int $sauce_id ID de la sauce choisie.
 * @return bool True si le choix est ajouté avec succès, False sinon.
 */
function addChoixSauce($pdo, $lgn_cmd_id, $sauce_id)
{
    $reqAddChoixSauce = "INSERT INTO `choixsauces` (`LGN_CMD_ID`, `SAUCE_ID`) VALUES (:lgn_cmd_id, :sauce_id)";
    $res = $pdo->prepare($reqAddChoixSauce);

    $res->bindParam(':lgn_cmd_id', $lgn_cmd_id, PDO::PARAM_INT);
    $res->bindParam(':sauce_id', $sauce_id, PDO::PARAM_INT);

    return $res->execute();
}

/**
 * Ajoute un choix de boisson pour une ligne de commande spécifique.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $lgn_cmd_id ID de la ligne de commande concernée.
 * @param int $boisson_id ID de la boisson choisie.
 * @return bool True si le choix est ajouté avec succès, False sinon.
 */
function addChoixBoisson($pdo, $lgn_cmd_id, $boisson_id)
{
    $reqAddChoixBoisson = "INSERT INTO `choixboissons` (`LGN_CMD_ID`, `BOISSON_ID`) VALUES (:lgn_cmd_id, :boisson_id)";
    $res = $pdo->prepare($reqAddChoixBoisson);

    $res->bindParam(':lgn_cmd_id', $lgn_cmd_id, PDO::PARAM_INT);
    $res->bindParam(':boisson_id', $boisson_id, PDO::PARAM_INT);

    return $res->execute();
}


/**
 * Récupère les détails complets d'une commande par son ID.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $commandeId ID de la commande à récupérer.
 * @return array|null Détails de la commande ou null si non trouvée.
 */
function getCommandeDetailsByCommandeId($pdo, $commandeId)
{
    $reqGetCommandeDetailsByCommandeId = "SELECT
            Commande.CMD_ID,
            Utilisateur.UTI_ID,
            Utilisateur.UTI_NOM,
            Utilisateur.UTI_PRENOM,
            Utilisateur.UTI_NUM,
            Utilisateur.UTI_ADR,
            Utilisateur.UTI_MODE_PAYMENT,
            LigneCommande.LGN_CMD_ID,
            DATE_FORMAT(Commande.CMD_DATE, '%d/%m/%Y %H:%i') AS CMD_DATE_FORMATTED
            FROM
            Commande
            INNER JOIN Utilisateur ON Commande.UTI_ID = Utilisateur.UTI_ID
            INNER JOIN LigneCommande ON Commande.CMD_ID = LigneCommande.CMD_ID
            WHERE Commande.CMD_ID = :commandeId
            ORDER BY Commande.CMD_DATE DESC;
            ";

    $res = $pdo->prepare($reqGetCommandeDetailsByCommandeId);
    $res->bindParam(':commandeId', $commandeId, PDO::PARAM_INT);

    $res->execute();
    return $res->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère les ID des commandes non livrées selon le statut spécifié.
 * @param PDO $pdo Connexion à la base de données.
 * @param bool $livre Statut de livraison des commandes à récupérer.
 * @return array Liste des ID de commandes non livrées.
 */
function getUnDeliveredCommandeIds($pdo, $livre)
{
    $reqGetUnDeliveredCommandeIds = "SELECT CMD_ID FROM Commande WHERE CMD_LIVRE = :livre";

    $res = $pdo->prepare($reqGetUnDeliveredCommandeIds);
    $res->bindParam(':livre', $livre, PDO::PARAM_INT);
    $res->execute();

    return $res->fetchAll(PDO::FETCH_COLUMN, 0);
}

/**
 * Récupère les détails d'une ligne de commande par son ID, incluant les informations sur les produits sélectionnés.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $ligneCommandeId ID de la ligne de commande à détailler.
 * @return array|null Détails de la ligne de commande ou null si non trouvée.
 */
function getLigneCommandeDetailsById($pdo, $ligneCommandeId)
{
    $reqGetLigneCommandeDetailsById = "SELECT
            Burger.BURGER_NOM,
            GROUP_CONCAT(DISTINCT Crudite.CRUDITE_NOM) AS Crudites,
            GROUP_CONCAT(DISTINCT Sauce.SAUCE_NOM) AS Sauces,
            GROUP_CONCAT(DISTINCT Boisson.BOISSON_NOM) AS Boissons,
            LigneCommande.LGN_CMD_PRIX
            FROM
            LigneCommande
            LEFT JOIN Burger ON LigneCommande.BURGER_ID = Burger.BURGER_ID
            LEFT JOIN ChoixCrudites ON LigneCommande.LGN_CMD_ID = ChoixCrudites.LGN_CMD_ID
            LEFT JOIN Crudite ON ChoixCrudites.CRUDITE_ID = Crudite.CRUDITE_ID
            LEFT JOIN ChoixSauces ON LigneCommande.LGN_CMD_ID = ChoixSauces.LGN_CMD_ID
            LEFT JOIN Sauce ON ChoixSauces.SAUCE_ID = Sauce.SAUCE_ID
            LEFT JOIN ChoixBoissons ON LigneCommande.LGN_CMD_ID = ChoixBoissons.LGN_CMD_ID
            LEFT JOIN Boisson ON ChoixBoissons.BOISSON_ID = Boisson.BOISSON_ID
            WHERE LigneCommande.LGN_CMD_ID = :ligneCommandeId
            GROUP BY LigneCommande.LGN_CMD_ID";

    $res = $pdo->prepare($reqGetLigneCommandeDetailsById);
    $res->bindParam(':ligneCommandeId', $ligneCommandeId, PDO::PARAM_INT);
    $res->execute();
    return $res->fetch(PDO::FETCH_ASSOC);
}

/**
 * Marque une commande comme livrée en mettant à jour son statut dans la base de données.
 * @param PDO $pdo Connexion à la base de données.
 * @param int $commandeId ID de la commande à marquer comme livrée.
 * @return bool True si la mise à jour a réussi, False sinon.
 */
function marquerCommandeCommeLivree($pdo, $commandeId)
{
    $reqMarquerCommandeCommeLivree = "UPDATE Commande SET CMD_LIVRE = True WHERE CMD_ID = :commandeId";
    $req = $pdo->prepare($reqMarquerCommandeCommeLivree);
    $req->bindParam(':commandeId', $commandeId, PDO::PARAM_INT);
    $success = $req->execute();
    return $success;
}
