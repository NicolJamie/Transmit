<?php

return [
    'directories' => [
        'js',
        'css',
        'image'
        //.. directories to push
    ],
    'jsMinify' => [
        // .. file everything will be compiled into
        'app.js' => [
            'modules/example.js',
        ]
    ]
];