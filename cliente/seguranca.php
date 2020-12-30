<?php
if (session_status() == PHP_SESSION_NONE){
	session_start();
}


require_once('conexao.php');


function validaHOSTNAME($pHostNameCliente)
{
    try {
        $conexao = Conexao::getInstance();
        $sql = 'SELECT *,(select des_tipoLocal from configuracao ORDER BY id_configuracao DESC LIMIT 1) as des_tipoLocal from estacoes where hostname="' . $pHostNameCliente . '" LIMIT 1';
        $stm = $conexao->prepare($sql);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (!isset($resultado[0]["hostname"])) {

            return false;
        } else {
            return true;
        }
    } catch (PDOException $e) {
        return 'Erro: ' . $e->getMessage();
    }

}

function validaUsuario($pUsuario, $pSenha, $pValidaAdmin = "0")
{
    try {
        $conexao = Conexao::getInstance();
        $sql = 'SELECT id_usuario, des_usuario, des_nome,des_senha FROM usuario where des_usuario="' . $pUsuario . '" and ind_situacao="1"';
        if ($pValidaAdmin == "1") {
            $sql .= ' AND id_tipousuario=(SELECT TU.id_tipousuario FROM tipousuario as TU WHERE TU.des_sigla="adm")';
        }

        $stm = $conexao->prepare($sql);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);

        $compar = password_verify($pSenha, $resultado[0]['des_senha']);

        if (isset($resultado) && $compar == true) {
            $_SESSION['id_usuario'] = $resultado[0]['id_usuario'];
            $_SESSION['des_usuario'] = $resultado[0]['des_usuario'];
            $_SESSION['des_nome'] = $resultado[0]['des_nome'];
            $_SESSION['des_senha'] = $resultado[0]['des_senha'];
            return true;
        } else {
            session_destroy();
            return false;
        }
    } catch (PDOException $e) {
        return retornoErro($e);
    }
}

function retornoErro(PDOException $err)
{
    return 'Erro: ' . $err->getMessage();
}

function buscaUsuarios($pIDUsuario, $usersAtivos = 1)
{
    try {
        $conexao = Conexao::getInstance();
        $sqlListUsers = "";
        $sqlListUsers .= "SELECT US.id_usuario, ";
        $sqlListUsers .= "       US.des_usuario, ";
        $sqlListUsers .= "       US.des_nome, ";
        $sqlListUsers .= "       US.des_email, ";
        $sqlListUsers .= "       US.ind_situacao, ";
        $sqlListUsers .= "       (CASE US.ind_situacao ";
        $sqlListUsers .= "             WHEN \"1\" THEN \"ATIVO\" ";
        $sqlListUsers .= "             WHEN \"0\" THEN \"INATIVO\" ";
        $sqlListUsers .= "       END) as des_situacao, ";
        $sqlListUsers .= "       TU.des_sigla,";
        $sqlListUsers .= "       TU.des_tipousuario ";
        $sqlListUsers .= "FROM usuario as US ";
        $sqlListUsers .= "LEFT JOIN tipousuario as TU ON TU.id_tipousuario=US.id_tipousuario ";
        $sqlListUsers .= "WHERE US.ind_situacao = 1 ";
        if ($pIDUsuario != 1){
            $sqlListUsers .= "AND US.des_usuario <> 'adminsms' ";
        } else {
            if ($usersAtivos == 0) {
                $sqlListUsers .= "OR US.ind_situacao = 0 ";
            }
        }
        $sqlListUsers .= "ORDER BY des_situacao ASC, TU.des_tipousuario ASC, US.id_usuario ASC";
        $stm = $conexao->prepare($sqlListUsers);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    } catch (PDOException $e) {
        return retornoErro($e);
    }
}

function getUsuario($pIDUsuario)
{
    try {
        $conexao = Conexao::getInstance();
        $sqlListUsers = "";
        $sqlListUsers .= "SELECT US.id_usuario, ";
        $sqlListUsers .= "       US.des_usuario, ";
        $sqlListUsers .= "       US.des_nome, ";
        $sqlListUsers .= "       US.des_email, ";
        $sqlListUsers .= "       US.des_senha, ";
        $sqlListUsers .= "       US.ind_situacao, ";
        $sqlListUsers .= "       US.id_tipousuario, ";
        // $sqlListUsers .= "       US.olostechuser,"
        $sqlListUsers .= "       (CASE US.ind_situacao ";
        $sqlListUsers .= "             WHEN \"1\" THEN \"ATIVO\" ";
        $sqlListUsers .= "             WHEN \"0\" THEN \"INATIVO\" ";
        $sqlListUsers .= "       END) as des_situacao, ";
        $sqlListUsers .= "       TU.des_sigla ";
        $sqlListUsers .= "FROM usuario as US ";
        $sqlListUsers .= "LEFT JOIN tipousuario as TU ON TU.id_tipousuario=US.id_tipousuario ";
        $sqlListUsers .= "WHERE US.id_usuario=" . $pIDUsuario;

        $stm = $conexao->prepare($sqlListUsers);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch (PDOException $e) {
        return retornoErro($e);
    }
}

function getServicosUsuario($p_IDUsuario)
{
    try {
        $conexao = Conexao::getInstance();
        $sqlServicos = "";
        $sqlServicos .= "SELECT US.id_servico ";
        $sqlServicos .= "FROM usuario_servico as US ";
        $sqlServicos .= "WHERE US.id_usuario=" . $p_IDUsuario;

//        return $sqlListUsers;
        $stm = $conexao->prepare($sqlServicos);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch (PDOException $e) {
        return retornoErro($e);
    }
}

function validaExisteServicoUsuario($p_ServicosUsuario, $p_IDServicoComparar)
{
    foreach ($p_ServicosUsuario as $servUser) {
        if ($servUser['id_servico'] == $p_IDServicoComparar) {
            return true;
        }
    }
    return false;
}

function validaUsuarioNovo($pUsuarioNew)
{
    $conexao = Conexao::getInstance();
    $sqlListUsers = "SELECT * from usuario as US ";
    $sqlListUsers .= "WHERE US.des_usuario='" . $pUsuarioNew . "'";
    $stm = $conexao->prepare($sqlListUsers);
    $stm->execute();
    $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
    if (isset($resultado)) {
        return true;
    } else {
        return false;
    }
}

function getServicos($pIDServico = null)
{
    try {
        $conexao = Conexao::getInstance();
        $sqlServicos = "SELECT SE.id_servico, ";
        $sqlServicos .= "       SE.des_descricao, ";
        $sqlServicos .= "       (SELECT BOT.id_botao FROM botao as BOT WHERE BOT.id_servico=SE.id_servico) as id_botao ";
        $sqlServicos .= "FROM servico as SE";
        if ($pIDServico != null) {
            $sqlServicos .= " WHERE SE.id_servico=" . $pIDServico;
        }
        $stm = $conexao->prepare($sqlServicos);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
        if ($pIDServico != null) {
            return $resultado[0];
        } else {
            return $resultado;
        }
    } catch (PDOException $e) {
        return retornoErro($e);
    }
}

function geraSenhaCriptografada($pSenha)
{
    return password_hash($pSenha, PASSWORD_DEFAULT);
}

function salvaUsuario($p_id, $p_des_usuario, $p_des_nome, $p_des_email, $p_des_senha, $p_ind_situacao, $p_tipo_usuario, $p_servicosSelecteds = null)
{
    try {
        // if ($olostechuser == ''){
        //     $olostechuser = 'nothing';
        // }
        if (isset($p_id) and $p_id != '') {
            //UPDATE
            $sqlUPDATE = 'UPDATE usuario SET ';
            $sqlUPDATE .= 'des_usuario = "' . $p_des_usuario . '"';
            $sqlUPDATE .= ', des_nome = "' . $p_des_nome . '"';
            if ($p_des_senha != '') {
                $p_des_senha = geraSenhaCriptografada($p_des_senha);
                $sqlUPDATE .= ', des_senha = "' . $p_des_senha . '"';
            }
            $sqlUPDATE .= ', des_email = "' . $p_des_email . '@jaraguadosul.sc.gov.br"';
            $sqlUPDATE .= ', id_tipousuario = (select TU.id_tipousuario from tipousuario as TU where TU.des_sigla="' . $p_tipo_usuario . '")';
            $sqlUPDATE .= ', ind_situacao = "' . $p_ind_situacao . '"';
            // $sqlUPDATE .= ', olostechuser = "' . $olostechuser . '"';
            $sqlUPDATE .= ' WHERE `id_usuario` = ' . $p_id;
            $conexao = Conexao::getInstance();
            $stm = $conexao->prepare($sqlUPDATE);
            $stm->execute();
            $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);

            if (isset($p_servicosSelecteds)) {
                $conexao = Conexao::getInstance();
                $sqlRemoveVinculoServico = 'DELETE FROM usuario_servico WHERE id_usuario=' . $p_id;
                $stm = $conexao->prepare($sqlRemoveVinculoServico);
                $stm->execute();
                $resultado2 = $stm->fetchAll(PDO::FETCH_ASSOC);
                $sqlUserServ = 'INSERT INTO usuario_servico (id_usuario, id_servico) VALUES';
                $rodo = '';
                foreach ($p_servicosSelecteds as $serv) {
                    $sqlUserServ .= $rodo;
                    $sqlUserServ .= '(' . $p_id . ',' . $serv . ')';
                    $rodo = ', ';
                }
                $conexao = Conexao::getInstance();
                $stm = $conexao->prepare($sqlUserServ);
                $stm->execute();
                $resultado2 = $stm->fetchAll(PDO::FETCH_ASSOC);
            }

            return $resultado;
        } else {
            //INSERT
            $sqlINSERT = 'INSERT INTO usuario (des_usuario,des_senha,des_nome,des_email,id_tipousuario,ind_situacao)';
            $sqlINSERT .= 'VALUES (';
            $sqlINSERT .= '"' . $p_des_usuario . '",';

            $p_des_senha = geraSenhaCriptografada($p_des_senha);

            $sqlINSERT .= '"' . $p_des_senha . '",';
            $sqlINSERT .= '"' . $p_des_nome . '",';
            $sqlINSERT .= '"' . $p_des_email . '@jaraguadosul.sc.gov.br",';
            $sqlINSERT .= '(select TU.id_tipousuario from tipousuario as TU where TU.des_sigla="' . $p_tipo_usuario . '"),';
            // $sqlINSERT .= '"' . $olostechuser . '", '
            $sqlINSERT .= '"' . $p_ind_situacao . '");';
            $conexao = Conexao::getInstance();
            $stm = $conexao->prepare($sqlINSERT);
            $stm->execute();
            $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        }
    } catch (PDOException $e) {
        return retornoErro($e);
    }
}

function deletaUser($pIDUser)
{
    return '';
    $strRemove = 'delete from usuario where id_usuario=' . $pIDUser;
    $conexao = Conexao::getInstance();
    $stm = $conexao->prepare($strRemove);
    $stm->execute();
    $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
}

function buscaBotoes()
{
    try {
        $conexao = Conexao::getInstance();
        $sqlBotoes = "";
        $sqlBotoes .= "select BT.*,SRV.des_descricao as des_servico from botao as BT ";
        $sqlBotoes .= "LEFT JOIN servico as SRV ON SRV.id_servico=BT.id_servico ";
        $sqlBotoes .= "ORDER BY BT.id_botao";
        $stm = $conexao->prepare($sqlBotoes);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch (PDOException $e) {
        return retornoErro($e);
    }
}

function buscaBotao($pIDBotao)
{
    try {
        $conexao = Conexao::getInstance();
        $sqlBotao = "SELECT BT.id_botao, BT.des_descricao, BT.cod_botaoteclado, BT.id_servico ";
        $sqlBotao .= " FROM botao AS BT ";
        $sqlBotao .= " WHERE BT.id_botao=" . $pIDBotao;
        $stm = $conexao->prepare($sqlBotao);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch (PDOException $e) {
        return retornoErro($e);
    }
}

function salvaBotao($pIdBotao, $pDescricao, $pCodBotao, $pIdServico){
    try {

        $conexao = Conexao::getInstance();
        $stringSQL = '';
        if ( $pIdBotao > 0){
            $stringSQL = "UPDATE botao AS btn ";
            $stringSQL .= "SET btn.des_descricao='" . $pDescricao . "' ";
            $stringSQL .= ", btn.cod_botaoteclado='" . $pCodBotao . "'";
            $stringSQL .= ", btn.id_servico=" . $pIdServico;
            $stringSQL .= " WHERE btn.id_botao=" . $pIdBotao;
        } else {
            $stringSQL = "INSERT INTO botao AS BTN";
            $stringSQL .= " (id_servico, des_descricao, cod_botaoteclado)";
            $stringSQL .= " VALULES(" . $pIdServico . ",'" . $pDescricao . "','" . $pCodBotao . "')";
        }
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        return 'OK';
    } catch (Exception $e) {
         return $e->getMessage();
    }
}

function getMenus($idPai = null)
{
    try {
        $conexao = Conexao::getInstance();
        $stringSQL = 'SELECT menu1.id,';
        $stringSQL .= '   menu1.nome,';
        $stringSQL .= '   menu1.caminho,';
        $stringSQL .= '   menu1.icone,';
        $stringSQL .= '   menu1.parametros,';
        $stringSQL .= '   menu1.id_menupai as idPai,';
        $stringSQL .= '   (SELECT count(menu2.id) FROM menu as menu2 WHERE menu2.id_menupai = menu1.id) as numfilhos ';
        $stringSQL .= 'FROM menu as menu1';
        if (is_null($idPai) == true) {
            $stringSQL .= ' WHERE menu1.id_menupai is null';
        } else {
            $stringSQL .= ' WHERE menu1.id_menupai=' . $idPai;
        }
        $stringSQL .= ' ORDER BY menu1.nome ASC';

        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch (PDOException $e) {
        return retornoErro($e);
    }
}

function truncate($string, $length, $dots = "...")
{
    return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
}

function liberaPassRecovery()
{
    try {
        $conexao = Conexao::getInstance();
        $stringSQL = "select con.log_permiterecoverypass as recovery from configuracao as con";
        $stringSQL .= " ORDER BY con.id_configuracao DESC LIMIT 1";
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        $retorno = $stm->fetchAll(PDO::FETCH_ASSOC);
        if ($retorno[0]['recovery'] == 1) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }

}

function enviarecuperacaosenha($pUsuario)
{
    try {
        $conexao = Conexao::getInstance();
        $stringSQL = 'SELECT * FROM usuario';
        $stringSQL .= ' WHERE usuario.des_usuario="' . $pUsuario . '"';
        $stringSQL .= '   AND usuario.log_olostech="1"';
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);

        $resultado = $resultado[0];

        $horario = new DateTime('now');
        $horarioLimite = new DateTime('now');
        $dateInter = new DateInterval('PT1H');
        $horarioLimite->add($dateInter);
        $recohash = geraHashRecuperacao($resultado, $horario);


        $retornoGrava = gravaRecuperacao($resultado, $recohash, $horario, $horarioLimite);
        if ($retornoGrava == true) {
            $retEmail = enviaEmailRecuperacao($resultado, geraLinkRecuperacao($recohash), 'SGF - Sistema de Gerenciamento de Filas', $horario, $horarioLimite);
            return 'Recuperação enviada com sucesso!<br>Verifique seu e-mail: ' . $resultado['des_email'];
        } else {
             return 'IFRetornoGrava Falhou' . $retornoGrava;
        }
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

/**
 * @param $pUsuario - Passar o usuário com todos os dados em formato de array
 * @param $pLink - passar o link gerado para o usuário acessar o sistema e efetuar a troca da senha.
 * @param $sisName - Nome do sistema.
 * @param $horario - horário gerado
 * @param $horaLimite - horário limite
 * @return string - retorno em String.
 */
function enviaEmailRecuperacao($pUsuario, $pLink, $sisName, $horario, $horaLimite)
{
    try {
        $unidade = getLocalServidor();
        $assunto = 'Recuperação de Senha - (' . $sisName . ') - Unidade: ' . $unidade;
        $corpoEmail = '<div style="background-color: lightgray;" >
	<table style="width: 100%;" cellpadding="3px" border="0px"><tbody>
		<tr><td style="width: 100%; text-align: center; font-size: 18pt; background-color: darkgray;" colspan="2"><strong>Recuperação de Senha</strong></td></tr>
		<tr><td style="width: 20%; text-align: right;">Sistema:</td><td style="width: 78.9349%;">' . $sisName . '</td></tr>
		<tr><td style="width: 20%; text-align: right;">Unidade:</td><td style="width: 78.9349%;">' . $unidade . '</td></tr>
		<tr><td style="width: 20%; text-align: right;">Horário:</td><td style="width: 78.9349%;">' . date_format($horario, 'd/m/Y H:i:s') . '</td></tr>
		<tr><td style="width: 20%; text-align: right;">Validade:</td><td style="width: 78.9349%;">' . date_format($horaLimite, 'd/m/Y H:i:s') . '</td></tr>
		<tr><td style="width: 20%; text-align: right;">Usuário:</td><td style="width: 78.9349%;">' . $pUsuario['des_usuario'] . '</td></tr>
		<tr><td style="width: 20%; text-align: right;">Nome:</td><td style="width: 78.9349%;">' . $pUsuario['des_nome'] . '</td></tr>
		<tr><td style="width: 20%; text-align: right;">E-mail:</td><td style="width: 78.9349%;">' . $pUsuario['des_email'] . '</td></tr>
		<tr><td style="width: 20%; text-align: center;" colspan="2"><strong><em>O link abaixo só funciona estando nos computadores da unidade mencionada acima!</strong></em></td></tr>
		<tr><td style="width: 20%; text-align: center;" colspan="2">' . $pLink . '</td></tr>
		<tr><td style="width: 20%; text-align: center;" colspan="2"></td></tr>
		<tr><td style="width: 20%; text-align: center;" colspan="2">NÃO RESPONDA ESSE E-MAIL</td></tr>
		<tr><td style="width: 20%; text-align: center;" colspan="2"></td></tr>
		<tr><td style="width: 20%; text-align: center;" colspan="2">Caso não tenha efetuado a solicitação, ignorar essa mensagem!</td></tr>
	</tbody></table>
</div>';

        require_once('../admin/phpmailer/class.phpmailer.php');
        $mail = new PHPMailer();
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "none";
        $mail->Host = "webmail.jaraguadosul.sc.gov.br";
        $mail->Port = 25;
        $mail->Username = "no-reply";
        $mail->Password = "OGNmNDBk";
        $mail->CharSet = 'utf-8';
        $mail->SetFrom('no-reply@jaraguadosul.sc.gov.br', 'no-reply - SEMSA PMJS');
        $mail->AddReplyTo($pUsuario['des_email'], $pUsuario['des_nome']);
        $mail->Subject = $assunto;
        $mail->MsgHTML($corpoEmail);
        $mail->AddAddress($pUsuario['des_email'], $pUsuario['des_nome']);
        $mail->Send();

        return 'OK-Enviado';
    } catch (Exception $e) {
        return 'Ocorreu um erro ao tentar enviar o e-mail: ' . $e->getMessage();
    }

}

function getLocalServidor()
{
    try {
        $conexao = Conexao::getInstance();
        $stringSQL = "select con.des_nomeUnidade as unidade from configuracao as con";
        $stringSQL .= " ORDER BY con.id_configuracao DESC LIMIT 1";
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $resultado[0]['unidade'];
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function geraLinkRecuperacao($hash)
{
    return 'http://' . $_SERVER['SERVER_ADDR'] . '/senhas/admin/recoverypass.php?recohash=' . $hash;
}

function gravaRecuperacao(array $pUsuario, $pHash, $pHora, $pHoraLimite)
{
    try {
        $conexao = Conexao::getInstance();
        $stringSQL = 'UPDATE recuperasenha SET recuperasenha.log_usado="1" WHERE recuperasenha.id_usuario_usuario=' . $pUsuario['id_usuario'];
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();

        $stringSQL = 'INSERT INTO recuperasenha (hash, datahora, datahoralimite, id_usuario_usuario)';
        $stringSQL .= ' VALUES ("' . $pHash . '",';
        $stringSQL .= '         "' . date_format($pHora, "Y-m-d H:i:s") . '",';
        $stringSQL .= '         "' . date_format($pHoraLimite, "Y-m-d H:i:s") . '",';
        $stringSQL .= '         ' . $pUsuario['id_usuario'] . ')';
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        return true;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function atualizaSenhaRecovery($pHash, $pSenha)
{
    try {
        $conexao = Conexao::getInstance();
        $stringSQL = 'UPDATE usuario SET des_senha="' . geraSenhaCriptografada($pSenha) . '" ';
        $stringSQL .= 'WHERE usuario.id_usuario=(SELECT recuperasenha.id_usuario_usuario FROM recuperasenha WHERE recuperasenha.hash="' . $pHash . '"); ';
        $stringSQL .= 'UPDATE recuperasenha SET recuperasenha.log_usado="1" WHERE recuperasenha.hash="' . $pHash . '"';
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        return true;
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

function validaTempoUsoHash($pHash)
{
    try {
        $conexao = Conexao::getInstance();
        $dataHoraAgora = new DateTime('now');
        $stringSQL = 'SELECT count(recuperasenha.id) as retTotal FROM recuperasenha ';
        $stringSQL .= 'WHERE recuperasenha.hash="' . $pHash;
        $stringSQL .= '" and recuperasenha.datahoralimite>="' . date_format($dataHoraAgora, 'Y-m-d H:i:s') . '"';

        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);

        if ($resultado[0]['retTotal'] == 0) {
            return false;
        } else {
            return true;
        }
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function validaHashJaUsado($pHash)
{
    try {
        $conexao = Conexao::getInstance();
        $dataHoraAgora = new DateTime('now');
        $stringSQL = 'SELECT count(recuperasenha.id) as retTotal FROM recuperasenha ';
        $stringSQL .= 'WHERE recuperasenha.hash="' . $pHash;
        $stringSQL .= '" and recuperasenha.log_usado="1"';

        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);

        if ($resultado[0]['retTotal'] == 0) {
            return false;
        } else {
            return true;
        }
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function geraHashRecuperacao(array $pUsuario, $pHora)
{
    $options = ['cost' => 11,];
    $valHashear = $pUsuario['des_usuario'] . date_format($pHora, 'dmYHis');
    return password_hash($valHashear, PASSWORD_BCRYPT, $options);
}

function getFileName($path)
{
    $retorno = explode('/', explode('.', $path)[0])[count(explode('/', explode('.', $path)[0])) - 1];
    return $retorno;
}

function getLastConfiguracaoGeral()
{
    try {
        $conexao = Conexao::getInstance();
        $stringSQL = 'SELECT id_configuracao, ';
        $stringSQL .= '    des_tipoLocal, ';
        $stringSQL .= '    des_nomeUnidade, ';
        $stringSQL .= '    des_modeloprint, ';
        $stringSQL .= '    ind_tipoprint, ';
        $stringSQL .= '    des_enderecoprint, ';
        $stringSQL .= '    des_portaprintnetwork, ';
        $stringSQL .= '    des_subcabecalho, ';
        $stringSQL .= '    des_subcabecalho2, ';
        $stringSQL .= '    int_intervalopresskey, ';
        $stringSQL .= '    log_consultMedico, ';
        $stringSQL .= '    log_permiterecoverypass, ';
        $stringSQL .= '    log_vinculartipoestacapservico, ';
        $stringSQL .= '    num_limiterechamar';
        $stringSQL .= '    FROM configuracao ';
        $stringSQL .= '    ORDER BY id_configuracao DESC LIMIT 1';
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultado) > 0) {
            return $resultado[0];
        } else {
            return null;
        }

    } catch (Exception $e) {
        return 'Erro: ' . $e->getMessage();
    }

}

function salvaConfiguracaoGeral(Array $pCampos)
{
    try {
//        var_dump($pCampos);
        $conexao = Conexao::getInstance();
        $stringSQL = "INSERT INTO configuracao (";
        $coluns = '';
        $values = '';
        foreach (array_keys($pCampos) as $keys) {
            if (strlen($coluns) > 0) {
                $coluns .= ', ';
                $values .= ', ';
            }
            $coluns .= $keys;
            $values .= "'" . $pCampos[$keys] . "'";
        }
        $stringSQL .= $coluns . ") VALUES(" . $values . ")";
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        return 'OK';
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

function getServicoHora($pidServicoHora = null, $pidServico = null)
{
    try {
        $conexao = Conexao::getInstance();
        $stringSQL = "SELECT sh.id_servicohora, ";
        $stringSQL .= "(CASE WHEN sh.diasemana = 'Dom' THEN 0 WHEN sh.diasemana = 'Seg' THEN 1 WHEN sh.diasemana = 'Ter' THEN 2 WHEN sh.diasemana = 'Qua' THEN 3 WHEN sh.diasemana = 'Qui' THEN 4 WHEN sh.diasemana = 'Sex' THEN 5 WHEN sh.diasemana = 'Sab' THEN 6 END) AS numdiasemana, ";
        $stringSQL .= " sh.diasemana,";
        $stringSQL .= " sh.horainicio,";
        $stringSQL .= " sh.horafim,";
        $stringSQL .= " ser.des_descricao as servicoDescricao";
        $stringSQL .= " FROM servicohora as sh";
        $stringSQL .= " LEFT JOIN servico as ser ON ser.id_servico=sh.id_servico";
        if ($pidServicoHora != null) {
            $stringSQL .= " WHERRE sh.id_servicohora=" . $pidServicoHora;
            if ($pidServico != null) {
                $stringSQL .= " AND sh.id_servico=" . $pidServico;
            }
        } elseif ($pidServico != null) {
            $stringSQL .= " WHERE sh.id_servico=" . $pidServico;
        }
        $stringSQL .= " ORDER BY ser.des_descricao ASC, numdiasemana ASC";
//        return $stringSQL;
        $stm = $conexao->prepare($stringSQL);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado) > 0) {
            return $resultado;
        } else {
            return array();
        }
    } catch (Exception $e) {
        return $e;
    }
}

function retornaDiaSemana($pNumeroDiaSemana)
{
    $diaSemana = array('Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado', 'Domingo');
    $diaSemanaText = array('Seg' => 'Segunda-feira', 'Ter' => 'Terça-feira', 'Qua' => 'Quarta-feira', 'Qui' => 'Quinta-feira', 'Sex' => 'Sexta-feira', 'Sab' => 'Sábado', 'Dom' => 'Domingo');
    if (is_string($pNumeroDiaSemana) == true) {
        $diaSemanaRetorno = $diaSemanaText[$pNumeroDiaSemana];
    } else {
        $diaSemanaRetorno = $diaSemana[$pNumeroDiaSemana];
    }
    return $diaSemanaRetorno;
}

function montaMenuDropDownWithFind($pNomeDropDown, $pIdDropDown, Array $pArrayItens, $pPlaceHolderFind = 'Filtrar por:')
{
    $varRetorno = '<div class="dropdown">';
    $varRetorno .= '<button class="btn btn-default dropdown-toggle" type="button" id="' . $pIdDropDown . '" data-toggle="dropdown" aria-expanded="true">';
    $varRetorno .= 'Ícones ';
    $varRetorno .= '<span class="caret"></span>';
    $varRetorno .= '</button>';
    $varRetorno .= '<ul class="dropdown-menu" role="menu" aria-labelledby="' . $pNomeDropDown . '" data-filter data-filter-label="' . $pPlaceHolderFind . '">';
    foreach ($pArrayItens as $pArrayItem) {
        $varRetorno .= '<li role="presentation"><i class="fa ' . $pArrayItem['nome'] . '" aria-hidden="true"></i>';
        $varRetorno .= '<a role="menuitem" tabindex="-1" >' . $pArrayItem['nome'] . '</a></li>';
    }
    $varRetorno .= '</ul>';
    $varRetorno .= '</div>';
    $varRetorno .= '</div>';
    $varRetorno .= '<script src="./js/bootstrap-dropdown-filter.js"></script>';
    return $varRetorno;
}

function get_post_action(array $listaBtns)
{
    foreach ($listaBtns as $param) {
        if (isset($_POST[$param])) {
            return $param;
        }
    }
}
