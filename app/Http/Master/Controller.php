<?php

namespace App\Http\Master;

use Illuminate\Http\Request;

use Laravel\Lumen\Routing\Controller as MyController;

class Controller extends MyController
{
    public function Files(Request $request)
    {
        return $this->UploadFile(
            (object)[
                'asli'          =>  $request,
                'type_upload'   =>  $request->input('type'),
                'old_file'      =>  $request->input('old_file'),
                'path'          =>  $request->input('path')
            ]
        );
    }

    public function deleteGambar(Request $request)
    {
        $file = $request->input('file');
        // return response()->json(['message' => is_file($file)]);
        try {
            if (!is_file($file)) {
                throw new \Exception('File tidak ditemukan');
            }

            unlink($file);
            return response()->json(['message' => 'file berhasil dihapus']);
        }
        catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
        
    }

    public function UploadFile($dat)
    {
        try {
            $file = $dat->asli->file();
            $file1 = $dat->asli->hasFile('file');
            $size = $dat->asli->file('file')->getSize();
            if ($file1 != false) {
                $data = (object)[
                    'original'      =>  $file['file']->getClientOriginalName(),
                    'path'          =>  $dat->path,
                    'type'          =>  $dat->type_upload,
                    'old'           =>  $dat->old_file,
                    'file'          =>  $file['file']
                ];
                $original_filename = $data->original;
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = 'upload/' . $data->path . '/' . $file_ext . '/';
                !is_dir($destination_path) ? @mkdir($destination_path) : true;
                // $image = 'F-' . time() . '.' . $file_ext;
                
                // get filename
                array_pop($original_filename_arr);
                $filename = join(' ', $original_filename_arr);
                $filename = str_replace(array('\\','/',':','*','?','"','<','>','|'),' ',$filename);
                $image = $filename . '_' . time() . '.'. $file_ext;

                if ($data->file->move($destination_path, $image)) {
                    if (!empty($data->old) && is_file($data->old)) {
                        unlink($data->old);
                    }
                    if ($data->type = "Tambah") {
                        return [
                            "size"     => is_file($data->old),
                            "link"     => $destination_path . $image,
                            "format"   => $file_ext
                        ];
                    } else if ($data->type == "Edit") {
                        
                        return [
                            "size"     => is_file($data->old),
                            "link"     => $destination_path . $image,
                            "format"   => $file_ext
                        ];
                    }
                }
                return false;
            } else {
                return "Tidak Ada File!!";
            }
        } catch (\Exception $e) {
            echo $e;
        }
    }
}
