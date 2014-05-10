/* 
 faz a busca na tabela pelos os ids do usuario e add a acl como role
 */
select usuario_id from role group by usuario_id;
/*
 faz a busca dos resources do sistema e add a acl
 */
select nome from resource;

/*
 seta os privilegios para os resources
 */
-- select * from role, resource where role.resource_id = resource.id;
select usuario_id as role, privilegio, resource.nome as nome from role left join resource on resource_id = resource.id where privilegio = 'allow';
-- select usuario_id as role, privilegio, resource.nome as nome from role left join resource on resource_id = resource.id where privilegio = 'deny';
/** 
 acl->allow(role, resource) fica acl->allow(usuario_id, resource.nome)
 acl->deny(role, resource)
 */