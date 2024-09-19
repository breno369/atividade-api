<?php
require_once "../controllers/usuarioController.php";
require_once "../controllers/pessoaController.php";
require_once "../controllers/cidadeController.php";
require_once "../controllers/enderecoController.php";

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$url = $_SERVER['REQUEST_URI'];

$NewUrl = explode("/", $url);
$positionUrl = count(explode("/", $url));
$tableUrl = $positionUrl - 1;

// $_SERVER['PHP_AUTH_USER'] = '93b13dd932756d696e0cbfe46e95a621';
// var_dump($_SERVER['PHP_AUTH_USER']);
// var_dump($_SERVER['PHP_AUTH_PW']);

$headers = apache_request_headers();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Token não fornecido']);
    exit();
}

$authHeader = $headers['Authorization'];
list($clientToken) = sscanf($authHeader, 'Bearer %s');
$_SESSION['token'] = $clientToken;
if (isset($_SESSION['token']) && $clientToken === $_SESSION['token']) {

    $ClasseUsuario = new usuarioController();
    $getusser = $ClasseUsuario->BuscaUsuarioPor($_SESSION['token']);
    
    echo json_encode([
        'message' => 'Acesso concedido',
        'user' => $getusser[0]['nick'],
        'token' => $_SESSION['token']
    ]);
} else {
    http_response_code(401);
    echo json_encode(['message' => 'Acesso negado. Token inválido ou expirado.']);
    exit();
}


// var_dump($_SERVER);

switch ($NewUrl[$tableUrl]) {
    case 'usuario':
        Usuario($method);
        break;
    case 'pessoa':
        Pessoa($method);
        break;
    case 'cidade':
        Cidade($method);
        break;
    case 'endereco':
        Endereco($method);
        break;
    default:
        echo json_encode(['Error' => "Erro 404"]);
        break;
}

function Usuario($metodo)
{
    $data = json_decode(file_get_contents('php://input'), true);
    $ClassUsuario = new usuarioController();

    if (($metodo == "GET") && (empty($data))) {
        echo json_encode($ClassUsuario->BuscaUsuario());
    }

    if ($metodo == "POST") {
        if (empty($data['nick'])) {
            echo json_encode(['error' => 'O nome do usuario é obrigatório']);
            exit;
        }
        $senha = hash('sha256', $data['senha']);
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        if ($email) {
            // $data['token'];
            $token = md5(uniqid(rand(), true));
            echo $ClassUsuario->InsereUsuario($data['nick'], $email, $senha, $token);
        } else {
            echo json_encode(["Error" => "E-mail inválido"]);
        }
    }

    if ($metodo == "PUT") {
        if (empty($data['id'])) {
            echo json_encode(['error' => 'O ID do usuario é obrigatório']);
            exit;
        }
        $senha = hash('sha256', $data['senha']);
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        if ($email) {
            echo $ClassUsuario->AlteraUsuario($data['id'], $data['nick'], $email, $senha);
        } else {
            echo json_encode(["Error" => "E-mail inválido"]);
        }
    }

    if ($metodo == "DELETE") {
        if (empty($data['id'])) {
            echo json_encode(['error' => 'O ID do usuario é obrigatório']);
            exit;
        }
        echo $ClassUsuario->DeletaUsuario($data['id']);
    }
}

function Pessoa($metodo)
{
    $data = json_decode(file_get_contents('php://input'), true);
    $ClassPessoa = new pessoaController();

    if (($metodo == "GET") && (empty($data))) {
        echo json_encode($ClassPessoa->BuscaPessoa());
    }

    if (($metodo == "POST")) {
        if (empty($data['nome_completo'])) {
            echo json_encode(['error' => 'O nome da pessoa é obrigatório']);
            exit;
        }

        if (array_key_exists('nome_completo', $data) && array_key_exists('id_usuario', $data) && array_key_exists('sexo', $data) && array_key_exists('tel', $data) && array_key_exists('cpf', $data)) {

            if ((validacao('string', $data['nome_completo']) == false) || ((validacao('string', $data['sexo']) == false) && !empty($data['sexo'])) || (validacao('int', $data['id_usuario']) == false) || (validacao('cpf', $data['cpf']) == false)) {
                echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
            } else {

                // var_dump(preg_match("/^\([0-9]{2}\) 9?[0-9]{4}\-[0-9]{4}$/", $data['tel']));
                if (preg_match("/^\([0-9]{2}\) 9?[0-9]{5}\-[0-9]{4}$/", $data['tel']) == 1) {
                    
                    // var_dump($data['tel']);
                    
                    $telefoneLimpo = preg_replace('/[^0-9]/', '', $data['tel']);
                    
                    // var_dump($telefoneLimpo);
                    // var_dump(intval($telefoneLimpo));

                    echo $ClassPessoa->InserePessoa($data['nome_completo'], intval($telefoneLimpo), $data['cpf'], $data['sexo'], $data['id_usuario']);
                } else {
                    echo json_encode(["Error" => "Telefone inválido, siga esse padrão (00) 00000-0000"]);
                }
            }
        } else {
            echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
        }
    }

    if (($metodo == "PUT")) {
        if (empty($data['id'])) {
            echo json_encode(['error' => 'O ID da pessoa é obrigatório']);
            exit;
        }

        if (array_key_exists('id', $data) && array_key_exists('nome_completo', $data) && array_key_exists('tel', $data) && array_key_exists('sexo', $data)) {

            if ((validacao('int', $data['id']) == false) || (validacao('string', $data['nome_completo']) == false) || (validacao('string', $data['sexo']) == false)) {
                echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
            } else {

                if (preg_match("/^\([0-9]{2}\) 9?[0-9]{4}\-[0-9]{4}$/", $data['tel'])) {
                    echo $ClassPessoa->AtualizaPessoa($data['id'], $data['nome_completo'], $data['tel'], $data['sexo']);
                } else {
                    echo json_encode(["Error" => "Telefone inválido, siga esse padrão (00) 00000-0000"]);
                }
            }
        } else {
            echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
        }
    }

    if (($metodo == "DELETE")) {
        if (empty($data['id'])) {
            echo json_encode(['error' => 'O ID da pessoa é obrigatório']);
            exit;
        }

        if (validacao('int', $data['id']) == false) {
            echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
        } else {
            echo $ClassPessoa->DeletaPessoa($data['id']);
        }
    }
}

function Cidade($metodo)
{
    $data = json_decode(file_get_contents('php://input'), true);
    $ClassCidade = new cidadeController();

    if (($metodo == "GET") && (empty($data))) {
        echo json_encode($ClassCidade->BuscaCidade());
    }

    if ($metodo == "POST") {
        if (empty($data['nome_cidade'])) {
            echo json_encode(['error' => 'O nome do cidade é obrigatório']);
            exit;
        }

        if (validacao('string', $data['nome_cidade']) == false) {
            echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
        } else {
            echo $ClassCidade->InsereCidade($data['nome_cidade']);
        }
    }

    if ($metodo == "PUT") {
        if (empty($data['id_cidade'])) {
            echo json_encode(['error' => 'O ID do cidade é obrigatório']);
            exit;
        }

        if ((validacao('string', $data['nome_cidade']) == false) || (validacao('int', $data['id_cidade']) == false)) {
            echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
        } else {
            echo $ClassCidade->AlteraCidade($data['id_cidade'], $data['nome_cidade']);
        }
    }

    if ($metodo == "DELETE") {
        if (empty($data['id_cidade'])) {
            echo json_encode(['error' => 'O ID do cidade é obrigatório']);
            exit;
        }

        if (validacao('int', $data['id_cidade']) == false) {
            echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
        } else {
            echo $ClassCidade->DeletaCidade($data['id_cidade']);
        }
    }
}

function Endereco($metodo)
{
    $data = json_decode(file_get_contents('php://input'), true);
    $ClassEndereco = new enderecoController();
    
    if (($metodo == "GET") && (empty($data))) {
        echo json_encode($ClassEndereco->BuscaEndereco());
    }

    if ($metodo == "POST") {
        if (empty($data['cep'])) {
            echo json_encode(['error' => 'O cep do endereco é obrigatório']);
            exit;
        }
        if (array_key_exists('cep', $data) && array_key_exists('numero', $data) && array_key_exists('logradouro', $data) && array_key_exists('bairro', $data) && array_key_exists('id_pessoa', $data) && array_key_exists('id_cidade', $data)) {
            
            if ((validacao('cep', $data['cep']) == false) || (validacao('string', $data['logradouro']) == false) || (validacao('bairro', $data['bairro']) == false) || (validacao('int', $data['numero']) == false) || (validacao('int', $data['id_pessoa']) == false) || (validacao('int', $data['id_cidade']) == false)) {
                echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
            } else {
                $cep = preg_replace("/[^0-9]/", "", $data['cep']);
                echo $ClassEndereco->InsereEndereco($cep, $data['numero'], $data['logradouro'], $data['bairro'], $data['id_pessoa'], $data['id_cidade']);
            }
        } else {
            echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
        }
    }
    
    if ($metodo == "PUT") {
        if (empty($data['id'])) {
            echo json_encode(['error' => 'O ID da pessoa é obrigatório']);
            exit;
        }

        if (array_key_exists('id', $data) && array_key_exists('id_pessoa', $data)) {
            if ((validacao('int', $data['id']) == false) || (validacao('int', $data['id_pessoa']) == false)) {
                echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
            } else {
                echo $ClassEndereco->AlteraEndereco($data['id'], $data['id_pessoa']);
            }
        } else {
            echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
        }
    }

    if ($metodo == "DELETE") {
        if (empty($data['id'])) {
            echo json_encode(['error' => 'O ID do endereco é obrigatório']);
            exit;
        }

        if (array_key_exists('id', $data)) {
            if ((validacao('int', $data['id']) == false)) {
                echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
            } else {
                echo $ClassEndereco->DeletaEndereco($data['id']);
            }
        } else {
            echo json_encode(['message' => 'solicitação inválida', 'http_response' => ['message' => 'Invalid syntax for this request was provided.', 'code' => 400]]);
        }

    }
}

function validacao($tipo, $input)
{

    switch ($tipo) {
        case 'string':
            return a($input);
            break;
        case 'int':
            return b($input);
            break;
        case 'cpf':
            return d($input);
            break;
        case 'cep':
            return e($input);
            break;
        default:
            return 'not found';
            break;
    }
}

function a($nome)
{
    return preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/", $nome) ? true : false;
}

function b($numero)
{
    return filter_var($numero, FILTER_VALIDATE_INT) ? true : false;
}

function d($cpf)
{
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);
    if (strlen($cpf) != 11) {
        return false;
    }
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

function e($cep)
{
    $cep2 = preg_replace("/[^0-9]/", "", $cep);
    if (preg_match("/^[0-9]{8}$/", $cep2)) {
        return true;
    } else {
        return false;
    }
}
