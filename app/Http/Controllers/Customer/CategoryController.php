<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\ProductCategory;

/*
 |--------------------------------------------------------------------------
 | Category Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles category page.
 */
class CategoryController extends Controller
{  
    /**
     * Show category details page.
     *
     * @param Request $request
     * @param string $categorySlug
     * 
     * @return view
     */
    public function categoryDetails(Request $request, $categorySlug)
    {
        $category = ProductCategory::where([
            'category_slug' =>  $categorySlug,
            'Status' => 'Active',
        ])->first();
        
        if(!empty($category))
        {
            $request->category_id = $category->id;
            $subCatIds = $this->getSubCategory($category);

            $stores = ApiHelper::getCategoryStoreQuery($request, null, $subCatIds)['data'];
            
            $products = $this->getCategoryProducts($request, $subCatIds);

            return view('front.category.category_details', [
                'category'=>$category,
                'stores' => $stores,
                'products' => $products,
            ]);
        }

        abort(404);
    }

    /**
     * Gets collection ajax products view.
     *
     * @param Request $request
     * 
     * @return view
     */
    public function showCategoryProducts(Request $request)
    {
        $category = ProductCategory::find($request->category_id);

        $subCatIds = $this->getSubCategory($category);

        $data['products'] = $this->getCategoryProducts($request, $subCatIds);

        return view('front.common.products', $data);
    }

    /**
     * Get subcategories by main category
     * @param Request $category
     * @param array $subCatIds
     */

    public function getSubCategory($category){

        $subCategories = $category->sub_categories()->get();

        $subCatIds[] = $category->id;
        if(!$subCategories->isEmpty())
        {
            $subCatIds = $subCategories->pluck('id')->toArray();
            $subCatIds[] = $category->id;
        }

        return $subCatIds;
    }

    /**
     * Gets category products.
     *
     * @param Request $request
     * @param array $subCatIds
     * 
     * @return Collections
     */
    private function getCategoryProducts(Request $request, $subCatIds)
    {
        $user = Auth::guard('customer')->user();

        $productQuery = ApiHelper::getProductsQuery($request, $user);

        return $productQuery->whereIn('shopzz_product_categories.shopzz_category_id', $subCatIds)
                                        ->orderBy('id', 'desc')
                                        ->paginate($this->perPage);
    }
}