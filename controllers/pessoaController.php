<?php

require_once "../models/pessoaModel.php";
header('Content-Type: application/json');

class pessoaController
{
    private $ClassPessoa;

    public function __construct()
    {
        $this->ClassPessoa = new pessoaModel();
    }

    function BuscaPessoa()
    {
        $pessoa = $this->ClassPessoa->GetPessoa();
        return $pessoa;
    }

    function InserePessoa($nome_pessoa, $tel_pessoa, $cpf_pessoa, $sexo_pessoa, $id_usuario)
    {
        // var_dump($tel_pessoa . ' InserePessoa');
        $pessoa = $this->ClassPessoa->PostPessoa($nome_pessoa, $tel_pessoa, $cpf_pessoa, $sexo_pessoa, $id_usuario);
        if (strpos($pessoa, "Integrity constraint violation: 1062 Duplicate entry")) {
            // Integrity constraint violation: 1062 Duplicate entry '56053429082' for key 'cpf'<br>
            $NewPessoa = explode("'", $pessoa);
            $LenghtPessoa = count($NewPessoa) - 2;
            // var_dump($pessoa);
            return json_encode(['message' => 'Conflict', "details" => "duplicate value $NewPessoa[$LenghtPessoa]", 'http_response' => ['message' => 'The request could not be completed due to a conflict with the current state of the resource.', 'code' => 409]]);
        } else {
            return $pessoa;
        }
        
    }

    function AtualizaPessoa($id_pessoa, $nome_pessoa, $tel_pessoa, $sexo_pessoa)
    {
        $pessoa = $this->ClassPessoa->PutPessoa($id_pessoa, $nome_pessoa, $tel_pessoa, $sexo_pessoa);
        return $pessoa;
    }

    function DeletaPessoa($id_pessoa)
    {
        $pessoa = $this->ClassPessoa->DeletePessoa($id_pessoa);
        return $pessoa;
    }
}
