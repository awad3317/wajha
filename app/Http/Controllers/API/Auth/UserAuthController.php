<?php

namespace App\Http\Controllers\API\Auth;

use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Services\HypersenderService;

class UserAuthController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private UserRepository $UserRepository,private OtpService $otpService,private HypersenderService $HypersenderService)
    {
        //
    }
    public function register(Request $request){
        $fields=$request->validate([
            'phone'=>['required','string','min:9','max:15',Rule::unique('users')],
            'password' => ['required','string','min:6','confirmed',],
            'name'=>['required','string','max:100'],
        ]);
        
        // $fields['user_type']='user';
        // $user=$this->UserRepository->store($fields);

        $otp=$this->otpService->generateOTP($fields['phone'],'account_creation');
        $this->HypersenderService->sendTextMessage($fields['phone'],strval($otp));

        return ApiResponseClass::sendResponse($fields['phone'],'تم إرسال رمز التحقق الى رقم الهاتف :'. $fields['phone']);
    }

    public function login(Request $request)
    {

        $fields=$request->validate([
            'phone'=>['required','string'],
            'password' => ['required','string'],
        ],[
            'phone.required' => 'حقل رقم الهاتف مطلوب.',
            'phone.string'   => 'يجب أن يكون رقم الهاتف نصًا صالحًا.',
            'password.required' => 'حقل كلمة المرور مطلوب.',
            'password.string'   => 'يجب أن تكون كلمة المرور نصًا صالحًا.',
        ]);
        $user=$this->UserRepository->findByPhone($fields['phone']);

        if ($user && $user->user_type == 'admin') {
            return ApiResponseClass::sendError('لا يمكن للمشرفين تسجيل الدخول من خلال هذا التطبيق', null, 403);
        }
        
        if($user && Hash::check($fields['password'], $user->password)){

            if (is_null($user->phone_verified_at)) {
                
                $otp=$this->otpService->generateOTP($user->phone,'account_creation');

                $this->HypersenderService->sendTextMessage($user->phone,strval($otp));
            

                return ApiResponseClass::sendError("حسابك غير مفعل بعد. تم إرسال رمز تحقق جديد إليك.", null, 403);
            }
            if($user->is_banned){
                return ApiResponseClass::sendError('الحساب محظور', null, 401);
            }
            $user->tokens()->delete();
            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
            return ApiResponseClass::sendResponse([
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ], 'تم تسجيل الدخول بنجاح');

        }
        return ApiResponseClass::sendError('البيانات المدخلة غير صحيحه', ['error' => 'بيانات الاعتماد غير صالحة'], 401);
        
    }

    public function logout(Request $request)
    {
        $user = auth('sanctum')->user();
        $user->device_token = null;
        $user->save();
        $user->tokens()->delete();

        return ApiResponseClass::sendResponse(null, 'تم تسجيل الخروج بنجاح');
    }

   
}
