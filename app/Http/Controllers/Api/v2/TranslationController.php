<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\App;

class TranslationController extends BaseController
{
    public function setLocale ($locale)
    {
        if (! in_array($locale, config('app.locales')))
            return $this->errorResponse(__('other.locale.fail', ['locale' => $locale]));

        App::setLocale($locale);

        return $this->successResponse(__('other.locale.success'));
    }
}
