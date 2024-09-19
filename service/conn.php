<?php

class usePDO
{
    private $host = 'localhost';
    private $db = 'api_aula';
    private $port = 3306;
    private $user = "root";
    private $pass = "";
    private $instance; // instância de conexão, usada no Singleton

    // método que retorna a instância de conexão
    function getInstance()
    {
        if (empty($instance)) {
            $instance = $this->connection();
        }
        return $instance;
    }

    private function connection()
    {
        try {
            $conn = new PDO("mysql:host=$this->host;dbname=$this->db;charset=utf8", $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            // echo 'Erro na conexão com o banco de dados: ' . $e->getMessage() . "<br>";
            if (strpos($e->getMessage(), "Unknown database")) {
                echo "Conexão nula, criando o banco pela primeira vez <br>";
                $conn = $this->createDB();
                return $conn;
            } else {
                die("Connection failed: " . $e->getMessage() . "<br>");
            }
        }
    }


    private function createDB()
    {
        $sql = null;
        try {
            $cnx = new PDO("mysql:host=$this->host;charset=utf8", $this->user, $this->pass);
            $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE IF NOT EXISTS $this->db";
            $cnx->exec($sql);
            $cnx->exec("USE $this->db");
            $this->createTable();
            return $cnx;
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage() . "<br>";
            die();
        }
    }

    private function createTable()
    {
        $sql = null;
        try {
            $cnx = $this->getInstance();
            $sql = "CREATE TABLE IF NOT EXISTS usuarios (
                    	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                	    nick VARCHAR(150) NOT NULL,
	                    email VARCHAR(150) UNIQUE NOT NULL,
                    	senha TEXT NOT NULL
                    );
                    CREATE TABLE IF NOT EXISTS pessoas (
                    	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    	nome_completo VARCHAR(150) NOT NULL,
                    	tel INT UNIQUE,
                    	cpf VARCHAR(14) UNIQUE NOT NULL,
                    	sexo VARCHAR(1),
                    	id_usuario INT UNSIGNED UNIQUE NOT NULL,
                    	CONSTRAINT fk_table1_id_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
                    	ON DELETE CASCADE 
                    	ON UPDATE CASCADE
                    ); 
                     CREATE TABLE IF NOT EXISTS cidade (
                    	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    	cidade VARCHAR(100) NOT NULL
                    );
                    CREATE TABLE IF NOT EXISTS endereco (
                    	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    	cep INT NOT NULL,
                    	numero INT NOT NULL,
                    	logradouro VARCHAR(100) NOT NULL,
                    	bairro VARCHAR(100) NOT NULL,
                    	id_pessoa INT UNSIGNED NOT NULL,
                    	id_cidade INT UNSIGNED NOT NULL,
                    	CONSTRAINT fk_table2_id_pessoa FOREIGN KEY (id_pessoa) REFERENCES pessoas(id)
                    	ON DELETE CASCADE,
                    	CONSTRAINT fk_id_cidade FOREIGN KEY (id_cidade) REFERENCES cidade(id)
                    	ON DELETE CASCADE
                    );";

            // var_dump($cnx->exec($sql));
            $cnx->exec($sql);
            return $cnx;
        } catch (PDOException $e) {
            // var_dump($cnx->exec($sql));
            echo $sql . "<br>" . $e->getMessage();
            return "ErroCreateTables";
        }
    }
}
