<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Actividades;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\FacturasCobro;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Carbon;
use PDF;
use App\Models\Tablas\Empresas;

class CobrosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $cobros = DB::table('TBL_Actividades as a')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('a.id as Id_Actividad', 'u.id as Id_Cliente', 'a.*', 'u.*', 'p.*')
            ->where('a.ACT_Estado_Actividad', '=', 'En Cobro')
            ->orderBy('p.id')
            ->get();
        $proyectos = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('p.id as Id_Proyecto', 'a.*', 'p.*', 'u.*', DB::raw('COUNT(a.id) as No_Actividades'))
            ->where('a.ACT_Costo_Actividad', '<>', 0)
            ->where('a.ACT_Estado_Actividad', '=', 'Esperando Pago')
            ->groupBy('fc.FACT_Cliente_Id')
            ->get();
        return view('director.cobros.listar', compact('cobros', 'proyectos', 'datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function agregarFactura($idA, $idC)
    {
        $cliente = Usuarios::findOrFail($idC);
        Actividades::findOrFail($idA)->update(['ACT_Estado_Actividad' => 'Facturado']);
        FacturasCobro::create([
            'FACT_Actividad_Id' => $idA,
            'FACT_Cliente_Id' => $idC,
            'FACT_Fecha_Cobro' => Carbon::now()
        ]);
        return redirect()->back()->with('mensaje', 'Actividad agregada a la factura del cliente '.$cliente->USR_Nombre.' '.$cliente->USR_Apellido);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generarFactura($id)
    {
        $proyecto = DB::table('TBL_Proyectos as p')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->where('p.id', '=', $id)
            ->first();
        $informacion = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('p.*', 'a.*', 'u.*', 'fc.*')
            ->where('a.ACT_Costo_Actividad', '<>', 0)
            ->where('a.ACT_Estado_Actividad', '=', 'Esperando Pago')
            ->where('p.id', '=', $id)
            ->get();
        $empresa = Empresas::findOrFail($proyecto->USR_Empresa_Id);
        $total = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('a.*', DB::raw('SUM(a.ACT_Costo_Actividad) as Costo'))
            ->groupBy('a.ACT_Proyecto_Id')
            ->where('p.id', '=', $id)
            ->where('a.ACT_Estado_Actividad', '=', 'Esperando Pago')
            ->first();
        foreach ($informacion as $info) {
            $factura = $info->id;
        }
        $datos = ['proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa];
        $pdf = PDF::loadView('includes.pdf.factura.factura', compact('datos'));

        $fileName = 'FacturaINK-'.$factura;
        return $pdf->download($fileName);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
