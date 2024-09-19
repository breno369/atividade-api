<?php

require_once "../service/conn.php";

class UsuarioModel
{
    private $pdo;
    private $conn;

    public function __construct()
    {
        $this->pdo = new usePDO();
        $this->conn = $this->pdo->getInstance();        
    }

    function GetUsuarioWhere($token)
    {
        try {
            $stmt = $this->conn->query("SELECT * FROM usuarios WHERE token = \"$token\"");
            $usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $usuario;
        } catch (PDOException $e) {
            return 'Error GetUsuario: ' . $e->getMessage() . "<br>";
        }
    }

    function GetUsuario()
    {
        try {
            $stmt = $this->conn->query('SELECT * FROM usuarios');
            $usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $usuario;
        } catch (PDOException $e) {
            return 'Error GetUsuario: ' . $e->getMessage() . "<br>";
        }
    }

    function PostUsuario($nick, $email, $senha, $token)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO usuarios (nick, email, senha, token) VALUES (:nick, :email, :senha, :token)');
            $stmt->bindValue(':nick', $nick);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', $senha);
            $stmt->bindValue(':token', $token);
            $stmt->execute();
            $usuarioId = $this->conn->lastInsertId();
            return json_encode(['usuario' => ['id' => $usuarioId, 'usuario' => $nick, 'token' => $token], 'success' => true]);
        } catch (PDOException $e) {
            return "<br>" . 'Error PostUsuario: ' . $e->getMessage() . "<br>";
        }
    }
    
    function PutUsuario($id_usuario, $nick_usuario, $email_usuario, $senha_usuario)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE usuarios SET nick = :nick_usuario, email = :email_usuario, senha = :senha_usuario WHERE id = :id_usuario');
            $stmt->bindParam(':id_usuario',$id_usuario);
            $stmt->bindParam(':nick_usuario',$nick_usuario);
            $stmt->bindParam(':email_usuario',$email_usuario);
            $stmt->bindParam(':senha_usuario',$senha_usuario);
            $stmt->execute();
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            return "<br>" . 'Error PutUsuario: ' . $e->getMessage() . "<br>";
        }
    }
    
    function DeleteUsuario($id_usuario)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM usuarios WHERE id = :id_usuario');
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            return "<br>" . 'Error DeleteUsuario: ' . $e->getMessage() . "<br>";
        }
    }
}