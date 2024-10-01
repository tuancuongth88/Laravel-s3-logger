<?php


namespace VehoDev\S3Logger\Http\Controller;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;

class S3LoggerController
{

    protected $s3Client;

    public function __construct()
    {
        $this->s3Client = new S3Client([
                'version' => config('s3logger.version'),
                'region' => config('s3logger.region'),
                'credentials' => [
                    'key' => config('s3logger.key'),
                    'secret' => config('s3logger.secret'),
                ],
            ]);
    }

    public function index()
    {
        // Get list of folders (projects)
        $bucket = config('s3logger.bucket');
        $folders = $this->s3Client->listObjects([
            'Bucket' => $bucket,
            'Delimiter' => '/',
        ]);
        $projects = [];
        if (isset($folders['CommonPrefixes'])){
            foreach ($folders['CommonPrefixes'] as $prefix) {
                if (request('show') == 'all'){
                    $projects[] = trim($prefix['Prefix'], '/');
                }else{
                    if (config('app.name') == trim($prefix['Prefix'], '/')){
                        $projects[] = trim($prefix['Prefix'], '/');
                    }
                }
            }
        }
        // Phân trang thủ công
        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 10; // Số mục trên mỗi trang
        $currentItems = array_slice($projects, ($currentPage - 1) * $perPage, $perPage);
        $paginatedProjects = new LengthAwarePaginator($currentItems, count($projects), $perPage);

        // Đặt URL đúng cho các liên kết phân trang
        $paginatedProjects->setPath(request()->url());
        return view('s3loggerView::s3logger.index', compact('paginatedProjects'));
    }

    public function show($folder)
    {
        // Get list of files in the folder
        $bucket = config('s3logger.bucket');
        $files = $this->s3Client->listObjects([
            'Bucket' => $bucket,
            'Prefix' => $folder . '/',
        ]);
        $filesList = [];
        foreach ($files['Contents'] as $file) {
            $filesList[] = $file['Key'];
        }

        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 10; // Số mục trên mỗi trang
        $currentItems = array_slice($filesList, ($currentPage - 1) * $perPage, $perPage);
        $paginatedItems = new LengthAwarePaginator($currentItems, count($filesList), $perPage);

        // Đặt URL đúng cho các liên kết phân trang
        $paginatedItems->setPath(request()->url());
        return view('s3loggerView::s3logger.show', compact('folder', 'paginatedItems'));
    }

    public function showLogFile($folder, $fileName){
        $filePath = $folder . "/logs/$fileName";
        // Check if the file exists
        if (!Storage::disk('s3')->exists($filePath)) {
            abort(404, 'File not found');
        }

        // Retrieve the file contents from S3
        $fileContents = Storage::disk('s3')->get($filePath);
        // Pass the contents to the view
        return view('s3loggerView::s3logger.view-log-file', compact('folder','fileName','fileContents'));
    }

    public function download($folder, $file)
    {
        // Download file from S3
        $bucket = config('s3logger.bucket');
        $key = $folder . '/logs/' . $file;
        return Storage::disk('s3')->download($key);
    }
}
