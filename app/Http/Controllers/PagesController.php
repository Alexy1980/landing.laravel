<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PagesController extends Controller
{
    //
    public function execute()
    {
        if(view()->exists('admin.pages')){
            $pages = Page::all();

                $data = [
                    'title' => 'Страницы',
                    'pages' => $pages,
                ];
                return view('admin.pages', $data);

        }
        abort(404);
    }
}
