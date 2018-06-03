<?php

namespace App\Http\Controllers;

use App\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class PortfoliosEditController extends Controller
{
    //
    public function execute($id, Request $request)
    {
            $portfolio = Portfolio::find($id); // в случае, если параметром метода будет $id
            // dd($portfolio);
        if(Gate::allows('update', $portfolio)){

            if($request->isMethod('delete')){
                $portfolio->delete();
                return redirect('admin')->with('status', 'Портфолио успешно удалено!');
            }
            // обновление
            if($request->isMethod('post')){
                $input = $request->except('_token');
                //dd($input);
                $validator = Validator::make($input, [
                    'name' => 'required|max:255',

                    'filter' => 'required|max:255',
                    'images' => 'required'
                ]);
                if($validator->fails()){
                    return redirect()->route('portfoliosEdit', ['portfolio' => $input['id']])->withErrors($validator);
                }
                if($request->hasFile('images')){
                    $file = $request->file('images');
                    // перемещаем файл из временной директории в каталог
                    $file->move(public_path().'/assets/img', $file->getClientOriginalName());
                    $input['images'] = $file->getClientOriginalName();
                }
                else {
                    // $input['old_images'] - скрытое поле, в котором хранится имя файла, который был ранее загружен на
                    // сервер
                    $input['images'] = $input['old_images'];
                }

                unset($input['old_images']);
                $portfolio->fill($input);

                if($portfolio->update()){
                    return redirect('admin')->with('status', 'Портфолио обновлено!');
                }
            }
            $old = $portfolio->toArray();
            // dd($old);
            if(view()->exists('admin.portfolios_edit')){
                $data = [
                    'title' => 'Редактирование портфолио - '.$old['name'],
                    'data' => $old
                ];
                return view('admin.portfolios_edit', $data);


            }
        }   else {
            return redirect('admin')->with(['message' => 'У Вас нет прав на данную операцию!']);
        }

    }
}
