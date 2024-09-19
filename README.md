para fazer um solicitação do json use: http://localhost/{seus diretorios}/jean/routes/router.php/{nome da tabela}
tabelas disponiveis:
    usuario
    pessoa
    cidade
    endereco

estrutura do json para:
    usuario:
        POST:
            {
                "nick": "{seu nome de usuario}",
                "email": "{email valido, xxxxxx@xxxx.xxxx}",
                "senha": "{senha}"
            }
        PUT:
            {
                "id": {numero inteiro},
                "nick": "{seu nome de usuario}",
                "email": "{email valido, xxxxxx@xxxx.xxxx}",
                "senha": "{senha}"
            }
        DELETE:
            {
                "id": {numero inteiro}
            }

    pessoa:
        POST:
            {
                "nome_completo": "{nome com apenas letras}",
                "tel": "{numero de telefone com esse formato, (xx) xxxxx-xxxx}",
                "cpf": {cpf valido sem pontuacao},
                "sexo": "{sexo pode ser "M", "F" ou null, nao tem validacao do m,f ou null para isso}",
                "id_usuario": {numero inteiro}
            }
        PUT:
            {
                "id": {numero inteiro},
                "nome_completo": "{nome com apenas letras}",
                "tel": "{numero de telefone com esse formato, (xx) xxxxx-xxxx}",
                "sexo": "{sexo pode ser "M", "F" ou null, nao tem validacao do m,f ou null para isso}"
            }
        DELETE:
            {
                "id_pessoa": {numero inteiro}
            }

    cidade:
        POST:
            {
                "nome_cidade": "{cidade com apenas letras}"
            }
        PUT:
            {
                "id_cidade": {numero inteiro},
                "nome_cidade": "{cidade com apenas letras}"
            }
        DELETE:
            {
                "id_cidade": {numero inteiro}
            }

    endereco:
        POST:
            {
                "cep": {numero inteiro com ou sem '-' de 8 digitos},
                "numero": {numero inteiro},
                "logradouro": "{logradouros sem caracteres especiais}",
                "bairro": "{bairros sem caracteres especiais}",
                "id_pessoa": {numero inteiro},
                "id_cidade": {numero inteiro}
            }
        PUT:
            {
                "id": {numero inteiro},
                "id_pessoa": {numero inteiro}
            }
        DELETE:
            {
                "id": {numero inteiro}
            }