<?php

namespace App\Filament\Static;

use Filament\Forms;

trait Form
{
    public static function fileInput($dir)
    {
        return Forms\Components\FileUpload::make('path')
            /* ->multiple() */->label(__('views.FILE'))
            ->disk('public')
            ->directory($dir)
            // ->panelLayout('grid')
            // ->reorderable()
            ->openable()
            ->downloadable()
            ->preserveFilenames()
            // ->maxFiles(10)
            ->maxSize(4096)
            // ->default(fn ($record) => $record ? $record->files->map(fn ($file) => $file->file_path)->toArray() : [])
            ->acceptedFileTypes([
                'application/pdf',
                'image/*',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/x-gzip',
                'application/zip',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'application/vnd.rar',
                'application/x-7z-compressed',
                'application/x-tar',
                'text/plain',
                'video/*',
            ]);
    }
}
