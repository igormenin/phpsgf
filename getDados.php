<?php
if (session_status() == PHP_SESSION_NONE){
	session_start();
}
header("Access-Control-Allow-Headers", "");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Request-Headers", "*");
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Content-Security-Policy: upgrade-insecure-requests');

include_once("./Serializer.class.php");
require_once("./cliente/conexao.php");
include_once("./impressao_etiquetas.php");


date_default_timezone_set('America/Sao_Paulo');

$funcao = strtolower($_POST['funcao']??"");
$displayhostname = strtolower($_POST['displayhostname']);
if ($funcao != null) {
    // gravaArquivoLogTeste('teste');
    if ($funcao == "proxsenha") {
        buscaNovoRegistro($displayhostname);
    } elseif ($funcao == "updatesenhachamada") {
        atualizaRegistroChamado($_POST['idsenha']);
    } elseif ($funcao == "buscateclas") {
        buscaTeclasPermitidas();
    } elseif ($funcao == "imprimesenha") {
        try {
            if (validaServicoHora($_POST['teclaPress']) == true) {
                geraSenhaNova($_POST['teclaPress']);
            }
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    } elseif ($funcao == "gravalog") {
//        gravaLog($_POST['pfuncao'], $_POST['tipoarquivo'], $_POST['texto']);
    } elseif ($funcao == "buscaintervaloteclas") {
        buscaConfigIntervaloTeclas();
    } elseif ($funcao == "olostech") {
        //gravaArquivoLogTeste('EntrouOlostech');
        gravarequisicaoolostech($_POST['pmatricula'], $_POST['pnomeusuario'], $_POST['pestacao'], $_POST['plogin'], $_POST['pnomeprofissional'], $_POST['phorachamada']);
    }
}

function buscaNovoRegistro($displayhostname = null)
{
    try {
        $retornar = $_POST['retornar'];
        $stringSQL = "SELECT senhaschamadas.id_senhaschamadas,";
        $stringSQL .= " senhaschamadas.des_local,";
        $stringSQL .= " senhaschamadas.log_chamada,";
        $stringSQL .= " senhaschamadas.log_rechamado,";
        $stringSQL .= " IF(senhaschamadas.id_senha IS NOT null, (SELECT LPAD(senhas.num_sequencia,4,0) as num_sequencia FROM senhas WHERE senhas.id_senha=senhaschamadas.id_senha ),IF(senhaschamadas.id_chamadaolostech IS NOT null, (SELECT paciente.nome from paciente where paciente.id=(SELECT chamadaolostech.id_paciente FROM chamadaolostech WHERE chamadaolostech.id=senhaschamadas.id_chamadaolostech)), 'no_data')) as num_sequencia,";
        $stringSQL .= " IF(senhaschamadas.id_senha IS NOT null, (SELECT servico.des_descricao FROM servico WHERE servico.id_servico = (SELECT senhas.id_servico from senhas WHERE senhas.id_senha=senhaschamadas.id_senha)),'no_data') as des_descricao,";
        $stringSQL .= " IF(senhaschamadas.id_senha IS NOT null, (SELECT servico.sigla FROM servico WHERE servico.id_servico = (SELECT senhas.id_servico from senhas WHERE senhas.id_senha=senhaschamadas.id_senha)),'no_data') as sigla,";
        $stringSQL .= " IF(senhaschamadas.id_senha IS NOT null, (SELECT CONCAT('#',servico.des_cor) FROM servico WHERE servico.id_servico = (SELECT senhas.id_servico from senhas WHERE senhas.id_senha=senhaschamadas.id_senha)),IF(senhaschamadas.id_senha IS null, (SELECT CONCAT('#',tipoestacao.cor) FROM tipoestacao WHERE tipoestacao.id=(SELECT estacoes.id_tipoestacao FROM estacoes WHERE estacoes.id=(SELECT chamadaolostech.id_estacoes FROM chamadaolostech WHERE chamadaolostech.id=senhaschamadas.id_chamadaolostech))), 'no_data')) as cor";
        $stringSQL .= " FROM senhaschamadas";
        $stringSQL .= " WHERE senhaschamadas.log_chamada=0";
	    $stringSQL .= " AND IF(senhaschamadas.id_senha IS NOT NULL,";
		$stringSQL .= " 	    IF((SELECT COUNT(servico.id_servico) FROM servico WHERE servico.id_servico=(SELECT senhas.id_servico FROM senhas WHERE senhas.id_senha=senhaschamadas.id_senha) AND servico.id_tipoestacao IN(SELECT edi.id_tipoestacaoliberado FROM estacaodisplay as edi WHERE edi.id_estacao=(SELECT est.id FROM estacoes as est WHERE est.hostname='" . $displayhostname . "')) LIMIT 1)=1 ,1,0),";
        $stringSQL .= "         IF((SELECT COUNT(cho.id) FROM chamadaolostech as cho WHERE cho.id_estacoes IN(SELECT estacoes.id FROM estacoes WHERE estacoes.id_tipoestacao IN(SELECT edi.id_tipoestacaoliberado FROM estacaodisplay as edi WHERE edi.id_estacao=(SELECT est.id FROM estacoes as est WHERE est.hostname='" . $displayhostname . "'))) AND cho.id=senhaschamadas.id_chamadaolostech LIMIT 1)=1,1,0)) = 1";
        $stringSQL .= " ORDER BY senhaschamadas.id_senhaschamadas ASC";
        $stringSQL .= " LIMIT 1";

    //    $a = fopen("meleca.txt", "a+");
    //    fputs($a, $stringSQL . "\n");
    //    fclose($a);

        $conexao = Conexao::getInstance();
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        $resultados = $stm->fetchAll(PDO::FETCH_ASSOC);

        if ($retornar == '1') {
            $xml = new arr2xml($resultados);
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preserveWhiteSpace = TRUE;
            $dom->encoding = 'UTF-8';
            $dom->loadXML($xml->get_xml());
            $dom->formatOutput = false;
            return $dom->saveXml();
        }
    } catch (Exception $e) {
        return $e;
    }
}

function atualizaRegistroChamado($idSenhaChamada)
{
    try {
        $stringSQL = "UPDATE senhaschamadas ";
        $stringSQL .= "SET log_chamada='1' ";
        $stringSQL .= "WHERE id_senhaschamadas=" . $idSenhaChamada;
        $conexao = Conexao::getInstance();
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function buscaTeclasPermitidas()
{
    try {
        $strSQL = "select cod_botaoteclado from botao";
        $conexao = Conexao::getInstance();
        $stm = $conexao->prepare($strSQL);
        $stm->execute();
        $resultados = $stm->fetchAll(PDO::FETCH_ASSOC);

        $xml = new arr2xml($resultados);
        header('Content-Type: text/xml');
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = FALSE;
        $dom->encoding = 'UTF-8';
        $dom->loadXML($xml->get_xml());
        $dom->formatOutput = true;
        return $dom->saveXml();
    } catch (Exception $e) {
        return $e;
    }
}

function geraSenhaNova($pBotaoPress)
{
    try {

        $stringSQL = "INSERT INTO senhas (dat_gerada, num_sequencia, id_servico)";
        $stringSQL .= "VALUES (NOW(),";
        $stringSQL .= "        IF((SELECT count(sen.id_senha) as numRet";
        $stringSQL .= "            FROM senhas AS sen";
        $stringSQL .= "            LEFT JOIN servico AS ser ON sen.id_servico = ser.id_servico";
        $stringSQL .= "            LEFT JOIN botao AS bot ON ser.id_servico = bot.id_servico";
        $stringSQL .= "            WHERE bot.cod_botaoteclado = " . $pBotaoPress ;
        $stringSQL .= "                AND DATE_FORMAT(sen.dat_gerada, '%d/%m/%Y') = DATE_FORMAT(NOW(), '%d/%m/%Y')";
        $stringSQL .= "            ORDER BY sen.id_senha DESC";
        $stringSQL .= "            LIMIT 1) = 0, '1',";
        $stringSQL .= "               (SELECT (sen.num_sequencia + 1) as num_sequencia";
        $stringSQL .= "            FROM senhas AS sen";
        $stringSQL .= "            LEFT JOIN servico AS ser ON sen.id_servico = ser.id_servico";
        $stringSQL .= "            LEFT JOIN botao AS bot ON ser.id_servico = bot.id_servico";
        $stringSQL .= "            WHERE bot.cod_botaoteclado = " . $pBotaoPress ;
        $stringSQL .= "                AND DATE_FORMAT(sen.dat_gerada, '%d/%m/%Y') = DATE_FORMAT(NOW(), '%d/%m/%Y')";
        $stringSQL .= "            ORDER BY sen.id_senha DESC";
        $stringSQL .= "            LIMIT 1)),";
        $stringSQL .= "            (SELECT btn.id_servico FROM botao AS btn WHERE btn.cod_botaoteclado = " . $pBotaoPress;
        $stringSQL .= "))";


//        gravaArquivoLogTeste('geraSenhaNova - SQL -> ' . $stringSQL);

        $conexao = Conexao::getInstance();
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();


        $stringSQL = "SELECT DATE_FORMAT(sen.dat_gerada, '%d/%m/%Y %H:%i:%s') as sendata, ";
        $stringSQL .= "       CONCAT(ser.sigla,substring('0000',LENGTH(sen.num_sequencia)-4),sen.num_sequencia) as sennumero, ";
        $stringSQL .= "       ser.des_descricao as serdesc, ";
        $stringSQL .= "       conf.des_nomeUnidade as nomeunidade, ";
        $stringSQL .= "       conf.des_modeloprint as modeloprint, ";
        $stringSQL .= "       conf.ind_tipoprint as tipoprint, ";
        $stringSQL .= "       conf.des_enderecoprint as enderecoprint, ";
        $stringSQL .= "       conf.des_portaprintnetwork as portaprint, ";
        $stringSQL .= "       conf.des_subcabecalho as subcabecalho ";
        $stringSQL .= "FROM senhas AS sen ";
        $stringSQL .= "LEFT JOIN servico AS ser ON ser.id_servico=sen.id_servico ";
        $stringSQL .= "LEFT JOIN configuracao AS conf ON conf.id_configuracao = (SELECT cf.id_configuracao FROM configuracao AS cf ORDER BY cf.id_configuracao DESC LIMIT 1) ";
        $stringSQL .= "ORDER BY id_senha DESC LIMIT 1";
        $conexao2 = Conexao::getInstance();
        $stm2 = $conexao2->prepare($stringSQL);
        $stm2->execute();
        $resultado = $stm2->fetchAll(PDO::FETCH_ASSOC);

        $sennumero = $resultado[0]["sennumero"];
        $sendata = $resultado[0]['sendata'];
        $serdesc = $resultado[0]['serdesc'];
        $nomeunidade = $resultado[0]['nomeunidade'];
        $modeloprint = $resultado[0]['modeloprint'];
        $tipoprint = $resultado[0]['tipoprint'];
        $enderecoprint = $resultado[0]['enderecoprint'];
        $portaprint = $resultado[0]['portaprint'];
        $subcabecalho = $resultado[0]['subcabecalho'];

        imprimiretiquetas($tipoprint, $modeloprint, $nomeunidade, $sendata, $sennumero, $serdesc, $enderecoprint, $portaprint, $subcabecalho);

        return 'OK';

    } catch (Exception $e) {
        echo 'Erro: ' . $e->getMessage();
    }
}

function gravaLog($pFuncao, $ptipoArquivo, $pConteudo)
{
    try {
        $stringSQL = "INSERT INTO logs";
        $stringSQL .= " (desfuncao, des_tipoarquivo, des_descricao, dat_datahora)";
        $stringSQL .= " VALUES('" . $pFuncao . "',";
        $stringSQL .= " '" . $ptipoArquivo . "',";
        $stringSQL .= " '" . $pConteudo . "',";
        $stringSQL .= " now())";
        $conexao = Conexao::getInstance();
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

function buscaConfigIntervaloTeclas()
{
    try {
        $stringSQL = "select con.int_intervalopresskey as intervalo from configuracao as con";
        $stringSQL .= " ORDER BY con.id_configuracao DESC LIMIT 1";

        $conexao = Conexao::getInstance();
        $stm2 = $conexao->prepare($stringSQL);
        $stm2->execute();
        $resultado = $stm2->fetchAll(PDO::FETCH_ASSOC);

        return $resultado[0]["intervalo"];

    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
}

function gravarequisicaoolostech($pMatUsuario, $pNomeUsuario, $pEstacao, $pLoginOlos, $pNomeOlos, $pHoraChamada)
{
    try {

        $stringSQL = "SELECT requisicao_olostech('" . $pMatUsuario . "', '" . $pNomeUsuario . "', '" . $pEstacao . "', '" . $pHoraChamada . "', '" . $pLoginOlos . "', '" . $pNomeOlos . "')";
        $conexao = Conexao::getInstance();
        $stm2 = $conexao->prepare($stringSQL);
        $stm2->execute();

    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
}

function verificaExistenciaServicoHora($pCodBotaoPressionado)
{
    try {
        $stringSQL = "SELECT COUNT(sh.id_servicohora) as total";
        $stringSQL .= " FROM servicohora as sh";
        $stringSQL .= " WHERE sh.id_servico=(SELECT btn.id_servico FROM botao AS btn WHERE btn.cod_botaoteclado=" . $pCodBotaoPressionado . ')';
        $conexao = Conexao::getInstance();
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        $retorno = $stm->fetchAll(PDO::FETCH_ASSOC);
//        gravaArquivoLogTeste('SQL-VerifiSH -> ' . $stringSQL);
//        gravaArquivoLogTeste('VerifiSH -> ' . json_encode($retorno));
        if ($retorno[0]['total'] == 0) {
            return 'SemSH';
        } else {
            return 'ComSH';
        }
    } catch (Exception $e) {
        throw $e;
    }
}

function validaServicoHora($pCodBotaoPressionado)
{
    try {
        if (verificaExistenciaServicoHora($pCodBotaoPressionado) == 'ComSH') {
            $stringSQL = 'SELECT count(sh.id_servicohora) as validash';
            $stringSQL .= ' FROM servicohora as sh';
            $stringSQL .= ' WHERE DATE_FORMAT(sh.horainicio, "%H:%i") <= DATE_FORMAT(NOW(),"%H:%i")';
            $stringSQL .= ' AND DATE_FORMAT(sh.horafim, "%H:%i") >= DATE_FORMAT(NOW(),"%H:%i")';
            $stringSQL .= ' AND sh.diasemana=\'' . diaSemana() . '\' ';
            $stringSQL .= ' AND sh.id_servico=(SELECT btn.id_servico FROM botao AS btn WHERE btn.cod_botaoteclado=' . $pCodBotaoPressionado . ')';
            // gravaArquivoLogTeste('SQL-ValidaSH -> ' . $stringSQL);
            $conexao = Conexao::getInstance();
            $stm = $conexao->prepare($stringSQL);
            $stm->execute();
            $retorno = $stm->fetchAll(PDO::FETCH_ASSOC);

//            gravaArquivoLogTeste('ValidaSH -> ' . json_encode($retorno));
//            gravaArquivoLogTeste('ValidaSH -> ' . $retorno[0]['validash']);
            if ($retorno[0]['validash'] == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    } catch (Exception $e) {
        throw $e;
    }
}

function diaSemana(){
    $diaSemana = array();
    $diaSemana[0]='Dom';
    $diaSemana[1]='Seg';
    $diaSemana[2]='Ter';
    $diaSemana[3]='Qua';
    $diaSemana[4]='Qui';
    $diaSemana[5]='Sex';
    $diaSemana[6]='Sab';
    return $diaSemana[date('w')];
}

function convertArrayToXML($pArrayResult, $pInserirCabecalho = true)
{
    try {
        $xml = new arr2xml($pArrayResult, $pInserirCabecalho);
        header('Content-Type: text/xml');
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = FALSE;
        $dom->encoding = 'UTF-8';
        $dom->loadXML($xml->get_xml());
        $dom->formatOutput = true;
        return $dom->saveXml();
    } catch (Exception $e) {
        throw $e;
    }
}

function gravaArquivoLogTeste($pValor)
{
    $a = fopen("meleca.txt", "a+");
    fputs($a, date_format(new DateTime('now'), 'd/m/Y H:i:s') . ' -> ' . $pValor . "\n");
    fclose($a);
}


// function atualizaSenhasFaltanteTempoReal(){
// //Funcao Painel Administrativo
// // NÃO USAR em outro lugar.

//     $stringSQL .= ' select count(ss.id_senha) as total, ';
//     $stringSQL .= '         servico.des_descricao as servico, ';
//     $stringSQL .= '         (SELECT CONCAT(se.num_sequencia,' - ', DATE_FORMAT(se.dat_gerada,"%d/%m/%Y %T")) as dtgerada ';
//     $stringSQL .= '             FROM senhas as se ';
//     $stringSQL .= '             where se.id_servico=ss.id_servico ';
//     $stringSQL .= '             and se.id_senha NOT IN (SELECT senhaschamadas.id_senha ';
//     $stringSQL .= '                                             from senhaschamadas ';
//     $stringSQL .= '                                             WHERE senhaschamadas.id_senha IS NOT NULL) ';
//     $stringSQL .= '             and DATE_FORMAT(se.dat_gerada,'%d/%m/%Y')=DATE_FORMAT(NOW(),'%d/%m/%Y') ';
//     $stringSQL .= '             order by se.id_senha asc ';
//     $stringSQL .= '             LIMIT 1) as "senhaantiga", ';
//     $stringSQL .= '         DATE_FORMAT(NOW(),"%d/%m/%Y %T") as DataHora ';
//     $stringSQL .= ' from senhas as ss ';
//     $stringSQL .= ' left join servico on servico.id_servico=ss.id_servico ';
//     $stringSQL .= ' where DATE_FORMAT(ss.dat_gerada,'%d/%m/%Y')=DATE_FORMAT(NOW(),'%d/%m/%Y') ';
//     $stringSQL .= ' and ss.id_senha NOT IN (SELECT senhaschamadas.id_senha ';
//     $stringSQL .= '                                 from senhaschamadas ';
//     $stringSQL .= '                                 WHERE senhaschamadas.id_senha IS NOT NULL) ';
//     $stringSQL .= ' GROUP BY ss.id_servico ASC; ';
    
//     $conexao = Conexao::getInstance();
//     $stm = $conexao->prepare($stringSQL);
//     $stm->execute();
//     $retorno = $stm->fetchAll(PDO::FETCH_ASSOC);

//   }