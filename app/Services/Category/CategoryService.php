<?php


namespace App\Services\Category;


use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Services\BaseService;
use \Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CategoryService extends BaseService
{
    public function getById($id)
    {
        $brand = Category::find($id);
        if (!$brand) {
            Log::error('Unable to find category with id: '.$id);
            throw new Exception(trans('messages.errors.category.destroy'));
        }
        return $brand;

    }

    public function getAll(array $relations)
    {
        $categories = Category::with($relations)->get()->toArray();

        return $categories;
    }

    public function store($data)
    {
        return Category::create($data);
    }

    public function update($data, $id)
    {
        $this->getById($id)->update($data);
    }

    public function destroy($id)
    {
        $brand = $this->getById($id);
        $brandDeleted = $brand->delete();
        if (!$brandDeleted) {
            Log::error('Unable to delete category with id:' . $id);
            throw new Exception(trans('messages.errors.default'));
        }
    }

    public function getFileUrls(array $ids)
    {
        $data = Http::withHeaders([
            'access-key' => config('swagify.media_service_access_key'),
            'Accept' => 'application/json'
        ])
            ->post(config('swagify.media_service_url') . '/files/get-file-urls', $ids);

        $responseBody = json_decode($data->body(), true);

        if ($data->successful()) {//status == 200
            return $responseBody['data'];
        } elseif ($data->status() >= 400) {
            throw new Exception(Arr::flatten($responseBody['message'])[0]);
        } else {
            throw new Exception(__('messages.errors.default'));
        }
    }

    //===================================
    //categories && products data section
    //===================================
    public function getAvailableAttributes($views, $viewFileUrls): array
    {
        $availableAttributes = [];

        if (isset($views) && count($views)) {
            foreach ($views as $view) {
                if (isset($view['view_files']) && count($view['view_files'])) {
                    foreach ($view['view_files'] as $viewFile) {
                        $availableAttributes[$view['product_id']]['variant_base'][] = [
                            'attribute_value_id' => $viewFile['attribute_value_id'],
                            'views' => [
                                'view_id' => $view['id'],
                                'view_file_id' => $viewFile['id'],
                                'file_url' => array_key_exists($viewFile['id'], $viewFileUrls)
                                    ? $viewFileUrls[$viewFile['id']]
                                    : null
                            ]
                        ];
                    }
                }
            }
        }

        return $availableAttributes;
    }

    public function getViewsData($views, $viewFileUrls): array
    {
        $viewsData = [];

        foreach ($views as $view) {
            $viewsData[$view['product_id']][] = [
                'id' => $view['id'],
                'file_url' => array_key_exists($view['id'], $viewFileUrls)
                    ? $viewFileUrls[$view['id']]
                    : null,
                'shadow' => "https:\/\/ewi-s3-uploads-stage.s3.amazonaws.com\/shadow-1607459647.png",// Todo
                'name' => 'Front',
                'width_in' => $view['width_in'],
                'height_in' => $view['height_in'],
                'width_px' => $view['width_px'],
                'height_px' => $view['height_px'],
                'left_px' => $view['left_px'],
                'top_px' => $view['top_px'],
                // TODO need to clarify this product_templates
                'product_templates' => [
                    'id' => 24,
                    'png_file' => 'https:\/\/ewi-s3-uploads-stage.s3.amazonaws.com\/pngfile-1603726144.png',
                    'red_mask' => 'https:\/\/ewi-s3-uploads-stage.s3.amazonaws.com\/red-mask-1603726146.png',
                    'blue_mask' => 'https:\/\/ewi-s3-uploads-stage.s3.amazonaws.com\/blue-mask-1603726146.png'
                ]

            ];
        }

        return $viewsData;
    }

    public function getAllData($categories, $categoryImageUrls, $availableAttributesVariantBases, $viewData): array
    {
        $data = [];

        foreach ($categories as $cey => $category) {
            $category['image_urls'] = $categoryImageUrls;
            $data[$cey] = (new CategoryResource($category))->resolve();
            if (count($category['products'])) {
                foreach ($category['products'] as $key => $product) {
                    $product['variant_bases'] = $availableAttributesVariantBases;
                    $product['view_data'] = $viewData;

                    $newProduct = (new ProductResource($product))->resolve();
                    $data[$cey]['products'][$key] = $newProduct;
                }
            } else {
                $data[$cey]['products'] = [];
            }
        }

        return $data;
    }
    //==========================================
    //end of categories && products data section
    //==========================================

}