<?php
namespace Config;

use PDO;

USE PDOException;

class Conexao{
    private static $host = 'localhost';
    private static $dbname = 'acme';
    private static $usuario = 'root';
    private static $senha = '';
    private static $conexao;

    public static function getConexao(){
        if(!self::$conexao){
            try{
                self::$conexao = new PDO(
                    "mysql:host=".self::$host.";dbname=".self::$dbname,
                    self::$usuario,
                    self::$senha
                );
                self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e){
                die ('Erro ao conectar com o BD'. $e->getMessage());
            }
        }
        return self::$conexao;
    }


}
?>