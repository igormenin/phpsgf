-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 19/06/2020 às 18:43
-- Versão do servidor: 10.1.44-MariaDB-0ubuntu0.18.04.1
-- Versão do PHP: 7.2.24-0ubuntu0.18.04.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `senhas`
--
CREATE DATABASE IF NOT EXISTS `senhas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `senhas`;

DELIMITER $$
--
-- Funções
--
DROP FUNCTION IF EXISTS `proximasenhapainel`$$
CREATE DEFINER=`inext`@`localhost` FUNCTION `proximasenhapainel` (`phostnamedisplay` VARCHAR(45)) RETURNS VARCHAR(100) CHARSET utf8 BEGIN
DECLARE id_senhachamada INT DEFAULT 0;

return id_senhachamada;
END$$

DROP FUNCTION IF EXISTS `requisicao_olostech`$$
CREATE DEFINER=`inext`@`localhost` FUNCTION `requisicao_olostech` (`pmatricula` VARCHAR(45), `pnomepaciente` VARCHAR(500), `pestacao` VARCHAR(10), `phora` VARCHAR(30), `plogin` VARCHAR(30), `pnomeprofissional` VARCHAR(300)) RETURNS VARCHAR(100) CHARSET utf8 BEGIN
DECLARE id_paciente INT DEFAULT 0;
DECLARE id_estacao INT DEFAULT 0;
DECLARE msgerro VARCHAR(100) DEFAULT '';
DECLARE id_chamadaolostech INT DEFAULT 0;
DECLARE desLocal VARCHAR(100) DEFAULT '';
DECLARE idSenhaChamada INT DEFAULT 0;

if (pmatricula != '' and pnomepaciente != '') then
    SELECT paciente.id INTO id_paciente FROM paciente
		WHERE paciente.matricula = pmatricula;
    if ( id_paciente = 0 ) then
        INSERT INTO paciente (matricula,nome) values(pmatricula,pnomepaciente); 
        SELECT paciente.id INTO id_paciente FROM paciente
				WHERE paciente.matricula = pmatricula;
    end if;

        SELECT estacoes.id, CONCAT(tipoestacao.des_tipo, ' ', estacoes.numero) INTO id_estacao, desLocal FROM estacoes
		LEFT JOIN tipoestacao ON tipoestacao.id=estacoes.id_tipoestacao
		WHERE estacoes.codolostech = pestacao;

	if ( id_estacao > 0 ) then
		INSERT INTO chamadaolostech (id_estacoes,id_paciente,olostechdatahora,olostechusuario,olostechnomeusuario) VALUES(id_estacao,id_paciente, phora, plogin, pnomeprofissional);
        
		SELECT chamadaolostech.id INTO id_chamadaolostech FROM chamadaolostech
				WHERE chamadaolostech.id_estacoes=id_estacao
				  AND chamadaolostech.id_paciente=id_paciente
				  AND chamadaolostech.olostechdatahora=phora
                  AND chamadaolostech.olostechusuario=plogin
                  AND chamadaolostech.olostechnomeusuario=pnomeprofissional;
                  
		INSERT INTO senhaschamadas (dat_datahorachamada, id_usuario, des_local, id_chamadaolostech)
			VALUES(now(),(select usuario.id_usuario from usuario where usuario.log_olostech=1 and usuario.olostechuser=plogin),desLocal,id_chamadaolostech);
		SELECT senhaschamadas.id_senhaschamadas INTO idSenhaChamada FROM senhaschamadas WHERE senhaschamadas.id_chamadaolostech=id_chamadaolostech;
	end if;
end if;
RETURN CONVERT(idSenhaChamada, CHAR(100));
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `botao`
--

DROP TABLE IF EXISTS `botao`;
CREATE TABLE `botao` (
  `id_botao` int(11) NOT NULL,
  `des_descricao` varchar(100) NOT NULL,
  `cod_botaoteclado` varchar(10) NOT NULL,
  `id_servico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `botao`
--

INSERT INTO `botao` (`id_botao`, `des_descricao`, `cod_botaoteclado`, `id_servico`) VALUES
(3, 'Referente à tecla: 3', '51', 1),
(4, 'Referente à tecla: F', '70', 2),
(5, 'Referente à tecla: L', '76', 3),
(6, 'Referente à tecla: D', '68', 4),
(7, 'Referente à tecla: 4', '52', 5),
(8, 'Referente à tecla: 9', '57', 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `chamadaolostech`
--

DROP TABLE IF EXISTS `chamadaolostech`;
CREATE TABLE `chamadaolostech` (
  `id` int(11) NOT NULL,
  `id_estacoes` int(11) NOT NULL,
  `olostechdatahora` varchar(30) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `olostechusuario` varchar(45) NOT NULL,
  `olostechnomeusuario` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `chamadaolostech`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuracao`
--

DROP TABLE IF EXISTS `configuracao`;
CREATE TABLE `configuracao` (
  `id_configuracao` int(11) NOT NULL,
  `des_tipoLocal` varchar(30) NOT NULL,
  `des_nomeUnidade` varchar(300) NOT NULL,
  `des_modeloprint` varchar(45) NOT NULL DEFAULT 'tmt20',
  `ind_tipoprint` varchar(4) NOT NULL DEFAULT 'IP',
  `des_enderecoprint` varchar(45) NOT NULL,
  `des_portaprintnetwork` varchar(4) DEFAULT '9100',
  `des_subcabecalho` varchar(100) DEFAULT NULL,
  `des_subcabecalho2` varchar(100) DEFAULT NULL,
  `int_intervalopresskey` int(2) DEFAULT '5',
  `log_consultMedico` int(1) DEFAULT '0',
  `log_permiterecoverypass` int(1) DEFAULT '0',
  `log_vinculartipoestacapservico` int(1) DEFAULT '0',
  `num_limiterechamar` int(2) NOT NULL DEFAULT '3'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `configuracao`
--

INSERT INTO `configuracao` (`id_configuracao`, `des_tipoLocal`, `des_nomeUnidade`, `des_modeloprint`, `ind_tipoprint`, `des_enderecoprint`, `des_portaprintnetwork`, `des_subcabecalho`, `des_subcabecalho2`, `int_intervalopresskey`, `log_consultMedico`, `log_permiterecoverypass`, `log_vinculartipoestacapservico`, `num_limiterechamar`) VALUES
(1, 'GUICHE', 'POLICLINICA', 'tmt20', 'IP', '192.168.33.221', '9100', 'de Especialidade Dr. Joao Biron', 'Sec. Mun. da Saude - PMJS', 2, 0, 0, 0, 3),
(3, 'GUICHÊ', 'POLICLINICA', 'tmt20', 'ip', '192.168.33.221', '9100', 'de Especialidade Dr. Joao Biron', 'Sec. Mun. da Saude - PMJS', 2, 0, 0, 0, 3),
(4, 'GUICHÊ', 'POLICLINICA', 'tmt20', 'ip', '192.168.33.221', '9100', 'de Especialidade Dr. Joao Biron', 'Sec. Mun. da Saude - PMJS', 2, 1, 1, 0, 3),
(5, 'GUICHÊ', 'POLICLINICA', 'tmt20', 'ip', '192.168.33.221', '9100', 'de Especialidade Dr. Joao Biron', 'Sec. Mun. da Saude - PMJS', 2, 1, 1, 1, 3),
(6, 'GUICHÊ', 'POLICLINICA', 'tmt20', 'ip', '192.168.33.221', '9100', 'de Especialidade Dr. Joao Biron', 'Sec. Mun. da Saude - PMJS', 2, 1, 1, 1, 2),
(7, 'GUICHÊ', 'POLICLINICA', 'tmt20', 'ip', '192.168.33.221', '9100', 'de Especialidade Dr. Joao Biron', 'Sec. Mun. da Saude - PMJS', 2, 1, 1, 1, 3),
(8, 'GUICHÊ', 'POLICLINICA', 'tmt20', 'ip', '192.168.33.221', '9100', 'de Especialidade Dr. Joao Biron', 'Sec. Mun. da Saude - PMJS', 2, 1, 1, 1, 3),
(9, 'GUICHÊ', 'POLICLINICA', 'tmt20', 'ip', '192.168.33.221', '9100', 'de Especialidade Dr. Joao Biron', 'Sec. Mun. da Saude - PMJS', 2, 1, 1, 1, 3),
(10, 'GUICHÊ', 'POLICLINICA', 'tmt20', 'ip', '192.168.33.221', '9100', 'de Especialidade Dr. Joao Biron', 'Sec. Mun. da Saude - PMJS', 2, 1, 1, 1, 3),
(11, 'GUICHÊ', 'POLICLINICA', 'tmt20', 'ip', '192.168.33.221', '9100', 'de Especialidade Dr. Joao Biron', 'Sec. Mun. da Saude - PMJS', 2, 1, 1, 1, 3),
(12, 'GUICHÊ', 'POLICLINICA', 'tmt20', 'ip', '192.168.33.221', '9100', 'de Especialidade Dr. Joao Biron', 'Sec. Mun. da Saude - PMJS', 2, 1, 1, 1, 3),
(13, 'GUICHÊ', 'POLICLINICA', 'tmt20', 'ip', '192.168.33.221', '9100', 'de Especialidade Dr. Joao Biron', 'Sec. Mun. da Saude - PMJS', 2, 1, 1, 1, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `coreshtml`
--

DROP TABLE IF EXISTS `coreshtml`;
CREATE TABLE `coreshtml` (
  `id` int(11) NOT NULL,
  `codigo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `coreshtml`
--

INSERT INTO `coreshtml` (`id`, `codigo`) VALUES
(1, '#F0F8FF'),
(2, '#FAEBD7'),
(3, '#FFEFDB'),
(4, '#EEDFCC'),
(5, '#CDC0B0'),
(6, '#8B8378'),
(7, '#7FFFD4'),
(8, '#7FFFD4'),
(9, '#76EEC6'),
(10, '#66CDAA'),
(11, '#458B74'),
(12, '#F0FFFF'),
(13, '#F0FFFF'),
(14, '#E0EEEE'),
(15, '#C1CDCD'),
(16, '#838B8B'),
(17, '#F5F5DC'),
(18, '#FFE4C4'),
(19, '#FFE4C4'),
(20, '#EED5B7'),
(21, '#CDB79E'),
(22, '#8B7D6B'),
(23, '#000000'),
(24, '#FFEBCD'),
(25, '#0000FF'),
(26, '#0000FF'),
(27, '#0000EE'),
(28, '#0000CD'),
(29, '#00008B'),
(30, '#8A2BE2'),
(31, '#A52A2A'),
(32, '#FF4040'),
(33, '#EE3B3B'),
(34, '#CD3333'),
(35, '#8B2323'),
(36, '#DEB887'),
(37, '#FFD39B'),
(38, '#FFD39B'),
(39, '#EEC591'),
(40, '#EEC591'),
(41, '#CDAA7D'),
(42, '#CDAA7D'),
(43, '#8B7355'),
(44, '#8B7355'),
(45, '#5F9EA0'),
(46, '#98F5FF'),
(47, '#8EE5EE'),
(48, '#7AC5CD'),
(49, '#53868B'),
(50, '#7FFF00'),
(51, '#7FFF00'),
(52, '#76EE00'),
(53, '#66CD00'),
(54, '#458B00'),
(55, '#D2691E'),
(56, '#FF7F24'),
(57, '#EE7621'),
(58, '#CD661D'),
(59, '#8B4513'),
(60, '#FF7F50'),
(61, '#FF7256'),
(62, '#EE6A50'),
(63, '#CD5B45'),
(64, '#8B3E2F'),
(65, '#6495ED'),
(66, '#FFF8DC'),
(67, '#FFF8DC'),
(68, '#EEE8CD'),
(69, '#CDC8B1'),
(70, '#8B8878'),
(71, '#00FFFF'),
(72, '#00FFFF'),
(73, '#00EEEE'),
(74, '#00CDCD'),
(75, '#008B8B'),
(76, '#00008B'),
(77, '#008B8B'),
(78, '#B8860B'),
(79, '#FFB90F'),
(80, '#FFB90F'),
(81, '#EEAD0E'),
(82, '#EEAD0E'),
(83, '#CD950C'),
(84, '#CD950C'),
(85, '#8B658B'),
(86, '#8B658B'),
(87, '#006400'),
(88, '#A9A9A9'),
(89, '#BDB76B'),
(90, '#8B008B'),
(91, '#556B2F'),
(92, '#CAFF70'),
(93, '#BCEE68'),
(94, '#A2CD5A'),
(95, '#6E8B3D'),
(96, '#FF8C00'),
(97, '#FF7F00'),
(98, '#EE7600'),
(99, '#CD6600'),
(100, '#8B4500'),
(101, '#9932CC'),
(102, '#BF3EFF'),
(103, '#B23AEE'),
(104, '#9A32CD'),
(105, '#68228B'),
(106, '#8B0000'),
(107, '#E9967A'),
(108, '#8FBC8F'),
(109, '#C1FFC1'),
(110, '#B4EEB4'),
(111, '#9BCD9B'),
(112, '#698B69'),
(113, '#483D8B'),
(114, '#2F4F4F'),
(115, '#97FFFF'),
(116, '#8DEEEE'),
(117, '#79CDCD'),
(118, '#528B8B'),
(119, '#00CED1'),
(120, '#9400D3'),
(121, '#FF1493'),
(122, '#FF1493'),
(123, '#EE1289'),
(124, '#CD1076'),
(125, '#8B0A50'),
(126, '#00BFFF'),
(127, '#00BFFF'),
(128, '#00B2EE'),
(129, '#009ACD'),
(130, '#00688B'),
(131, '#696969'),
(132, '#1E90FF'),
(133, '#1E90FF'),
(134, '#1C86EE'),
(135, '#1874CD'),
(136, '#104E8B'),
(137, '#B22222'),
(138, '#FF3030'),
(139, '#EE2C2C'),
(140, '#CD2626'),
(141, '#8B1A1A'),
(142, '#FFFAF0'),
(143, '#228B22'),
(144, '#DCDCDC'),
(145, '#F8F8FF'),
(146, '#FFD700'),
(147, '#FFD700'),
(148, '#EEC900'),
(149, '#CDAD00'),
(150, '#8B7500'),
(151, '#8B7500'),
(152, '#DAA520'),
(153, '#FFC125'),
(154, '#FFC125'),
(155, '#EEB422'),
(156, '#EEB422'),
(157, '#CD9B1D'),
(158, '#CD9B1D'),
(159, '#8B6914'),
(160, '#8B6914'),
(161, '#CFCFCF'),
(162, '#E8E8E8'),
(163, '#00FF00'),
(164, '#00FF00'),
(165, '#00EE00'),
(166, '#00CD00'),
(167, '#008B00'),
(168, '#ADFF2F'),
(169, '#BEBEBE'),
(170, '#1C1C1C'),
(171, '#363636'),
(172, '#4F4F4F'),
(173, '#696969'),
(174, '#828282'),
(175, '#9C9C9C'),
(176, '#B5B5B5'),
(177, '#F0FFF0'),
(178, '#F0FFF0'),
(179, '#E0EEE0'),
(180, '#C1CDC1'),
(181, '#838B83'),
(182, '#FF69B4'),
(183, '#FF6EB4'),
(184, '#EE6AA7'),
(185, '#CD6090'),
(186, '#8B3A62'),
(187, '#CD5C5C'),
(188, '#FF6A6A'),
(189, '#FF6A6A'),
(190, '#EE6363'),
(191, '#EE6363'),
(192, '#CD5555'),
(193, '#CD5555'),
(194, '#8B3A3A'),
(195, '#8B3A3A'),
(196, '#FFFFF0'),
(197, '#FFFFF0'),
(198, '#EEEEE0'),
(199, '#CDCDC1'),
(200, '#8B8B83'),
(201, '#FFF68F'),
(202, '#EEE685'),
(203, '#CDC673'),
(204, '#8B864E'),
(205, '#E6E6FA'),
(206, '#FFF0F5'),
(207, '#FFF0F5'),
(208, '#EEE0E5'),
(209, '#CDC1C5'),
(210, '#8B8386'),
(211, '#7CFC00'),
(212, '#FFFACD'),
(213, '#FFFACD'),
(214, '#EEE9BF'),
(215, '#CDC9A5'),
(216, '#8B8970'),
(217, '#ADD8E6'),
(218, '#BFEFFF'),
(219, '#B2DFEE'),
(220, '#9AC0CD'),
(221, '#68838B'),
(222, '#F08080'),
(223, '#E0FFFF'),
(224, '#E0FFFF'),
(225, '#D1EEEE'),
(226, '#B4CDCD'),
(227, '#7A8B8B'),
(228, '#EEDD82'),
(229, '#FFEC8B'),
(230, '#EEDC82'),
(231, '#CDBE70'),
(232, '#8B814C'),
(233, '#D3D3D3'),
(234, '#90EE90'),
(235, '#FFB6C1'),
(236, '#FFAEB9'),
(237, '#EEA2AD'),
(238, '#CD8C95'),
(239, '#8B5F65'),
(240, '#FFA07A'),
(241, '#FFA07A'),
(242, '#EE9572'),
(243, '#CD8162'),
(244, '#8B5742'),
(245, '#20B2AA'),
(246, '#87CEFA'),
(247, '#B0E2FF'),
(248, '#A4D3EE'),
(249, '#8DB6CD'),
(250, '#607B8B'),
(251, '#8470FF'),
(252, '#778899'),
(253, '#B0C4DE'),
(254, '#CAE1FF'),
(255, '#BCD2EE'),
(256, '#A2B5CD'),
(257, '#6E7B8B'),
(258, '#FFFFE0'),
(259, '#FFFFE0'),
(260, '#EEEED1'),
(261, '#CDCDB4'),
(262, '#8B8B7A'),
(263, '#32CD32'),
(264, '#FAF0E6'),
(265, '#FAFAD2'),
(266, '#FF00FF'),
(267, '#FF00FF'),
(268, '#EE00EE'),
(269, '#CD00CD'),
(270, '#8B008B'),
(271, '#B03060'),
(272, '#FF34B3'),
(273, '#EE30A7'),
(274, '#CD2990'),
(275, '#8B1C62'),
(276, '#66CDAA'),
(277, '#0000CD'),
(278, '#BA55D3'),
(279, '#E066FF'),
(280, '#D15FEE'),
(281, '#B452CD'),
(282, '#7A378B'),
(283, '#9370DB'),
(284, '#AB82FF'),
(285, '#9F79EE'),
(286, '#8968CD'),
(287, '#5D478B'),
(288, '#3CB371'),
(289, '#7B68EE'),
(290, '#48D1CC'),
(291, '#C71585'),
(292, '#00FA9A'),
(293, '#191970'),
(294, '#F5FFFA'),
(295, '#FFE4E1'),
(296, '#FFE4E1'),
(297, '#EED5D2'),
(298, '#CDB7B5'),
(299, '#8B7D7B'),
(300, '#FFE4B5'),
(301, '#FFDEAD'),
(302, '#FFDEAD'),
(303, '#EECFA1'),
(304, '#CDB38B'),
(305, '#8B795E'),
(306, '#000080'),
(307, '#FDF5E6'),
(308, '#6B8E23'),
(309, '#C0FF3E'),
(310, '#B3EE3A'),
(311, '#9ACD32'),
(312, '#698B22'),
(313, '#FFA500'),
(314, '#FFA500'),
(315, '#EE9A00'),
(316, '#CD8500'),
(317, '#8B5A00'),
(318, '#FF4500'),
(319, '#FF4500'),
(320, '#EE4000'),
(321, '#CD3700'),
(322, '#8B2500'),
(323, '#DA70D6'),
(324, '#FF83FA'),
(325, '#EE7AE9'),
(326, '#CD69C9'),
(327, '#8B4789'),
(328, '#EEE8AA'),
(329, '#98FB98'),
(330, '#9AFF9A'),
(331, '#90EE90'),
(332, '#7CCD7C'),
(333, '#548B54'),
(334, '#AFEEEE'),
(335, '#BBFFFF'),
(336, '#AEEEEE'),
(337, '#96CDCD'),
(338, '#668B8B'),
(339, '#DB7093'),
(340, '#FF82AB'),
(341, '#EE799F'),
(342, '#CD6889'),
(343, '#8B475D'),
(344, '#FFEFD5'),
(345, '#FFDAB9'),
(346, '#FFDAB9'),
(347, '#EECBAD'),
(348, '#CDAF95'),
(349, '#8B7765'),
(350, '#CD853F'),
(351, '#FFC0CB'),
(352, '#FFB5C5'),
(353, '#EEA9B8'),
(354, '#CD919E'),
(355, '#8B636C'),
(356, '#DDA0DD'),
(357, '#FFBBFF'),
(358, '#EEAEEE'),
(359, '#CD96CD'),
(360, '#8B668B'),
(361, '#B0E0E6'),
(362, '#A020F0'),
(363, '#9B30FF'),
(364, '#912CEE'),
(365, '#7D26CD'),
(366, '#551A8B'),
(367, '#FF0000'),
(368, '#FF0000'),
(369, '#EE0000'),
(370, '#CD0000'),
(371, '#8B0000'),
(372, '#BC8F8F'),
(373, '#FFC1C1'),
(374, '#FFC1C1'),
(375, '#EEB4B4'),
(376, '#EEB4B4'),
(377, '#CD9B9B'),
(378, '#CD9B9B'),
(379, '#8B6969'),
(380, '#8B6969'),
(381, '#4169E1'),
(382, '#4876FF'),
(383, '#436EEE'),
(384, '#3A5FCD'),
(385, '#27408B'),
(386, '#8B4513'),
(387, '#FA8072'),
(388, '#FF8C69'),
(389, '#EE8262'),
(390, '#CD7054'),
(391, '#8B4C39'),
(392, '#F4A460'),
(393, '#2E8B57'),
(394, '#54FF9F'),
(395, '#4EEE94'),
(396, '#43CD80'),
(397, '#2E8B57'),
(398, '#FFF5EE'),
(399, '#FFF5EE'),
(400, '#EEE5DE'),
(401, '#CDC5BF'),
(402, '#8B8682'),
(403, '#A0522D'),
(404, '#FF8247'),
(405, '#FF8247'),
(406, '#EE7942'),
(407, '#EE7942'),
(408, '#CD6839'),
(409, '#CD6839'),
(410, '#8B4726'),
(411, '#8B4726'),
(412, '#87CEEB'),
(413, '#87CEFF'),
(414, '#7EC0EE'),
(415, '#6CA6CD'),
(416, '#4A708B'),
(417, '#6A5ACD'),
(418, '#836FFF'),
(419, '#7A67EE'),
(420, '#6959CD'),
(421, '#473C8B'),
(422, '#C6E2FF'),
(423, '#B9D3EE'),
(424, '#9FB6CD'),
(425, '#6C7B8B'),
(426, '#708090'),
(427, '#FFFAFA'),
(428, '#FFFAFA'),
(429, '#EEE9E9'),
(430, '#CDC9C9'),
(431, '#8B8989'),
(432, '#00FF7F'),
(433, '#00FF7F'),
(434, '#00EE76'),
(435, '#00CD66'),
(436, '#008B45'),
(437, '#4682B4'),
(438, '#63B8FF'),
(439, '#5CACEE'),
(440, '#4F94CD'),
(441, '#36648B'),
(442, '#D2B48C'),
(443, '#FFA54F'),
(444, '#EE9A49'),
(445, '#CD853F'),
(446, '#8B5A2B'),
(447, '#D8BFD8'),
(448, '#FFE1FF'),
(449, '#EED2EE'),
(450, '#CDB5CD'),
(451, '#8B7B8B'),
(452, '#FF6347'),
(453, '#FF6347'),
(454, '#EE5C42'),
(455, '#CD4F39'),
(456, '#8B3626'),
(457, '#40E0D0'),
(458, '#00F5FF'),
(459, '#00E5EE'),
(460, '#00C5CD'),
(461, '#00868B'),
(462, '#EE82EE'),
(463, '#D02090'),
(464, '#FF3E96'),
(465, '#EE3A8C'),
(466, '#CD3278'),
(467, '#8B2252'),
(468, '#F5DEB3'),
(469, '#FFE7BA'),
(470, '#EED8AE'),
(471, '#CDBA96'),
(472, '#8B7E66'),
(473, '#FFFFFF'),
(474, '#F5F5F5'),
(475, '#FFFF00'),
(476, '#FFFF00'),
(477, '#EEEE00'),
(478, '#CDCD00'),
(479, '#8B8B00'),
(480, '#9ACD32');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estacaodisplay`
--

DROP TABLE IF EXISTS `estacaodisplay`;
CREATE TABLE `estacaodisplay` (
  `Id` int(11) NOT NULL,
  `id_estacao` int(11) DEFAULT NULL,
  `id_tipoestacaoliberado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Fazendo dump de dados para tabela `estacaodisplay`
--

INSERT INTO `estacaodisplay` (`Id`, `id_estacao`, `id_tipoestacaoliberado`) VALUES
(1, 1, 2),
(3, 8, 1),
(4, 8, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `estacoes`
--

DROP TABLE IF EXISTS `estacoes`;
CREATE TABLE `estacoes` (
  `id` int(11) NOT NULL,
  `hostname` varchar(100) NOT NULL,
  `numero` varchar(50) NOT NULL,
  `id_tipoestacao` int(11) NOT NULL,
  `log_olostech` int(1) NOT NULL DEFAULT '0',
  `codolostech` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `estacoes`
--

INSERT INTO `estacoes` (`id`, `hostname`, `numero`, `id_tipoestacao`, `log_olostech`, `codolostech`) VALUES
(1, 's2751', '99', 2, 0, NULL),
(2, 's1052', '4', 3, 0, NULL),
(3, 's1050', '3', 3, 0, NULL),
(4, 's1051', '2', 3, 0, NULL),
(5, 's0550', '1', 3, 0, NULL),
(6, 's1150', '6', 1, 0, NULL),
(7, 's1066', '7', 1, 0, NULL),
(8, 's2998', '100', 2, 0, NULL),
(9, 's1259', '8', 1, 0, NULL),
(10, 's1256', '5', 1, 0, NULL),
(11, 's1099', ' ', 2, 1, '22977'),
(12, 's1127', ' ', 2, 1, '22978'),
(13, 's1120', ' ', 2, 1, '21749');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fontawesome`
--

DROP TABLE IF EXISTS `fontawesome`;
CREATE TABLE `fontawesome` (
  `id_fontawesome` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Fazendo dump de dados para tabela `fontawesome`
--

INSERT INTO `fontawesome` (`id_fontawesome`, `nome`) VALUES
(1, '500px'),
(2, 'accessible-icon'),
(3, 'accusoft'),
(4, 'acquisitions-incorporated'),
(5, 'ad'),
(6, 'address-book'),
(7, 'address-card'),
(8, 'adjust'),
(9, 'adn'),
(10, 'adobe'),
(11, 'adversal'),
(12, 'affiliatetheme'),
(13, 'air-freshener'),
(14, 'algolia'),
(15, 'align-center'),
(16, 'align-justify'),
(17, 'align-left'),
(18, 'align-right'),
(19, 'alipay'),
(20, 'allergies'),
(21, 'amazon'),
(22, 'amazon-pay'),
(23, 'ambulance'),
(24, 'american-sign-language-interpr'),
(25, 'amilia'),
(26, 'anchor'),
(27, 'android'),
(28, 'angellist'),
(29, 'angle-double-down'),
(30, 'angle-double-left'),
(31, 'angle-double-right'),
(32, 'angle-double-up'),
(33, 'angle-down'),
(34, 'angle-left'),
(35, 'angle-right'),
(36, 'angle-up'),
(37, 'angry'),
(38, 'angrycreative'),
(39, 'angular'),
(40, 'ankh'),
(41, 'app-store'),
(42, 'app-store-ios'),
(43, 'apper'),
(44, 'apple'),
(45, 'apple-alt'),
(46, 'apple-pay'),
(47, 'archive'),
(48, 'archway'),
(49, 'arrow-alt-circle-down'),
(50, 'arrow-alt-circle-left'),
(51, 'arrow-alt-circle-right'),
(52, 'arrow-alt-circle-up'),
(53, 'arrow-circle-down'),
(54, 'arrow-circle-left'),
(55, 'arrow-circle-right'),
(56, 'arrow-circle-up'),
(57, 'arrow-down'),
(58, 'arrow-left'),
(59, 'arrow-right'),
(60, 'arrow-up'),
(61, 'arrows-alt'),
(62, 'arrows-alt-h'),
(63, 'arrows-alt-v'),
(64, 'artstation'),
(65, 'assistive-listening-systems'),
(66, 'asterisk'),
(67, 'asymmetrik'),
(68, 'at'),
(69, 'atlas'),
(70, 'atlassian'),
(71, 'atom'),
(72, 'audible'),
(73, 'audio-description'),
(74, 'autoprefixer'),
(75, 'avianex'),
(76, 'aviato'),
(77, 'award'),
(78, 'aws'),
(79, 'baby'),
(80, 'baby-carriage'),
(81, 'backspace'),
(82, 'backward'),
(83, 'bacon'),
(84, 'balance-scale'),
(85, 'ban'),
(86, 'band-aid'),
(87, 'bandcamp'),
(88, 'barcode'),
(89, 'bars'),
(90, 'baseball-ball'),
(91, 'basketball-ball'),
(92, 'bath'),
(93, 'battery-empty'),
(94, 'battery-full'),
(95, 'battery-half'),
(96, 'battery-quarter'),
(97, 'battery-three-quarters'),
(98, 'bed'),
(99, 'beer'),
(100, 'behance'),
(101, 'behance-square'),
(102, 'bell'),
(103, 'bell-slash'),
(104, 'bezier-curve'),
(105, 'bible'),
(106, 'bicycle'),
(107, 'bimobject'),
(108, 'binoculars'),
(109, 'biohazard'),
(110, 'birthday-cake'),
(111, 'bitbucket'),
(112, 'bitcoin'),
(113, 'bity'),
(114, 'black-tie'),
(115, 'blackberry'),
(116, 'blender'),
(117, 'blender-phone'),
(118, 'blind'),
(119, 'blog'),
(120, 'blogger'),
(121, 'blogger-b'),
(122, 'bluetooth'),
(123, 'bluetooth-b'),
(124, 'bold'),
(125, 'bolt'),
(126, 'bomb'),
(127, 'bone'),
(128, 'bong'),
(129, 'book'),
(130, 'book-dead'),
(131, 'book-medical'),
(132, 'book-open'),
(133, 'book-reader'),
(134, 'bookmark'),
(135, 'bowling-ball'),
(136, 'box'),
(137, 'box-open'),
(138, 'boxes'),
(139, 'braille'),
(140, 'brain'),
(141, 'bread-slice'),
(142, 'briefcase'),
(143, 'briefcase-medical'),
(144, 'broadcast-tower'),
(145, 'broom'),
(146, 'brush'),
(147, 'btc'),
(148, 'bug'),
(149, 'building'),
(150, 'bullhorn'),
(151, 'bullseye'),
(152, 'burn'),
(153, 'buromobelexperte'),
(154, 'bus'),
(155, 'bus-alt'),
(156, 'business-time'),
(157, 'buysellads'),
(158, 'calculator'),
(159, 'calendar'),
(160, 'calendar-alt'),
(161, 'calendar-check'),
(162, 'calendar-day'),
(163, 'calendar-minus'),
(164, 'calendar-plus'),
(165, 'calendar-times'),
(166, 'calendar-week'),
(167, 'camera'),
(168, 'camera-retro'),
(169, 'campground'),
(170, 'canadian-maple-leaf'),
(171, 'candy-cane'),
(172, 'cannabis'),
(173, 'capsules'),
(174, 'car'),
(175, 'car-alt'),
(176, 'car-battery'),
(177, 'car-crash'),
(178, 'car-side'),
(179, 'caret-down'),
(180, 'caret-left'),
(181, 'caret-right'),
(182, 'caret-square-down'),
(183, 'caret-square-left'),
(184, 'caret-square-right'),
(185, 'caret-square-up'),
(186, 'caret-up'),
(187, 'carrot'),
(188, 'cart-arrow-down'),
(189, 'cart-plus'),
(190, 'cash-register'),
(191, 'cat'),
(192, 'cc-amazon-pay'),
(193, 'cc-amex'),
(194, 'cc-apple-pay'),
(195, 'cc-diners-club'),
(196, 'cc-discover'),
(197, 'cc-jcb'),
(198, 'cc-mastercard'),
(199, 'cc-paypal'),
(200, 'cc-stripe'),
(201, 'cc-visa'),
(202, 'centercode'),
(203, 'centos'),
(204, 'certificate'),
(205, 'chair'),
(206, 'chalkboard'),
(207, 'chalkboard-teacher'),
(208, 'charging-station'),
(209, 'chart-area'),
(210, 'chart-bar'),
(211, 'chart-line'),
(212, 'chart-pie'),
(213, 'check'),
(214, 'check-circle'),
(215, 'check-double'),
(216, 'check-square'),
(217, 'cheese'),
(218, 'chess'),
(219, 'chess-bishop'),
(220, 'chess-board'),
(221, 'chess-king'),
(222, 'chess-knight'),
(223, 'chess-pawn'),
(224, 'chess-queen'),
(225, 'chess-rook'),
(226, 'chevron-circle-down'),
(227, 'chevron-circle-left'),
(228, 'chevron-circle-right'),
(229, 'chevron-circle-up'),
(230, 'chevron-down'),
(231, 'chevron-left'),
(232, 'chevron-right'),
(233, 'chevron-up'),
(234, 'child'),
(235, 'chrome'),
(236, 'church'),
(237, 'circle'),
(238, 'circle-notch'),
(239, 'city'),
(240, 'clinic-medical'),
(241, 'clipboard'),
(242, 'clipboard-check'),
(243, 'clipboard-list'),
(244, 'clock'),
(245, 'clone'),
(246, 'closed-captioning'),
(247, 'cloud'),
(248, 'cloud-download-alt'),
(249, 'cloud-meatball'),
(250, 'cloud-moon'),
(251, 'cloud-moon-rain'),
(252, 'cloud-rain'),
(253, 'cloud-showers-heavy'),
(254, 'cloud-sun'),
(255, 'cloud-sun-rain'),
(256, 'cloud-upload-alt'),
(257, 'cloudscale'),
(258, 'cloudsmith'),
(259, 'cloudversify'),
(260, 'cocktail'),
(261, 'code'),
(262, 'code-branch'),
(263, 'codepen'),
(264, 'codiepie'),
(265, 'coffee'),
(266, 'cog'),
(267, 'cogs'),
(268, 'coins'),
(269, 'columns'),
(270, 'comment'),
(271, 'comment-alt'),
(272, 'comment-dollar'),
(273, 'comment-dots'),
(274, 'comment-medical'),
(275, 'comment-slash'),
(276, 'comments'),
(277, 'comments-dollar'),
(278, 'compact-disc'),
(279, 'compass'),
(280, 'compress'),
(281, 'compress-arrows-alt'),
(282, 'concierge-bell'),
(283, 'confluence'),
(284, 'connectdevelop'),
(285, 'contao'),
(286, 'cookie'),
(287, 'cookie-bite'),
(288, 'copy'),
(289, 'copyright'),
(290, 'couch'),
(291, 'cpanel'),
(292, 'creative-commons'),
(293, 'creative-commons-by'),
(294, 'creative-commons-nc'),
(295, 'creative-commons-nc-eu'),
(296, 'creative-commons-nc-jp'),
(297, 'creative-commons-nd'),
(298, 'creative-commons-pd'),
(299, 'creative-commons-pd-alt'),
(300, 'creative-commons-remix'),
(301, 'creative-commons-sa'),
(302, 'creative-commons-sampling'),
(303, 'creative-commons-sampling-plus'),
(304, 'creative-commons-share'),
(305, 'creative-commons-zero'),
(306, 'credit-card'),
(307, 'critical-role'),
(308, 'crop'),
(309, 'crop-alt'),
(310, 'cross'),
(311, 'crosshairs'),
(312, 'crow'),
(313, 'crown'),
(314, 'crutch'),
(315, 'css3'),
(316, 'css3-alt'),
(317, 'cube'),
(318, 'cubes'),
(319, 'cut'),
(320, 'cuttlefish'),
(321, 'd-and-d'),
(322, 'd-and-d-beyond'),
(323, 'dashcube'),
(324, 'database'),
(325, 'deaf'),
(326, 'delicious'),
(327, 'democrat'),
(328, 'deploydog'),
(329, 'deskpro'),
(330, 'desktop'),
(331, 'dev'),
(332, 'deviantart'),
(333, 'dharmachakra'),
(334, 'dhl'),
(335, 'diagnoses'),
(336, 'diaspora'),
(337, 'dice'),
(338, 'dice-d20'),
(339, 'dice-d6'),
(340, 'dice-five'),
(341, 'dice-four'),
(342, 'dice-one'),
(343, 'dice-six'),
(344, 'dice-three'),
(345, 'dice-two'),
(346, 'digg'),
(347, 'digital-ocean'),
(348, 'digital-tachograph'),
(349, 'directions'),
(350, 'discord'),
(351, 'discourse'),
(352, 'divide'),
(353, 'dizzy'),
(354, 'dna'),
(355, 'dochub'),
(356, 'docker'),
(357, 'dog'),
(358, 'dollar-sign'),
(359, 'dolly'),
(360, 'dolly-flatbed'),
(361, 'donate'),
(362, 'door-closed'),
(363, 'door-open'),
(364, 'dot-circle'),
(365, 'dove'),
(366, 'download'),
(367, 'draft2digital'),
(368, 'drafting-compass'),
(369, 'dragon'),
(370, 'draw-polygon'),
(371, 'dribbble'),
(372, 'dribbble-square'),
(373, 'dropbox'),
(374, 'drum'),
(375, 'drum-steelpan'),
(376, 'drumstick-bite'),
(377, 'drupal'),
(378, 'dumbbell'),
(379, 'dumpster'),
(380, 'dumpster-fire'),
(381, 'dungeon'),
(382, 'dyalog'),
(383, 'earlybirds'),
(384, 'ebay'),
(385, 'edge'),
(386, 'edit'),
(387, 'egg'),
(388, 'eject'),
(389, 'elementor'),
(390, 'ellipsis-h'),
(391, 'ellipsis-v'),
(392, 'ello'),
(393, 'ember'),
(394, 'empire'),
(395, 'envelope'),
(396, 'envelope-open'),
(397, 'envelope-open-text'),
(398, 'envelope-square'),
(399, 'envira'),
(400, 'equals'),
(401, 'eraser'),
(402, 'erlang'),
(403, 'ethereum'),
(404, 'ethernet'),
(405, 'etsy'),
(406, 'euro-sign'),
(407, 'exchange-alt'),
(408, 'exclamation'),
(409, 'exclamation-circle'),
(410, 'exclamation-triangle'),
(411, 'expand'),
(412, 'expand-arrows-alt'),
(413, 'expeditedssl'),
(414, 'external-link-alt'),
(415, 'external-link-square-alt'),
(416, 'eye'),
(417, 'eye-dropper'),
(418, 'eye-slash'),
(419, 'facebook'),
(420, 'facebook-f'),
(421, 'facebook-messenger'),
(422, 'facebook-square'),
(423, 'fantasy-flight-games'),
(424, 'fast-backward'),
(425, 'fast-forward'),
(426, 'fax'),
(427, 'feather'),
(428, 'feather-alt'),
(429, 'fedex'),
(430, 'fedora'),
(431, 'female'),
(432, 'fighter-jet'),
(433, 'figma'),
(434, 'file'),
(435, 'file-alt'),
(436, 'file-archive'),
(437, 'file-audio'),
(438, 'file-code'),
(439, 'file-contract'),
(440, 'file-csv'),
(441, 'file-download'),
(442, 'file-excel'),
(443, 'file-export'),
(444, 'file-image'),
(445, 'file-import'),
(446, 'file-invoice'),
(447, 'file-invoice-dollar'),
(448, 'file-medical'),
(449, 'file-medical-alt'),
(450, 'file-pdf'),
(451, 'file-powerpoint'),
(452, 'file-prescription'),
(453, 'file-signature'),
(454, 'file-upload'),
(455, 'file-video'),
(456, 'file-word'),
(457, 'fill'),
(458, 'fill-drip'),
(459, 'film'),
(460, 'filter'),
(461, 'fingerprint'),
(462, 'fire'),
(463, 'fire-alt'),
(464, 'fire-extinguisher'),
(465, 'firefox'),
(466, 'first-aid'),
(467, 'first-order'),
(468, 'first-order-alt'),
(469, 'firstdraft'),
(470, 'fish'),
(471, 'fist-raised'),
(472, 'flag'),
(473, 'flag-checkered'),
(474, 'flag-usa'),
(475, 'flask'),
(476, 'flickr'),
(477, 'flipboard'),
(478, 'flushed'),
(479, 'fly'),
(480, 'folder'),
(481, 'folder-minus'),
(482, 'folder-open'),
(483, 'folder-plus'),
(484, 'font'),
(485, 'font-awesome'),
(486, 'font-awesome-alt'),
(487, 'font-awesome-flag'),
(488, 'fonticons'),
(489, 'fonticons-fi'),
(490, 'football-ball'),
(491, 'fort-awesome'),
(492, 'fort-awesome-alt'),
(493, 'forumbee'),
(494, 'forward'),
(495, 'foursquare'),
(496, 'free-code-camp'),
(497, 'freebsd'),
(498, 'frog'),
(499, 'frown'),
(500, 'frown-open'),
(501, 'fulcrum'),
(502, 'funnel-dollar'),
(503, 'futbol'),
(504, 'galactic-republic'),
(505, 'galactic-senate'),
(506, 'gamepad'),
(507, 'gas-pump'),
(508, 'gavel'),
(509, 'gem'),
(510, 'genderless'),
(511, 'get-pocket'),
(512, 'gg'),
(513, 'gg-circle'),
(514, 'ghost'),
(515, 'gift'),
(516, 'gifts'),
(517, 'git'),
(518, 'git-square'),
(519, 'github'),
(520, 'github-alt'),
(521, 'github-square'),
(522, 'gitkraken'),
(523, 'gitlab'),
(524, 'gitter'),
(525, 'glass-cheers'),
(526, 'glass-martini'),
(527, 'glass-martini-alt'),
(528, 'glass-whiskey'),
(529, 'glasses'),
(530, 'glide'),
(531, 'glide-g'),
(532, 'globe'),
(533, 'globe-africa'),
(534, 'globe-americas'),
(535, 'globe-asia'),
(536, 'globe-europe'),
(537, 'gofore'),
(538, 'golf-ball'),
(539, 'goodreads'),
(540, 'goodreads-g'),
(541, 'google'),
(542, 'google-drive'),
(543, 'google-play'),
(544, 'google-plus'),
(545, 'google-plus-g'),
(546, 'google-plus-square'),
(547, 'google-wallet'),
(548, 'gopuram'),
(549, 'graduation-cap'),
(550, 'gratipay'),
(551, 'grav'),
(552, 'greater-than'),
(553, 'greater-than-equal'),
(554, 'grimace'),
(555, 'grin'),
(556, 'grin-alt'),
(557, 'grin-beam'),
(558, 'grin-beam-sweat'),
(559, 'grin-hearts'),
(560, 'grin-squint'),
(561, 'grin-squint-tears'),
(562, 'grin-stars'),
(563, 'grin-tears'),
(564, 'grin-tongue'),
(565, 'grin-tongue-squint'),
(566, 'grin-tongue-wink'),
(567, 'grin-wink'),
(568, 'grip-horizontal'),
(569, 'grip-lines'),
(570, 'grip-lines-vertical'),
(571, 'grip-vertical'),
(572, 'gripfire'),
(573, 'grunt'),
(574, 'guitar'),
(575, 'gulp'),
(576, 'h-square'),
(577, 'hacker-news'),
(578, 'hacker-news-square'),
(579, 'hackerrank'),
(580, 'hamburger'),
(581, 'hammer'),
(582, 'hamsa'),
(583, 'hand-holding'),
(584, 'hand-holding-heart'),
(585, 'hand-holding-usd'),
(586, 'hand-lizard'),
(587, 'hand-middle-finger'),
(588, 'hand-paper'),
(589, 'hand-peace'),
(590, 'hand-point-down'),
(591, 'hand-point-left'),
(592, 'hand-point-right'),
(593, 'hand-point-up'),
(594, 'hand-pointer'),
(595, 'hand-rock'),
(596, 'hand-scissors'),
(597, 'hand-spock'),
(598, 'hands'),
(599, 'hands-helping'),
(600, 'handshake'),
(601, 'hanukiah'),
(602, 'hard-hat'),
(603, 'hashtag'),
(604, 'hat-wizard'),
(605, 'haykal'),
(606, 'hdd'),
(607, 'heading'),
(608, 'headphones'),
(609, 'headphones-alt'),
(610, 'headset'),
(611, 'heart'),
(612, 'heart-broken'),
(613, 'heartbeat'),
(614, 'helicopter'),
(615, 'highlighter'),
(616, 'hiking'),
(617, 'hippo'),
(618, 'hips'),
(619, 'hire-a-helper'),
(620, 'history'),
(621, 'hockey-puck'),
(622, 'holly-berry'),
(623, 'home'),
(624, 'hooli'),
(625, 'hornbill'),
(626, 'horse'),
(627, 'horse-head'),
(628, 'hospital'),
(629, 'hospital-alt'),
(630, 'hospital-symbol'),
(631, 'hot-tub'),
(632, 'hotdog'),
(633, 'hotel'),
(634, 'hotjar'),
(635, 'hourglass'),
(636, 'hourglass-end'),
(637, 'hourglass-half'),
(638, 'hourglass-start'),
(639, 'house-damage'),
(640, 'houzz'),
(641, 'hryvnia'),
(642, 'html5'),
(643, 'hubspot'),
(644, 'i-cursor'),
(645, 'ice-cream'),
(646, 'icicles'),
(647, 'id-badge'),
(648, 'id-card'),
(649, 'id-card-alt'),
(650, 'igloo'),
(651, 'image'),
(652, 'images'),
(653, 'imdb'),
(654, 'inbox'),
(655, 'indent'),
(656, 'industry'),
(657, 'infinity'),
(658, 'info'),
(659, 'info-circle'),
(660, 'instagram'),
(661, 'intercom'),
(662, 'internet-explorer'),
(663, 'invision'),
(664, 'ioxhost'),
(665, 'italic'),
(666, 'itunes'),
(667, 'itunes-note'),
(668, 'java'),
(669, 'jedi'),
(670, 'jedi-order'),
(671, 'jenkins'),
(672, 'jira'),
(673, 'joget'),
(674, 'joint'),
(675, 'joomla'),
(676, 'journal-whills'),
(677, 'js'),
(678, 'js-square'),
(679, 'jsfiddle'),
(680, 'kaaba'),
(681, 'kaggle'),
(682, 'key'),
(683, 'keybase'),
(684, 'keyboard'),
(685, 'keycdn'),
(686, 'khanda'),
(687, 'kickstarter'),
(688, 'kickstarter-k'),
(689, 'kiss'),
(690, 'kiss-beam'),
(691, 'kiss-wink-heart'),
(692, 'kiwi-bird'),
(693, 'korvue'),
(694, 'landmark'),
(695, 'language'),
(696, 'laptop'),
(697, 'laptop-code'),
(698, 'laptop-medical'),
(699, 'laravel'),
(700, 'lastfm'),
(701, 'lastfm-square'),
(702, 'laugh'),
(703, 'laugh-beam'),
(704, 'laugh-squint'),
(705, 'laugh-wink'),
(706, 'layer-group'),
(707, 'leaf'),
(708, 'leanpub'),
(709, 'lemon'),
(710, 'less'),
(711, 'less-than'),
(712, 'less-than-equal'),
(713, 'level-down-alt'),
(714, 'level-up-alt'),
(715, 'life-ring'),
(716, 'lightbulb'),
(717, 'line'),
(718, 'link'),
(719, 'linkedin'),
(720, 'linkedin-in'),
(721, 'linode'),
(722, 'linux'),
(723, 'lira-sign'),
(724, 'list'),
(725, 'list-alt'),
(726, 'list-ol'),
(727, 'list-ul'),
(728, 'location-arrow'),
(729, 'lock'),
(730, 'lock-open'),
(731, 'long-arrow-alt-down'),
(732, 'long-arrow-alt-left'),
(733, 'long-arrow-alt-right'),
(734, 'long-arrow-alt-up'),
(735, 'low-vision'),
(736, 'luggage-cart'),
(737, 'lyft'),
(738, 'magento'),
(739, 'magic'),
(740, 'magnet'),
(741, 'mail-bulk'),
(742, 'mailchimp'),
(743, 'male'),
(744, 'mandalorian'),
(745, 'map'),
(746, 'map-marked'),
(747, 'map-marked-alt'),
(748, 'map-marker'),
(749, 'map-marker-alt'),
(750, 'map-pin'),
(751, 'map-signs'),
(752, 'markdown'),
(753, 'marker'),
(754, 'mars'),
(755, 'mars-double'),
(756, 'mars-stroke'),
(757, 'mars-stroke-h'),
(758, 'mars-stroke-v'),
(759, 'mask'),
(760, 'mastodon'),
(761, 'maxcdn'),
(762, 'medal'),
(763, 'medapps'),
(764, 'medium'),
(765, 'medium-m'),
(766, 'medkit'),
(767, 'medrt'),
(768, 'meetup'),
(769, 'megaport'),
(770, 'meh'),
(771, 'meh-blank'),
(772, 'meh-rolling-eyes'),
(773, 'memory'),
(774, 'mendeley'),
(775, 'menorah'),
(776, 'mercury'),
(777, 'meteor'),
(778, 'microchip'),
(779, 'microphone'),
(780, 'microphone-alt'),
(781, 'microphone-alt-slash'),
(782, 'microphone-slash'),
(783, 'microscope'),
(784, 'microsoft'),
(785, 'minus'),
(786, 'minus-circle'),
(787, 'minus-square'),
(788, 'mitten'),
(789, 'mix'),
(790, 'mixcloud'),
(791, 'mizuni'),
(792, 'mobile'),
(793, 'mobile-alt'),
(794, 'modx'),
(795, 'monero'),
(796, 'money-bill'),
(797, 'money-bill-alt'),
(798, 'money-bill-wave'),
(799, 'money-bill-wave-alt'),
(800, 'money-check'),
(801, 'money-check-alt'),
(802, 'monument'),
(803, 'moon'),
(804, 'mortar-pestle'),
(805, 'mosque'),
(806, 'motorcycle'),
(807, 'mountain'),
(808, 'mouse-pointer'),
(809, 'mug-hot'),
(810, 'music'),
(811, 'napster'),
(812, 'neos'),
(813, 'network-wired'),
(814, 'neuter'),
(815, 'newspaper'),
(816, 'nimblr'),
(817, 'nintendo-switch'),
(818, 'node'),
(819, 'node-js'),
(820, 'not-equal'),
(821, 'notes-medical'),
(822, 'npm'),
(823, 'ns8'),
(824, 'nutritionix'),
(825, 'object-group'),
(826, 'object-ungroup'),
(827, 'odnoklassniki'),
(828, 'odnoklassniki-square'),
(829, 'oil-can'),
(830, 'old-republic'),
(831, 'om'),
(832, 'opencart'),
(833, 'openid'),
(834, 'opera'),
(835, 'optin-monster'),
(836, 'osi'),
(837, 'otter'),
(838, 'outdent'),
(839, 'page4'),
(840, 'pagelines'),
(841, 'pager'),
(842, 'paint-brush'),
(843, 'paint-roller'),
(844, 'palette'),
(845, 'palfed'),
(846, 'pallet'),
(847, 'paper-plane'),
(848, 'paperclip'),
(849, 'parachute-box'),
(850, 'paragraph'),
(851, 'parking'),
(852, 'passport'),
(853, 'pastafarianism'),
(854, 'paste'),
(855, 'patreon'),
(856, 'pause'),
(857, 'pause-circle'),
(858, 'paw'),
(859, 'paypal'),
(860, 'peace'),
(861, 'pen'),
(862, 'pen-alt'),
(863, 'pen-fancy'),
(864, 'pen-nib'),
(865, 'pen-square'),
(866, 'pencil-alt'),
(867, 'pencil-ruler'),
(868, 'penny-arcade'),
(869, 'people-carry'),
(870, 'pepper-hot'),
(871, 'percent'),
(872, 'percentage'),
(873, 'periscope'),
(874, 'person-booth'),
(875, 'phabricator'),
(876, 'phoenix-framework'),
(877, 'phoenix-squadron'),
(878, 'phone'),
(879, 'phone-slash'),
(880, 'phone-square'),
(881, 'phone-volume'),
(882, 'php'),
(883, 'pied-piper'),
(884, 'pied-piper-alt'),
(885, 'pied-piper-hat'),
(886, 'pied-piper-pp'),
(887, 'piggy-bank'),
(888, 'pills'),
(889, 'pinterest'),
(890, 'pinterest-p'),
(891, 'pinterest-square'),
(892, 'pizza-slice'),
(893, 'place-of-worship'),
(894, 'plane'),
(895, 'plane-arrival'),
(896, 'plane-departure'),
(897, 'play'),
(898, 'play-circle'),
(899, 'playstation'),
(900, 'plug'),
(901, 'plus'),
(902, 'plus-circle'),
(903, 'plus-square'),
(904, 'podcast'),
(905, 'poll'),
(906, 'poll-h'),
(907, 'poo'),
(908, 'poo-storm'),
(909, 'poop'),
(910, 'portrait'),
(911, 'pound-sign'),
(912, 'power-off'),
(913, 'pray'),
(914, 'praying-hands'),
(915, 'prescription'),
(916, 'prescription-bottle'),
(917, 'prescription-bottle-alt'),
(918, 'print'),
(919, 'procedures'),
(920, 'product-hunt'),
(921, 'project-diagram'),
(922, 'pushed'),
(923, 'puzzle-piece'),
(924, 'python'),
(925, 'qq'),
(926, 'qrcode'),
(927, 'question'),
(928, 'question-circle'),
(929, 'quidditch'),
(930, 'quinscape'),
(931, 'quora'),
(932, 'quote-left'),
(933, 'quote-right'),
(934, 'quran'),
(935, 'r-project'),
(936, 'radiation'),
(937, 'radiation-alt'),
(938, 'rainbow'),
(939, 'random'),
(940, 'raspberry-pi'),
(941, 'ravelry'),
(942, 'react'),
(943, 'reacteurope'),
(944, 'readme'),
(945, 'rebel'),
(946, 'receipt'),
(947, 'recycle'),
(948, 'red-river'),
(949, 'reddit'),
(950, 'reddit-alien'),
(951, 'reddit-square'),
(952, 'redhat'),
(953, 'redo'),
(954, 'redo-alt'),
(955, 'registered'),
(956, 'renren'),
(957, 'reply'),
(958, 'reply-all'),
(959, 'replyd'),
(960, 'republican'),
(961, 'researchgate'),
(962, 'resolving'),
(963, 'restroom'),
(964, 'retweet'),
(965, 'rev'),
(966, 'ribbon'),
(967, 'ring'),
(968, 'road'),
(969, 'robot'),
(970, 'rocket'),
(971, 'rocketchat'),
(972, 'rockrms'),
(973, 'route'),
(974, 'rss'),
(975, 'rss-square'),
(976, 'ruble-sign'),
(977, 'ruler'),
(978, 'ruler-combined'),
(979, 'ruler-horizontal'),
(980, 'ruler-vertical'),
(981, 'running'),
(982, 'rupee-sign'),
(983, 'sad-cry'),
(984, 'sad-tear'),
(985, 'safari'),
(986, 'sass'),
(987, 'satellite'),
(988, 'satellite-dish'),
(989, 'save'),
(990, 'schlix'),
(991, 'school'),
(992, 'screwdriver'),
(993, 'scribd'),
(994, 'scroll'),
(995, 'sd-card'),
(996, 'search'),
(997, 'search-dollar'),
(998, 'search-location'),
(999, 'search-minus'),
(1000, 'search-plus'),
(1001, 'searchengin'),
(1002, 'seedling'),
(1003, 'sellcast'),
(1004, 'sellsy'),
(1005, 'server'),
(1006, 'servicestack'),
(1007, 'shapes'),
(1008, 'share'),
(1009, 'share-alt'),
(1010, 'share-alt-square'),
(1011, 'share-square'),
(1012, 'shekel-sign'),
(1013, 'shield-alt'),
(1014, 'ship'),
(1015, 'shipping-fast'),
(1016, 'shirtsinbulk'),
(1017, 'shoe-prints'),
(1018, 'shopping-bag'),
(1019, 'shopping-basket'),
(1020, 'shopping-cart'),
(1021, 'shopware'),
(1022, 'shower'),
(1023, 'shuttle-van'),
(1024, 'sign'),
(1025, 'sign-in-alt'),
(1026, 'sign-language'),
(1027, 'sign-out-alt'),
(1028, 'signal'),
(1029, 'signature'),
(1030, 'sim-card'),
(1031, 'simplybuilt'),
(1032, 'sistrix'),
(1033, 'sitemap'),
(1034, 'sith'),
(1035, 'skating'),
(1036, 'sketch'),
(1037, 'skiing'),
(1038, 'skiing-nordic'),
(1039, 'skull'),
(1040, 'skull-crossbones'),
(1041, 'skyatlas'),
(1042, 'skype'),
(1043, 'slack'),
(1044, 'slack-hash'),
(1045, 'slash'),
(1046, 'sleigh'),
(1047, 'sliders-h'),
(1048, 'slideshare'),
(1049, 'smile'),
(1050, 'smile-beam'),
(1051, 'smile-wink'),
(1052, 'smog'),
(1053, 'smoking'),
(1054, 'smoking-ban'),
(1055, 'sms'),
(1056, 'snapchat'),
(1057, 'snapchat-ghost'),
(1058, 'snapchat-square'),
(1059, 'snowboarding'),
(1060, 'snowflake'),
(1061, 'snowman'),
(1062, 'snowplow'),
(1063, 'socks'),
(1064, 'solar-panel'),
(1065, 'sort'),
(1066, 'sort-alpha-down'),
(1067, 'sort-alpha-up'),
(1068, 'sort-amount-down'),
(1069, 'sort-amount-up'),
(1070, 'sort-down'),
(1071, 'sort-numeric-down'),
(1072, 'sort-numeric-up'),
(1073, 'sort-up'),
(1074, 'soundcloud'),
(1075, 'sourcetree'),
(1076, 'spa'),
(1077, 'space-shuttle'),
(1078, 'speakap'),
(1079, 'spider'),
(1080, 'spinner'),
(1081, 'splotch'),
(1082, 'spotify'),
(1083, 'spray-can'),
(1084, 'square'),
(1085, 'square-full'),
(1086, 'square-root-alt'),
(1087, 'squarespace'),
(1088, 'stack-exchange'),
(1089, 'stack-overflow'),
(1090, 'stamp'),
(1091, 'star'),
(1092, 'star-and-crescent'),
(1093, 'star-half'),
(1094, 'star-half-alt'),
(1095, 'star-of-david'),
(1096, 'star-of-life'),
(1097, 'staylinked'),
(1098, 'steam'),
(1099, 'steam-square'),
(1100, 'steam-symbol'),
(1101, 'step-backward'),
(1102, 'step-forward'),
(1103, 'stethoscope'),
(1104, 'sticker-mule'),
(1105, 'sticky-note'),
(1106, 'stop'),
(1107, 'stop-circle'),
(1108, 'stopwatch'),
(1109, 'store'),
(1110, 'store-alt'),
(1111, 'strava'),
(1112, 'stream'),
(1113, 'street-view'),
(1114, 'strikethrough'),
(1115, 'stripe'),
(1116, 'stripe-s'),
(1117, 'stroopwafel'),
(1118, 'studiovinari'),
(1119, 'stumbleupon'),
(1120, 'stumbleupon-circle'),
(1121, 'subscript'),
(1122, 'subway'),
(1123, 'suitcase'),
(1124, 'suitcase-rolling'),
(1125, 'sun'),
(1126, 'superpowers'),
(1127, 'superscript'),
(1128, 'supple'),
(1129, 'surprise'),
(1130, 'suse'),
(1131, 'swatchbook'),
(1132, 'swimmer'),
(1133, 'swimming-pool'),
(1134, 'synagogue'),
(1135, 'sync'),
(1136, 'sync-alt'),
(1137, 'syringe'),
(1138, 'table'),
(1139, 'table-tennis'),
(1140, 'tablet'),
(1141, 'tablet-alt'),
(1142, 'tablets'),
(1143, 'tachometer-alt'),
(1144, 'tag'),
(1145, 'tags'),
(1146, 'tape'),
(1147, 'tasks'),
(1148, 'taxi'),
(1149, 'teamspeak'),
(1150, 'teeth'),
(1151, 'teeth-open'),
(1152, 'telegram'),
(1153, 'telegram-plane'),
(1154, 'temperature-high'),
(1155, 'temperature-low'),
(1156, 'tencent-weibo'),
(1157, 'tenge'),
(1158, 'terminal'),
(1159, 'text-height'),
(1160, 'text-width'),
(1161, 'th'),
(1162, 'th-large'),
(1163, 'th-list'),
(1164, 'the-red-yeti'),
(1165, 'theater-masks'),
(1166, 'themeco'),
(1167, 'themeisle'),
(1168, 'thermometer'),
(1169, 'thermometer-empty'),
(1170, 'thermometer-full'),
(1171, 'thermometer-half'),
(1172, 'thermometer-quarter'),
(1173, 'thermometer-three-quarters'),
(1174, 'think-peaks'),
(1175, 'thumbs-down'),
(1176, 'thumbs-up'),
(1177, 'thumbtack'),
(1178, 'ticket-alt'),
(1179, 'times'),
(1180, 'times-circle'),
(1181, 'tint'),
(1182, 'tint-slash'),
(1183, 'tired'),
(1184, 'toggle-off'),
(1185, 'toggle-on'),
(1186, 'toilet'),
(1187, 'toilet-paper'),
(1188, 'toolbox'),
(1189, 'tools'),
(1190, 'tooth'),
(1191, 'torah'),
(1192, 'torii-gate'),
(1193, 'tractor'),
(1194, 'trade-federation'),
(1195, 'trademark'),
(1196, 'traffic-light'),
(1197, 'train'),
(1198, 'tram'),
(1199, 'transgender'),
(1200, 'transgender-alt'),
(1201, 'trash'),
(1202, 'trash-alt'),
(1203, 'trash-restore'),
(1204, 'trash-restore-alt'),
(1205, 'tree'),
(1206, 'trello'),
(1207, 'tripadvisor'),
(1208, 'trophy'),
(1209, 'truck'),
(1210, 'truck-loading'),
(1211, 'truck-monster'),
(1212, 'truck-moving'),
(1213, 'truck-pickup'),
(1214, 'tshirt'),
(1215, 'tty'),
(1216, 'tumblr'),
(1217, 'tumblr-square'),
(1218, 'tv'),
(1219, 'twitch'),
(1220, 'twitter'),
(1221, 'twitter-square'),
(1222, 'typo3'),
(1223, 'uber'),
(1224, 'ubuntu'),
(1225, 'uikit'),
(1226, 'umbrella'),
(1227, 'umbrella-beach'),
(1228, 'underline'),
(1229, 'undo'),
(1230, 'undo-alt'),
(1231, 'uniregistry'),
(1232, 'universal-access'),
(1233, 'university'),
(1234, 'unlink'),
(1235, 'unlock'),
(1236, 'unlock-alt'),
(1237, 'untappd'),
(1238, 'upload'),
(1239, 'ups'),
(1240, 'usb'),
(1241, 'user'),
(1242, 'user-alt'),
(1243, 'user-alt-slash'),
(1244, 'user-astronaut'),
(1245, 'user-check'),
(1246, 'user-circle'),
(1247, 'user-clock'),
(1248, 'user-cog'),
(1249, 'user-edit'),
(1250, 'user-friends'),
(1251, 'user-graduate'),
(1252, 'user-injured'),
(1253, 'user-lock'),
(1254, 'user-md'),
(1255, 'user-minus'),
(1256, 'user-ninja'),
(1257, 'user-nurse'),
(1258, 'user-plus'),
(1259, 'user-secret'),
(1260, 'user-shield'),
(1261, 'user-slash'),
(1262, 'user-tag'),
(1263, 'user-tie'),
(1264, 'user-times'),
(1265, 'users'),
(1266, 'users-cog'),
(1267, 'usps'),
(1268, 'ussunnah'),
(1269, 'utensil-spoon'),
(1270, 'utensils'),
(1271, 'vaadin'),
(1272, 'vector-square'),
(1273, 'venus'),
(1274, 'venus-double'),
(1275, 'venus-mars'),
(1276, 'viacoin'),
(1277, 'viadeo'),
(1278, 'viadeo-square'),
(1279, 'vial'),
(1280, 'vials'),
(1281, 'viber'),
(1282, 'video'),
(1283, 'video-slash'),
(1284, 'vihara'),
(1285, 'vimeo'),
(1286, 'vimeo-square'),
(1287, 'vimeo-v'),
(1288, 'vine'),
(1289, 'vk'),
(1290, 'vnv'),
(1291, 'volleyball-ball'),
(1292, 'volume-down'),
(1293, 'volume-mute'),
(1294, 'volume-off'),
(1295, 'volume-up'),
(1296, 'vote-yea'),
(1297, 'vr-cardboard'),
(1298, 'vuejs'),
(1299, 'walking'),
(1300, 'wallet'),
(1301, 'warehouse'),
(1302, 'water'),
(1303, 'weebly'),
(1304, 'weibo'),
(1305, 'weight'),
(1306, 'weight-hanging'),
(1307, 'weixin'),
(1308, 'whatsapp'),
(1309, 'whatsapp-square'),
(1310, 'wheelchair'),
(1311, 'whmcs'),
(1312, 'wifi'),
(1313, 'wikipedia-w'),
(1314, 'wind'),
(1315, 'window-close'),
(1316, 'window-maximize'),
(1317, 'window-minimize'),
(1318, 'window-restore'),
(1319, 'windows'),
(1320, 'wine-bottle'),
(1321, 'wine-glass'),
(1322, 'wine-glass-alt'),
(1323, 'wix'),
(1324, 'wizards-of-the-coast'),
(1325, 'wolf-pack-battalion'),
(1326, 'won-sign'),
(1327, 'wordpress'),
(1328, 'wordpress-simple'),
(1329, 'wpbeginner'),
(1330, 'wpexplorer'),
(1331, 'wpforms'),
(1332, 'wpressr'),
(1333, 'wrench'),
(1334, 'x-ray'),
(1335, 'xbox'),
(1336, 'xing'),
(1337, 'xing-square'),
(1338, 'y-combinator'),
(1339, 'yahoo'),
(1340, 'yandex'),
(1341, 'yandex-international'),
(1342, 'yarn'),
(1343, 'yelp'),
(1344, 'yen-sign'),
(1345, 'yin-yang'),
(1346, 'yoast'),
(1347, 'youtube'),
(1348, 'youtube-square'),
(1349, 'zhihu');

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `des_funcao` varchar(150) DEFAULT NULL,
  `des_tipoarquivo` varchar(150) DEFAULT NULL,
  `des_descricao` varchar(5000) DEFAULT NULL,
  `dat_datahora` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `caminho` varchar(300) NOT NULL,
  `icone` varchar(45) NOT NULL DEFAULT '',
  `id_menupai` int(11) DEFAULT NULL,
  `parametros` varchar(100) NOT NULL DEFAULT '',
  `log_predivisor` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `menu`
--

INSERT INTO `menu` (`id`, `nome`, `caminho`, `icone`, `id_menupai`, `parametros`, `log_predivisor`) VALUES
(1, 'Usuário', '#', 'fa-users', NULL, '', 0),
(2, 'Listar', './usuarios.php', 'fa-list', 1, '', 0),
(3, 'Novo Usuário', './editaUsuario.php', 'fa-plus', 1, 'tipoEntrada=new', 0),
(4, 'Geral', './configuracao.php', 'fa-gears', 10, '', 0),
(6, 'Botões', '#', 'fa-keyboard-o', NULL, '', 1),
(7, 'Listar', './botoes.php', 'fa-list', 6, '', 0),
(8, 'Novo Botão', './editaBotao.php', 'fa-plus', 6, 'tipoEntrada=new', 0),
(9, 'Serviço Horas', './servicohora.php', 'fa-clock-o', 10, '', 0),
(10, 'Configuração', '#', 'fa-gear', NULL, '', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `paciente`
--

DROP TABLE IF EXISTS `paciente`;
CREATE TABLE `paciente` (
  `id` int(11) NOT NULL,
  `matricula` varchar(45) NOT NULL,
  `nome` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `paciente`
--

INSERT INTO `paciente` (`id`, `matricula`, `nome`) VALUES
(30, '11', 'Grupo de Usuarios SUS');

-- --------------------------------------------------------

--
-- Estrutura para tabela `recuperasenha`
--

DROP TABLE IF EXISTS `recuperasenha`;
CREATE TABLE `recuperasenha` (
  `id` int(11) NOT NULL,
  `hash` varchar(150) NOT NULL,
  `datahora` datetime NOT NULL,
  `datahoralimite` datetime NOT NULL,
  `id_usuario_usuario` int(11) NOT NULL,
  `log_usado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `recuperasenha`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `senhas`
--

DROP TABLE IF EXISTS `senhas`;
CREATE TABLE `senhas` (
  `id_senha` int(11) NOT NULL,
  `dat_gerada` datetime NOT NULL,
  `num_sequencia` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `senhas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `senhaschamadas`
--

DROP TABLE IF EXISTS `senhaschamadas`;
CREATE TABLE `senhaschamadas` (
  `id_senhaschamadas` int(11) NOT NULL,
  `dat_datahorachamada` datetime NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_senha` int(11) DEFAULT NULL,
  `des_local` varchar(50) NOT NULL,
  `log_chamada` int(1) NOT NULL DEFAULT '0' COMMENT '0 - NÃ£o Chamado na Tela | 1 - Foi Chamado',
  `log_rechamado` int(1) NOT NULL DEFAULT '0' COMMENT '0 - NÃ£o Ã© rechamado | 1 - Rechamado um registro anterior',
  `log_encerrada` int(1) NOT NULL DEFAULT '0',
  `log_naocompareceu` int(1) NOT NULL DEFAULT '0',
  `id_chamadaolostech` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `senhaschamadas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `servico`
--

DROP TABLE IF EXISTS `servico`;
CREATE TABLE `servico` (
  `id_servico` int(11) NOT NULL,
  `des_descricao` varchar(100) NOT NULL,
  `sigla` varchar(3) NOT NULL,
  `ind_situacao` varchar(1) NOT NULL DEFAULT 'A',
  `num_sequencia` int(1) NOT NULL DEFAULT '0',
  `qtde_sequencia` int(11) NOT NULL DEFAULT '0',
  `des_cor` varchar(30) NOT NULL DEFAULT '0033ff',
  `val_fator` decimal(3,2) NOT NULL,
  `id_tipoestacao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `servico`
--

INSERT INTO `servico` (`id_servico`, `des_descricao`, `sigla`, `ind_situacao`, `num_sequencia`, `qtde_sequencia`, `des_cor`, `val_fator`, `id_tipoestacao`) VALUES
(1, 'Cons. Normal', 'CN', 'A', 3, 1, 'da2222', '1.80', 3),
(2, 'At. Ext. Preferencial', 'EP', 'A', 2, 2, 'FFFF00', '1.50', 1),
(3, 'At. Ext. Normal', 'EN', 'A', 1, 3, 'FFFF00', '1.00', 1),
(4, 'At. Ext. Preferencial +80', 'EP+', 'A', 4, 4, 'FFFF00', '2.20', 1),
(5, 'Transp./Ex. Normal', 'TRN', 'A', 5, 5, '009933', '1.00', 3),
(6, 'Transp./Ex. Preferencial', 'TRP', 'A', 6, 6, '009933', '1.50', 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicohora`
--

DROP TABLE IF EXISTS `servicohora`;
CREATE TABLE `servicohora` (
  `id_servicohora` int(11) NOT NULL,
  `diasemana` varchar(3) NOT NULL COMMENT 'Dom,Seg,Ter,Qua,Qui,Sex,Sab,All',
  `horainicio` datetime NOT NULL,
  `horafim` datetime NOT NULL,
  `id_servico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Fazendo dump de dados para tabela `servicohora`
--

INSERT INTO `servicohora` (`id_servicohora`, `diasemana`, `horainicio`, `horafim`, `id_servico`) VALUES
(1, 'Seg', '2020-01-23 07:30:00', '2020-01-23 16:59:00', 5),
(3, 'Ter', '2020-01-23 07:30:00', '2020-01-23 16:59:00', 5),
(4, 'Qua', '2020-01-23 07:30:00', '2020-01-23 16:59:00', 5),
(5, 'Qui', '2020-01-23 07:30:00', '2020-01-23 16:59:00', 5),
(6, 'Sex', '2020-01-23 07:30:00', '2020-01-23 16:59:00', 5),
(7, 'Seg', '2020-01-23 07:30:00', '2020-01-23 16:59:00', 6),
(8, 'Ter', '2020-01-23 07:30:00', '2020-01-23 16:59:00', 6),
(9, 'Qua', '2020-01-23 07:30:00', '2020-01-23 16:59:00', 6),
(10, 'Qui', '2020-01-23 07:30:00', '2020-01-23 16:59:00', 6),
(11, 'Sex', '2020-01-23 07:30:00', '2020-01-23 16:59:00', 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipoestacao`
--

DROP TABLE IF EXISTS `tipoestacao`;
CREATE TABLE `tipoestacao` (
  `id` int(11) NOT NULL,
  `des_tipo` varchar(45) NOT NULL,
  `sigla` varchar(2) NOT NULL,
  `cor` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `tipoestacao`
--

INSERT INTO `tipoestacao` (`id`, `des_tipo`, `sigla`, `cor`) VALUES
(1, 'Recepção', 'RE', ''),
(2, 'Triagem', 'TR', '8F01FF'),
(3, 'Recepção Transp', 'RT', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipousuario`
--

DROP TABLE IF EXISTS `tipousuario`;
CREATE TABLE `tipousuario` (
  `id_tipousuario` int(11) NOT NULL,
  `des_tipousuario` varchar(75) NOT NULL,
  `des_sigla` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `tipousuario`
--

INSERT INTO `tipousuario` (`id_tipousuario`, `des_tipousuario`, `des_sigla`) VALUES
(1, 'Administrador', 'adm'),
(2, 'Usuário', 'usr');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `des_usuario` varchar(45) NOT NULL,
  `des_senha` varchar(400) DEFAULT NULL,
  `des_nome` varchar(250) NOT NULL,
  `des_email` varchar(250) NOT NULL,
  `id_tipousuario` int(11) NOT NULL,
  `ind_situacao` int(1) NOT NULL DEFAULT '1',
  `log_olostech` int(1) NOT NULL DEFAULT '0',
  `olostechuser` varchar(45) NOT NULL DEFAULT 'nothing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `des_usuario`, `des_senha`, `des_nome`, `des_email`, `id_tipousuario`, `ind_situacao`, `log_olostech`, `olostechuser`) VALUES
(1, 'admin', '$2y$10$aR4opWTo.EaLlAVS7KD2DuG5936PE7Tt5jpggtj0fkGb5e9SVa72i', 'Administrador', 'email@adminsistema.com', 1, 1, 0, ''),;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuariomenu`
--

DROP TABLE IF EXISTS `usuariomenu`;
CREATE TABLE `usuariomenu` (
  `Id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario_servico`
--

DROP TABLE IF EXISTS `usuario_servico`;
CREATE TABLE `usuario_servico` (
  `id_usuario_servico` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `usuario_servico`
--

INSERT INTO `usuario_servico` (`id_usuario_servico`, `id_usuario`, `id_servico`) VALUES
(23, 1, 1),
(24, 1, 2);

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `botao`
--
ALTER TABLE `botao`
  ADD PRIMARY KEY (`id_botao`),
  ADD KEY `fk_botao_servico1_idx` (`id_servico`);

--
-- Índices de tabela `chamadaolostech`
--
ALTER TABLE `chamadaolostech`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_chamadasolostech_estacoes1_idx` (`id_estacoes`),
  ADD KEY `fk_chamadasolostech_paciente1_idx` (`id_paciente`);

--
-- Índices de tabela `configuracao`
--
ALTER TABLE `configuracao`
  ADD PRIMARY KEY (`id_configuracao`);

--
-- Índices de tabela `coreshtml`
--
ALTER TABLE `coreshtml`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `estacaodisplay`
--
ALTER TABLE `estacaodisplay`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_estacaodisplay_estacoes` (`id_estacao`),
  ADD KEY `fk_estacaodisplay_tipoestacao` (`id_tipoestacaoliberado`);

--
-- Índices de tabela `estacoes`
--
ALTER TABLE `estacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_estacoes_tipoestacao1_idx` (`id_tipoestacao`);

--
-- Índices de tabela `fontawesome`
--
ALTER TABLE `fontawesome`
  ADD PRIMARY KEY (`id_fontawesome`);

--
-- Índices de tabela `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu_menu1_idx` (`id_menupai`);

--
-- Índices de tabela `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricula` (`matricula`);

--
-- Índices de tabela `recuperasenha`
--
ALTER TABLE `recuperasenha`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_recuperasenha_usuario1_idx` (`id_usuario_usuario`);

--
-- Índices de tabela `senhas`
--
ALTER TABLE `senhas`
  ADD PRIMARY KEY (`id_senha`),
  ADD KEY `id_servico` (`id_servico`);

--
-- Índices de tabela `senhaschamadas`
--
ALTER TABLE `senhaschamadas`
  ADD PRIMARY KEY (`id_senhaschamadas`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_senha` (`id_senha`),
  ADD KEY `fk_senhaschamadas_chamadaolostech1_idx` (`id_chamadaolostech`);

--
-- Índices de tabela `servico`
--
ALTER TABLE `servico`
  ADD PRIMARY KEY (`id_servico`),
  ADD KEY `fk_servico_tipoestacao1_idx` (`id_tipoestacao`);

--
-- Índices de tabela `servicohora`
--
ALTER TABLE `servicohora`
  ADD PRIMARY KEY (`id_servicohora`),
  ADD KEY `fk_servicohora_servico` (`id_servico`);

--
-- Índices de tabela `tipoestacao`
--
ALTER TABLE `tipoestacao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tipousuario`
--
ALTER TABLE `tipousuario`
  ADD PRIMARY KEY (`id_tipousuario`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_usuario_tipousuario1_idx` (`id_tipousuario`);

--
-- Índices de tabela `usuariomenu`
--
ALTER TABLE `usuariomenu`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_usuariomenu_menu` (`id_menu`),
  ADD KEY `fk_usuariomenu_usuario` (`id_usuario`);

--
-- Índices de tabela `usuario_servico`
--
ALTER TABLE `usuario_servico`
  ADD PRIMARY KEY (`id_usuario_servico`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_servico` (`id_servico`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `botao`
--
ALTER TABLE `botao`
  MODIFY `id_botao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de tabela `chamadaolostech`
--
ALTER TABLE `chamadaolostech`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT de tabela `configuracao`
--
ALTER TABLE `configuracao`
  MODIFY `id_configuracao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de tabela `coreshtml`
--
ALTER TABLE `coreshtml`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=481;
--
-- AUTO_INCREMENT de tabela `estacaodisplay`
--
ALTER TABLE `estacaodisplay`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de tabela `estacoes`
--
ALTER TABLE `estacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de tabela `fontawesome`
--
ALTER TABLE `fontawesome`
  MODIFY `id_fontawesome` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1350;
--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de tabela `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT de tabela `recuperasenha`
--
ALTER TABLE `recuperasenha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de tabela `senhas`
--
ALTER TABLE `senhas`
  MODIFY `id_senha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41569;
--
-- AUTO_INCREMENT de tabela `senhaschamadas`
--
ALTER TABLE `senhaschamadas`
  MODIFY `id_senhaschamadas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51174;
--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `id_servico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de tabela `servicohora`
--
ALTER TABLE `servicohora`
  MODIFY `id_servicohora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de tabela `tipoestacao`
--
ALTER TABLE `tipoestacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de tabela `tipousuario`
--
ALTER TABLE `tipousuario`
  MODIFY `id_tipousuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT de tabela `usuario_servico`
--
ALTER TABLE `usuario_servico`
  MODIFY `id_usuario_servico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;
--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `botao`
--
ALTER TABLE `botao`
  ADD CONSTRAINT `fk_botao_servico1` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id_servico`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `chamadaolostech`
--
ALTER TABLE `chamadaolostech`
  ADD CONSTRAINT `fk_chamadasolostech_estacoes1` FOREIGN KEY (`id_estacoes`) REFERENCES `estacoes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_chamadasolostech_paciente1` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `estacaodisplay`
--
ALTER TABLE `estacaodisplay`
  ADD CONSTRAINT `fk_estacaodisplay_estacoes` FOREIGN KEY (`id_estacao`) REFERENCES `estacoes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_estacaodisplay_tipoestacao` FOREIGN KEY (`id_tipoestacaoliberado`) REFERENCES `tipoestacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `estacoes`
--
ALTER TABLE `estacoes`
  ADD CONSTRAINT `fk_estacoes_tipoestacao1` FOREIGN KEY (`id_tipoestacao`) REFERENCES `tipoestacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_menu1` FOREIGN KEY (`id_menupai`) REFERENCES `menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `recuperasenha`
--
ALTER TABLE `recuperasenha`
  ADD CONSTRAINT `fk_recuperasenha_usuario1` FOREIGN KEY (`id_usuario_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `senhas`
--
ALTER TABLE `senhas`
  ADD CONSTRAINT `senhas_ibfk_1` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id_servico`);

--
-- Restrições para tabelas `senhaschamadas`
--
ALTER TABLE `senhaschamadas`
  ADD CONSTRAINT `fk_senhaschamadas_chamadaolostech1` FOREIGN KEY (`id_chamadaolostech`) REFERENCES `chamadaolostech` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `senhaschamadas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `senhaschamadas_ibfk_2` FOREIGN KEY (`id_senha`) REFERENCES `senhas` (`id_senha`);

--
-- Restrições para tabelas `servico`
--
ALTER TABLE `servico`
  ADD CONSTRAINT `fk_servico_tipoestacao1` FOREIGN KEY (`id_tipoestacao`) REFERENCES `tipoestacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `servicohora`
--
ALTER TABLE `servicohora`
  ADD CONSTRAINT `fk_servicohora_servico` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id_servico`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_tipousuario1` FOREIGN KEY (`id_tipousuario`) REFERENCES `tipousuario` (`id_tipousuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `usuariomenu`
--
ALTER TABLE `usuariomenu`
  ADD CONSTRAINT `fk_usuariomenu_menu` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuariomenu_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `usuario_servico`
--
ALTER TABLE `usuario_servico`
  ADD CONSTRAINT `usuario_servico_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `usuario_servico_ibfk_2` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id_servico`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
