<?php

require_once "../service/conn.php";

class pessoaModel
{
    private $pdo;
    private $conn;

    public function __construct()
    {
        $this->pdo = new usePDO();
        $this->conn = $this->pdo->getInstance();        
    }

    function GetPessoa()
    {
        try {
            $stmt = $this->conn->query('SELECT * FROM pessoas');
            $pessoa = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $pessoa;
        } catch (PDOException $e) {
            return 'Error Getpessoa: ' . $e->getMessage() . "<br>";
        }
    }

    function PostPessoa($nome_pessoa, $tel_pessoa, $cpf_pessoa, $sexo_pessoa, $id_usuario)
    {
        // var_dump($tel_pessoa);
        try {
            $stmt = $this->conn->prepare('INSERT INTO pessoas (nome_completo, tel, cpf, sexo, id_usuario) VALUES (:nome_completo, :tel, :cpf, :sexo, :id_user)');
            $stmt->bindValue(':nome_completo', $nome_pessoa);

            $stmt->bindValue(':tel', $tel_pessoa);
            $stmt->bindValue(':cpf', $cpf_pessoa);
            $stmt->bindValue(':sexo', $sexo_pessoa);
            $stmt->bindValue(':id_user', $id_usuario);
            
            // var_dump($stmt->bindValue(':tel', $tel_pessoa));
            $stmt->execute();

            $pessoaId = $this->conn->lastInsertId();
            return json_encode(['pessoa' => ['id' => $pessoaId, 'nome_completo' => $nome_pessoa, 'tel' => $tel_pessoa, 'cpf' => $cpf_pessoa, 'sexo' => $sexo_pessoa, 'id_usuario' => $id_usuario]]);

        } catch (PDOException $e) {

            // var_dump($tel_pessoa . ' PostPessoa');
            // var_dump($e->getMessage());
            
            return "<br>" . 'Error PostPessoa: ' . $e->getMessage() . "<br>";
        }
    }
    
    function PutPessoa($id_pessoa, $nome_pessoa, $tel_pessoa, $sexo_pessoa)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE pessoas SET nome_completo = :nome_completo, tel = :tel, sexo = :sexo WHERE id = :id_pessoa');
            $stmt->bindValue(':nome_completo', $nome_pessoa);
            $stmt->bindValue(':tel', $tel_pessoa);
            $stmt->bindValue(':sexo', $sexo_pessoa);
            $stmt->bindValue(':id_pessoa', $id_pessoa);
            $stmt->execute();
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            return "<br>" . 'Error Putpessoa: ' . $e->getMessage() . "<br>";
        }
    }
    
    function DeletePessoa($id_pessoa)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM pessoas WHERE id = :id_pessoa');
            $stmt->bindParam(':id_pessoa', $id_pessoa);
            $stmt->execute();
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            return "<br>" . 'Error Deletepessoa: ' . $e->getMessage() . "<br>";
        }
    }
}