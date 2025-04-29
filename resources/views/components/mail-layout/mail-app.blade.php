<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/css">
        /* Fallback styles for email clients that don't support Tailwind */
        .fallback-font {
            font-family: Arial, sans-serif;
        }
        @media only screen and (max-width: 600px) {
            .responsive-container {
                width: 100% !important;
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
        }
    </style>
</head>
<body class="fallback-font bg-gray-100" style="margin: 0; padding: 0;">
    {{ $slot }}    
</body>
</html>