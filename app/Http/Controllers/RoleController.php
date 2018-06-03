<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $role_user = RoleUser::paginate($perPage);
        } else {
            $role_user = RoleUser::paginate($perPage);
        }

        return view('role_user.index', compact('role_user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('role_user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();
        
        RoleUser::create($requestData);

        Session::flash('flash_message', 'RoleUser added!');

        return redirect('admin/role_user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $role_user = RoleUser::findOrFail($id);

        return view('role_user.show', compact('role_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $role_user = RoleUser::findOrFail($id);

        return view('role_user.edit', compact('role_user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        $requestData = $request->all();

        $role_user = RoleUser::findOrFail($id);
        $role = $requestData['role_id'];
        if($role > 0 && $role <=3 ){
            $role_user->update($requestData);

            Session::flash('flash_message', 'RoleUser updated!');

            return redirect('admin/role_user');
        } else {
            Session::flash('flash_message', "Уважаемый администратор сайта! Роли с идентификатором $role не существует в природе");

            return redirect('admin/role_user');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        RoleUser::destroy($id);

        Session::flash('flash_message', 'RoleUser deleted!');

        return redirect('admin/role_user');
    }
}
