<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class PagesEditController extends Controller
{
    //
    public function execute(Page $page, Request $request)
    {
        /*$page = Page::find($id); // в случае, если параметром метода будет $id */
        // dd($page);
        if(Gate::allows('update', $page)){
            // удаление
            if($request->isMethod('delete')){
                $page->delete();
                return redirect('admin')->with('status', 'Страница успешно удалена!');
            }
            // обновление
            if($request->isMethod('post')){
                $input = $request->except('_token');
                // dd($input);
                $validator = Validator::make($input, [
                    'name' => 'required|max:255',
                    // значение alias д/б уникальным в таблице pages, но т.к. оно там уже есть, то текущее ($input['id']) значение надо  проигнорировать
                    'alias' => 'required|max:255|unique:pages,alias,'.$input['id'],
                    'text' => 'required'
                ]);
                if($validator->fails()){
                    return redirect()->route('pagesEdit', ['page' => $input['id']])->withErrors($validator);
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
                $page->fill($input);
                if($page->update()){
                    return redirect('admin')->with('status', 'Страница обновлена!');
                }
            }
            $old = $page->toArray();
            // dd($page);
            if(view()->exists('admin.pages_edit')){
                $data = [
                    'title' => 'Редактирование страницы - '.$old['name'],
                    'data' => $old
                ];
                return view('admin.pages_edit', $data);
            }
        } else {
            return redirect('admin')->with(['message' => 'У Вас нет прав на данную операцию!']);
        }

    }
}
