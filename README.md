# PHPSGF
Sistema de Gerenciamento de Filas de Atendimento

[![GitHub license](https://img.shields.io/github/license/igormenin/phpsgf)](https://github.com/igormenin/phpsgf/blob/main/LICENSE)
![GitHub Status](https://img.shields.io/badge/Status-Production-brightgreen)

## Implantação:
- Secretaria Municipal de Saúde do Município de Jaraguá do Sul - SC
Locais implantados na SEMSA:
-- Centro Vida - Centro de Especialidades Médicas Dr. João Biron
-- Farmácia Básica
-- Laboratório Municipal

## Tecnologias Usadas:
- PHP
- MariaDB
- Javascript
- jQuery

### Descritivo Básico:
  Aplicação foi desenvolvida inicialmente pensando em substituir os paineis que eram adquiridos de fornecedores, que com o passar do tempo estragavam e não tinham como consertar ou aproveitar parte do equipamento, além do mesmo ter muitas limitações de funcionamento.
  Foi então efetuado o levantamento da demanda real para esse desenvolvimento:
  -> Ser possível a inclusão de várias filas de atendimentos (normal, idosos, idosos com +80 anos, etc...)
  -> Especificar quais computadores iriam chamar determinadas filas, não sendo possível chamar outras filas além das determinadas.
  -> Os atendentes podem chamar de qualquer computador desde que os mesmos tenham permissão para as filas que aquele computador tenha permissão. Eles também tem uma quantidade limite para re-chamar a senha x vezes, conforme configurado nas configurações gerais no painel administrativo.
  -> As filas de atendimentos também tem configurações específicas para funcionar dentro de um terminado horário, por exemplo, a fila de Transporte (Normal, Preferencial) no Centro Vida, podem ser impressas das 07:30 às 16:59, mesmo o restante dos atendimentos funcionar até as 18:00.
  -> As senhas das filas são impressas em uma impressora térmica com suporte a ESC/POS, atualmente usada impressora EPSON TM-T20 com Ethernet.
  -> O sistema contempla também uma API que funciona para integração com outro sistema SaudeTech da Empresa Olostech, sendo que os médicos ou o pessoal que faz a triagem, podem chamar os pacientes no painel de chamada pelo nome. Sendo que essa API só fica disponível para uso interno na mesma rede aonde está funcionando. Ex.: 192.168.33.0/24


### Captura de Telas
![Painel de Chamada](https://i.ibb.co/xCSYYxs/Captura-de-tela-de-srvsenhapoliclinica-0-s-2021-01-03-10-46-37.png "Painel de Chamada")
![Painel Administrativo - Login](https://i.ibb.co/kBGYTX6/Tela-Captura-Painel-Adm-Login.png "painel administrativo - Login")
![Painel Administrativo - Tela Inicial](https://i.ibb.co/6gvrr5P/Tela-Captura-Painel-Adm-Tela-Inicial.png "painel administrativo - Tela Inicial")
![Painel Administrativo - Configurações Gerais](https://i.ibb.co/k9D27CX/Tela-Captura-Painel-Adm-Conf-Geral.png "painel administrativo - Configurações Gerais")
![Painel Administrativo - Cadastro de Botões da Fila](https://i.ibb.co/NYhHtsx/Tela-Captura-Painel-Adm-Cad-Botoes-Fila.png "painel administrativo - Cadastro de Botões da Fila")
![Painel Administrativo - Lista Botões Fila](https://i.ibb.co/FDgLKYZ/Tela-Captura-Painel-Adm-Lista-Botoes.png "painel administrativo - Lista Botões Fila")
![Painel Administrativo - Serviço Horas](https://i.ibb.co/M2wn3x1/Tela-Captura-Painel-Adm-Servico-Horas.png "painel administrativo - Serviço Horas")
![Painel Administrativo - Lista Usuários](https://i.ibb.co/CBwcgzn/Tela-Captura-Painel-Adm-Lista-Usuarios.png "painel administrativo - Lista Usuários")
![Painel Administrativo - Cadastro Usuário](https://i.ibb.co/PNJg91b/Tela-Captura-Painel-Adm-Cad-Usuario.png "painel administrativo - Cadastro Usuário")

### EM BREVE MAIORES INFORMAÇÕES

-----------------------------------------

