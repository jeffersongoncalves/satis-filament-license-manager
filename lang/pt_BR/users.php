<?php

return [
    'slug' => 'Usuários',
    'label' => 'Usuário',
    'plural' => 'Usuários',
    'title' => 'Usuários',

    'fields' => [
        'name' => 'Nome',
        'email' => 'E-mail',
        'avatar' => 'Avatar',
        'password' => 'Senha',
        'locale' => 'Idioma',
        'password_confirmation' => 'Confirmação da Senha',
        'notes' => 'Observações',
        'is_active' => 'Ativo',
        'created_at' => 'Criado em',
        'updated_at' => 'Atualizado em',

    ],

    'section' => [
        'basic_information' => 'Informações Básicas do Fornecedor',
        'supplier_information' => 'Observações para o Fornecedor',
    ],

    'validations' => [
        'document_number' => 'Já existe um fornecedor com esse número de documento.',
        'internal_code' => 'Já existe um fornecedor com esse código interno.',
    ],

    'actions' => [
        'view' => 'Visualizar',
        'edit' => 'Editar',
        'delete' => 'Excluir',
    ],
];
