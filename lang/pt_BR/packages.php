<?php

return [
    'label' => 'Pacote',
    'plural' => 'Pacotes',
    'navigation' => [
        'label' => 'Pacotes',
        'group' => 'Pacote',
    ],
    'form' => [
        'url' => [
            'composer' => 'URL do Repositório Composer',
            'github' => 'Lançamentos de Pacotes',
        ],
        'username' => [
            'composer' => 'Nome de Usuário do Composer',
            'github' => 'Nome de Usuário ou Organização do GitHub',
        ],
        'password' => [
            'composer' => 'Senha do Composer',
            'github' => 'Token de Acesso Pessoal (PAT)',
        ],
    ],
    'instructions' => [
        'composer' => [
            'label' => 'Configurações do Composer',
            'content' => 'Para adicionar um pacote Composer, você deve fornecer as credenciais de acesso para o repositório privado. O produto será sublicenciado usando o Satis. Cada membro da equipe receberá credenciais de acesso individuais. Não compartilhe essas credenciais com ninguém.',
        ],
        'github' => [
            'label' => 'Configurações do GitHub',
            'content' => 'Para adicionar um pacote do GitHub, você deve fornecer as credenciais de acesso para o repositório privado. O produto será sublicenciado usando o GitHub Packages. Cada membro da equipe receberá um Token de Acesso Pessoal (PAT). Não compartilhe essas credenciais com ninguém.',
        ],
    ],
    'validations' => [
        'name' => [
            'composer' => 'O nome do pacote deve seguir o formato "vendor/package".',
            'github' => 'O nome do pacote deve seguir o formato "user/repo".',
        ],
        'url' => [
            'github' => 'Use uma URL SSH válida para o repositório do GitHub.',
        ],
        'password' => [
            'github' => 'O Token de Acesso Pessoal (PAT) deve seguir o formato "github_pat_".',
        ],
    ],
    'infolist' => [
        'name' => 'Nome',
        'type' => 'Tipo',
        'url' => 'Url',
        'composer_command' => 'Comando do Composer',
        'section' => [
            'package_releases' => 'Lançamentos de Pacotes',
            'package_release' => 'Lançamento de Pacote',
            'dependencies' => 'Dependências',
        ],
    ],
    'table' => [
        'name' => 'Nome',
        'package_releases_count' => 'Lançamentos de Pacotes',
        'type' => 'Tipo',
        'url' => 'Url',
        'created_at' => 'Criado Em',
        'updated_at' => 'Atualizado Em',
    ],
];
