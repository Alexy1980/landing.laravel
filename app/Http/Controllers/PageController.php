<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //
    public function execute($alias)
    {
        // параметром передается псевдоним материала
        if(!$alias)
        {
            abort(404); // если параметр не передается, генерируем исключение и прекращаем работу контроллера
        }
        // если существует вид page.blade.php
        if(view()->exists('site.page'))
        {
            $page = Page::where('alias', '=', strip_tags($alias))->first();
            $data = [
                'title' => $page->name,
                'page' => $page
            ];
            return view('site.page', $data);
        } else {
            abort(404);
        }
    }

}
