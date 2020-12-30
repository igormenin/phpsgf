<?php
include_once('db_config.php');

class SQL    {
    public $mysql_handler;

    function __construct ($param = null)    {
        global $driver_db;
        global $host_db;
        global $database;
        global $usuario_db;
        global $senha_db;
        global $opcoes_driver_db;

        //echo '..'.print_r((isset($param['database']) ? $param['database'] : $database), true).'..';

        if(! $this->ConectaMysql(    (isset($param['driver_db']) ? $param['driver_db'] : $driver_db),
            (isset($param['host_db']) ? $param['host_db'] : $host_db),
            (isset($param['database']) ? $param['database'] : $database),
            (isset($param['usuario_db']) ? $param['usuario_db'] : $usuario_db),
            (isset($param['senha_db']) ? $param['senha_db'] : $senha_db),
            (isset($param['opcoes_driver_db']) ? $param['opcoes_driver_db'] : $opcoes_driver_db)
        ))    {
            return false;
        }
    }

    private function ConectaMysql ($driver_db, $host_db, $database, $usuario_db, $senha_db, $opcoes_driver_db) {
        try {
            // echo '++'.$database.'++';
            $this->mysql_handler = new PDO ("$driver_db:host=$host_db;dbname=$database", $usuario_db, $senha_db, $opcoes_driver_db);
        }
        catch (PDOException $erro)  {
            print __METHOD__.'(): erro '.$erro->getMessage().'<br>'; // TODO: handling
            return false;
        }
    }

    public function Query ($query, $campos = false, $fetch_style = PDO::FETCH_ASSOC) {
        try    {
            if(!is_string($query))    {
                throw new Exception (0);
            }
            if(!is_array($campos) && $campos !== false)    {
                throw new Exception (1);
            }
            /* TODO: função que testa se fetch style é válido */

            $stmt = $this->mysql_handler->prepare($query);

            if(is_array($campos))   {
                $x = $stmt->execute($campos);
            }
            elseif($campos === false)    {
                $x = $stmt->execute();
            }
            if($x === false)   {
                throw new Exception ('Falha PDO.');
            }

            $resultado = $stmt->fetchAll($fetch_style);

            return $resultado;
        }
        catch (Exception $e) {
            echo 'Erro: '.$e->getMessage()."\n";
            echo 'Query: '.print_r($query, true)."\n";
            echo 'Campos: '.print_r($campos, true)."\n";
            echo 'X: '.print_r($x, true).' :isset-'.(isset($x) ? 'true' : 'false')."\n";
            return false;
        }
    }
}
?> 