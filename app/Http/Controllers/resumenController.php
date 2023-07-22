<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\tb_resumen;
use App\Models\tb_actividades_transversale;
class resumenController extends Controller
{
    public function viewBienvenida() {
        return Inertia::render('SideBarMenus/Bienvenido');
    }

    public function resumenView() {
        $resumen = tb_resumen::all();
        $transversales = tb_actividades_transversale::all();

        // Inicializar variables de totales
        $totalAcreditacion = 0;
        $totalCertificacion = 0;
        $totalEvento = 0;
        $totalInvestigacion = 0;
        $totalMaterialEducativo = 0;
        $totalPlanEstudios = 0;
        $totalProyecto = 0;
        $totalOtro = 0;
        $total = 0;

        // Iterar sobre los registros de la tabla
        foreach ($resumen as $registro) {
            $totalAcreditacion += $registro->acreditacion;
            $totalCertificacion += $registro->certificacion_competencias;
            $totalEvento += $registro->evento;
            $totalInvestigacion += $registro->investigacion;
            $totalMaterialEducativo += $registro->material_educativo;
            $totalPlanEstudios += $registro->plan_estudios;
            $totalProyecto += $registro->proyecto;
            $totalOtro += $registro->otro;
            $total += $registro->total;
        }


        return Inertia::render('SideBarMenus/ResumenComponentes/TabMenu', [
            'registrosResumen' => $resumen,
            'registrosTransversales' => $transversales,

            'totalAcreditacion' => $totalAcreditacion,
            'totalCertificacion' => $totalCertificacion,
            'totalEvento' => $totalEvento,
            'totalInvestigacion' => $totalInvestigacion,
            'totalMaterialEducativo' => $totalMaterialEducativo,
            'totalPlanEstudios' => $totalPlanEstudios,
            'totalProyecto' => $totalProyecto,
            'totalOtro' => $totalOtro,
            'total' => $total,
        ]);
    }

    public function registrar(Request $request) {
        $programa_educativo = $request->input('programa_educativo');
        $acreditacion = $request->input('acreditacion');
        $certificacion_competencias = $request->input('certificacion_competencias');
        $evento = $request->input('evento');
        $investigacion = $request->input('investigacion');
        $material_educativo = $request->input('material_educativo');
        $plan_estudios = $request->input('plan_estudios');
        $proyecto = $request->input('proyecto');
        $otro = $request->input('otro');
        $total = $request->input('total');

        $resumen = new tb_resumen();
        $resumen->programa_educativo = $programa_educativo;
        $resumen->acreditacion = $acreditacion;
        $resumen->certificacion_competencias = $certificacion_competencias;
        $resumen->evento = $evento;
        $resumen->investigacion = $investigacion;
        $resumen->material_educativo = $material_educativo;
        $resumen->plan_estudios = $plan_estudios;
        $resumen->proyecto = $proyecto;
        $resumen->otro = $otro;
        $resumen->total = $total;
        $resumen->save();

        return redirect()->route('resumen');
    }

    public function eliminar(Request $request) {
        $id = $request->id;
        $admision = tb_resumen::whereIn('id', $id);
        $admision->delete();
        return redirect()->route('resumen');
    }

    public function editar(Request $request) {
        $id = $request->input('id');
        $programa_educativo = $request->input('programa_educativo');
        $acreditacion = $request->input('acreditacion');
        $certificacion_competencias = $request->input('certificacion_competencias');
        $evento = $request->input('evento');
        $investigacion = $request->input('investigacion');
        $material_educativo = $request->input('material_educativo');
        $plan_estudios = $request->input('plan_estudios');
        $proyecto = $request->input('proyecto');
        $otro = $request->input('otro');
        $total = $request->input('total');
        $actividades_transversales = $request->input('actividades_transversales');
        $persona_acargo = $request->input('persona_acargo');
        
        $resumen = tb_resumen::find($id);
        $resumen->programa_educativo = $programa_educativo;
        $resumen->acreditacion = $acreditacion;
        $resumen->certificacion_competencias = $certificacion_competencias;
        $resumen->evento = $evento;
        $resumen->investigacion = $investigacion;
        $resumen->material_educativo = $material_educativo;
        $resumen->plan_estudios = $plan_estudios;
        $resumen->proyecto = $proyecto;
        $resumen->otro = $otro;
        $resumen->total = $total;
        $resumen->actividades_transversales = $actividades_transversales;
        $resumen->persona_acargo = $persona_acargo;
        $resumen->save();

        return redirect()->route('resumen');

    }

    public function asignarActTransversal(Request $request) {
        $nombre_evento = $request->input('nombre_evento');
        $id = $request->input('id');
        
        // Obtener el valor anterior de la columna actividades_transversales de la tabla tb_resumen
        $resumen = tb_resumen::findOrFail($id);
        $actividades_transversales_anterior = $resumen->actividades_transversales;
    
        // Concatenar el valor anterior con el nuevo valor, separados por una coma y un espacio
        $actividades_transversales_nuevo = $actividades_transversales_anterior ? $actividades_transversales_anterior . ', ' . $nombre_evento : $nombre_evento;
    
        // Actualizar la fila en la tabla tb_resumen con el nuevo valor de actividades_transversales
        $resumen->update(['actividades_transversales' => $actividades_transversales_nuevo]);
    
        return redirect()->route('resumen');
    }

    public function registrarActTransversal(Request $request) {
        $nombre_evento = $request->input('nombre_evento');
        $transversal = new tb_actividades_transversale();
        $transversal->nombre_evento = $nombre_evento;
        $transversal->save();

        return redirect()->route('resumen');
    }

    public function editarActividadTransversal(Request $request) {
        $id = $request->input('id');
        $nombre_evento = $request->input('nombre_evento');

        $transversal = tb_actividades_transversale::find($id);
        $transversal->nombre_evento = $nombre_evento;
        $transversal->save();

        return redirect()->route('resumen');
    }

    public function eliminarActividadTransversal(Request $request) {
        $id = $request->id;
        $transversal = tb_actividades_transversale::whereIn('id', $id);
        $transversal->delete();
        return redirect()->route('resumen');
    }
    
  
    
}
