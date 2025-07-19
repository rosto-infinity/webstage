<?php

return [
    'default_paper_size' => 'A4',
    'default_orientation' => 'landscape', // paysage large
    'default_font' => 'helvetica',
    'dpi' => 300,
    'enable_remote' => true,
    'temp_dir' => storage_path('app/dompdf/temp'),
    'chroot' => realpath(base_path()),
    'logOutputFile' => storage_path('logs/dompdf.log'),
];
