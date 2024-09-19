<?php
require_once "../models/cidadeModel.php";
header('Content-Type: application/json');

class cidadeController
{
    private $ClassCidade;

    public function __construct()
    {
        $this->ClassCidade = new cidadeModel();
    }

    function BuscaCidade()
    {
        $cidade = $this->ClassCidade->GetCidade();
        return $cidade;
    }

    function InsereCidade($nome_cidade)
    {
        $cidade = $this->ClassCidade->PostCidade($nome_cidade);
        return $cidade;
    }

    function AlteraCidade($id_cidade, $nome_cidade)
    {  
        $cidade = $this->ClassCidade->PutCidade($id_cidade, $nome_cidade);
        return $cidade;
    }

    function DeletaCidade($id_cidade)
    {
        $cidade = $this->ClassCidade->DeleteCidade($id_cidade);
        return $cidade;
    }
}
