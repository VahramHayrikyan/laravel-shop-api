<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
//use SVG\SVG;

trait AwsS3Trait
{
    //upload base64 image on aws s3
    public function uploadFromBase64($image, $fileName)
    {
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
        Storage::disk('s3')->put($fileName, $image, 'public');
        return Storage::disk('s3')->url($fileName);
    }


    //upload svg file on aws s3
    public function uploadSvg($svg, $fileName)
    {
        Storage::disk('s3')->put($fileName, $svg, 'public');
        return Storage::disk('s3')->url($fileName);
    }

    public function getSvgString($string)
    {
        preg_match_all('/xlink:href="([^"]+)"/', $string, $matchs);

        if (!empty($matchs)) {
            $foundArray = $matchs[0];
            $foundURLArray = $matchs[1];

            foreach ($foundURLArray as $key => $foundURL) {

                $getString = @file_get_contents($foundURL);
                if ($getString === false) {
                    continue;
                }

                $base64Str = base64_encode($getString);
                $x = pathinfo($foundURL);


                if (!empty($x['extension']) && (strtolower($x['extension']) == 'jpeg' || strtolower($x['extension']) == 'jpg')) {
                    $ext = 'jpeg';
                } else {
                    $ext = 'png';
                }

                $string = str_replace($foundArray[$key], 'xlink:href="data:image/' . $ext . ';base64,' . $base64Str . '"', $string);
            }
        }

        return $string;

    }

    //delete image from aws s3
    public function deleteFile($path)
    {
        if (Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->delete($path);
            return true;
        }
        return false;
    }


    public function getFileObject(string $value)
    {
        if (strpos($value, ';base64') !== false) {
            [, $value] = explode(';', $value);
            [, $value] = explode(',', $value);
        }

        $binaryData = base64_decode($value);
        $tmpFile = tempnam(sys_get_temp_dir(), 'base64validator');
        file_put_contents($tmpFile, $binaryData);
        return new File($tmpFile);
    }

    public function getExtension(string $value)
    {
        return 'png';
        /*$file = $this->getFileObject($value);
        return $file->guessExtension();*/
    }

    public function getFileMimeType(string $value)
    {
        $file = $this->getFileObject($value);
        return $file->getMimeType();
    }

    public function getFileSize(string $value)
    {
        return strlen(base64_decode($value));
    }

    public function getFileWidthHeight(string $value)
    {
        list($width, $height) = getimagesize($value);
        return [$width, $height];
    }

    public function getFileUrl($fileName)
    {
        $url = (!empty($fileName)) ? config('constants.s3_bucket_url') . $fileName : null;
        return $url;
    }

    public function getImageMetaInfo(string $value)
    {
        list($width, $height) = getimagesize($value);
        $imgInfo = get_headers($value, TRUE);
        $mime = $imgInfo['Content-Type'];
        $size = $imgInfo['Content-Length'];
        $displaySize = $this->getImageDisplaySize($size);
        $imgMetaInfo = [
            'original_size' => $size,
            'size' => $displaySize,
            'mime' => $mime,
            'resolution' => $width . ' x ' . $height
        ];

        return $imgMetaInfo;
    }

    public function getImageDisplaySize($size)
    {

        $displaySize = 0;
        $size = (int)$size;
        if (($size / 1024) < 1024) {
            $displaySize = round($size / (1024), 1) . ' KB';
        } else {
            $displaySize = round($size / (1024 * 1024), 1) . ' MB';
        }
        return $displaySize;
    }
}
