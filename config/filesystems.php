<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | PENTING: Default disk untuk file storage
    | - 'local': storage/app/private (tidak bisa diakses public)
    | - 'public': storage/app/public (bisa diakses via /storage URL)
    | 
    | SETUP: Run 'php artisan storage:link' untuk create symbolic link
    | dari public/storage ke storage/app/public
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        /**
         * Public Disk - Untuk file yang bisa diakses public
         * 
         * USAGE: Storage::disk('public')->put('file.jpg', $content)
         * ACCESS: asset('storage/file.jpg') atau APP_URL/storage/file.jpg
         * 
         * File yang disimpan disini:
         * - Gambar berita, galeri, potensi
         * - Foto profil struktur organisasi
         * - Thumbnail publikasi
         * - Logo desa
         * 
         * SETUP REQUIRED: php artisan storage:link
         */
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | PENTING: Jalankan 'php artisan storage:link' setelah clone/deploy
    | Command ini akan create symbolic link dari public/storage ke storage/app/public
    | 
    | Tanpa link ini, gambar uploaded tidak akan bisa diakses dari browser!
    | 
    | Verifikasi: Cek apakah folder public/storage exist setelah run command
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
