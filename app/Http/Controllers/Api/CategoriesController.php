<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Transformers\CategoryTransformer;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    use Helpers;

    public function index()
    {
        return $this->response->collection(Category::all(), new CategoryTransformer());
    }
}