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

/**
 * Recuperar Clave Controller, controlador para la recuperación de
 * la contraseña del usuario en caso de ser olvidada
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class RecuperarClaveController extends Controller
{
    use SendsPasswordResetEmails;
    
    /**
     * Visualiza el promulario para recuperar la contraseña
     *
     * @return \Illuminate\View\View Vista de recuperar contraseña
     */
    public function showLinkRequestForm()
    {
        return view('general.recuperar');
    }

    /**
     * Verifica los datos del usuario y envía información al correo electrónico
     *
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        if(
            $user = Usuarios::where(
                'USR_Correo_Usuario', $request->input('USR_Correo_Usuario')
            )->first()
        ){
            $token=Crypt::encryptString(Carbon::now());

            DB::table(config('auth.passwords.users.table'))
                ->where('USR_Correo_Usuario', '=', $user->USR_Correo_Usuario)
                ->delete();
            
            DB::table(config('auth.passwords.users.table'))->insert([
                'USR_Correo_Usuario' => $user->USR_Correo_Usuario,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::send(
                'general.correo.correo',
                ['user' => $user, 'token' => $token],
                function($message) use ($user){
                    $message->from('yonathancam1997@gmail.com', 'InkBrutalPRY');
                    $message->to($user->USR_Correo_Usuario, 'Envío nombre de usuario')
                        ->subject('Reestablecer Clave');
                }
            );

            if (Mail::failures()) {
                return redirect()
                    ->back()
                    ->withErrors('Error al envíar el correo.');
            } else {
                return redirect()
                    ->back()
                    ->with('mensaje', 'Revise la bandeja de entrada de su correo.');
            }

            return redirect()
                ->back()
                ->with('status', trans(Password::RESET_LINK_SENT));
        }

        return redirect()
            ->route('login')
            ->withErrors('El correo no está registrado en el sistema.');
    }
    
    protected function validateEmail(Request $request)
    {
        $request->validate(['USR_Correo_Usuario' => 'required|email']);
    }

    protected function credentials(Request $request)
    {
        return $request->only('USR_Correo_Usuario');
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
            ->withInput($request->only('USR_Correo_Usuario'))
            ->withErrors(['USR_Correo_Usuario' => trans($response)]);
    }

    protected function cambiarClave($token)
    {
        $consulta = DB::table(config('auth.passwords.users.table'))
            ->where('token', '=', $token)
            ->first();

        if(!$consulta) {
            return redirect()
                ->route('login')
                ->withErrors('Token invalido.');
        }

        $fechaToken = Crypt::decryptString($token);
        $diferencia=Carbon::now()->diffInMinutes($fechaToken);
        
        if($diferencia>60) {
            return redirect()
                ->route('recuperar_clave')
                ->withErrors('El token está vencido, Genere uno nuevamente.');
        }
        
        return view(
            'general.cambiar',
            compact('consulta')
        );
    }

    protected function actualizarClave(Request $request)
    {
        if($request->password != $request->confirmar) {
            return redirect()
                ->back()
                ->withErrors('Las contraseñas no coinciden.');
        }
        
        DB::table(config('auth.passwords.users.table'))
            ->where('USR_Correo_Usuario', '=', $request->USR_Correo_Usuario)
            ->delete();
        
        $usuario = DB::table('TBL_Usuarios')
            ->where('USR_Correo_Usuario', '=', $request->USR_Correo_Usuario)
            ->first();
        
        Usuarios::findOrFail($usuario->id)->update([
            'password' => bcrypt($request->password)
        ]);

        return redirect()
            ->route('login')
            ->with('mensaje', 'Contraseña reestablecida, Inicia Sesión.');
    }
}
