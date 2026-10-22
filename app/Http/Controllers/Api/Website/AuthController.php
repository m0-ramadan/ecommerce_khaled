<?php

namespace App\Http\Controllers\Api\Website;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\Website\OtpMail;
use App\Models\OtpVerification;
use App\Models\PhoneOtp;
use App\Support\SaudiPhone;
use App\Services\WhatsAppOtpService;

use App\Traits\ApiResponseTrait;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Website\LoginRequest;

use App\Http\Resources\Website\UserResource;
use App\Http\Requests\Website\RegisterRequest;

use App\Http\Requests\Website\Auth\SendOtpRequest;


use App\Http\Requests\Website\Auth\VerifyOtpRequest;

use App\Http\Requests\Website\ChangePasswordRequest;
use App\Http\Requests\Website\SocialMediaLoginRequest;
use App\Http\Requests\Website\Auth\ResetPasswordRequest;

class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * 🔹 تسجيل مستخدم جديد
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'password'    => Hash::make($request->password),
                'google_id'   => $request->google_id,
                'facebook_id' => $request->facebook_id,
                'apple_id'    => $request->apple_id,
            ]);

            $token = $user->createToken('api_token')->plainTextToken;

            return $this->success([
                'user'  => new UserResource($user),
                'token' => $token,
            ], 'تم إنشاء الحساب بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء إنشاء الحساب', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * 🔹 تسجيل الدخول
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = User::whereIn('phone', SaudiPhone::variants($request->phone))->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->error('بيانات الدخول غير صحيحة', 401);
            }

            $token = $user->createToken('api_token')->plainTextToken;

            return $this->success([
                'user'  => new UserResource($user),
                'token' => $token,
            ], 'تم تسجيل الدخول بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء تسجيل الدخول', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * 🔹 تسجيل الدخول أو إنشاء حساب عبر Google / Facebook / Apple
     */
    public function socialLogin(SocialMediaLoginRequest $request)
    {

        try {
            $column = "{$request->provider}_id";

            $user = User::where($column, $request->provider_id)->first();

            if (!$user) {
                $user = User::where('email', $request->email)->first();

                if ($user) {
                    // الحساب موجود بطريقة عادية → لا نسمح بربطه إلا إذا كان فارغًا
                    if (!empty($user->google_id) || !empty($user->facebook_id) || !empty($user->apple_id)) {
                        return $this->error('الحساب مرتبط بحساب اجتماعي آخر', 409);
                    }
                    // نربط الحساب الموجود
                    $user->update([$column => $request->provider_id]);
                } else {
                    // إنشاء مستخدم جديد
                    $user = User::create([
                        'name'        => $request->name ?? 'User',
                        'email'       => $request->email,
                        $column       => $request->provider_id,
                        'password' => Hash::make(Str::random(32)),
                    ]);
                }
            }



            $token = $user->createToken('api_token')->plainTextToken;

            return $this->success([
                'user'  => new UserResource($user),
                'token' => $token,
            ], 'تم تسجيل الدخول بنجاح عبر ' . ucfirst($request->provider));
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء تسجيل الدخول الاجتماعي', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * 🔹 إرسال رمز OTP لتسجيل الدخول برقم الجوال عبر واتساب
     */
    public function sendPhoneOtp(Request $request, WhatsAppOtpService $whatsapp)
    {
        $request->validate([
            'phone' => ['required', 'string'],
        ], [
            'phone.required' => 'رقم الجوال مطلوب.',
        ]);

        $phone = SaudiPhone::normalize($request->input('phone'));

        if (! $phone) {
            return $this->error('يرجى إدخال رقم جوال سعودي صالح.', 422);
        }

        // منع الإرسال المتكرر: 3 رسائل كل 10 دقائق ورسالة واحدة كل 60 ثانية
        $recentCount = PhoneOtp::where('phone', $phone)
            ->where('created_at', '>', now()->subMinutes(10))
            ->count();

        if ($recentCount >= 3) {
            return $this->error('تم طلب رموز تحقق كثيرة لهذا الرقم. يرجى المحاولة بعد 10 دقائق.', 429);
        }

        $lastOtp = PhoneOtp::where('phone', $phone)->latest()->first();
        if ($lastOtp && $lastOtp->created_at->gt(now()->subSeconds(60))) {
            $seconds = 60 - $lastOtp->created_at->diffInSeconds(now());

            return $this->error("يرجى الانتظار {$seconds} ثانية قبل إعادة الإرسال.", 429);
        }

        $code = (string) random_int(100000, 999999);

        // إبطال أي رموز سابقة غير مستخدمة
        PhoneOtp::where('phone', $phone)->whereNull('used_at')->update(['used_at' => now()]);

        $otp = PhoneOtp::create([
            'phone' => $phone,
            'otp' => Hash::make($code),
            'expires_at' => now()->addMinutes(5),
            'ip' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
        ]);

        $result = $whatsapp->send($phone, $code);

        if (! ($result['success'] ?? false)) {
            $otp->delete();

            return $this->error('تعذر إرسال رمز التحقق عبر واتساب. يرجى المحاولة لاحقًا.', 422, [
                'provider_error' => $result['error'] ?? null,
            ]);
        }

        return $this->success([
            'phone' => $phone,
            'expires_in' => 300,
            'resend_after' => 60,
            'delivery' => 'whatsapp',
        ], 'تم إرسال رمز التحقق إلى واتساب');
    }

    /**
     * 🔹 التحقق من رمز واتساب وتسجيل الدخول (أو إنشاء حساب تلقائيًا)
     */
    public function verifyPhoneOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'otp' => ['required', 'digits:6'],
        ], [
            'phone.required' => 'رقم الجوال مطلوب.',
            'otp.required' => 'رمز التحقق مطلوب.',
            'otp.digits' => 'رمز التحقق يجب أن يكون 6 أرقام.',
        ]);

        $phone = SaudiPhone::normalize($request->input('phone'));

        if (! $phone) {
            return $this->error('يرجى إدخال رقم جوال سعودي صالح.', 422);
        }

        $otp = PhoneOtp::where('phone', $phone)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (! $otp) {
            return $this->error('رمز التحقق غير صالح أو تم استخدامه. يرجى طلب رمز جديد.', 422);
        }

        if ($otp->isExpired()) {
            $otp->update(['used_at' => now()]);

            return $this->error('انتهت صلاحية رمز التحقق. يرجى طلب رمز جديد.', 422);
        }

        if ($otp->attempts >= 5) {
            return $this->error('تم تجاوز عدد المحاولات المسموح. يرجى طلب رمز جديد.', 422);
        }

        if (! Hash::check((string) $request->input('otp'), $otp->otp)) {
            $otp->increment('attempts');

            return $this->error('رمز التحقق غير صحيح.', 422);
        }

        $otp->update(['used_at' => now()]);

        $user = User::whereIn('phone', SaudiPhone::variants($phone))->first();
        $isNewUser = false;

        if (! $user) {
            $baseEmail = 'phone_' . ltrim(preg_replace('/\D+/', '', $phone), '0') . '@otp.tala.local';
            $email = $baseEmail;
            $suffix = 1;

            while (User::where('email', $email)->exists()) {
                $email = 'phone_' . ltrim(preg_replace('/\D+/', '', $phone), '0') . '_' . $suffix++ . '@otp.tala.local';
            }

            $user = User::create([
                'name' => 'عميل تالا',
                'email' => $email,
                'phone' => $phone,
                'password' => Hash::make(Str::random(32)),
                'phone_verified_at' => now(),
            ]);
            $isNewUser = true;
        } else {
            $user->forceFill([
                'phone' => $phone,
                'phone_verified_at' => now(),
            ])->save();
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return $this->success([
            'user' => new UserResource($user),
            'token' => $token,
            'is_new_user' => $isNewUser,
        ], $isNewUser ? 'تم إنشاء حسابك وتسجيل الدخول بنجاح' : 'تم تسجيل الدخول بنجاح');
    }

    /**
     * 🔹 تسجيل الخروج
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->success(null, 'تم تسجيل الخروج بنجاح');
    }

    /**
     * 🔹 عرض بيانات المستخدم الحالي
     */
    public function profile(Request $request)
    {
        return $this->success(new UserResource($request->user()), 'تم جلب بيانات المستخدم');
    }

    /**
     * Send OTP to email
     */
    public function sendOtp(SendOtpRequest $request)
    {
        try {
            $email = $request->email;

            // Delete old OTPs
            OtpVerification::where('email', $email)->delete();

            // Generate 6-digit OTP
            $otp = (string) random_int(100000, 999999);

            // Store hashed
            OtpVerification::create([
                'email'      => $email,
                'otp'        => Hash::make($otp),
                'expires_at' => Carbon::now()->addMinutes(5),
            ]);

            // Send email (queue in production)
            Mail::to($email)->send(new OtpMail($otp));

            return $this->success(null, 'تم إرسال رمز التحقق (OTP) إلى بريدك الإلكتروني');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء إرسال رمز OTP', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }

    /**
     * Verify OTP + Reset Password
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $email = $request->email;
            $plainOtp = $request->otp;

            $record = OtpVerification::where('email', $email)
                ->whereNull('used_at')
                ->first();

            if (!$record) {
                return $this->error('رمز OTP غير صالح أو تم استخدامه', 422);
            }

            if ($record->isExpired()) {
                $record->delete();
                return $this->error('انتهت صلاحية رمز OTP', 422);
            }

            if (!Hash::check($plainOtp, $record->otp)) {
                return $this->error('رمز OTP غير صحيح', 422);
            }

            // Mark as used
            $record->used_at = now();
            $record->save();

            // Update password
            $user = User::where('email', $email)->firstOrFail();
            $user->password = Hash::make($request->password);
            $user->save();

            // Revoke old tokens
            $user->tokens()->delete();

            // Create new token
            $token = $user->createToken('api_token')->plainTextToken;

            // Cleanup OTP records
            OtpVerification::where('email', $email)->delete();

            return $this->success([
                'user'  => new UserResource($user),
                'token' => $token,
            ], 'تم إعادة تعيين كلمة المرور بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء إعادة تعيين كلمة المرور', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }


    public function verifyOtp(VerifyOtpRequest $request)
    {
        try {
            $email = $request->email;
            $plainOtp = $request->otp;

            $record = OtpVerification::where('email', $email)
                ->whereNull('used_at')
                ->first();

            if (!$record) {
                return $this->error('رمز OTP غير صالح أو تم استخدامه', 422);
            }

            if ($record->isExpired()) {
                $record->delete();
                return $this->error('انتهت صلاحية رمز OTP', 422);
            }

            if (!Hash::check($plainOtp, $record->otp)) {
                return $this->error('رمز OTP غير صحيح', 422);
            }

            // يمكن فقط تمييزه مؤقتاً أو تركه كما هو بدون حذف
            return $this->success([
                'email' => $email,
            ], 'تم التحقق من رمز OTP بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء التحقق من OTP', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = $request->user();
            // Check old password
            if (!Hash::check($request->old_password, $user->password)) {
                return $this->error('كلمة المرور الحالية غير صحيحة', 422);
            }
            if ($request->old_password === $request->new_password) {
                return $this->error('لا يمكن استخدام نفس كلمة المرور القديمة', 422);
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            $user->tokens()->delete();
            $token = $user->createToken('api_token')->plainTextToken;

            return $this->success([
                'user'  => new UserResource($user),
                'token' => $token,
            ], 'تم تغيير كلمة المرور بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء تغيير كلمة المرور', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }
}
