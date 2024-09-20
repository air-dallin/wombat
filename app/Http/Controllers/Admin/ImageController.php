<?php

namespace App\Http\Controllers\Admin;

use App\Models\Image;

class ImageController extends Controller
{

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $request = request();

        if($request->isMethod('post') && $request->ajax() ) {
            if($image = Image::find($id)) {
                $image->deleteSmall();
                $image->delete();
                return ['status' => true];
            }
        }
        return ['status'=>false];

    }
}
