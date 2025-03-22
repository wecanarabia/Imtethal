<?php

return [
    'profile_photo_path' => 'avatar',
    'disk' => env('FILESYSTEM_DISK', 'public'),
    'visibility' => 'public', // or replace by filesystem disk visibility with fallback value
    /* 'show_custom_fields' => true,
    'custom_fields' => [
    /*     'phone' => [
            'type'=>'tel',
            'label' => __('views.PHONE'),
            'required' => true,
            'column_span' => 'full',
            'rules' => ['required', 'phone:INTERNATIONAL', 'unique:users,phone,'.auth()->user()->id],
            'autocomplete' => true,
        ],  */ 
        /* 'job_title' => [
            'type'=>'text',
            'label' => __('views.JOB_TITLE'),
            'required' => false,
            'rules' => ['nullable', 'string', 'max:255'],
        ], */
    //     'phone' => [
    //         'type'=>'text',
    //         'label' => 'dfdcvcv',
    //         // 'label' => __('views.PHONE'),
    //         'required' => true, // optional
    //         'rules' => [], // optional
    //         'column_span' => 'full',
    //         'autocomplete' => true, 
    //     ],
    // ] 
];
