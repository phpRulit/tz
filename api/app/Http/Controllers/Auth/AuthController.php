<?php


namespace App\Http\Controllers\Auth;

use App\Entity\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ThrottlesLogins;

    protected $maxAttempts = 5;
    protected $decayMinutes = 10;

    public function __construct () {
        $this->maxAttempts = getenv('TROTTLES_MAX_ATTEMPS');
        $this->decayMinutes = getenv('TROTTLES_DECAY_MINUTES');
    }

    private function throttlesLogins (Request $request) {
        $this->fireLockoutEvent($request);
        return $this->sendLockoutResponse($request);
    }

    public function login(Request $request) :JsonResponse
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email',
            'password'  => 'required|string|min:3',
        ]);
        if ($v->fails()){
            return response()->json(['errors' => $v->errors()]);
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $data = $this->throttlesLogins($request);
            $data = json_decode($data, true);
            if ($data) {
                return response()->json([
                    'messageError' => 'Вы совершили слишком много неудачных попыток входа. Следующую попытку
                    можно будет сделать через ' . $data['seconds'] . ' секунд(ы)...',
                ]);
            }
        }

        if ($token = $this->guard()->setTTL(1440)->attempt($v->validated())) {

            $user = $this->guard()->user();
            /*if (!$user->email_verified_at) {
                $this->incrementLoginAttempts($request);
                return response()->json([
                    'messageError' => 'Вы не подтвердили Вашу регистрацию, отправить Вам новое письмо?',
                    'email' => $user->email
                ]);
            }*/
            $this->clearLoginAttempts($request);
            return response()->json([
                'user'=>$user,
                'token' => $token,
                'token_type' => 'bearer',
                'token_validity' => $this->guard()->factory()->getTTl() * 60
            ], 200);
        }
        $this->incrementLoginAttempts($request);
        return response()->json([
            'messageError' => 'Неверный логин или пароль...',
            'email' => null
            ]);

    }

    protected function respondWithToken($token)  :JsonResponse
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'token_validity' => $this->guard()->factory()->getTTl() * 60
        ]);
    }

    public function register(Request $request) :JsonResponse
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|string|between:2,50',
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:3|confirmed',
        ]);
        if ($v->fails()){
            return response()->json(['errors' => $v->errors()]);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        //$user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Вы успешно зарегистрировались. Можете авторизоваться'
        /*Мы отправили Вам письмо,
        для подтверждения регистрации. Проверьте Вашу почту...*/]);
    }

    public function resendMailVerificationRegistry($email) :JsonResponse
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->sendEmailVerificationNotification();
            return response()->json(['message' => 'Мы отправили Вам письмо, для подтверждения регистрации,
         на указанный e-mail. Проверьте Вашу почту...']);
        }
        return response()->json(['messageError' => 'Вы ещё не проходили регистрацию на сайте. Пожалуйста пройдите...']);
    }


    public function verifyRegistry($verification_code) :JsonResponse
    {
        $check = DB::table('user_verifications')->where('token', Crypt::decrypt($verification_code))->first();
        if(!is_null($check)){
            DB::table('users')->where('id',$check->user_id)->update(['email_verified_at' => Carbon::now()]);
            DB::table('user_verifications')->where('token',Crypt::decrypt($verification_code))->delete();
            return response()->json(['message' => 'Вы успешно подтвердили Вашу регистрацию, теперь можете осуществить вход...']);
        }
        return response()
            ->json(['messageError'=> "Вы слишком поздно нажали на ссылку, она уже не действительна."]);
    }

    public function logout() :JsonResponse
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Вы успешно выши...'
        ], 200);
    }

    public function getUserAuth() :JsonResponse
    {
        return response()->json(Auth::user());
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    private function guard()
    {
        return Auth::guard('api');
    }
}
