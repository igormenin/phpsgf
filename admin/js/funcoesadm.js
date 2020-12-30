function ProgressCountdown(timeleft, text) {
  return new Promise((resolve, reject) => {
    var countdownTimer = setInterval(() => {
      timeleft--;
      minutos = parseInt(timeleft/60);
      segundos = parseInt(timeleft-(minutos*60));
      textReturn = minutos + 'm ' + segundos + 's';
      document.getElementById(text).textContent = textReturn;
      if (timeleft <= 0) {
        clearInterval(countdownTimer);
        window.location.replace("../admin/logout.php");
      }
    }, 1000);
  });
}
ProgressCountdown(600,"countdown"); 


function atualizaLista(){
  // console.log('Atualizaaaaa');
  checkUsers = 0;
  if (document.getElementById("showInativeUser").checked == true){
    checkUsers=1;
  }  
  // console.log(checkUsers);
  var stringParametros = "funcao=atualizaListaUsuarios&chkUsers=" + checkUsers;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.overrideMimeType('text/html');
  xmlhttp.open("POST", './funcoes.php', true);
  xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xmlhttp.send(stringParametros);
  window.location.replace("../admin/usuarios.php")
}