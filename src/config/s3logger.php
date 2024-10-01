<?php

return [
    'version' => env('AWS_SDK_VERSION', 'latest'),
    'region' => env('AWS_DEFAULT_REGION', 'ap-northeast-1'),
    'bucket' => env('AWS_S3_BUCKET', 'veho-log'),
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'roleArn' => env('AWS_Role_Arn'),
    'roleSessionName' => env('AWS_Role_Session_Name'),
];
