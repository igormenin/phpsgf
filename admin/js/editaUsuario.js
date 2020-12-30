/**
 * Created by adminsms on 25/10/16.
 */

function validarFormulario(form){
    validaUserNew();
    senha = document.formulario.des_senha.value;
    senhaRepetida = document.formulario.redes_senha.value;
    if (senha != senhaRepetida){
        alert("Senhas digitadas são diferentes!");
        document.formulario.repetir_senha.focus();
        return false;
    }

    if(document.formulario.id.value == ''){
        return validaUserNew();
    }
}

function validaUserNew(){
    var stringParametros = "funcao=validaNewUser&userdigitado=" + document.formulario.des_usuario.value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.overrideMimeType('text/html');
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var respons = xmlhttp.response;
            if (respons == true) {
                alert('Usuário digitado já está cadastrado!');
                document.formulario.des_usuario.focus();
                return false;
            }
        }
    }
    xmlhttp.open("POST", './getDadosJS.php', true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlhttp.send(stringParametros);
}

function validaExclusao(pUsuario, pNome, pEmail) {
    var msgm = 'Deseja excluir o usuário \n' + pUsuario + ' - ' + pNome + ' (' + pEmail + ')';
    var x = confirm(msgm);
    alert(x);
    window.location.replace('index.php');
}