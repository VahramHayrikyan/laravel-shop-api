<?php

namespace App\Services\Product;


use App\Models\PrintingMethodProduct;
use App\Models\Product;
use App\Models\ProductPrintingPrice;
use App\Models\ProductVariant;
use App\Services\BaseService;
use App\Traits\AwsS3Trait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use \Exception;

class ProductService extends BaseService
{
    use AwsS3Trait;

    public function getById($id, $relations = [])
    {
        $product = Product::with($relations)->find($id);
        if (!$product) {
            Log::error('Unable to find product with id:' . $id);
            throw new Exception(trans('messages.errors.product.show'));
        }

        return $product;
    }

    public function getAll($relations = [])
    {
        return Product::with($relations)->get();
    }

    public function store($data)
    {
        return Product::create($data);
    }

    public function update($data, $id)
    {
        $product = $this->getById($id);
        $product->update($data);

        return $product;
    }

    public function destroy($product)
    {
        $productDeleted = $product->delete();
        if (!$productDeleted) {
            Log::error('Unable to delete product.');
            throw new Exception(trans('messages.errors.default'));
        }
    }

    public function syncCategories($product, $categoryIds)
    {
        $product->categories()->sync($categoryIds);
    }

    public function syncTags($product, $tagIds)
    {
        $product->tags()->sync($tagIds);
    }

    public function savePrintingMethods($product, $printingMethodsData)
    {
        foreach ($printingMethodsData as $printingMethod) {
            PrintingMethodProduct::updateOrCreate(
                ['product_id' => $product->id, 'printing_method_id' => $printingMethod['printing_method_id']],
                ['time_estimate' => $printingMethod['time_estimate']]
            );
        }
    }

    public function createView($product, $viewData)
    {
        return $product->views()->create($viewData);
    }

    public function createProductVariant($product, $variantData)
    {
        return $product->variants()->create($variantData);
    }

    public function updateProductVariant($variantData)
    {
        return ProductVariant::updateOrCreate(
            ['id' => $variantData['id']],
            $variantData
        );
    }

    public function createProductPrintingPrices($prices, $authId, $productVariant, $viewId)
    {
        $productPrintingPrices = [];
        foreach ($prices as $value) {
            $productPrintingPrices[] = [
                'supplier_id' => $authId,
                'printing_method_id' => $value['printing_method_id'],
                'view_id' => $viewId,
                'price_in_cents' => $value['price_in_cents'],
            ];
        }

        $productVariant->productPrintingPrices()->createMany($productPrintingPrices);
    }

    public function updateProductPrintingPrices($prices, $productVariant)
    {
        foreach ($prices as $value) {
            ProductPrintingPrice::updateOrCreate(
                ['product_variant_id' => $productVariant->id, 'printing_method_id' => $value['printing_method_id']],
                ['price_in_cents' => $value['price_in_cents']]
            );
        }
    }

    public function saveImage($image, $slug, $update = false, $oldImgId = null): string
    {
        $fileName = $slug . '_' . Str::random() . '.png';

        $postData = [
            'feature_img' => $image,
            'file_name' => $fileName,
        ];

        if ($update)
            $postData['old_feature_img_id'] = $oldImgId;

        $mediaResponse = Http::withHeaders(['access-key' => config('swagify.media_service_access_key')])
            ->post(config('swagify.media_service_url') . '/files/uploadFeatureImage', $postData);

        $responseBody = json_decode($mediaResponse->body(), true);

        if ($mediaResponse->ok()) {//status == 200
            return $responseBody['data']['feature_img_id'];
        } else {
            throw new Exception($responseBody['message']);
        }
    }

    public function deleteFeatureImage($fileId)
    {
        Http::withHeaders(['access-key' => config('swagify.media_service_access_key')])
            ->delete(config('swagify.media_service_url') . '/files/delete-file/' . $fileId);
    }

}