<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Retrieves a paginated list of categories with their associated products.
     *
     * @param  Request  $request  The HTTP request object.
     * @return JsonResponse The JSON response containing the categories and the total count.
     */
    public function index(Request $request)
    {
        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        $shop = generaleSetting('rootShop');

        $categories = CategoryRepository::query()->active()
            ->whereHas('shops', function ($query) use ($shop) {
                $query->where('id', $shop->id);
            })->whereHas('products', function ($query) {
                $query->whereHas('shop', function ($query) {
                    return $query->isActive();
                });
            })->latest('id');

        $total = $categories->count();

        $categories = $categories->when($perPage && $page, function ($query) use ($perPage, $skip) {
            return $query->skip($skip)->take($perPage);
        })->with('subCategories')->get();
        return $this->json('categories', [
            'total' => $total,
            'categories' => CategoryResource::collection($categories),
        ]);
    }

    public function brands(Request $request)
    {
        $brands = BrandRepository::query()
            ->where('is_active', 1)
            ->select('id', 'name', 'shop_id')
            ->orderBy('name')
            ->get();


        return $this->json('brands', [
            'brands' => BrandResource::collection($brands),
        ]);
    }
}
