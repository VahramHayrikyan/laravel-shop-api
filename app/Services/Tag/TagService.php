<?php
namespace App\Services\Tag;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use \Exception;

class TagService
{
    public function getById($id)
    {
        $tag = Tag::find($id);
        if (!$tag){
            Log::error('Unable to find tag with id:'.$id);
            throw new Exception(trans('messages.errors.tag.show'));
        }
        return $tag;
    }

    public function getAll()
    {
        return Tag::all();
    }

    public function search($search): Collection
    {
        return Tag::search($search)->get();
    }

    public function store($data)
    {
        return Tag::create($data);
    }

    public function destroy($id)
    {
        $tag = $this->getById($id);
        $tagDeleted = $tag->delete();
        if (!$tagDeleted) {
            Log::error('Unable to delete tag with id:' . $id);
            throw new Exception(trans('messages.errors.default'));
        }
    }

}