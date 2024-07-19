<?php

namespace App\Http\Controllers\Admin\Usuarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Area;


use Spatie\Permission\Models\Role;

class UsuariosController extends Controller
{
    
    public function __construct(){

        $this->middleware('can:usuarios.index')->only('index');
        $this->middleware('can:usuarios.edit')->only('edit', 'update');

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $usuarios=User::all();
        return view('myViews.Admin.usuarios.index')->with('usuarios', $usuarios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // Mostrar vista para editar los roles
    public function edit(User $usuario)
    {
        $roles= Role::all();
        return view('myViews.Admin.usuarios.edit')->with(['usuario'=>$usuario, 'roles'=>$roles]);
    }

    //  Actualizar los roles editados de los usuarios
    public function update(Request $request, User $usuario)
    {
        $usuario->roles()->sync($request->roles);

        return redirect()->route('usuarios.edit', $usuario)->with('info', 'La asignación de rol(es) se realizó correctamente');
    }
    
    // asignar areas a los usuarios
    public function asignar_area($id)
    {
        $usuario= User::find($id);
        $areas= Area::all();
        
        return view('myViews.Admin.usuarios.area')->with(['usuario'=>$usuario, 'areas'=>$areas]);
    }

    // actualizar area asignadas al usuario
    public function actualizar_area(Request $request, $id)
    {
        $usuario= User::find($id);
        // sync(): Para asignarle las areas ingresadas al usuario, haciendo una nueva asignación, eliminando las asignaciones
        // que estaban seleccionadas anteriormente pero ahora no estan en la nueva asignación.
        $usuario->areas()->sync($request->areas);
        return redirect()->route('asignar_area', $usuario)->with('info', 'La asignación de área se realizó correctamente');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario= User::find($id);
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('eliminar' , 'ok');
    }
    
}
