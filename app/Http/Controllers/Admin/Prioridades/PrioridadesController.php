<?php

namespace App\Http\Controllers\Admin\Prioridades;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prioridad;

class PrioridadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prioridades=Prioridad::all();
        return view('myViews.Admin.prioridades.index')->with('prioridades', $prioridades);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('myViews.Admin.prioridades.create');
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
            'nombre' => 'required',
            'tiempo_resolucion' => 'required|integer|min:0', // Validación actualizada
        ],
        [
            'nombre.required' => 'El campo nombre es requerido',
            'tiempo_resolucion.required' => 'El campo tiempo de resolución es requerido',
            'tiempo_resolucion.integer' => 'El campo tiempo de resolución debe ser un número entero',
            'tiempo_resolucion.min' => 'El campo tiempo de resolución no puede ser negativo',
        ]);
    

        $prioridad=new Prioridad();
        $prioridad->nombre=$request->nombre;
        $prioridad->tiempo_resolucion=$request->tiempo_resolucion;
        $prioridad->save();

        return redirect('/prioridades')->with('status', 'Prioridad creada exitosamente :)');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $prioridad=Prioridad::find($id);
        return view('myViews.Admin.prioridades.edit')->with('prioridad', $prioridad);
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
            'nombre.required' => 'El campo nombre es requerido',
        ]
    );

        $prioridad=Prioridad::find($id);
        $prioridad->nombre=$request->nombre;
        $prioridad->tiempo_resolucion=$request->tiempo_resolucion;
        $prioridad->save();

        return redirect('/prioridades')->with('status', 'Prioridad editada exitosamente :)');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prioridad= Prioridad::find($id);
        $prioridad->delete();
        return redirect()->route('prioridades.index')->with('eliminar' , 'ok');
    }
}
