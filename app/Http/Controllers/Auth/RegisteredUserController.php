<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\ArtisanProfile;
use App\Models\UserProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'],
        ]);


        if ($request->role === 'company') {
            $request->validate([
                'company_name' => ['required', 'string', 'max:255'],
                'company_size' => ['required', 'string', 'in:1-10,11-50,51-200,201-500,500+'],
                'waste_types' => ['nullable', 'array'],
            ]);
        } elseif ($request->role === 'artisan') {
            $request->validate([
                'artisan_specialty' => ['required', 'string', 'in:clothing,accessories,home_decor,furniture,other'],
                'artisan_experience' => ['required', 'string', 'in:beginner,intermediate,experienced,master'],
                'materials_interest' => ['nullable', 'array'],
            ]);
        } elseif ($request->role === 'user') {
            $request->validate([
                'sustainability_importance' => ['required', 'string', 'in:very,somewhat,neutral,learning'],
                'interests' => ['nullable', 'array'],
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->role);

        switch ($request->role) {
            case 'company':
                $profile = new CompanyProfile();
                $profile->user_id = $user->id;
                $profile->company_name = $request->company_name;
                $profile->company_size = $request->company_size;
                $profile->waste_types = $request->waste_types ? json_encode($request->waste_types) : null;
                $profile->save();
                break;
            case 'artisan':
                $profile = new ArtisanProfile();
                $profile->user_id = $user->id;
                $profile->artisan_specialty = $request->artisan_specialty;
                $profile->artisan_experience = $request->artisan_experience;
                $profile->materials_interest = $request->materials_interest ? json_encode($request->materials_interest) : null;
                
                $profile->save();
                break;
            default:
                $profile = new UserProfile();
                $profile->user_id = $user->id;
                $profile->sustainability_importance = $request->sustainability_importance;
                $profile->interests = $request->interests ? json_encode($request->interests) : null;
                $profile->save();
                break;
        }



        event(new Registered($user));

        Auth::login($user);
if ($request->role === 'user') {
            return redirect()->route('home');
        }
        return redirect()->route('dashboard');
    }
}
