CREATE TABLE clientes (
id INTEGER  NOT NULL,
tipo char(1) DEFAULT 'J' NULL,
data date  NULL,
time time  NULL);

CREATE TABLE configurar (
pedido_minimo float  NULL,
valor_minimo_parcela float  NULL,
id_empresa INTEGER  NULL);

CREATE TABLE desconto (
id_desconto INTEGER  NOT NULL PRIMARY KEY AUTO_INCREMENT,
porcentagem integer  NULL);

CREATE TABLE detalhe_pedido (
id_detalhe INTEGER  PRIMARY KEY AUTO_INCREMENT NOT NULL,
numero_pedido inteGER  NULL,
id_produto inteGER  NULL,
quantidade integer  NULL,
custo float  NULL,
id_desconto inteGER  NULL);

CREATE TABLE empresas (
id_perfil INTEGER  PRIMARY KEY AUTO_INCREMENT NOT NULL,
razao_social varcHAR(255)  NULL,
proprietario varchAR(255)  NULL,
telefone varCHAR(30)  NULL,
direcao varchar(255)  NULL,
email varchar(64)  NULL,
id_endereco inteGER  NULL,
nome_fantasia varcHAR(255)  NULL,
cnpj integer  NULL,
ie integer  NULL,
fonecedor integer(1) DEFAULT '1' NULL,
celular inteGER  NULL,
whatsapp inteGER  NULL,
endereco_numero char(11)  NULL);

CREATE TABLE endereco (
id_endereco INTEGER  NOT NULL PRIMARY KEY AUTO_INCREMENT,
endereco varchar(255)  NULL,
bairro varchar(150)  NULL,
estado varchar(2)  NULL,
cidade varchar(200)  NULL,
cep inteGER  NULL);



CREATE TABLE marcas (
id_marca INTEGER  NOT NULL PRIMARY KEY AUTO_INCREMENT,
nome_marca char(40)  NULL);

CREATE TABLE pedidos (
id_pedidos INTEGER  PRIMARY KEY AUTO_INCREMENT NOT NULL,
numero inteGER  NULL,
fecha_data DATE  NULL,
fecha_hora TIME  NULL,
id_fornecedor inteGER  NULL,
id_transportadora varchar(255)  NULL,
id_prazos varchar(255)  NULL,
comentarios varCHAR(20)  NULL,
id_representante inteGER  NULL,
comissao_representante char(1) DEFAULT 'N' NULL,
tipo_pedido char(1) DEFAULT 'C' NULL,
id_cliente inTEGER DEFAULT '0' NULL);


CREATE TABLE pessoa_fisica (
id_pessoa INTEGER  PRIMARY KEY AUTO_INCREMENT NOT NULL,
nome_pessoa varchar(40)  NULL,
sobrenome_pessoa varCHAR(40)  NULL,
nascimento date  NULL,
telefone inteGER  NULL,
email varCHAR(255)  NULL,
celular integer  NULL,
RG integer  NULL,
CPF inteGER  NULL,
endereco_numero char(11)  NULL,
id_endereco inteGER  NULL);


CREATE TABLE prazos (
id_prazos INTEGER  NOT NULL PRIMARY KEY AUTO_INCREMENT,
prazo_em_dias vaRCHAR(40)  NULL,
parcelas inteGER  NULL);



CREATE TABLE produtos (
id_produto INTEGER  PRIMARY KEY AUTO_INCREMENT NOT NULL,
codigo_produto inteGER  NULL,
nome_produto char(100)  NULL,
modelo_produto varCHAR(30)  NULL,
id_departamento_produto inteGER  NULL,
id_marca_produto INTEGER  NULL,
status_produto inteGER  NULL,
unidade_medida_produto char(20)  NULL,
peso_produto char(20)  NULL,
data_adicionado date  NULL,
hora_adicionado time  NULL,
preco_produto floaT  NULL,
descricao varCHAR(100)  NULL,
ipi inteGER  NULL);


CREATE TABLE representantes (
id_representantes INTEGER  NOT NULL PRIMARY KEY AUTO_INCREMENT,
id_pessoa integer  NULL,
comissao integer  NULL);


CREATE TABLE tranportadora (
id_transportadora INTEGER  NOT NULL PRIMARY KEY AUTO_INCREMENT,
transportadora varCHAR(255)  NULL,
id_empresa_transportadora integer  NULL);
