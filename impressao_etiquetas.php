<?php
/**
 * Created by PhpStorm.
 * User: adminsms
 * Date: 28/09/15
 * Time: 15:40
 */
require_once("Escpos.php");


function imprimiretiquetas($tipoprint, $modeloprint, $nomeunidade, $sendata, $sennumero, $serdesc, $enderecoprint, $portaprint, $subcabecalho)
{
    try {
//        return print_r($tipoprint . ' - ' . $modeloprint . ' - ' . $nomeunidade . ' - ' . $sendata . ' - ' . $sennumero . ' - ' . $serdesc . ' - ' . $enderecoprintnt . ' - ' . $portaprint . ' - ' . $subcabecalho);
        if (strtoupper($tipoprint) == "IP") {
            if (strtoupper($modeloprint) == "TMT20") {
                tmt20escpos($enderecoprint, $portaprint, $sendata, $serdesc, $sennumero, $nomeunidade, $subcabecalho);
            }
        }
    } catch (Exception $e) {
        return $e;
    }
}


function tmt20escpos($caminhoImpressora, $portaImpressora, $dataHora, $tipo, $senha, $cabecalho, $subcabecalho = "")
{
    try {
        $connector = null;
        $connector = new NetworkPrintConnector($caminhoImpressora, $portaImpressora);
        $printer = new Escpos($connector);
        $printer->setJustification(Escpos::JUSTIFY_CENTER);
//        $printer->setTextSize(1, 1);
//        $printer->text("-----------------------------------");
//        $printer->feed();
        $printer->setTextSize(1, 2);
        $printer->text($cabecalho);
        if ($subcabecalho != null && $subcabecalho != '') {
            $printer->feed();
            $printer->setTextSize(1, 1);
            $printer->text($subcabecalho);
        }
        $printer->feed();
        $printer->setTextSize(1, 1);
        $printer->text("-----------------------------------");
        $printer->feed();
        $printer->setTextSize(1, 2);
        $printer->text($dataHora);
        $printer->setTextSize(1, 1);
        $printer->feed();
        $printer->text("-----------------------------------");
        $printer->feed();
        $printer->setTextSize(2, 2);
        $palavras = explode(" ", $tipo);
        foreach ($palavras as $palavra) {
            $printer->text($palavra);
            $printer->feed();
        }
//        $printer->feed();
        $printer->setTextSize(5, 5);
        $printer->text($senha);
//        $printer->feed();
        $printer->feed();
        $printer->setTextSize(1, 1);
        $printer->text("-----------------------------------");
        $printer->feed();
        $printer->feed();
        $printer->cut();

        /* Close printer */
        $printer->close();

        echo "OK";

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


?>