<?php

namespace Packages\GameSlots\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GameSlotsController extends Controller
{
    
    public function files(Request $request)
    {
        $path = storage_path() .'/app/public/games/slots/';
        $used = array_filter(explode(",",$request->input('used')));
        if(!is_dir($path))
            mkdir($path,0777,true);
        
        $files = scandir($path);
        foreach($files as $file)
            if($file!='.'&&$file!='..'&&!in_array($file,$used))
                unlink($path.$file);
        
        $files = [];
        
        if($request->file('files')){
            $is_error=false;
            foreach($request->file('files') as $file)
                if($file->getMimeType()!='image/png')
                    $is_error = true;
            if($is_error)
                return response()->json([
                    'success' => false
                ]);
            
            foreach($request->file('files') as $file){
                $file_name = "sym-".str_replace('.','-',microtime(true))."-".mt_rand(10,30).".png";
                $files[] = $file_name;
                $file->move($path, $file_name);
                \Intervention\Image\ImageManagerStatic::make($path.$file_name)->resize(200, 200)->save($path.$file_name);
            }
        }
        return response()->json([
            'success' => true,
            'files' => $files
        ]);
    }
}
