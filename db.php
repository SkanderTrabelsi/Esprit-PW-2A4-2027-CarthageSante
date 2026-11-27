<?php
class config
{
    private static $pdo = null;
      // lit une valeur du fichier .env (ex: GOOGLE_CLIENT_ID)
    public static function env($cle){
        $fichier = __DIR__ . '/.env';
        // si le fichier .env n'existe pas, on renvoie un texte vide
        if (!file_exists($fichier)) {
            return '';
        }
        $env = parse_ini_file($fichier);
        // si la cle existe on renvoie sa valeur, sinon texte vide
        if (isset($env[$cle])) {
            return $env[$cle];
        } else {
            return '';
        }
    }
    public static function getConnexion(){
        if (!isset(self::$pdo)) {
            $env = @parse_ini_file(__DIR__ . '/.env');

            $host = $env['DB_HOST'];
            $dbname = $env['DB_NAME'];
            $username = $env['DB_USER'];
            $password = $env['DB_PASS'];

            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false //force la preparation des req
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