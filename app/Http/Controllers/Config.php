<?php

$a = [
    'title' => [
        'description' => '文章标题',
        'type' => 'string'
    ]
];

$b = [
    'role' => [
        'description' => '人物介绍',
        'type' => 'one_array',
        'children' => [
            'name' => [
                'description' => '人物名称',
                'type' => 'string',
                'children' => [

                ],
            ]
        ],
    ]
];