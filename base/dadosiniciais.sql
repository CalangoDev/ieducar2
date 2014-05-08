/**
 * Usuario admin
 */
insert into cadastro.pessoa (idpes, nome, data_cad, situacao, origem_gravacao, operacao, idsis_cad, tipo) values (nextval('cadastro.seq_pessoa'), 'admin', NOW(), 'A', 'U', 'I', 1, 'F');
insert into cadastro.fisica (idpes) values (currval('cadastro.seq_pessoa'));

/**
 * Resources
 */
insert into portal.resource (id, nome, descricao) values (nextval('portal.seq_resource'), 'Application\Controller\Index.index', 'Tela inicial do sistema');

/**
 * Roles
 *
 * Acesso ao index da application
 */
insert into portal.role (id, funcionario_id, resource_id, privilegio) values (nextval('portal.seq_role'), 7, 1, 0);


