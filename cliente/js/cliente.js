// INICIO CLIENTE CONTROLE
var seted = false;

function monitoraBotaoChamar() {
  if (
    document.getElementById('jscliente').getAttribute('idsenhaValue') != '' ||
    document.getElementsByName('btnChamar').length > 0
  ) {
    if (seted == false) {
      seted = true;
      setInterval(monitoraBotaoChamar, 2000); // Alterado de 1s para 2s para teste - 18-04-2018 16:07
    }
  }
  var stringParametros = 'retornar=1&funcao=monitora';
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.overrideMimeType('text/xml');
  xmlhttp.onreadystatechange = function() {
    // processa resposta assíncrona
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      console.log(xmlhttp.responseText);
      var responseText = xmlhttp.responseText;
      if (responseText == '1') {
        controleBotoes('btnChamar', false);
      } else {
        controleBotoes('btnChamar', true);
      }
    }
  };
  xmlhttp.open('POST', './validador.php', true);
  xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xmlhttp.send(stringParametros);
}

function controleBotoes(pNameButton, pSituacao) {
  element = document.getElementsByName(pNameButton);
  element[0].setAttribute(
    'class',
    element[0].getAttribute('class').replace(/disabled /gi, '')
  );
  if (pSituacao == true) {
    element[0].setAttribute(
      'class',
      'disabled ' + element[0].getAttribute('class').toString()
    );
    element[0].style.visibility = 'hidden';
  } else {
    element[0].setAttribute(
      'class',
      element[0].getAttribute('class').replace(/disabled /gi, '')
    );
    element[0].style.visibility = 'visible';
  }
}

function abrenovajanela(pHostName) {
  console.log('Hostname: ' + pHostName);
  ip = window.location.host;
  url =
    'http://' + ip + '/senhas/cliente/login.php?hostNameCliente=' + pHostName;
  newwindow = window.open(
    url,
    '_blank',
    'toolbar=no, menubar=no, resizable=no, location=no, directories=no, status=no, copyhistory=no, width=340, height=630'
  );

  window.top.close();
}

function fecharjanela() {
  window.top.close();
}

// FIM CLIENTE CONTROLE
