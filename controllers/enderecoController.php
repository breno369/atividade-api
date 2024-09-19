<?php

require_once "../models/enderecoModel.php";
header('Content-Type: application/json');

class enderecoController
{
    private $ClassEndereco;

    public function __construct()
    {
        $this->ClassEndereco = new enderecoModel();
    }

    function BuscaEndereco()
    {
        $endereco = $this->ClassEndereco->GetEndereco();
        return $endereco;
    }

    function InsereEndereco($cep_endereco, $numero_endereco, $logradouro_endereco, $bairro_endereco, $id_pessoa, $id_cidade)
    {
        $endereco = $this->ClassEndereco->PostEndereco($cep_endereco, $numero_endereco, $logradouro_endereco, $bairro_endereco, $id_pessoa, $id_cidade);
        return $endereco;
    }

    function AlteraEndereco($id, $id_pessoa)
    {
        $endereco = $this->ClassEndereco->PutEndereco($id, $id_pessoa);
        return $endereco;
    }

    function DeletaEndereco($id_endereco)
    {
        $endereco = $this->ClassEndereco->DeleteEndereco($id_endereco);
        return $endereco;
    }
}
