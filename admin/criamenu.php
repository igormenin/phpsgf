<?php

require_once('../cliente/seguranca.php');

function montaMenu($idPai = null, $nivel = 0)
{
    try {
        $menuMontado = '';
        $listMenu = array();

        if (is_null($idPai)==true) {
            $listMenu = getMenus();
        } else {
            $listMenu = getMenus($idPai);
        }

        foreach ($listMenu as $menu) {
            if ($menu['numfilhos'] == 0 AND $menu['caminho'] != '#') {
                $caminhoFinalLink = $menu['caminho'];
                if ( $menu['parametros'] != '' ){
                    $caminhoFinalLink .= '?' . $menu['parametros'];
                }
                $menuMontado .= montaLink($menu['icone'], $caminhoFinalLink, $menu['nome'],$nivel);
            } elseif ($menu['numfilhos'] > 0 && $menu['caminho'] == '#') {
                $menuMontado .= montaMenuSubMenu($nivel, $menu['caminho'], $menu['icone'], $menu['nome'], $menu['id']);
            } else {
                $caminhoFinal = $menu['caminho'];
                if ( $menu['parametros'] != '' ){
                    $caminhoFinal .= '?' . $menu['parametros'];
                }
                $menuMontado .= montaItemMenu($caminhoFinal, $menu['icone'], $menu['nome'],$nivel);
            }
        }
        return $menuMontado;
    } catch (Exception $e) {
        print_r('Erro: ' . $e);
    }
}

function geraTextIcone($reficone)
{
    $icone = '';
    if ($reficone != '') {
        $icone = '<i class="fa ' . $reficone . '" aria-hidden="true"></i> ';
    }
    return $icone;
}

function montaLink($reficone, $caminho, $nome, $nivel)
{
    $retorno = '<li><a href="' . $caminho . '"> '  . geraTextIcone($reficone) . $nome . '</a></li>';
    return $retorno;
}

function montaMenuSubMenu($nivel, $caminho, $icone, $nome, $id)
{
    $subnivel = '';
    $notSubnivel='<b class="caret"></b>';
    if ($nivel > 0 and $caminho == '#') {
        $subnivel = ' dropdown-submenu';
        $notSubnivel='';
    }
    $retorno = '<li class="dropdown' . $subnivel . '"><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
    $retorno .= geraTextIcone($icone) . $nome . $notSubnivel .'</a><ul class="dropdown-menu">';
    $retorno .= montaMenu($id, $nivel + 1);
    $retorno .= '</ul></li>';
    return $retorno;
}

function montaItemMenu($caminho, $icone, $nome, $nivel)
{
    $retorno = '<li><a href="' . $caminho . '">' . geraTextIcone($icone) . $nome . '</a></li>';
    return $retorno;
}

function rand_color()
{
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

