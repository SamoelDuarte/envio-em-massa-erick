<?php
return [
    'required' => 'O campo :attribute é obrigatório.',
    'attributes' => [
        'name' => 'Nome',
        'description' => 'descrição',
        'number' => 'Número',
        'price' => 'preço',
        'size' => 'Tamanho',
        'imageInput' => 'imagem',
    ],
    'numeric' => 'O campo :attribute deve ser um número.',
    'image' => 'O arquivo do campo :attribute deve ser uma imagem.',
    'mimes' => 'O campo :attribute deve ser um arquivo do tipo :values.',
    'max' => [
        'numeric' => 'O campo :attribute não pode ser maior que :max.',
        'file' => 'O arquivo do campo :attribute não pode ser maior que :max kilobytes.',
    ],
    // Adicione mais mensagens conforme necessário
];
