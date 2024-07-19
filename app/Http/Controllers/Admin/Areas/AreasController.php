<?php

namespace App\Http\Controllers\Admin\Areas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Clasificacion;


class AreasController extends Controller
{

    public function __construct(){

        $this->middleware('can:areas.index')->only('index');
        $this->middleware('can:areas.edit')->only('edit', 'update');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas=Area::all();
        return view('myViews.Admin.areas.index')->with('areas', $areas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('myViews.Admin.areas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
                'nombre' =>'required',
            ],
            [
                'nombre.required' => 'El campo nombre del área o departamento es requerido',
            ]
        );

        // Llenar los datos de area tambien en la tabla clasificacion
        $area=new Area();
        $clasificacion=new Clasificacion();
        $area->nombre=$request->nombre;
        $clasificacion->nombre =$request->nombre;
        $area->save();
        $clasificacion->save();

        $usuario= Auth::user();

        // Asigna todas las áreas al administrador
        if($usuario->hasRole('Administrador')){
            $areas = Area::all();

           foreach ($areas as $area) {
             //attach() para asignarle las areas al momento que se crean
             //si eliminar las ya existentes
              $usuario->areas()->attach($area->id);
           }
        }

        return redirect('/areas')->with('status', 'Área creada exitosamente :)');
    
    }

    public function area_tecnicos($areaid)
    { 
        $area = Area::find($areaid);
        $usuarios = $area->users->unique('id'); // Obtiene todos los usuarios de un área específica
        return view('myViews.Admin.areas.tecnicos')->with(['usuarios'=> $usuarios, 'area'=>$area]);
    }

    public function edit($id)
    {
        $area=Area::find($id);
        return view('myViews.Admin.areas.edit')->with('area', $area);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
                'nombre' =>'required',
            ],
            [
                'nombre.required' => 'El campo nombre del área o departamento es requerido',
            ]
        );

        $area=Area::find($id);
        $clasificacion=Clasificacion::find($id);
        $area->nombre=$request->nombre;
        $clasificacion->nombre =$request->nombre;
        $area->save();
        $clasificacion->save();

        return redirect('/areas')->with('status', 'Área editada exitosamente :)');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $area= Area::find($id);
        $clasificacion= Clasificacion::find($id);
        $area->delete(); 
        $clasificacion->delete();
        return redirect()->route('areas.index')->with('eliminar' , 'ok');
    }
}
