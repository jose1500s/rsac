<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\tb_negocio; 

class negociosController extends Controller
{
    public function negociosView() {
        $negocios = tb_negocio::all();
        $enProcesoCount = tb_negocio::where('estatus', 'En proceso')->count();
        $concluidoCount = tb_negocio::where('estatus', 'Concluido')->count();
        $canceladoCount = tb_negocio::where('estatus', 'Cancelado')->count();

        $acreditacionCount = tb_negocio::where('categoria', 'Acreditación')->count();
        $capacitacionCount = tb_negocio::where('categoria', 'Capacitación')->count();
        $CertificaciónCompetenciasCount = tb_negocio::where('categoria', 'Certificación de competencias')->count();
        $eventoCount = tb_negocio::where('categoria', 'Evento')->count();
        $investigacionCount = tb_negocio::where('categoria', 'Investigación')->count();
        $materialEducativoCount = tb_negocio::where('categoria', 'Material educativo')->count();
        $planDeEstudioCount = tb_negocio::where('categoria', 'Plan de estudio')->count();
        $proyectoCount = tb_negocio::where('categoria', 'Proyecto')->count();
        $otroCount = tb_negocio::where('categoria', 'Otro')->count();

        return Inertia::render('SideBarMenus/NegociosComponentes/Negocios', [
            'registrosNegocios' => $negocios,
            'enProcesoCount' => $enProcesoCount,
            'concluidoCount' => $concluidoCount,
            'canceladoCount' => $canceladoCount,

            'acreditacionCount' => $acreditacionCount,
            'capacitacionCount' => $capacitacionCount,
            'CertificaciónCompetenciasCount' => $CertificaciónCompetenciasCount,
            'eventoCount' => $eventoCount,
            'investigacionCount' => $investigacionCount,
            'materialEducativoCount' => $materialEducativoCount,
            'planDeEstudioCount' => $planDeEstudioCount,
            'proyectoCount' => $proyectoCount,
            'otroCount' => $otroCount,
        ]);
    }

    
    public function registrar(Request $request) {
        $programa_educativo = $request->input('programaEducativo');
        $proyecto_actividad = $request->input('proyecto_actividad');
        $descripcion = $request->input('descripcion');
        $beneficios_impacto = $request->input('beneficios_impacto');
        $responsable = $request->input('responsable');
        $involucrados = $request->input('involucrados');
        $PDI = $request->input('pdi');
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');
        $cantidad = $request->input('cantidad');
        $unidad = $request->input('unidad1');
        $hombres1 = $request->input('hombres1');
        $mujeres1 = $request->input('mujeres1');
        $unidad2 = $request->input('unidad2');
        $hombres2 = $request->input('hombres2');
        $mujeres2 = $request->input('mujeres2');
        $estatus = $request->input('estatusModel');
        $categoria = $request->input('categoria');
        $especificar = $request->input('especificar');


         // crear un nuevo registro en la tabla tb_admision
        $automotriz = new tb_negocio;
        $automotriz->programa_educativo = $programa_educativo;
        $automotriz->proyecto_actividad = $proyecto_actividad;
        $automotriz->descripcion = $descripcion;
        $automotriz->beneficios_impacto = $beneficios_impacto;
        $automotriz->responsable = $responsable;
        $automotriz->involucrados = $involucrados;
        $automotriz->PDI = $PDI;        
        $automotriz->fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));
        $automotriz->fecha_fin = date('Y-m-d', strtotime($fecha_fin));
        $automotriz->cantidad = $cantidad;
        $automotriz->unidad = $unidad;
        $automotriz->hombres1 = $hombres1;
        $automotriz->mujeres1 = $mujeres1;
        $automotriz->unidad2 = $unidad2;
        $automotriz->hombres2 = $hombres2;
        $automotriz->mujeres2 = $mujeres2;
        $automotriz->estatus = $estatus;
        $automotriz->categoria = $categoria;
        $automotriz->especificar = $especificar;
        $automotriz->save();

        return redirect()->route('view.negocios');

    }


    public function eliminar(Request $request) {
        $id = $request->id;
        $admision = tb_negocio::whereIn('id', $id);
        $admision->delete();
        return redirect()->route('view.negocios');
    }

    public function editar(Request $request) {
        $id = $request->input('id');
        $programa_educativo = $request->input('programaEducativo');
        $proyecto_actividad = $request->input('proyecto_actividad');
        $descripcion = $request->input('descripcion');
        $beneficios_impacto = $request->input('beneficios_impacto');
        $responsable = $request->input('responsable');
        $involucrados = $request->input('involucrados');
        $PDI = $request->input('pdi');
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');
        $cantidad = $request->input('cantidad');
        $unidad = $request->input('unidad1');
        $hombres1 = $request->input('hombres1');
        $mujeres1 = $request->input('mujeres1');
        $unidad2 = $request->input('unidad2');
        $hombres2 = $request->input('hombres2');
        $mujeres2 = $request->input('mujeres2');
        $estatus = $request->input('estatusModel');
        $categoria = $request->input('categoria');
        $especificar = $request->input('especificar');


        $automotriz = tb_negocio::find($id);
        $automotriz->programa_educativo = $programa_educativo;
        $automotriz->proyecto_actividad = $proyecto_actividad;
        $automotriz->descripcion = $descripcion;
        $automotriz->beneficios_impacto = $beneficios_impacto;
        $automotriz->responsable = $responsable;
        $automotriz->involucrados = $involucrados;
        $automotriz->PDI = $PDI;        
        $automotriz->fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));
        $automotriz->fecha_fin = date('Y-m-d', strtotime($fecha_fin));
        $automotriz->cantidad = $cantidad;
        $automotriz->unidad = $unidad;
        $automotriz->hombres1 = $hombres1;
        $automotriz->mujeres1 = $mujeres1;
        $automotriz->unidad2 = $unidad2;
        $automotriz->hombres2 = $hombres2;
        $automotriz->mujeres2 = $mujeres2;
        $automotriz->estatus = $estatus;
        $automotriz->categoria = $categoria;
        $automotriz->especificar = $especificar;
        $automotriz->save();

        return redirect()->route('view.negocios');
    }
}
