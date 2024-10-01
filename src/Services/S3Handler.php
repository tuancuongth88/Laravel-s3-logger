<?php


namespace VehoDev\S3Logger\Services;


use Illuminate\Support\Facades\File;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;

class S3Handler extends AbstractProcessingHandler
{
    protected $client;
    protected $bucket;
    protected $projectName;

    public function __construct($projectName, $config, $level = Logger::DEBUG, $bubble = true)
    {
        $this->projectName = $projectName;
        $this->client = new S3Client($this->configAwsSDK($config));
        $this->bucket = $config['bucket'];
        parent::__construct($level, $bubble);
    }

    public function configAwsSDK()
    {
        $env = config('app.env');
        if ($env !== 'local') {
            $param = [
                'version' => 'latest',
                'region' => config('s3logger.region')
            ];

            if ($env === 'stage' || $env === 'product') { // case: access a server other than server 240
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

    protected function write(LogRecord  $record): void
    {
        $message = (string) $record['formatted'];
        $filename = $this->projectName . '/logs/' . 'crud-' . date('Y-m-d') . '.log';
        // Fetch the existing log content from S3
        $existingLog = null;
        try {
            // Fetch the existing log content from S3 if it exists
            $existingLog = $this->client->getObject([
                'Bucket' => $this->bucket,
                'Key' => $filename,
            ])->get('Body')->getContents();
        } catch (\Aws\S3\Exception\S3Exception $e) {
            // If the key does not exist, handle the exception (e.g., log the error or ignore it)
            if ($e->getAwsErrorCode() !== 'NoSuchKey') {
                throw $e; // Re-throw if it's another type of exception
            }
        }
        $message = $existingLog . PHP_EOL . $message;


        // Write the combined content back to S3
        $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $filename,
            'Body' => $message,
            'ACL' => 'private',
        ]);

        // Schedule log deletion from local storage
        $this->deleteLocalLogs($filename);
    }

    protected function deleteLocalLogs($filename)
    {
        $logPath = storage_path("logs/crud/$filename");
        if (File::exists($logPath)){
            File::delete($logPath);
//            $files = File::files($logPath);
//            foreach ($files as $file) {
//                // Check if the file is older than one week
//                if (now()->subWeek()->timestamp > $file->getMTime()) {
//                    // Delete the file if it's older than one week
//                    File::delete($file->getRealPath());
//                }
//            }
        }
    }
}
