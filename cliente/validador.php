<?php
if (session_status() == PHP_SESSION_NONE){
	session_start();
}
    require_once("./conexao.php");
    
    
    $funcao = $_POST['funcao'];
    
    if ($funcao != null) {
        
        if ($funcao == "monitora") {
            monitoraSenhas();
        }
    }
    
    function monitoraSenhas()
    {
        $sql = "SELECT sen.id_senha FROM senhas AS sen ";
        $sql .= "LEFT JOIN servico AS ser ON ser.id_servico = sen.id_servico ";
        $sql .= "LEFT JOIN usuario_servico AS uss ON uss.id_servico = ser.id_servico ";
        $sql .= "WHERE uss.id_usuario = " . $_SESSION['id_usuario'];
        $sql .= " AND sen.id_senha NOT IN(SELECT sc.id_senha FROM senhaschamadas AS sc WHERE sc.id_senha IS NOT NULL) ";
        $sql .= " AND DATE_FORMAT(sen.dat_gerada,'%d/%m/%Y')=DATE_FORMAT(NOW(),'%d/%m/%Y') ";
        if (getConfigGeralVinculoTipoEstacaoServico() == 1) {
            $sql .= ' AND ser.id_tipoestacao=(select estacoes.id_tipoestacao from estacoes where estacoes.hostname="' . $_SESSION['hostNameClient'] . '") ';
        }
        $sql .= "ORDER BY ROUND(((TO_SECONDS(now())-TO_SECONDS(sen.dat_gerada))*ser.val_fator)) DESC LIMIT 1";
        
        $conexao = Conexao::getInstance();
        $stm = $conexao->prepare($sql);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
        gravaLogs($resultado);
        if (isset($resultado[0]) && $resultado[0]['id_senha'] > 0) {
            echo 1;
        } else {
            echo 0;
        }
    }
    
    function gravaLogs($pValorLog)
    {
        $a = fopen("../meleca.txt", "a+");
        fputs($a, date_format(new DateTime('now'), 'd/m/Y H:i:s') . ' -> \n' . $pValorLog . "\n");
        fclose($a);
    }
