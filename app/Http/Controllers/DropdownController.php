<?php

namespace App\Http\Controllers;

use App\Models\KelompokTani;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    public function getKelompokTani() {
        $data = KelompokTani::select('id', 'name as text')
            ->where([
                ['name', 'like', '%' . request()->input('search', '') . '%']
            ])->get()->toArray();
        return response()->json(['results' => $data]);
    }
}
