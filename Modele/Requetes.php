<?php
class Requete
{
    protected $pdo;
    protected $tbs;
    protected $req;
    protected $gab;
    protected $data;

    public function __construct($_pdo, $_tbs, $_gab, $_req,)
    {
        $this->pdo = $_pdo;
        $this->req = $_req;
        $this->tbs = $_tbs;
        $this->gab = $_gab;
    }
    public function executerAll()
    {
        $res = $this->pdo->prepare($this->req);
        $res->execute();
        $this->data = $res->fetchAll();
    }

    public function executer()
    {
        $res = $this->pdo->prepare($this->req);
        $res->execute();
        $this->data = $res->fetch();
    }

    public function executeP1($sql, $params)
    {
        $res = $this->pdo->prepare($this->req);
        $res->execute($params);
        return $res->fetch(PDO::FETCH_ASSOC);
    }

    public function getData()
    {
        return $this->data;
    }
}

class RecupBugers extends Requete
{
    public function afficher()
    {
        $this->tbs->LoadTemplate($this->gab);
        $this->tbs->MergeBlock('burger', $this->data);
        $this->tbs->Show();
    }
}

class RecupCrudite extends Requete
{
    public function afficher()
    {
        $this->tbs->LoadTemplate($this->gab);
        $this->tbs->MergeBlock('crudite', $this->data);
        $this->tbs->Show();
    }
}

class RecupSauce extends Requete
{
    public function afficher()
    {
        $this->tbs->LoadTemplate($this->gab);
        $this->tbs->MergeBlock('sauce', $this->data);
        $this->tbs->Show();
    }
}


class RecupBoisson extends Requete
{
    public function afficher()
    {
        $this->tbs->LoadTemplate($this->gab);
        $this->tbs->MergeBlock('boisson', $this->data);
        $this->tbs->Show();
    }
}
