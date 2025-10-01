<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Services\Frontend\BlogService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __construct( private readonly BlogService $service){}


    /**
     * @param Request $request
     * @return Response
     */
    public function getList(Request $request): Response
    {
        $response = $this->service->getListData(['page_name' => HOME_PAGE]);

        return Inertia::render('Frontend/Home/Page', $response);
    }
}
