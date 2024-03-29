CREATE USER orcamento_contabil IDENTIFIED BY pepita_05052022_sjc_1555;

GRANT CREATE SESSION TO orcamento_contabil;
GRANT CREATE PROCEDURE TO orcamento_contabil;
GRANT CREATE TABLE TO orcamento_contabil;
GRANT CREATE VIEW TO orcamento_contabil;
GRANT UNLIMITED TABLESPACE TO orcamento_contabil;
GRANT CREATE SEQUENCE TO orcamento_contabil;

GRANT EXECUTE ON dbasgu.FNC_MV2000_HMVPEP TO orcamento_contabil;
GRANT SELECT ON dbasgu.USUARIOS TO orcamento_contabil;
GRANT SELECT ON DBASGU.PAPEL_USUARIOS TO orcamento_contabil;
GRANT SELECT ON DBASGU.PAPEL TO orcamento_contabil;

GRANT SELECT ON dbamv.PLANO_CONTAS TO orcamento_contabil;

GRANT SELECT ON dbamv.MVW_CONTA_CONTABIL_REALIZADO TO orcamento_contabil;

GRANT INSERT ON portal_projetos.ACESSO TO orcamento_contabil;
GRANT SELECT ON portal_projetos.SEQ_CD_ACESSO TO orcamento_contabil;
