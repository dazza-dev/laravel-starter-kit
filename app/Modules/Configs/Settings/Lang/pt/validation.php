<?php

declare(strict_types=1);

return [
    'attributes' => [
        'settings' => 'configurações',
        'settings.*.name' => 'nome',
        'settings.*.value' => 'valor',
        'file' => 'arquivo',
        'type' => 'tipo',
    ],

    'settings' => [
        'required' => 'Você deve enviar ao menos uma configuração.',
        'array' => 'O formato das configurações não é válido.',
    ],
    'settings_name' => [
        'required' => 'Cada configuração deve ter um nome.',
        'string' => 'O nome da configuração não é válido.',
    ],
    'settings_value' => [
        'present' => 'Cada configuração deve incluir um valor.',
    ],
    'file' => [
        'required' => 'Você deve selecionar um arquivo.',
        'file' => 'O arquivo não é válido.',
        'image' => 'O arquivo deve ser uma imagem.',
        'max' => 'A imagem não pode ultrapassar 2MB.',
    ],
];
