<?php

require_once "../service/conn.php";

class enderecoModel
{
    private $pdo;
    private $conn;

    public function __construct()
    {
        $this->pdo = new usePDO();
        $this->conn = $this->pdo->getInstance();        
    }

    function GetEndereco()
    {
        try {
            $stmt = $this->conn->query('SELECT * FROM endereco');
            $endereco = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $endereco;
        } catch (PDOException $e) {
            return 'Error GetEndereco: ' . $e->getMessage() . "<br>";
        }
    }

    function PostEndereco($cep_endereco, $numero_endereco, $logradouro_endereco, $bairro_endereco, $id_pessoa, $id_cidade)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO endereco (cep, numero, logradouro, bairro, id_pessoa, id_cidade) VALUES (:cep, :numero, :logradouro, :bairro, :id_pessoa, :id_cidade)');
            $stmt->bindValue(':cep', $cep_endereco);
            $stmt->bindValue(':numero', $numero_endereco);
            $stmt->bindValue(':logradouro', $logradouro_endereco);
            $stmt->bindValue(':bairro', $bairro_endereco);
            $stmt->bindValue(':id_pessoa', $id_pessoa);
            $stmt->bindValue(':id_cidade', $id_cidade);
            $stmt->execute();
            $enderecoId = $this->conn->lastInsertId();
            return json_encode(['endereco' => ['id' => $enderecoId, 'cep' => $cep_endereco, 'numero' => $numero_endereco, 'logradouro' => $logradouro_endereco, 'bairro' => $bairro_endereco, 'id_pessoa' => $id_pessoa, 'id_cidade' => $id_cidade]]);
        } catch (PDOException $e) {
            return "<br>" . 'Error PostEndereco: ' . $e->getMessage() . "<br>";
        }
    }
    
    function PutEndereco($id, $id_pessoa)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE endereco SET id_pessoa = :id_pessoa WHERE id = :id');
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':id_pessoa', $id_pessoa);
            $stmt->execute();
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            return "<br>" . 'Error PutEndereco: ' . $e->getMessage() . "<br>";
        }
    }
    
    function DeleteEndereco($id_endereco)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM endereco WHERE id = :id_endereco');
            $stmt->bindParam(':id_endereco', $id_endereco);
            $stmt->execute();
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            return "<br>" . 'Error DeleteEndereco: ' . $e->getMessage() . "<br>";
        }
    }
}