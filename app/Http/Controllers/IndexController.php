<?php

namespace App\Http\Controllers;

use App\Page;
use App\People;
use App\Portfolio;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

// use App\Http\Requests;

class IndexController extends Controller
{
    //
    public function execute(Request $request)
    {

        if($request->isMethod('post')){

            // все поля формы обязательны к заполнению. поэтому валидируем данные
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'email' => 'Поле :attribute должно соответствовать email адресу'
            ];

            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email',
                'text' => 'required'
            ], $messages);
            // dump($request);
            $data = $request->all();
            //отправляем email на определенный почтовый ящик (см. services -> mail в документации)
            // для отправки используем шаблон email.blade.php
            $result = Mail::send('site.email', [
                'data' => $data
            ], function($message) use($data){
                $mail_admin = env('MAIL_ADMIN'); // кому отправляется сообщение
                $message->from($data['email'], $data['name']);
                $message->to($mail_admin, 'Site admin')->subject('Question');
            });
            // сессия отрабатывает только если роут пропущен через middleware [web] (роут web)
            flash('Сообщение отправлено!');
            return redirect()->route('home');
            /*if($result){
                //return redirect()->route('home')->with('status', 'Сообщение отправлено!');

                //return home();

            }*/


        }

        $pages = Page::all();
        $services = Service::where('id', '<', 20)->get();
        $portfolios = Portfolio::get([
            'name', 'filter', 'images'
        ]);
        $peoples = People::take(3)->get(); // выбираем 3 сотрудников
        $tags = DB::table('portfolios')->distinct()->pluck('filter'); // выбираем поле filter из таблицы portfolios, с
        // пом. метода distinct() выбираем только уникальные значения
        // dd($tags);
        // $menu будет содержать в виде массива те пункты. которые будут отображаться
        $menu = array();
        // выбираем из каждой модели заголовки, которые будут заголовками пункта меню, каждый пункт ведет на
        // определенную секцию landing page
        foreach($pages as $page) {
            $item = array('title' => $page->name,'alias' => $page->alias);
            // добавляем массив $item в конец массива $menu
            array_push($menu, $item);
        }
        // dd($menu);
        // добавляем в пункты меню соотв. статические записи
        $item = array('title'=>'Services','alias'=>'service');
        array_push($menu,$item);

        $item = array('title'=>'Portfolio','alias'=>'Portfolio');
        array_push($menu,$item);

        $item = array('title'=>'Team','alias'=>'team');
        array_push($menu,$item);

        $item = array('title'=>'Contact','alias'=>'contact');
        array_push($menu,$item);

        return view('site.index', array(

            'menu'=> $menu,
            'pages' => $pages,
            'services' => $services,
            'portfolios' => $portfolios,
            'peoples' => $peoples,
            'tags' => $tags

        ));
    }

}
