<?php

return [
    'version' => env('LOG3_AWS_SDK_VERSION', 'latest'),
    'region' => env('LOG3_AWS_DEFAULT_REGION', 'ap-northeast-1'),
    'bucket' => env('LOG3_AWS_S3_BUCKET', 'veho-log'),
    'key' => env('LOG3_AWS_ACCESS_KEY_ID'),
    'secret' => env('LOG3_AWS_SECRET_ACCESS_KEY'),
    'roleArn' => env('LOG3_AWS_Role_Arn'),
    'roleSessionName' => env('LOG3_AWS_Role_Session_Name'),
    'assumeRole' => env('LOG3_ASSUME_ROLE'),
];
