-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 27-Maio-2018 às 22:42
-- Versão do servidor: 5.7.19
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pedidos`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

DROP TABLE IF EXISTS `categoria`;
CREATE TABLE IF NOT EXISTS `categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `id_pai` int(11) DEFAULT NULL,
  `categoria` varchar(140) NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `id_pai`, `categoria`) VALUES
(1, NULL, 'Produtos'),
(2, 1, 'Higiene');

-- --------------------------------------------------------

--
-- Estrutura da tabela `configurar`
--

DROP TABLE IF EXISTS `configurar`;
CREATE TABLE IF NOT EXISTS `configurar` (
  `id_configurar` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_minimo` float DEFAULT NULL,
  `valor_minimo_parcela` float DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_configurar`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `desconto`
--

DROP TABLE IF EXISTS `desconto`;
CREATE TABLE IF NOT EXISTS `desconto` (
  `id_desconto` int(11) NOT NULL AUTO_INCREMENT,
  `porcentagem` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_desconto`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `desconto`
--

INSERT INTO `desconto` (`id_desconto`, `porcentagem`) VALUES
(1, 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `detalhe_pedido`
--

DROP TABLE IF EXISTS `detalhe_pedido`;
CREATE TABLE IF NOT EXISTS `detalhe_pedido` (
  `id_detalhe` int(11) NOT NULL AUTO_INCREMENT,
  `numero_pedido` int(11) DEFAULT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `preco` float DEFAULT NULL,
  `desconto` int(11) DEFAULT NULL,
  `subtotal` float NOT NULL,
  PRIMARY KEY (`id_detalhe`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `detalhe_pedido`
--

INSERT INTO `detalhe_pedido` (`id_detalhe`, `numero_pedido`, `id_produto`, `quantidade`, `preco`, `desconto`, `subtotal`) VALUES
(1, 100, 1, 10, 90, 10, 810),
(2, 2, 1, 10, 90, 10, 810),
(3, 2, 1, 11, 90, 10, 891),
(4, 34, 1, 8, 90, 10, 648),
(5, 102, 1, 1, 90, 10, 81),
(6, 103, 1, 1, 90, 10, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresas`
--

DROP TABLE IF EXISTS `empresas`;
CREATE TABLE IF NOT EXISTS `empresas` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `razao_social` varchar(255) DEFAULT NULL,
  `proprietario` varchar(255) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `direcao` varchar(255) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `id_endereco` int(11) DEFAULT NULL,
  `nome_fantasia` varchar(255) DEFAULT NULL,
  `cnpj` int(11) DEFAULT NULL,
  `ie` int(11) DEFAULT NULL,
  `fonecedor` int(1) DEFAULT '1',
  `celular` int(11) DEFAULT NULL,
  `whatsapp` int(11) DEFAULT NULL,
  `endereco_numero` char(11) DEFAULT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `empresas`
--

INSERT INTO `empresas` (`id_perfil`, `razao_social`, `proprietario`, `telefone`, `direcao`, `email`, `id_endereco`, `nome_fantasia`, `cnpj`, `ie`, `fonecedor`, `celular`, `whatsapp`, `endereco_numero`) VALUES
(1, 'GTIS', 'Hadson Dias', '129990000', 'Hadson', 'gtis@gtis.com', 1, 'GTIS', 777777, 888888, 1, 999999, 888888, '111');

-- --------------------------------------------------------

--
-- Estrutura da tabela `endereco`
--

DROP TABLE IF EXISTS `endereco`;
CREATE TABLE IF NOT EXISTS `endereco` (
  `id_endereco` int(11) NOT NULL AUTO_INCREMENT,
  `endereco` varchar(255) DEFAULT NULL,
  `bairro` varchar(150) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `cidade` varchar(200) DEFAULT NULL,
  `cep` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_endereco`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `endereco`
--

INSERT INTO `endereco` (`id_endereco`, `endereco`, `bairro`, `estado`, `cidade`, `cep`) VALUES
(1, 'Rua X', 'Y', 'SP', 'SJC', 46430000);

-- --------------------------------------------------------

--
-- Estrutura da tabela `marcas`
--

DROP TABLE IF EXISTS `marcas`;
CREATE TABLE IF NOT EXISTS `marcas` (
  `id_marca` int(11) NOT NULL AUTO_INCREMENT,
  `nome_marca` char(40) DEFAULT NULL,
  PRIMARY KEY (`id_marca`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `marcas`
--

INSERT INTO `marcas` (`id_marca`, `nome_marca`) VALUES
(1, 'Nestle');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id_pedidos` int(11) NOT NULL AUTO_INCREMENT,
  `numero` int(11) DEFAULT NULL,
  `fecha_data` date DEFAULT NULL,
  `fecha_hora` time DEFAULT NULL,
  `id_fornecedor` int(11) DEFAULT NULL,
  `id_transportadora` int(11) DEFAULT NULL,
  `id_prazos` varchar(255) DEFAULT NULL,
  `comentarios` varchar(20) DEFAULT NULL,
  `id_representante` int(11) DEFAULT NULL,
  `comissao_representante` char(1) DEFAULT 'N',
  `tipo_pedido` char(1) DEFAULT 'C',
  `id_cliente` int(11) DEFAULT '0',
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id_pedidos`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`id_pedidos`, `numero`, `fecha_data`, `fecha_hora`, `id_fornecedor`, `id_transportadora`, `id_prazos`, `comentarios`, `id_representante`, `comissao_representante`, `tipo_pedido`, `id_cliente`, `status`) VALUES
(1, 100, '2018-05-25', '04:48:45', 1, 1, '1', NULL, 1, 'N', 'C', NULL, 1),
(2, 2, '2018-05-25', '04:50:29', NULL, NULL, NULL, NULL, NULL, 'N', 'C', 0, 0),
(3, 34, '2018-05-25', '05:10:19', 1, NULL, NULL, NULL, NULL, 'N', 'C', 0, 0),
(4, 102, '2018-05-27', '18:14:19', 1, 1, '1', 'teste', 1, 'S', 'C', 1, 0),
(5, 103, '2018-05-27', '18:52:25', NULL, NULL, NULL, NULL, 2, 'N', 'C', 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoa_fisica`
--

DROP TABLE IF EXISTS `pessoa_fisica`;
CREATE TABLE IF NOT EXISTS `pessoa_fisica` (
  `id_pessoa` int(11) NOT NULL AUTO_INCREMENT,
  `nome_pessoa` varchar(40) DEFAULT NULL,
  `sobrenome_pessoa` varchar(40) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `telefone` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `celular` int(11) DEFAULT NULL,
  `RG` int(11) DEFAULT NULL,
  `CPF` int(11) DEFAULT NULL,
  `endereco_numero` char(11) DEFAULT NULL,
  `id_endereco` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id_pessoa`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `pessoa_fisica`
--

INSERT INTO `pessoa_fisica` (`id_pessoa`, `nome_pessoa`, `sobrenome_pessoa`, `nascimento`, `telefone`, `email`, `celular`, `RG`, `CPF`, `endereco_numero`, `id_endereco`, `id_empresa`) VALUES
(1, 'Hadson', 'Dias', '2018-05-22', 1290000, 'gtis@gmail.com', 12900000, 2147483647, 2147483647, '111', 1, 1),
(2, 'Gilberto', 'Gomes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `prazos`
--

DROP TABLE IF EXISTS `prazos`;
CREATE TABLE IF NOT EXISTS `prazos` (
  `id_prazos` int(11) NOT NULL AUTO_INCREMENT,
  `prazo_em_dias` varchar(40) DEFAULT NULL,
  `parcelas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_prazos`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `prazos`
--

INSERT INTO `prazos` (`id_prazos`, `prazo_em_dias`, `parcelas`) VALUES
(1, '120', 20);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE IF NOT EXISTS `produtos` (
  `id_produto` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_produto` int(11) DEFAULT NULL,
  `nome_produto` char(100) DEFAULT NULL,
  `modelo_produto` varchar(30) DEFAULT NULL,
  `id_departamento_produto` int(11) DEFAULT NULL,
  `id_marca_produto` int(11) DEFAULT NULL,
  `status_produto` int(11) DEFAULT NULL,
  `unidade_medida_produto` char(20) DEFAULT NULL,
  `peso_produto` char(20) DEFAULT NULL,
  `data_adicionado` date DEFAULT NULL,
  `hora_adicionado` time DEFAULT NULL,
  `preco_produto` float DEFAULT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `ipi` int(11) DEFAULT NULL,
  `unidades` int(11) NOT NULL,
  PRIMARY KEY (`id_produto`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `codigo_produto`, `nome_produto`, `modelo_produto`, `id_departamento_produto`, `id_marca_produto`, `status_produto`, `unidade_medida_produto`, `peso_produto`, `data_adicionado`, `hora_adicionado`, `preco_produto`, `descricao`, `ipi`, `unidades`) VALUES
(1, 100, 'Leite', 'nd3', 1, 1, 1, 'kg', '10', '2018-05-25', '00:22:11', 90, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `representantes`
--

DROP TABLE IF EXISTS `representantes`;
CREATE TABLE IF NOT EXISTS `representantes` (
  `id_representantes` int(11) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(11) DEFAULT NULL,
  `comissao` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_representantes`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `representantes`
--

INSERT INTO `representantes` (`id_representantes`, `id_pessoa`, `comissao`) VALUES
(1, 2, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tranportadora`
--

DROP TABLE IF EXISTS `tranportadora`;
CREATE TABLE IF NOT EXISTS `tranportadora` (
  `id_transportadora` int(11) NOT NULL AUTO_INCREMENT,
  `transportadora` varchar(255) DEFAULT NULL,
  `id_empresa_transportadora` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_transportadora`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tranportadora`
--

INSERT INTO `tranportadora` (`id_transportadora`, `transportadora`, `id_empresa_transportadora`) VALUES
(1, NULL, 1),
(2, NULL, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `transportadoras`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `transportadoras`;
CREATE TABLE IF NOT EXISTS `transportadoras` (
`id_empresa_transportadora` int(11)
,`razao_social` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view1`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view1`;
CREATE TABLE IF NOT EXISTS `view1` (
`id_pessoa` int(11)
,`nome_pessoa` varchar(40)
,`sobrenome_pessoa` varchar(40)
,`comissao` int(11)
);

-- --------------------------------------------------------

--
-- Structure for view `transportadoras`
--
DROP TABLE IF EXISTS `transportadoras`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `transportadoras`  AS  select `tranportadora`.`id_empresa_transportadora` AS `id_empresa_transportadora`,`empresas`.`razao_social` AS `razao_social` from (`tranportadora` join `empresas` on((`tranportadora`.`id_empresa_transportadora` = `empresas`.`id_perfil`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view1`
--
DROP TABLE IF EXISTS `view1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view1`  AS  select `representantes`.`id_pessoa` AS `id_pessoa`,`pessoa_fisica`.`nome_pessoa` AS `nome_pessoa`,`pessoa_fisica`.`sobrenome_pessoa` AS `sobrenome_pessoa`,`representantes`.`comissao` AS `comissao` from (`representantes` join `pessoa_fisica` on((`representantes`.`id_pessoa` = `pessoa_fisica`.`id_pessoa`))) ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
