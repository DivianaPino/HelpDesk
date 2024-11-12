<?php

namespace App\Http\Controllers\Admin\Servicios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Servicio;

class ServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function crear_servicio($idArea)
    {
        $area=Area::find($idArea);
        return view('myViews.Admin.areas.servicios.create', compact('area'));
    }


    public function guardar_servicio(Request $request, $idArea){

        $request->validate([
                'nombre' =>'required',
            ],
            [
                'nombre.required' => 'El campo nombre del  servicio es requerido',
            ]
        );

        $servicio=new Servicio();
    
        $servicio->area_id=$idArea;
        $servicio->nombre=$request->nombre;
       
        $servicio->save();

        $area=Area::find($idArea);
        $servicios = $area->servicios; 

        return redirect('/area/'.$idArea.'/servicios')->with('status', 'Servicio creado exitosamente :)')->with(['area'=> $area, 'servicios' => $servicios]);
    
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
        $servicio=Servicio::find($id);
        $area= Area::find($servicio->area_id);
        return view('myViews.Admin.areas.servicios.edit', compact('servicio', 'area'));
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
                'nombre.required' => 'El campo nombre del servicio es requerido',
            ]
        );
    
        $servicio=Servicio::find($id);
        $servicio->nombre=$request->nombre;
        $servicio->save();

        $areaId=$servicio->area_id;
     
        return redirect()->route('area_servicios', compact('areaId'))->with('status', 'Servicio editado exitosamente :)');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $servicio= Servicio::find($id);
        $areaId=$servicio->area_id;
        $servicio->delete(); 
        return redirect()->route('area_servicios', compact('areaId'))->with('eliminar' , 'ok');
   
    }
}
