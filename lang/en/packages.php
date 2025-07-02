<?php

return [
    'label' => 'Package',
    'plural' => 'Packages',
    'navigation' => [
        'label' => 'Packages',
        'group' => 'Package',
    ],
    'form' => [
        'url' => [
            'composer' => 'Composer Repository URL',
            'github' => 'Package Releases',
        ],
        'username' => [
            'composer' => 'Composer Username',
            'github' => 'GitHub Username or Organization',
        ],
        'password' => [
            'composer' => 'Composer Password',
            'github' => 'Personal Access Token (PAT)',
        ],
    ],
    'instructions' => [
        'composer' => [
            'label' => 'Composer Settings',
            'content' => 'To add a Composer package, you must provide the access credentials for the private repository. The product will be sub-licensed using Satis. Each team member will receive individual access credentials. Do not share these credentials with anyone.',
        ],
        'github' => [
            'label' => 'GitHub Settings',
            'content' => 'To add a GitHub package, you must provide the access credentials for the private repository. The product will be sub-licensed using GitHub Packages. Each team member will receive a Personal Access Token (PAT). Do not share these credentials with anyone.',
        ],
    ],
    'validations' => [
        'name' => [
            'composer' => 'The package name must follow the "vendor/package" format.',
            'github' => 'The package name must follow the "user/repo" format.',
        ],
        'url' => [
            'github' => 'Use a valid SSH URL for the GitHub repository.',
        ],
        'password' => [
            'github' => 'The Personal Access Token (PAT) must follow the "github_pat_" format.',
        ],
    ],
    'infolist' => [
        'name' => 'Name',
        'type' => 'Type',
        'url' => 'Url',
        'composer_command' => 'Composer command',
        'section' => [
            'package_releases' => 'Package Releases',
            'package_release' => 'Package Release',
            'dependencies' => 'Dependencies',
        ],
    ],
    'table' => [
        'name' => 'Name',
        'package_releases_count' => 'Package Releases',
        'type' => 'Type',
        'url' => 'Url',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
    ],
];
