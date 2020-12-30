<?php
/*
 * Constantes de parâmetros para configuração da conexão
 */


define('HOST', 'localhost');
define('DBNAME', 'senhas');
define('CHARSET', 'utf8');
define('USER', 'developer');
define('PASSWORD', 'm1c9b2');



class Conexao{

    /*
     * Atributo estático para instância do PDO
     */
    private static $pdo;

    /*
     * Escondendo o construtor da classe
     */
    public function __construct() {
        //
    }

    /*
     * Método estático para retornar uma conexão válida
     * Verifica se já existe uma instância da conexão, caso não, configura uma nova conexão
     */
    public static function getInstance() {
        if (!isset(self::$pdo)) {
            try {
                $opcoes = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8', PDO::ATTR_PERSISTENT => TRUE);
                self::$pdo = new PDO("mysql:host=" . HOST . "; dbname=" . DBNAME . "; charset=" . CHARSET . ";", USER, PASSWORD, $opcoes);
            } catch (PDOException $e) {
                echo "Erro: " . $e->getMessage();
            }
        }
        return self::$pdo;
    }
}
    function getConfigGeralVinculoTipoEstacaoServico(){
        $stringSQL = 'SELECT log_vinculartipoestacapservico FROM configuracao ORDER BY id_configuracao DESC LIMIT 1';
        $conexao = Conexao::getInstance();
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (isset($resultado[0])){
            return $resultado[0]['log_vinculartipoestacapservico'];
        }
    }
?>
