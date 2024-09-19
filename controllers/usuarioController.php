<?php

require_once "../models/usuariosModel.php";
header('Content-Type: application/json');

class usuarioController
{
    private $ClassUsuario;

    public function __construct()
    {
        $this->ClassUsuario = new UsuarioModel();
    }

    function BuscaUsuarioPor($where)
    {
        $usuario = $this->ClassUsuario->GetUsuarioWhere($where);
        return $usuario;
    }

    function BuscaUsuario()
    {
        $usuario = $this->ClassUsuario->GetUsuario();
        return $usuario;
    }

    function InsereUsuario($nick_usuario, $email_usuario, $senha_usuario, $token)
    {    
        $usuario = $this->ClassUsuario->PostUsuario($nick_usuario, $email_usuario, $senha_usuario, $token);
        return $usuario;
    }

    function AlteraUsuario($id_usuario, $nick_usuario, $email_usuario, $senha_usuario)
    {
        $usuario = $this->ClassUsuario->PutUsuario($id_usuario, $nick_usuario, $email_usuario, $senha_usuario);
        return $usuario;
    }

    function DeletaUsuario($id_usuario)
    {
        $usuario = $this->ClassUsuario->DeleteUsuario($id_usuario);
        return $usuario;
    }
}
