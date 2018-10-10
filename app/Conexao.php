<?php 

define('DB_HOST'        , "127.0.0.1");
define('DB_USER'        , "root");
define('DB_PASSWORD'    , "root");
define('DB_NAME'        , "totem");
define('DB_DRIVER'      , "mysql");
define('DB_PORT'        , '3306');

class Conexao {
    private static $connection;
    
    private function __construct(){}
        
        public static function getConnection() {
            
            $pdoConfig  = DB_DRIVER . ":". "host=" . DB_HOST;
            
            if (defined("DB_PORT")){
                $pdoConfig .="," . DB_PORT;
            }
            $pdoConfig .= ";";
            $pdoConfig .= "dbname=".DB_NAME.";";
            try {
                if(!isset($connection)){
                    $connection =  new PDO($pdoConfig, DB_USER, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
                    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }
                return $connection;
            } catch (PDOException $e) {
                $mensagem = "Drivers disponiveis: " . implode(",", PDO::getAvailableDrivers());
                $mensagem .= "\nErro: " . $e->getMessage();
                throw new Exception($mensagem);
            }
        }
    }
    ?>
