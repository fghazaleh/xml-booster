<?php declare(strict_types=1);

namespace FGhazaleh\Support\Helpers;

final class Helper
{

    /**
     * Determine if the file exists in local or remote path.
     *
     * @param string $file
     * @return bool
     * */
    public static function fileExists(string $file):bool
    {
        if (static::isRemoteFile($file)) {
            $code = false;
            file_get_contents($file, false, stream_context_create([
                'http' => [
                    'method' => 'HEAD',
                    'ignore_errors' => 1,
                    'max_redirects' => 0
                ]
            ]));
            sscanf($http_response_header[0], 'HTTP/%*d.%*d %d', $code);
            return ($code === 200);
        }
        return file_exists($file);
    }

    /**
     * Determine if the file is remote path or not,
     * return true if is remote path.
     *
     * @param string $file
     * @return bool
     * */
    public static function isRemoteFile(string $file):bool
    {
        return (strpos($file, 'http://') !== false || strpos($file, 'https://') !== false);
    }
}
