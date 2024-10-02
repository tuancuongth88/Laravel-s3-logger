# S3 Logger Package for Laravel

## Introduction

The **S3 Logger** package provides a simple way to log messages to AWS S3 with project separation and automatic local log cleanup in Laravel. This package allows you to send logs from multiple Laravel projects to a specified S3 bucket, with each project's logs stored in separate folders. Additionally, logs are automatically deleted from the local storage after one week.

## Installation

### Step 1: Install via Composer

You can install the package via Composer. If you have registered the package on Packagist, run the following command:

```bash
composer require veho-dev/s3-logger
```

### Step 2: Publish the Configuration (Optional)
If you want to customize the configuration, you can publish the package configuration file using the command:

```php
php artisan vendor:publish --provider="VehoDev\S3Logger\S3LoggerServiceProvider"
```

### Step 3: Add Environment Variables

Add the following environment variables to your .env file:

```
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=your_region
AWS_S3_BUCKET=your_bucket_name
APP_NAME=your_project_name
AWS_Role_Arn=your_role_key
AWS_Role_Session_Name=your_role_session_name
ASSUME_ROLE=have_assume_role_yes_or_not

```
- `AWS_ACCESS_KEY_ID`: Your AWS access key.
- `AWS_SECRET_ACCESS_KEY`: Your AWS secret key.
- `AWS_DEFAULT_REGION`: The AWS region where your S3 bucket is located.
- `AWS_S3_BUCKET`: The name of your S3 bucket.
- `APP_NAME`: The name of your project (used for folder separation in S3).
- `AWS_Role_Arn`: Your AWS IAM Role key
- `AWS_Role_Session_Name`: Your AWS IAM Role session name
- `ASSUME_ROLE`: True if this project have AWS_Role_Arn or AWS_Role_Session_Name

## Usage

To log requests to AWS S3 in a Laravel 10 application, add the `logs3.crud` middleware to your routes. For example:

```php
use Illuminate\Support\Facades\Route;

Route::middleware(['logs3.crud'])->group(function () {
    Route::get('/example', function () {
        return 'This route logs to S3!';
    });
});
```
This will automatically log the message to a file stored in your S3 bucket under the folder named after your project. The logs will be organized by date, with each day's logs stored in a separate file.

### Local Log Cleanup

The package also provides automatic cleanup of local logs. Logs stored locally in your project will be deleted one week after they are created, reducing the storage burden on your local system.

### View all file log
- You can access the route `/s3-logger/logs` to see the log files on your s3.
- Note if you have multiple projects that share the same s3 you can add the attribute `/s3-logger/logs?show=all` to see the logs of all projects

### Contributing

If you find a bug or have a feature request, feel free to create an issue or submit a pull request. Contributions are always welcome!

###License

This package is open-source software licensed under the VehoWork license.

```
### Additional Notes
- Replace `https://github.com/tuancuongth88/s3-logger.git` with the actual URL of your GitHub repository.
- Ensure that all placeholders like `your_access_key`, `your_secret_key`, etc., are replaced with actual values in the `.env` file when configuring your projects.
- If you publish the package on Packagist, make sure to update the badges and links accordingly.

```




