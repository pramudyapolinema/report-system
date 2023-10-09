<?php

namespace App\Http\Controllers;

use App\Models\KelompokTani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KelompokTaniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $kelompokTani = KelompokTani::select('*');
            return DataTables::eloquent($kelompokTani)
                ->addColumn('actions', function ($kelompokTani) {
                    $button = '<button type="button" id="editButton" data-id="' . $kelompokTani->id . '" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editKelompokTaniModal"><i class="fa fa-edit"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" id="deleteButton" data-id="' . $kelompokTani->id . '" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['actions'])
                ->addIndexColumn()
                ->make();
        }
        return view('kelompokTani.index');
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
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required'
            ]);

            KelompokTani::create([
                'name' => $request->name
            ]);

            DB::commit();

            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KelompokTani  $kelompokTani
     * @return \Illuminate\Http\Response
     */
    public function show(KelompokTani $kelompokTani)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KelompokTani  $kelompokTani
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kelompokTani = KelompokTani::findOrFail($id);
        return response()->json($kelompokTani, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KelompokTani  $kelompokTani
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $kelompokTani = KelompokTani::findOrFail($id);
            $kelompokTani->update([
                'name'  => $request->name
            ]);
            DB::commit();
            return response()->json(['message' => 'Data berhasil diupdate'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KelompokTani  $kelompokTani
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $kelompokTani = KelompokTani::findOrFail($id);
            $kelompokTani->delete();
            DB::commit();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
