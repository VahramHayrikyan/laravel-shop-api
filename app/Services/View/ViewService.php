<?php

namespace App\Services\View;

use App\Models\View;
use \Exception;
use Illuminate\Support\Facades\Log;

class ViewService
{
    public function getById($id)
    {
        $view = View::find($id);
        if (!$view) {
            Log::error('Unable to find view with id:'.$id);
            throw new Exception(trans('messages.errors.view.destroy'));
        }
        return $view;

    }

    public function getAll()
    {
        return View::all();
    }

    public function store($data)
    {
        return View::create($data);
    }

    public function update($data, $id)
    {
        $view = $this->getById($id);
        $view->update($data);

        return $view;
    }

    public function destroy($id)
    {
        $view = $this->getById($id);
        $viewDeleted = $view->delete();
        if (!$viewDeleted) {
            Log::error('Unable to delete view with id:' . $id);
            throw new Exception(trans('messages.errors.default'));
        }
    }
}