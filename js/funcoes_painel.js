var divAtual = 'atualatual';
var campoSenhaAtual = 'txt_senhaatual';
var displayHostName = '';

//Variaveis da nova senha
var idSenhaNova = '';
var senhaNova = '';
var localNovo = '';
var corNovo = '';
var rechamadoNovo = '';
var logchamadoNovo = '';
var siglaChamado = '';
//Fim variaveis da nova senha

//Fim das variáveis das senhas anteriores
var arraybotoes = [];
var contatentativas = 0;
var lastTime = new Date();
var tempoIntervalo = 2;

function relogio() {
  var data = new Date();
  var dia = data.getDate() < 10 ? '0' + data.getDate() : data.getDate();
  var ano = data.getFullYear();
  var horas = data.getHours() < 10 ? '0' + data.getHours() : data.getHours();
  var minutos =
    data.getMinutes() < 10 ? '0' + data.getMinutes() : data.getMinutes();
  // var segundos = (data.getSeconds() < 10 ? "0" + data.getSeconds() : data.getSeconds());
  var month = new Array();
  month[0] = 'Janeiro';
  month[1] = 'Fevereiro';
  month[2] = 'Março';
  month[3] = 'Abril';
  month[4] = 'Maio';
  month[5] = 'Junho';
  month[6] = 'Julho';
  month[7] = 'Agosto';
  month[8] = 'Setembro';
  month[9] = 'Outubro';
  month[10] = 'Novembro';
  month[11] = 'Dezembro';
  var mes = month[data.getMonth()];
  var exibe = document.getElementById('data_hora');
  exibe.innerHTML =
    dia + ' de ' + mes + ' de ' + ano + ' - ' + horas + ':' + minutos; // + ":" + segundos; // + " Veri: " + acumula;
}

function buscaregistrosenha(pDisplayHostName = null) {
  if (pDisplayHostName == null){
    pDisplayHostName = displayHostName;
  }
  displayHostName = pDisplayHostName;
  var stringParametros = 'retornar=1&funcao=proxsenha&displayhostname=' + pDisplayHostName;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.overrideMimeType('text/xml');
  xmlhttp.onreadystatechange = function() {
    // processa resposta assíncrona
    idSenhaNova = '';
    senhaNova = '';
    localNovo = '';
    corNovo = '';
    rechamadoNovo = '';
    logchamadoNovo = '';
    siglaChamado = '';

    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      var responseText = xmlhttp.responseText;
      // console.log(responseText);
      if (responseText.replace(/ /g, '') != 'no_data') {
        var parser = new DOMParser();
        var xmldoc = parser.parseFromString(responseText, 'text/xml');

        var formatsequencial = '0000';
        // console.log(xmldoc);
        idSenhaNova = xmldoc.getElementsByTagName('id_senhaschamadas')[0]
          .childNodes[0].nodeValue;

        // Faz validação para caso seja uma senha chamada pelo Olostech.
        if (
          xmldoc.getElementsByTagName('sigla')[0].childNodes[0].nodeValue ==
          'no_data'
        ) {
          senhaNova = abreviaNomes(
            xmldoc.getElementsByTagName('num_sequencia')[0].childNodes[0]
              .nodeValue
          );
        } else {
          siglaChamado = xmldoc.getElementsByTagName('sigla')[0].childNodes[0]
            .nodeValue;
          senhaNova =
            xmldoc.getElementsByTagName('sigla')[0].childNodes[0].nodeValue +
            xmldoc.getElementsByTagName('num_sequencia')[0].childNodes[0]
              .nodeValue;
        }

        localNovo = xmldoc.getElementsByTagName('des_local')[0].childNodes[0]
          .nodeValue;
        corNovo = xmldoc.getElementsByTagName('cor')[0].childNodes[0].nodeValue;
        rechamadoNovo = xmldoc.getElementsByTagName('log_rechamado')[0]
          .childNodes[0].nodeValue;
        logchamadoNovo = xmldoc.getElementsByTagName('log_chamada')[0]
          .childNodes[0].nodeValue;

        atualizaOutrosCampos();

        atualizasenhachamada(idSenhaNova);
      }
    }
  };
  xmlhttp.open('POST', './getDados.php', true);
  xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  console.log(stringParametros);
  xmlhttp.send(stringParametros);
}

function atualizaOutrosCampos() {
  tocaCampainha();
  atualizaCamposDivs('txt_senhaatual', senhaNova);
  atualizaCamposDivs('txt_localatual', localNovo);
  mudacorelemento(divAtual, corNovo);

  BlinkElement(document.getElementById(divAtual), 2500);

  adicionaSenhaTable(senhaNova, localNovo, siglaChamado);
}

function adicionaSenhaTable(pNumNome, pLocal, ptipo = null) {
  var nomerefatorado = pNumNome;
  if (ptipo == '') {
    nomerefatorado = abreviaNomes(pNumNome);
  } else {
    pLocal = pLocal.split(' ')[1];
  }

  var dadosInserir =
    '<tr><td scope="col oldsenha" ><b>' +
    nomerefatorado +
    '</b></td><td scope="col oldsenha"><b>' +
    pLocal +
    '</b></td></tr>';
  var elementoTable = document.getElementById('tableanteriores');
  var elementoDIV = document.getElementById('anteriores');
  var elementoTdoby = document.getElementById('tbodyanteriores');
  var newRow = elementoTdoby.insertRow(0);

  newRow.innerHTML = dadosInserir;

  if (elementoDIV.offsetHeight - elementoTable.offsetHeight <= 22) {
    var rowCount = elementoTable.rows.length;
    elementoTable.deleteRow(rowCount - 1);
  }
}

function removeLastRow() {
  var elementoTable = document.getElementById('tableanteriores');
  var elementoDIV = document.getElementById('anteriores');
  if (elementoDIV.offsetHeight - elementoTable.offsetHeight <= 22) {
    var rowCount = elementoTable.rows.length;
    elementoTable.deleteRow(rowCount - 1);
  }
}

function atualizaCamposDivs(pCampoID, pValor) {
  var elementoSenha = document.getElementById(pCampoID);
  elementoSenha.innerHTML = pValor;
  if (campoSenhaAtual == pCampoID) {
    if (siglaChamado == '') {
      elementoSenha.className = 'senha_atualNome';
    } else {
      elementoSenha.className = 'senha_atual';
    }
  }
}

function mudacorelemento(pNomeElemento, pCor) {
  var elesel = document.getElementById(pNomeElemento);
  elesel.style.backgroundColor = pCor;
}

function getXMLToArray(xmlDoc) {
  var thisArray = new Array();
  //Check XML doc
  if ($(xmlDoc).children().length > 0) {
    //Foreach Node found
    $(xmlDoc)
      .children()
      .each(function() {
        if (
          $(xmlDoc)
            .find(this.nodeName)
            .children().length > 0
        ) {
          //If it has children recursively get the inner array
          var NextNode = $(xmlDoc).find(this.nodeName);
          thisArray[this.nodeName] = getXMLToArray(NextNode);
        } else {
          //If not then store the next value to the current array
          thisArray[this.nodeName] = $(xmlDoc)
            .find(this.nodeName)
            .text();
        }
      });
  }
  return thisArray;
}

function tocaCampainha() {
  srcplay='./media/campainha.wav';
  if (siglaChamado == ''){
    srcplay='./media/triagem.mp3';
  }
  var audio = new Audio(srcplay);
  audio.play();
}

function atualizasenhachamada(idsenha) {
  var stringParametros = 'funcao=updatesenhachamada&idsenha=' + idsenha;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.overrideMimeType('text/html');
  xmlhttp.open('POST', './getDados.php', true);
  xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xmlhttp.send(stringParametros);
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

  var formatoCOLOR = '000000';

  var corretornado = '';
  corretornado = digits[1] + '#' + rgb.toString(16);
  corretornado = corretornado.replace(/[#]/g, '');
  return (
    '#' +
    formatoCOLOR.substring(0, formatoCOLOR.length - corretornado.length) +
    corretornado
  );
}

function BlinkElement(elemento, miliseconds) {
  try {
    elemento.setAttribute('class', 'blink');

    if (window.BlinkInterval) clearInterval(window.BlinkInterval);
    window.BlinkInterval = setInterval(function() {
      elemento.removeAttribute('class');
      clearInterval(window.BlinkInterval);
    }, miliseconds);
  } catch (e) {
    return false;
  }
}

function eventkeyup(event) {
  contatentativas = 1;
  // console.log('entrou');

  if (
    float2int(new Date() / 1000) - float2int(lastTime / 1000) >=
    tempoIntervalo
  ) {
    monitoraTeclasKeyUP(event);
    lastTime = new Date();
  }
  window.focus();
}

function buscaTeclas() {
  // console.log('Buscou Teclas.');
  var stringParametros = 'funcao=buscateclas';
  window.arraybotoes = [];
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.overrideMimeType('text/html');
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      var responseText = xmlhttp.response;
      if (responseText.replace(/ /g, '') !== 'no_data') {
        var xmldocdc = new DOMParser().parseFromString(
          responseText,
          'text/xml'
        );
        var i;
        for (i = 0; i < xmldocdc.childNodes[0].childNodes.length; i++) {
          var nodePrinc = xmldocdc.childNodes[0].childNodes[i];
          var valueNode = nodePrinc.getElementsByTagName('cod_botaoteclado')[0]
            .childNodes[0].nodeValue;
          window.arraybotoes[i] = valueNode;
        }
        // console.log(window.arraybotoes);
      }
    }
  };
  xmlhttp.open('POST', './getDados.php', true);
  xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xmlhttp.send(stringParametros);
}

function monitoraTeclasKeyUP(event) {
  btnpress = event.keyCode;

  buscaConfigIntervaloTeclas();

  var i;
  for (i = 0; i < window.arraybotoes.length; i++) {
    if (window.arraybotoes[i] == btnpress) {
      console.log(btnpress);
      var stringParametros = 'funcao=imprimesenha&teclaPress=' + btnpress;
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.overrideMimeType('text/html');
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          var respons = xmlhttp.response;
          // console.log("\n" + respons);
          // // if (respons != 'OK' && contatentativas <= 5) {
          // //     contatentativas += 1;
          // //
          // //     monitoraTeclasKeyUP(event);
          // // }
        }
      };
      xmlhttp.open('POST', './getDados.php', true);
      xmlhttp.setRequestHeader(
        'Content-type',
        'application/x-www-form-urlencoded'
      );
      xmlhttp.send(stringParametros);
    }
  }
}

function buscaConfigIntervaloTeclas() {
  var stringParametros = 'funcao=buscaintervaloteclas';
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.overrideMimeType('text/html');
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      var respons = xmlhttp.response;

      if (parseInt(respons) > 0) {
        tempoIntervalo = parseInt(respons);
      } else {
        gravaLog(
          'buscaConfigIntervaloTeclas',
          'Erro ao buscar intervalo das teclas. Mensagem-> ' + respons
        );
      }
    }
  };
  xmlhttp.open('POST', './getDados.php', true);
  xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xmlhttp.send(stringParametros);
}

function gravaLog(pFuncOrigem, pTexto) {
  var stringParametros =
    'funcao=gravalog&pfuncao=' +
    pFuncOrigem +
    '&texto=' +
    btnpress +
    '&tipoarquivo=funcoes_painel.js';
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.overrideMimeType('text/html');
  xmlhttp.open('POST', './getDados.php', true);
  xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xmlhttp.send(stringParametros);
}

function float2int(value) {
  return value | 0;
}

/**
 * @description Função para efetuar o chamado dos pacientes pela sala de espera do Olostech.
 * @param pEnderecoPost - Endereço configurado no cadastro da unidade.
 * @param pMatUsuario - Matrícula do Paciente
 * @param pNomeUsuario - Nome completo do paciente
 * @param pEstacao - Nome/Número da estação que está chamando o paciente
 * @param pLogin - Login do profissional que está chamando o paciente
 * @param pNomeProfissional - Nome completo do profissional que está chamando o paciente.
 * @param pHora - Hora que o profissional efetuou o chamado do paciente.
 */
function chamapaciente(
  pEnderecoPost,
  pMatUsuario,
  pNomeUsuario,
  pEstacao,
  pLogin,
  pNomeProfissional,
  pHora
) {
  var stringParametros =
    'funcao=olostech&pmatricula=' +
    pMatUsuario +
    '&pnomeusuario=' +
    pNomeUsuario +
    '&pestacao=' +
    pEstacao +
    '&plogin=' +
    pLogin +
    '&pnomeprofissional=' +
    pNomeProfissional +
    '&phorachamada=' +
    pHora;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.overrideMimeType('text/html');
  xmlhttp.open('POST', pEnderecoPost, true);
  xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xmlhttp.send(stringParametros);
}

function abreviaNomes(pNome) {
  // console.log(pNome);
  var nameArray = new Array();
  nameArray = pNome.split(' ');
  // console.log(nameArray);
  var nameReturn = '';
  var i;
  for (i = 0; i <= nameArray.length - 1; i++) {
    if (i == 0) {
      nameReturn =
        nameArray[i].substring(0, 1).toUpperCase() +
        nameArray[i].substring(1).toLowerCase();
    } else if (i == nameArray.length - 1) {
      nameReturn +=
        ' ' +
        nameArray[i].substring(0, 1).toUpperCase() +
        nameArray[i].substring(1).toLowerCase();
    } else {
      if (nameArray[i].length > 2) {
        nameReturn += ' ' + nameArray[i].substring(0, 1).toUpperCase() + '.';
      } else {
        nameReturn += ' ' + nameArray[i];
      }
    }
    // console.log(nameReturn);
  }
  return nameReturn;
}

setInterval(buscaConfigIntervaloTeclas, 3600000);
setInterval(relogio, 1000);
setInterval(buscaregistrosenha, 4500);
