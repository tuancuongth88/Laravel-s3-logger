<?php

namespace VehoDev\S3Logger\Http\Helpers;

use Aws\S3\S3Client;
use Aws\Sts\StsClient;

class Common{
    public static function configAwsSDK()
    {
        $checkAssumeRole = config('s3logger.assumeRole');

        //server 142: IAM ROLE
        $param = [
            'version' => config('s3logger.version'),
            'region' => config('s3logger.region')
        ];

        //other server: assumeRole as IAM ROLE of 142 server
        if ($checkAssumeRole) {
            $stsClient = new StsClient($param);

            // Assume IAM role atmtc để lấy temporary credentials
            $assumeRoleResult = $stsClient->assumeRole([
                'RoleArn' => config('s3logger.roleArn'),
                'RoleSessionName' => config('s3logger.roleSessionName')
            ]);

            // Lấy temporary credentials từ AssumeRoleResult
            $credentials = $assumeRoleResult['Credentials'];
            $param = [
                'version' => 'latest',
                'region' => config('s3logger.region'),
                'credentials' => [
                    'key' => $credentials['AccessKeyId'],
                    'secret' => $credentials['SecretAccessKey'],
                    'token' => $credentials['SessionToken']
                ]
            ];
        }

        // IAM user for local
        if(!$checkAssumeRole && config('app.env') === 'local'){
            $param = [
                'version' => config('s3logger.version'),
                'region' => config('s3logger.region'),
                'credentials' => [
                    'key' => config('s3logger.key'),
                    'secret' => config('s3logger.secret'),
                ],
            ];    
        }
        return $param;
    }
}
