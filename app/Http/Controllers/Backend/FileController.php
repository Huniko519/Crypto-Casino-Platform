<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function store(Request $request)
    {
        try {
            // save uploaded logo in storage
            $path = $request->file->storeAs(
                $request->folder,
                $request->name . '-' . time() . '.' . $request->file->extension(),
                'public'
            );

            return [
                'success'   => TRUE,
                'path'      => $path ? '/storage/' . $path : ''
            ];
        } catch (\Exception $e) {
            return [
                'success' => FALSE,
                'message' => $e->getMessage()
            ];
        }
    }
}
