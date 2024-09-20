<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{


    public function info()
    {

        $with = [];

        $with[] = 'info';
        $with[] = 'image';


        if (Auth::user()->role == User::ROLE_COMPANY) {
            $with[] = 'companies';
        }

        $user = User::where(['id' => Auth::id()])->with($with)->first();

        return view('frontend.profile.info', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $user->with('info', 'sign', 'stamp');

        if ($request->has('sign')) {
            if (isset($user->sign)) {
                $user->sign->delete();
            }
            Image::add($request->file('sign'), 'sign', $user->id);
        }
        if ($request->has('stamp')) {
            if (isset($user->stamp)) {
                $user->stamp->delete();
            }
            Image::add($request->file('stamp'), 'stamp', $user->id);
        }

        return redirect()->to(request()->headers->get('referer'))->with('success', __('main.save_success'));
    }


    public function imageDestroy(Image $image)
    {
        $image->deleteSmall();
        $image->delete();
        return ['status'=>true];
    }



}
