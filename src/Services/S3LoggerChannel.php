<?php


namespace VehoDev\S3Logger\Services;


use Illuminate\Support\Facades\App;

class S3LoggerChannel
{
    public function __invoke(array $config)
    {
        return App::make('s3logger');
    }
}
