<?php

namespace App\Http\Controllers;

use App\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class PortfoliosAddController extends Controller
{
    //
    public function execute(Request $request)
    {
        $portfolio = new Portfolio();
        if(Gate::denies('add', $portfolio)){
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
                'filter' => 'required|unique:portfolios|max:255',
            ], $messages);
            // если валидация была совершена неудачно
            if($validator->fails()){
                return redirect()->route('portfoliosAdd')->withErrors($validator)->withInput();
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
            // записываем в базу данных в таблицу portfolios
            $portfolio = new Portfolio();
            //$portfolio->unguard(); // заполняем любое поле модели portfolio без указания $fillabel
            $portfolio->fill($input);
            if($portfolio->save()){
                return redirect('admin')->with('status', 'Портфолио успешно добавлено!');
            }
        }
        if(view()->exists('admin.portfolios_add')){
            $data = [
                'title' => 'Новая страница'
            ];
            return view('admin.portfolios_add', $data);
        }
        abort(404);
    }
}
