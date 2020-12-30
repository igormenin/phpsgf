<?php
if (session_status() == PHP_SESSION_NONE){
	session_start();
}
    require_once("./conexao.php");
    
    
    function get_post_action($name)
    {
        $params = func_get_args();
        foreach ($params as $name) {
            if (isset($_POST[$name])) {
                return $name;
            }
        }
    }
    
    getConfigGeralVinculoTipoEstacaoServico();
    
    switch (get_post_action('btnChamar', 'btnRechamar', 'btnNaoCompareceu', 'btnEncerrar', 'btnSair')) {
        case 'btnChamar':
            chamar("0");
            break;
        case 'btnRechamar':
            chamar("1");
            break;
        case 'btnNaoCompareceu':
            naoCompareceu();
            break;
        case 'btnEncerrar':
            encerrar();
            break;
        case 'btnSair':
            logout();
            break;
        default:
            break;
    }
    function naoCompareceu()
    {
        encerrar('1');
    }
    
    function encerrar($pNaoCompareceu = '0')
    {
        try {
            $sql = 'UPDATE senhaschamadas SET log_encerrada=1 ';
            if ($pNaoCompareceu == '1') {
                $sql .= ', log_naocompareceu=1 ';
            }
            $sql .= 'WHERE id_senha=' . $_SESSION['id_senha'];
            
            $conexao = Conexao::getInstance();
            $stm = $conexao->prepare($sql);
            $stm->execute();
            limpaSenhasChamadas();
            header('Location: index.php');
        } catch (PDOException $e) {
            echo '<script type="text/javascript">';
            echo 'alert("Erro ao encerrar atendimento!\n\n' . $e->getMessage() . '");';
            echo 'window.location.replace("index.php");</script>';
        }
    }
    
    function gravaLog($pFuncao, $pValorLog)
    {
        $sql = 'INSERT INTO logs (dat_datahora,des_funcao,logscol) VALUES (now(),"' . $pFuncao . '","' . $pValorLog . '")';
        $conexao = Conexao::getInstance();
        $stm = $conexao->prepare($sql);
        $stm->execute();
    }
    
    function logout()
    {
        try {
            if ($_SESSION['id_senha'] != '' || $_SESSION['id_senha'] != null) {
                echo '<script type="text/javascript">alert("Você ainda tem uma senha em atendimento, favor encerrar o atendimento, para depois poder sair do sistema!"); window.location.replace("./index.php");</script>';
            } else {
                $hostName = $_SESSION['hostNameClient'];
                session_destroy();
                header('Location: login.php?hostNameCliente=' . $hostName);
            }
        } catch (Exception $e) {
            echo '<script type="text/javascript">alert("' . $e->getMessage() . '"); window.location.replace("./index.php");</script>';
        }
        
    }
    
    function chamar($pRechamar = "0")
    {
        
        try {
            
            if ($pRechamar == "1") {
                //"Re-chamar a senha!";
                criaRegistroSenhasChamadas($_SESSION['hostNameClient'], $_SESSION['id_senha'], 1);
                header('Location: index.php');
            } else {
                if (validaSenhaJaChamada($_SESSION['id_usuario']) == false) {
                    header('Location: index.php');
                }
                //"Chamar a senha!";
                $sql = "SELECT sen.id_senha as id_senha, ";
                $sql .= "       CONCAT(ser.sigla, substring('0000', -4 + LENGTH(sen.num_sequencia)), sen.num_sequencia) as num_sequencia, ";
                $sql .= "       ser.des_descricao as des_descricao, ";
                $sql .= "       ser.sigla as sigla ";
                $sql .= "FROM senhas AS sen ";
                $sql .= "LEFT JOIN servico AS ser ON ser.id_servico = sen.id_servico ";
                $sql .= "LEFT JOIN usuario_servico AS uss ON uss.id_servico = ser.id_servico ";
                $sql .= "WHERE uss.id_usuario = " . $_SESSION['id_usuario'];
                $sql .= " AND sen.id_senha NOT IN(SELECT sc.id_senha FROM senhaschamadas AS sc WHERE sc.id_senha IS NOT NULL) ";
                $sql .= " AND DATE_FORMAT(sen.dat_gerada,'%d/%m/%Y')=DATE_FORMAT(NOW(),'%d/%m/%Y') ";
                if (getConfigGeralVinculoTipoEstacaoServico() == '1') {
                    $sql .= ' AND ser.id_tipoestacao=(select estacoes.id_tipoestacao from estacoes where estacoes.hostname="' . $_SESSION['hostNameClient'] . '") ';
                }
                $sql .= "ORDER BY ROUND(((TO_SECONDS(now())-TO_SECONDS(sen.dat_gerada))*ser.val_fator)) DESC LIMIT 1";
                
                // gravaLog(__FUNCTION__, $sql);
                
                $conexao = Conexao::getInstance();
                $stm = $conexao->prepare($sql);
                $stm->execute();
                $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
                limpaSenhasChamadas();
                if (isset($resultado[0])) {
                    //Gravar dados na tabela de senhaschamadas
                    criaRegistroSenhasChamadas($_SESSION['hostNameClient'], $resultado[0]['id_senha']);
                    $_SESSION['id_senha'] = $resultado[0]['id_senha'];
                    $_SESSION['num_sequencia'] = $resultado[0]['num_sequencia'];
                    $_SESSION['des_descricao'] = $resultado[0]['des_descricao'];
                    $_SESSION['sigla'] = $resultado[0]['sigla'];
                    header('Location: index.php');
                } else {
                    limpaSenhasChamadas();
                    echo '<script type="text/javascript">alert("Não possui nenhuma senha disponível para chamar!"); window.location.replace("./index.php");</script>';
                }
            }
        } catch (Exception $e) {
            limpaSenhasChamadas();
//            echo '<script type="text/javascript">alert("Ocorreu um erro ao ' . $local . '\n"' . $e->getMessage() . '); window.location.replace("./index.php");</script>';
        }
    }
    
    function validaSenhaJaChamada($pIDUser)
    {
        $sql = "SELECT count(sc.id_senhaschamadas) as totalsc FROM senhaschamadas as sc ";
        $sql .= " WHERE sc.id_usuario= " . $pIDUser;
        $sql .= " AND sc.log_encerrada='0' ";
        $sql .= " AND DATE_FORMAT(sc.dat_datahorachamada,'%d/%m/%Y')=DATE_FORMAT(NOW(),'%d/%m/%Y')";
        $conexao = Conexao::getInstance();
        $stm = $conexao->prepare($sql);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (isset($resultado[0])) {
            if ($resultado[0]['totalsc'] > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
    
    function criaRegistroSenhasChamadas($pHostNameCliente, $pIDSenha, $pLogRechamada = '0')
    {
        try {
            $sql = "INSERT INTO senhaschamadas (dat_datahorachamada,id_usuario,id_senha,des_local,log_rechamado, log_chamada) ";
            $sql .= "VALUES(now(), ";
            $sql .= $_SESSION['id_usuario'] . ", ";
            $sql .= $pIDSenha . ", ";
            $sql .= "CONCAT((SELECT CONF.des_tipoLocal FROM configuracao AS CONF ORDER BY CONF.id_configuracao DESC LIMIT 1),";
            $sql .= "' ',";
            $sql .= "(SELECT EST.numero FROM estacoes AS EST WHERE EST.hostname='" . $pHostNameCliente . "'))";
            $sql .= "," . $pLogRechamada;
            $sql .= ", 0)";
            $conexao = Conexao::getInstance();
            $stm = $conexao->prepare($sql);
            $stm->execute();
            
            
        } catch (PDOException $e) {
            return $e;
        }
        
    }
    
    
    function limpaSenhasChamadas()
    {
        $_SESSION['id_senha'] = null;
        $_SESSION['num_sequencia'] = null;
        $_SESSION['des_descricao'] = null;
        $_SESSION['sigla'] = null;
    }
    
    function validaBloquearRechamar()
    {
        try{
            $stringSQL = 'SELECT IF(COUNT(id_senhaschamadas)>=(SELECT configuracao.num_limiterechamar FROM configuracao ORDER BY configuracao.id_configuracao DESC LIMIT 1),"YES","NO") as validaRechamar FROM senhaschamadas';
            $stringSQL .= ' WHERE senhaschamadas.id_usuario=' . $_SESSION['id_usuario'];
            $stringSQL .= ' AND senhaschamadas.log_encerrada=0 ';
            $stringSQL .= ' AND senhaschamadas.id_senha=' . $_SESSION['id_senha'];
            gravaArquivoLogCliente('validaCliente -> ' . $stringSQL);
            $conexao = Conexao::getInstance();
            $stm = $conexao->prepare($stringSQL);
            $stm->execute();
            $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
            gravaArquivoLogCliente('validaCliente - Resultado -> ' . $resultado[0]['validaRechamar']);
            return $resultado[0]['validaRechamar'];
        } catch (Exception $e){
            throw $e;
        }
    }

function gravaArquivoLogCliente($pValor)
{
    $a = fopen("/var/www/html/senhas/meleca.txt", "a+");
    fputs($a, date_format(new DateTime('now'),'d/m/Y H:i:s') . ' -> ' . $pValor . "\n");
    fclose($a);
}