<?php

namespace Core;

use PDO;
use PDOException;

class Conexao
{

    private $pdo;
    private $server = "localhost";
    private $user = "root";
    private $pass = "";

    // METODO QUE ESTABELECE UMA CONEXÃO COM O BANCO DE DADOS
    public function __construct()
    {
        try {

            $this->pdo = new PDO("mysql:host=$this->server;dbname=erp", $this->user, $this->pass);

            echo "conectou";
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {

            echo "ERRO NA CLASSE CONEXÃO : " . $e;
        }
    }

    // Função para verificar se o usuário já existe
    private function userExists($table, $login)
    {
        $query = "SELECT * FROM $table WHERE login = :login";
        $cmd = $this->pdo->prepare($query);
        $cmd->bindParam(':login', $login);
        $cmd->execute();

        return $cmd->rowCount();
    }

    // METODO QUE INSERE OS DADOS NO BANCO /////////////////
    public function insert($table, $data)
    {
        /*
        VEJA A EXPLICAÇÃO DESTE METODO COM MAIS DETALHES EM README/função_insert_classe_Conexao.php 
        */

        try {

            // Verifica se o usuário já existe
            if ($this->userExists($table, $data['login'])) {

                echo " <p id = 'message'
                style=
                '
                background-color: #C0C0C0;
                color: red;
                font-weight: bold;
                font-size: 22px;
                text-align: center;
                border: 2px solid red;
                
                '
                >

                Este usuário já existe

                </p>";

                return;
            }

            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));

            $query = "INSERT INTO $table ($columns) VALUES ($values)";
            $cmd = $this->pdo->prepare($query);
            $cmd->execute($data);

            echo " <p id = 'message'
            style=
            '
            background-color: #00FA9A;
            color: green;
            font-weight: bold;
            font-size: 22px;
            text-align: center;
            border: 2px solid green;
            '
            >

            Dados Inseridos Com Sucesso

            </p>";
        } catch (PDOException $e) {
            echo "Erro ao inserir dados: " . $e->getMessage();
        }
    }


    // METODO QUE TRATA OS DADOS DO NOME E SENHA

    public function trata_dados($text = null, $pass = null)
    {
        //VEJA A EXPLICAÇÃO DA FUNÇÃO COM MAIS DETALHES EM README/função_trata_dados_classe_Conexao.php
        $result = [];

        if ($text !== null) {
            // Tratamento de Texto
            $data_text = trim(strtolower(htmlspecialchars(addslashes($text))));
            $result['data_text'] = $data_text;
        }

        if ($pass !== null) {
            // Tratamento de Senha
            $hashedPassword = $this->hashPassword($pass);
            $result['hashed_password'] = $hashedPassword;
        }
        return $result;
    }

    // METODO PARA GERAR O HASH DA SENHA
    private function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}//FINAL DA CLASSE
