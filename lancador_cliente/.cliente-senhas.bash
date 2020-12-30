#!/bin/bash

# Configurações
ip_servidor="192.168.33.19"

# Definições
pasta_proj="senhas"
iniciaYad="1"
lock="/tmp/scs.tmp"

# Funções
is_online () {
	ping -q -c2 $ip_servidor > /dev/null

	if [ $? -eq 0 ]; then
		return true
	else
		return false
	fi
}
kill_yad () {
	kill -9 $(cat $lock)
	rm -f "$lock"
}

# Configura acesso ao servidor de senha sem proxy
export no_proxy="$ip_servidor/32"

# Mata o processo do YAD cujo PID está no arquivo de lock
if [ -s "$lock" ]; then
	kill_yad
fi

# Rodar para sempre...
while [ true ]; do

	# Se servidor estiver online...
	if [ is_online ]; then

		# Se YAD não estiver iniciado...
		if [ $iniciaYad -eq 1 ]; then
			
		    # Inicia cliente local
		    yad --list \
			    --title='Cliente Senha' --fixed --width=340 --height=440 --geometry=325x460-1+0 --undecorated \
			    --html --browser --uri="http://$ip_servidor/$pasta_proj/cliente/login.php?hostNameCliente="$(hostname)  &

		    # Guarda o PID do processo do YAD que foi criado acima
		    pid="$!" 
		    echo $pid > "$lock"

		    # Sinaliza que YAD já foi iniciado
		    iniciaYad="0"
		fi

	# Se servidor não estiver online...
	else

		# Mata o processo do YAD
		kill_yad

		# Informa erro de conexão
		yad --title="Erro de conexão" \
		    --text="\n\n<span foreground='red'><big>   Erro ao conectar no servidor: $ip_servidor    </big></span>" \
		    --button="gtk-ok:0" --center &

		break

	fi

done