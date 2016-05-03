<?php
return array(
    'navigation' => array(
        'portal' => array(
            array(
                'label' => 'Inicio',
                'icon' => 'icon icon-home',
                'route' => 'home'
            ),
            array(
                'label' => 'i-Escola',
                'icon' => 'icon icon-pencil',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Apresentação',
                        'route' => 'escola',
                    ),
                    array(
                        'label' => 'Cadastros...',
                        'uri' => '#',
                        'pages' => array(
                            array(
                                'label' => 'Instituição',
                                'route' => 'escola/default',
                                'controller' => 'instituicao',
                                'action' => 'index'
                            ),
                            array(
                                'label' => 'Cursos..',
                                'uri' => '#',
                                'pages' => array(
                                    array(
                                        'label' => 'Cadastrar',
                                        'route' => 'escola/default',
                                        'controller' => 'curso',
                                        'action' => 'index'
                                    ),
                                    array(
                                        'label' => 'Habilitação',
                                        'route' => 'escola/default',
                                        'controller' => 'habilitacao',
                                        'action' => 'index'
                                    ),
                                    array(
                                        'label' => 'Tipo de Regime',
                                        'route' => 'escola/default',
                                        'controller' => 'tiporegime',
                                        'action' => 'index'
                                    ),
                                    array(
                                        'label' => 'Tipo de Ensino',
                                        'route' => 'escola/default',
                                        'controller' =>  'tipoensino',
                                        'action' => 'index'
                                    ),
                                    array(
                                        'label' => 'Nível Ensino',
                                        'route' => 'escola/default',
                                        'controller' => 'nivelensino',
                                        'action' => 'index'
                                    )
                                )
                            ),
                            array(
                                'label' => 'Regras de avaliação..',
                                'uri' => '#',
                                'icon' => 'glyphicon glyphicon-cog',
                                'pages' => array(
                                    array(
                                        'label' => 'Listar Regras',
                                        'icon' => 'icon icon-cog',
                                        'route' => 'escola/default',
                                        'controller' => 'regraavaliacao',
                                        'action' => 'index'
                                    ),
                                    array(
                                        'label' => 'Fórmulas de Cálculo de Média',
                                        'icon' => 'glyphicon glyphicon-edit',
                                        'route' => 'escola/default',
                                        'controller' => 'formulamedia',
                                        'action' => 'index'
                                    ),
                                    array(
                                        'label' => 'Tabelas de Arredondamento',
                                        'icon' => 'icon icon-table',
                                        'route' => 'escola/default',
                                        'controller' => 'tabelaarredondamento',
                                        'action' => 'index'
                                    )
                                )
                            ),
                            array(
                                'label' => 'Componentes curriculares',
                                'uri' => '#',
                                'pages' => array(
                                    array(
                                        'label' => 'Listar componentes',
                                        'route' => 'escola/default',
                                        'controller' => 'componentecurricular',
                                        'action' => 'index'
                                    ),
                                    array(
                                        'label' => 'Áreas de Conhecimento',
                                        'route' => 'escola/default',
                                        'controller' => 'areaconhecimento',
                                        'action' => 'index'
                                    ),
                                    array(
                                        'label' => 'Tipos de dispensa',
                                        'route' => 'escola/default',
                                        'controller' => 'tipodispensa',
                                        'action' => 'index'
                                    )
                                )
                            ),
                            array(
                                'label' => 'Séries',
                                'uri' => '#',
                                'pages' => array(
                                    array(
                                        'label' => 'Cadastrar',
                                        'route' => 'escola/default',
                                        'controller' => 'serie',
                                        'action' => 'index'
                                    ),
                                    array(
                                        'label' => 'Escola-Série',
                                        'route' => 'escola/default',
                                        'controller' => 'escolaserie',
                                        'action' => 'index'
                                    )
                                )
                            ),
                            array(
                                'label' => 'Sequência de Enturmação',
                                #'uri' => '#'
                                'route' => 'escola/default',
                                'controller' => 'sequenciaserie',
                                'action' => 'index'
                            ),
                            array(
                                'label' => 'Módulos',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Escola',
                                'uri' => '#',
                                'pages' => array(
                                    array(
                                        'label' => 'Cadastrar',
                                        'route' => 'escola/default',
                                        'controller' => 'escola',
                                        'action' => 'index'
                                    ),
                                    array(
                                        'label' => 'Rede de Ensino',
                                        'route' => 'escola/default',
                                        'controller' => 'redeensino',
                                        'action' => 'index'
                                    ),
                                    array(
                                        'label' => 'Localização',
                                        'route' => 'escola/default',
                                        'controller' => 'localizacao',
                                        'action' => 'index'
                                    ),
                                )
                            ),
                            array(
                                'label' => 'Infra Estrutura',
                                'uri' => '#',
                                'pages' => array(
                                    array(
                                        'label' => 'Prédios',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Comodo Prédio',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Função Cômodo',
                                        'uri' => '#'
                                    )
                                )
                            ),
                            array(
                                'label' => 'Turma',
                                'uri' => '#',
                                'pages' => array(
                                    array(
                                        'label' => 'Cadastrar',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Tipo',
                                        'uri' => '#'
                                    )
                                )
                            ),
                            array(
                                'label' => 'Calendário Letivo',
                                'icon' => 'icon icon-calendar',
                                'uri' => '#',
                                'pages' => array(
                                    array(
                                        'label' => 'Calendários',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Motivos',
                                        'uri' => '#'
                                    )
                                )
                            ),
                            array(
                                'label' => 'Deficiências',
                                'uri' => '#',
                            ),
                            array(
                                'label' => 'Aluno',
                                'uri' => '#',
                                'pages' => array(
                                    array(
                                        'label' => 'Alunos',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Benefícios',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Religião',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Raça',
                                        'route' => 'usuario/default',
                                        'controller' => 'raca',
                                        'action' => 'index'
                                    )
                                )
                            ),
                            array(
                                'label' => 'Tipos de Ocorrências',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Material Didático',
                                'uri' => '#',
                                'pages' => array(
                                    array(
                                        'label' => 'Materiais',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Tipo Material',
                                        'uri' => '#'
                                    )
                                )
                            ),
                            array(
                                'label' => 'Tipo de Transparência',
                                'uri' => '#'
                            ),
                        )
                    ),
                    array(
                        'label' => 'Servidores...',
                        'uri' => '#',
                        'pages' => array(
                            array(
                                'label' => 'Cadastrar',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Escolaridade',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Motivo de Afastamento',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Categoria Níveis',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Função',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Relatórios Servidores..',
                                'uri' => '#',
                                'pages' => array(
                                    array(
                                        'label' => 'Relatórios Professores',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Professores por Disciplina',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Servidores por Nível',
                                        'uri' => '#'
                                    )
                                )
                            )
                        )
                    ),
                    array(
                        'label' => 'Movimentação...',
                        'uri' => '#',
                        'pages' => array(
                            array(
                                'label' => 'Reserva de Vaga',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Quadro de Horário',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Enturmação',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Faltas/Notas',
                                'uri' => '#',
                                'pages' => array(
                                    array(
                                        'label' => 'Lançamento por Aluno',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Lançamento por Turma',
                                        'uri' => '#'
                                    )
                                )
                            ),
                            array(
                                'label' => 'Rematrícula Automática',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Relatório Quadros de Horário',
                                'uri' => '#'
                            )
                        )
                    ),
                    array(
                        'label' => 'Administrativo...',
                        'uri' => '#',
                        'pages' => array(
                            array(
                                'label' => 'Tipo de Usuário',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Usuários',
                                'uri' => '#'
                            )
                        )
                    ),
                    array(
                        'label' => 'Relatórios...',
                        'uri' => '#',
                        'pages' => array(
                            array(
                                'label' => 'Mais..',
                                'uri' => '#',
                                'pages' => array(
                                    array(
                                        'label' => 'Ficha de Rematrícula',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Registro de Transações Expedidas',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Relatório Alunos Idade x Sexo',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Alunos em Exame',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Ata Resultado Final',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Relatório de alunos por idade',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Registro de Matrículas',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Resultado Final',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Levantamento Alfabetizado e não Alfabetizado',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Alunos Benefícios',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Acompanhamento Mensal',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Levantamento Turma Período',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Demonstração Alunos Defasados Nominal',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Ficha de Leitura Escrita',
                                        'uri' => '#'
                                    ),
                                    array(
                                        'label' => 'Acompanhamento Leitura',
                                        'uri' => '#'
                                    )
                                )
                            ),
                            array(
                                'label' => 'Relação de Alunos ANEEs',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Quadro Sintético Alunos',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Alunos ANEEs Instituição',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Demonstrativo Alunos Defasados',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Demonstrativo Aluno Defasado Geral',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Controle Desempenho de Alunos',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Relatório Mov. Mensal Alunos',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Relação dos Alunos Enturmados',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Relação dos Alunos Não Enturmados',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Quadro Curricular',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Espelho de Notas Bimestral',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Espelho de Notas',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Alunos Matriculados Sintético',
                                'uri' => '#',
                            ),
                            array(
                                'label' => 'Documentos Pendentes',
                                'uri' => '#'
                            )
                        )
                    ),
                    array(
                        'label' => 'Documentos...',
                        'uri' => '#',
                        'pages' => array(
                            array(
                                'label' => 'Boletim Escolar',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Diário de Frequência',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Diário de Avaliações',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Histórico Escolar',
                                'uri' => '#'
                            )
                        )
                    ),
                )
            ),
            array(
                'label' => 'i-Biblioteca',
                'icon' => 'icon icon-book',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Apresentação',
                        'uri' => '#'
                    )
                )
            ),
            array(
                'label' => 'Administração',
                'icon' => 'icon icon-group',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Cadastro de Setores',
                        'route' => 'drh/default',
                        'controller' => 'setor',
                        'action' => 'index'
                    ),
                    array(
                        'label' => 'DRH...',
                        'uri' => '#',
                        'pages' => array(
                            array(
                                'label' => 'Cadastro de Funcionários',
                                'route' => 'drh/default',
                                'controller' => 'funcionario',
                                'action' => 'index'
                            )
                        )
                    ),
                    array(
                        'label' => 'Pessoa F/J...',
                        'uri' => '#',
                        'pages' => array(
                            array(
                                'label' => 'Pessoa Física',
                                'route' => 'usuario/default',
                                'controller' => 'fisica',
                                'action' => 'index'
                            ),
                            array(
                                'label' => 'Pessoa Jurídica',
                                'route' => 'usuario/default',
                                'controller' => 'juridica',
                                'action' => 'index'
                            )
                        )
                    )
                )
            ),
            array(
                'label' => 'Configurações',
                'uri' => '#',
                'icon' => 'icon icon-cogs',
                'pages' => array(
                    array(
                        'label' => 'Recursos',
                        'icon' => 'icon icon-sitemap',
                        'route' => 'auth/default',
                        'controller' => 'resource',
                        'action' => 'index'
                    ),
                    array(
                        'label' => 'Permissões',
                        'icon' => 'icon icon-lock',
                        'route' => 'auth/default',
                        'controller' => 'role',
                        'action' => 'index'
                    )
                )
            ),
            array(
                'label' => 'Sair',
                'route' => 'auth/default',
                'controller' => 'index',
                'action' => 'logout',
                'icon' => 'icon icon-signout',
            ),
        ),
    )
);