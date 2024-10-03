<?php

namespace VehoDev\S3Logger\Http\Helpers;

use Aws\S3\S3Client;
use Aws\Sts\StsClient;

class Common{
    public static function configAwsSDK()
    {
        $checkAssumeRole = config('s3logger.assumeRole');
        if ($checkAssumeRole) {
            $param = [
                'version' => 'latest',
                'region' => config('s3logger.region')
            ];
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
        } else {
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
