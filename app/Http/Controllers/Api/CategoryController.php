<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductCategory;
use App\Helpers\ApiHelper;

class CategoryController extends Controller
{

    protected $perPage = 20;
    /*
      |--------------------------------------------------------------------------
      | Category Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles categories data.
     */

    /**
     * Gets categories with sub category list.
     *
     * @return json
     */
    public function index(Request $request)
    {
        
        $colName = ($request->isAr) ? 'ifnull(category_name_ar,category_name)' : 'category_name';
        
        $categories = ProductCategory::selectRaw('id, '.$colName.' as category_name, category_image, background_image')

                                     ->with(['sub_categories' => function ($query) use($colName){
                                         return $query->selectRaw('product_category.id, '.$colName.' as category_name,category_image,parent_category_id')
                                                      ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.shopzz_category_id', '=', 'product_category.id')
                                                      ->groupBy('product_category.id');
                                     }])
                                     ->where([
                                        'status' => 'Active',
                                        'parent_category_id' => null,
                                     ])
                                     ->get();

        $categoriesData = [];      
        if(!$categories->isEmpty())
        {
            foreach ($categories as $key => $category)
            {
               
                if($category->sub_categories->count() > 0)
                {
                    $category->sub_categories->prepend([
                        'id' => $category->id,
                        'category_name' => trans('api.static_content.All')." ".$category->category_name,
                        'category_image' => $category->category_image,
                    ]);
                    
                    $categoriesData[] = $category;
                }
                
            }
            return $this->toJson(['categories' => $categoriesData]);
        }

        return $this->toJson([], trans('api.category.not_available'), 0);
    }

    /**
     * Gets category details Page.
     *
     * @return json
     */
    public function getCategoryDetails(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|numeric',
        ]);

        $category = $this->getCategoryById($request->category_id);
        
        $user = session()->get('authUser');
        
        if(!empty($category))
        {
           $subCategories = $category->sub_categories()->get();
           $subCatIds[] = $category->id;
           if(!$subCategories->isEmpty())
           {
               $subCatIds = $subCategories->pluck('id')->toArray();
               $subCatIds[] = $category->id;
           }

           $stores = ApiHelper::getCategoryStoreQuery($request, $user, $subCatIds);
           
           $productQuery = ApiHelper::getProductsQuery($request, $user);
           
           $products = $productQuery->whereIn('shopzz_product_categories.shopzz_category_id', $subCatIds)
                                    ->orderBy('id', 'desc')
                                    ->paginate($this->perPage)
                                    ->toArray();

           $products['data'] = ApiHelper::getProductResponse($products['data']);
                                    
           $result = [
               'category' => $category,
               'stores' => $stores,
               'products' => [
                   'data' => $products['data'],
                   'has_more' => !empty($products['next_page_url']) ? 1 : 0,
               ]
           ];
           
           return $this->toJson($result);
        }

        return $this->toJson([], trans('api.category.not_available'), 0);
    }
    
    /**
     * Gets category details Page.
     *
     * @return json
     */
    public function getCategoryProducts(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|numeric',
        ]);
        
        $user = session()->get('authUser');
        
        $category = $this->getCategoryById($request->category_id);
 
        // Check category is available or not
        if(!empty($category))
        {
            $subCategories = $category->sub_categories()->get();
            $subCatIds[] = $category->id;
            if(!$subCategories->isEmpty())
            {
                $subCatIds = $subCategories->pluck('id')->toArray();
                $subCatIds[] = $category->id;
            }
            
            $productQuery = ApiHelper::getProductsQuery($request, $user);

            $products = $productQuery->whereIn('shopzz_product_categories.shopzz_category_id', $subCatIds)
                                     ->orderBy('id', 'desc')
                                     ->paginate($this->perPage)->toArray();

            // Check products is available or not
            if(!empty($products['data']))
            {
                $products['data'] = ApiHelper::getProductResponse($products['data']);
                
                $result = [
                    'products' => [
                        'data' => $products['data'],
                        'has_more' => !empty($products['next_page_url']) ? 1 : 0,
                    ]
                ];

                return $this->toJson($result);
            }
            
            return $this->toJson([], trans('api.products.not_available'), 0);
            
        }

        return $this->toJson([], trans('api.category.not_available'), 0);
    }

    /**
     * Gets category stores api.
     *
     * @return json
     */
    public function getCategoryStores(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|numeric',
        ]);
        
        $user = session()->get('authUser');
        
        $category = $this->getCategoryById($request->category_id);
        
        // Check category is available or not
        if(!empty($category))
        {
            $subCategories = $category->sub_categories()->get();
            $subCatIds[] = $category->id;
            if(!$subCategories->isEmpty())
            {
                $subCatIds = $subCategories->pluck('id')->toArray();
                $subCatIds[] = $category->id;
            }
            
            $stores = ApiHelper::getCategoryStoreQuery($request, $user, $subCatIds);
            
            if(!empty($stores['data']))
            {
                $result = [
                    'stores' => $stores,
                ];
                return $this->toJson($result);
            }

            return $this->toJson([], trans('api.stores.not_available'), 0);
        }
        
        return $this->toJson([], trans('api.category.not_available'), 0);
    }
    
    /**
     * Gets category by category id.
     * 
     * @param $categoryId
     * @return json
     */
    public function getCategoryById($categoryId)
    {
        return ProductCategory::select('id', 'category_name',  'category_image')
                              ->where([
                                  'id' => $categoryId,
                                  'status' => 'Active',
                              ])
                              ->first();
    }
}
