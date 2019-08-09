<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class RecuperarClaveController extends Controller
{
    use SendsPasswordResetEmails;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('general.recuperar');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        if($user = Usuarios::where('USR_Correo', $request->input('USR_Correo'))->first()){
            
            $token=Crypt::encryptString(Carbon::now());

            DB::table(config('auth.passwords.users.table'))->where('USR_Correo', '=', $user->USR_Correo)->delete();
            DB::table(config('auth.passwords.users.table'))->insert([
                'USR_Correo' => $user->USR_Correo,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            Mail::send('general.correo.correo', ['user' => $user, 'token' => $token], function($message) use ($user){
                $message->from('8076cdda3e-9b8334@inbox.mailtrap.io', 'FacilPRY');
                $message->to($user->USR_Correo, 'Envío nombre de usuario')
                    ->subject('Reestablecer Clave');
            });

            if (Mail::failures()) {
                return redirect()->back()->withErrors('Error al envíar el correo.');
            } else {
                return redirect()->back()->with('mensaje', 'Revise la bandeja de entrada de su correo.');
            }
            
            /*Mail::send([
                'to' => $user->USR_Correo,
                'subject' => 'Link para reestablecer contraseña.',
                'view' => config('auth.passwords.users.email'),
                'view_data' => [
                    'token' => $token,
                    'user' => $user
                ]
            ]);*/

            return redirect()->back()->with('status', trans(Password::RESET_LINK_SENT));
        }
        return redirect()->back()->withErrors('El correo no está registrado en el sistema.');
    }
    

    protected function validateEmail(Request $request)
    {
        $request->validate(['USR_Correo' => 'required|email']);
    }

    protected function credentials(Request $request)
    {
        return $request->only('USR_Correo');
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
                ->withInput($request->only('USR_Correo'))
                ->withErrors(['USR_Correo' => trans($response)]);
    }

    protected function cambiarClave($token){
        $consulta = DB::table(config('auth.passwords.users.table'))->where('token', '=', $token)->first();

        if(!$consulta){
            return redirect()->route('login')->withErrors('Token invalido.');
        }
        $fechaToken = Crypt::decryptString($token);
        $diferencia=Carbon::now()->diffInMinutes($fechaToken);
        
        if($diferencia>60){
            return redirect()->route('recuperar_clave')->withErrors('El token está vencido, Genere uno nuevamente.');
        }
        
        return view('general.cambiar', compact('consulta'));
    }

    protected function actualizarClave(Request $request){
        if($request->password != $request->confirmar){
            return redirect()->back()->withErrors('Las contraseñas no coinciden.');
        }
        
        DB::table(config('auth.passwords.users.table'))->where('USR_Correo', '=', $request->USR_Correo)->delete();
        DB::table('TBL_Usuarios')->where('USR_Correo', '=', $request->USR_Correo)->update([
            'password' => bcrypt($request->password)
        ]);
        return redirect()->route('login')->with('mensaje', 'Contraseña reestablecida, Inicia Sesión.');
    }
}
