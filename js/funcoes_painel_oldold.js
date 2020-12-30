var acumula = 1;
function relogio() {
    var data = new Date();
    var dia = (data.getDate() < 10 ? "0" + data.getDate() : data.getDate());
    var ano = data.getFullYear();
    var horas = (data.getHours() < 10 ? "0" + data.getHours() : data.getHours());
    var minutos = (data.getMinutes() < 10 ? "0" + data.getMinutes() : data.getMinutes());
    var segundos = (data.getSeconds() < 10 ? "0" + data.getSeconds() : data.getSeconds());
    var month = new Array();
    month[0] = "Jeneiro";
    month[1] = "Fevereiro";
    month[2] = "Março";
    month[3] = "Abril";
    month[4] = "Maio";
    month[5] = "Junho";
    month[6] = "Julho";
    month[7] = "Agosto";
    month[8] = "Setembro";
    month[9] = "Outubro";
    month[10] = "Novembro";
    month[11] = "Dezembro";
    var mes = month[data.getMonth()];
    var exibe = document.getElementById("data_hora");
    exibe.innerHTML = dia + " de " + mes + " de " + ano + " - " + horas + ":" + minutos //+ ":" + segundos; // + " Veri: " + acumula;
}

function mudacorelemento(pNomeElemento, pCor) {
    var elesel = document.getElementById(pNomeElemento);
    console.log(pNomeElemento + ' - cor: ' + pCor);
    elesel.style.backgroundColor = pCor;
}

function buscaregistrosenha() {

    var stringParametros = "retornar=1&funcao=proxsenha";

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.overrideMimeType('text/xml');
    xmlhttp.onreadystatechange = function () {   // processa resposta assíncrona
        console.log("STATE: " + xmlhttp.readyState.toString());
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {
            //alert(xmlhttp.returnValue);
            var responseText = xmlhttp.responseText;
            //alert(responseText);

            var parser = new DOMParser();
            var xmldoc = parser.parseFromString(responseText, "application/xml");

            var formatsequencial = "0000";

            console.log(responseText);

            var idSenhaNova = xmldoc.getElementsByTagName('id_senhaschamadas')[0].childNodes[0].nodeValue
            var senhaNova = xmldoc.getElementsByTagName('sigla')[0].childNodes[0].nodeValue + formatsequencial.substring(0, formatsequencial.length - xmldoc.getElementsByTagName('num_sequencia')[0].childNodes[0].nodeValue.length) + xmldoc.getElementsByTagName('num_sequencia')[0].childNodes[0].nodeValue;
            var localNovo = xmldoc.getElementsByTagName('des_local')[0].childNodes[0].nodeValue;
            var corNovo = xmldoc.getElementsByTagName('cor')[0].childNodes[0].nodeValue;
            var rechamadoNovo = xmldoc.getElementsByTagName('log_rechamado')[0].childNodes[0].nodeValue;
            var logchamadoNovo = xmldoc.getElementsByTagName('log_chamada')[0].childNodes[0].nodeValue;

            console.log("Senha_Nova: " + senhaNova + '\nLocal_Novo: ' + localNovo + '\nCor_Nova: ' + corNovo + '\nRechamnado: ' + rechamadoNovo + '\nLogChamado: ' + logchamadoNovo);

            var exibiu1 = false;
            var exibiu2 = false;
            var exibiu3 = false;
            var exibiu4 = false;

            //console.log('CONTAINER: ' + document.getElementById("container_atual").style.visibility.toString());
            if (document.getElementById("container_atual").style.visibility == "hidden") {
                document.getElementById("container_atual").style.visibility = "visible";
                exibiu1 = true;
            } else {
                if (document.getElementById("right").style.visibility == "hidden") {
                    document.getElementById("container").style.visibility = "visible";
                    document.getElementById("right").style.visibility = "visible";
                    exibiu2 = true;
                } else {
                    if (document.getElementById("center").style.visibility == "hidden") {
                        document.getElementById("center").style.visibility = "visible";
                        exibiu3 = true;
                    }
                    else{
                        if (document.getElementById("left").style.visibility == "hidden") {
                            document.getElementById("left").style.visibility = "visible";
                            exibiu4 = true;
                        }
                    }
                }
            }

            //DADOS DA SENHA1 PEGANDO DA ULTIMA SENHA CHAMADA (container_atual)
            var senhaatual = document.getElementById("txt_senhaatual").innerHTML;
            var coratual = rgb2hex(jQuery("#container_atual").css("background-color"));
            var local1 = document.getElementById("txt_localatual").innerHTML;

            //console.log(senhaNova + '\n' + corNovo + '\n' + localNovo);
            if (exibiu1 == true) {

                if (exibiu2 == true) {
                    //DADOS DA SENHA2 PEGANDO DA SENHA1 (RIGHT)
                    var senha2 = document.getElementById('senha1').innerHTML;
                    var cor2 = rgb2hex(jQuery("#right").css("background-color"));
                    var local2 = document.getElementById('senha1_guiche').innerHTML;
                    if (exibiu3 == true) {
                        //DADOS DA SENHA3 PEGANDO DA SENHA2 (CENTER)
                        var senha3 = document.getElementById('senha2').innerHTML;
                        var cor3 = rgb2hex(jQuery("#center").css("background-color"));
                        var local3 = document.getElementById('senha2_guiche').innerHTML;
                    }
                }

                //Altera dados da nova senha

                var elmt_senhatual = document.getElementById('txt_senhaatual');
                elmt_senhatual.innerHTML = senhaNova;
                var elmt_locatual = document.getElementById('txt_localatual');
                elmt_locatual.innerHTML = localNovo;

                //Atualiza a senha para que ja foi chamada
                console.log("ID Senha Chamada: " + idSenhaNova);
                atualizasenhachamada(idSenhaNova);
                //Toca a campainha
                tocaCampainha();
                console.log("BLINKAR");
                //BlinkElement(document.getElementById("container_atual"),3000);

                //intercalacoratual("container_atual", corNovo);

                mudacorelemento("container_atual", corNovo);

                if (rechamadoNovo == 0) {

                    var elmt_senha1 = document.getElementById('senha1');
                    elmt_senha1.innerHTML = senhaatual;
                    var elmt_locsenha1 = document.getElementById('senha1_guiche');
                    elmt_locsenha1.innerHTML = local1;
                    mudacorelemento("right", coratual);
                    if (exibiu2 == true) {
                        var elmt_senha2 = document.getElementById('senha2');
                        elmt_senha2.innerHTML = senha2;
                        var elmt_locsenha2 = document.getElementById('senha2_guiche');
                        elmt_locsenha2.innerHTML = local2;
                        mudacorelemento("center", cor2);
                        if (exibiu3 == true) {
                            var elmt_senha3 = document.getElementById('senha3');
                            elmt_senha3.innerHTML = senha3;
                            var elmt_locsenha3 = document.getElementById('senha3_guiche');
                            elmt_locsenha3.innerHTML = local3;
                            mudacorelemento("left", cor3);
                        }
                    }
                }



            }

        }
    }
    xmlhttp.open("POST", './getDados.php', true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlhttp.send(stringParametros);
    
}

function getXMLToArray(xmlDoc) {
    var thisArray = new Array();
    //Check XML doc
    if ($(xmlDoc).children().length > 0) {
        //Foreach Node found
        $(xmlDoc).children().each(function () {
            if ($(xmlDoc).find(this.nodeName).children().length > 0) {
                //If it has children recursively get the inner array
                var NextNode = $(xmlDoc).find(this.nodeName);
                thisArray[this.nodeName] = getXMLToArray(NextNode);
            } else {
                //If not then store the next value to the current array
                thisArray[this.nodeName] = $(xmlDoc).find(this.nodeName).text();
            }
        });
    }
    return thisArray;
}

function tocaCampainha() {
    $('#audioalert')[0].play();
}

function atualizasenhachamada(idsenha) {
    console.log("Inicia Atualização da Senha..." + idsenha);
    var stringParametros = "funcao=updatesenhachamada&idsenha=" + idsenha;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.overrideMimeType('text/html');
    xmlhttp.open("POST", './getDados.php', true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlhttp.send(stringParametros);
    xmlhttp.abort();
}

function hex2rgb(hex) {
    /*hex = hex.replace(/ |#/g, '');
     if (hex.length === 3) hex = hex.replace(/(.)/g, '$1$1');

     // http://stackoverflow.com/a/6637537/1250044
     hex = hex.match(/../g);
     return [parseInt(hex[0], 16), parseInt(hex[1], 16), parseInt(hex[2], 16)];*/
}

function rgb2hex(color) {
    if (color.substr(0, 1) === '#') {
        return color;
    }
    var digits = /(.*?)rgb\((\d+), (\d+), (\d+)\)/.exec(color);

    var red = parseInt(digits[2]);
    var green = parseInt(digits[3]);
    var blue = parseInt(digits[4]);

    var rgb = blue | (green << 8) | (red << 16);
    return digits[1] + '#' + rgb.toString(16);
}

function BlinkElement(elemento, miliseconds)    {
    try    {
        elemento.setAttribute('class', 'blink');

        if(window.BlinkInterval)
            clearInterval(window.BlinkInterval);
        window.BlinkInterval = setInterval(    function(){
            elemento.removeAttribute('class');
            clearInterval(window.BlinkInterval);    }, miliseconds);
    }
    catch (e)    {
        return false;
    }
}


setInterval(relogio, 500);
setInterval(buscaregistrosenha, 7000);