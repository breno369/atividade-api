<?php

require_once "../service/conn.php";

class cidadeModel
{
    private $pdo;
    private $conn;

    public function __construct()
    {
        $this->pdo = new usePDO();
        $this->conn = $this->pdo->getInstance();        
    }

    function GetCidade()
    {
        try {
            $stmt = $this->conn->query('SELECT * FROM cidade');
            $cidade = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $cidade;
        } catch (PDOException $e) {
            return 'Error GetCidade: ' . $e->getMessage() . "<br>";
        }
    }

    function PostCidade($nome_cidade)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO cidade (cidade) VALUES (:cidade)');
            $stmt->bindValue(':cidade', $nome_cidade);
            $stmt->execute();
            $cidadeId = $this->conn->lastInsertId();
            return json_encode(['cidade' => ['id_cidade' => $cidadeId, 'cidade' => $nome_cidade]]);
        } catch (PDOException $e) {
            return "<br>" . 'Error PostCidade: ' . $e->getMessage() . "<br>";
        }
    }
    
    function PutCidade($id_cidade, $nome_cidade)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE cidade SET cidade = :nome_cidade WHERE id = :id_cidade');
            $stmt->bindParam(':id_cidade',$id_cidade);
            $stmt->bindParam(':nome_cidade',$nome_cidade);
            $stmt->execute();
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            return "<br>" . 'Error PutCidade: ' . $e->getMessage() . "<br>";
        }
    }
    
    function DeleteCidade($id_cidade)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM cidade WHERE id = :id_cidade');
            $stmt->bindParam(':id_cidade', $id_cidade);
            $stmt->execute();
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            return "<br>" . 'Error DeleteCidade: ' . $e->getMessage() . "<br>";
        }
    }
}