<?php
class config
{
    private static $pdo = null;
    public static function getConnexion(){
        if (!isset(self::$pdo)) {
            $env = @parse_ini_file('/.env');

            $host = $env['DB_HOST'];
            $dbname = $env['DB_NAME'];
            $username = $env['DB_USER'];
            $password = $env['DB_PASS'];

            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (Exception $e) {
                die('Erreur de connexion : ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
?>