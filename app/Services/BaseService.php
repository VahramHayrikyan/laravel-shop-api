<?php

namespace App\Services;

Class BaseService
{
    use ResponseService;

    public function getSlug($value)
    {
        $value = str_replace(" ", "-", strtolower($this->cleanData($value)));
        return preg_replace("/[-]+/i", "-", $value);
    }

    private function cleanData($data): string
    {
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
}
