<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class PagesAddController extends Controller
{
    //
    public function execute(Request $request)
    {
        $page = new Page();
        if(Gate::denies('add', $page)){
            return redirect('admin')->with(['message' => 'У Вас нет прав на данную операцию!']);
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); // все, кроме token
            // dd($input);
            // переводим на русский
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'unique' => 'Поле :attribute должно быть уникально'
            ];
            $validator = Validator::make($input, [
                    'name' => 'required|max:255',
                    'alias' => 'required|unique:pages|max:255',
                    'text' => 'required'
                ], $messages
            );
            // если валидация была совершена неудачно
            if($validator->fails()){
                return redirect()->route('pagesAdd')->withErrors($validator)->withInput();
            }

            // сохраняем изображение в директорию, см. документацию request->files
            if($request->hasFile('images')){
                $file = $request->file('images');
                // оставляем только имя картинки
                $input['images'] = $file->getClientOriginalName();
                // dd($input);
                // копируем файл в директорию
                $file->move(public_path().'/assets/img', $input['images']);
            }
            // записываем в базу данных в таблицу pages
            $page = new Page();
            //$page->unguard(); // заполняем любое поле модели Page без указания $fillabel
            $page->fill($input);
            if($page->save()){
                return redirect('admin')->with('status', 'Страница успешно добавлена!');
            }
        }
        if(view()->exists('admin.pages_add')){
            $data = [
                'title' => 'Новая страница'
            ];
            return view('admin.pages_add', $data);
        }
        abort(404);
    }
}
