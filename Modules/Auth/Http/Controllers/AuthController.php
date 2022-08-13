<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Config\ModuleAuthConfig;
use Modules\Auth\Entities\AuthEntity;
use Modules\Auth\Enums\RoleType;
use Modules\Auth\Interfaces\AuthControllerInterface;
use Modules\Auth\Models\UserModel;
use Modules\Auth\Services\AuthService;
use Modules\Shared\Config\ModuleSharedConfig;
use Modules\Shared\Enums\NotificationType;
use mysql_xdevapi\Exception;
use Pusher\Pusher;
use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller implements AuthControllerInterface
{


    public function signUp(Request $request, AuthService $authService)
    {


        if (RateLimiter::tooManyAttempts('send-message:' . $request->getClientIp(), $perMinute = 5)) {
            return response()->json(['data' => RateLimiter::availableIn('send-message:' . $request->getClientIp())])
                ->setStatusCode(ResponseAlias::HTTP_TOO_MANY_REQUESTS, __('Auth.tooManyRequests'));

        }


        if (loginVia($request->input('login') ?? $request->input('login')) == 'email') {

            $lineRule = 'required|email:rfc,dns|unique:Modules\Auth\Models\UserModel,email';
        } else if (loginVia($request->input('login') ?? $request->input('login')) == 'phone') {

            $lineRule = 'required|min:9|unique:Modules\Auth\Models\UserModel,phone';
        } else {

            return response()->json(['error' => __('auth.emailOrPhone')])
                ->setStatusCode(ResponseAlias::HTTP_NOT_ACCEPTABLE, __('auth.youPhoneOrEmail'));
        }

        $rules = [
            'username' => 'required|min:3',
            'login' => $lineRule,
            'password' => 'required|min:6|required_with:passwordConfirm|same:passwordConfirm',
            'passwordConfirm' => 'required',
            'action' => 'required',
            'token' => 'required',
            'socialLogin' => 'required'

        ];


        $authConfig = new  \Modules\Auth\Config\ModuleAuthConfig();
        // ->setExpectedHostname($_SERVER['SERVER_NAME'])
        $recaptcha = new ReCaptcha($authConfig->captcha['secretKey']);
        $resp = $recaptcha->setExpectedAction($request->input('action'))
            ->setScoreThreshold(0.2)
            ->verify($request->input('token'), $_SERVER['REMOTE_ADDR']);
        // verify the response
        if (!$resp->isSuccess() && !$request->input('socialLogin')) {
            // spam submission
            // show error message
            return response()->json([
                'error' => $resp->getErrorCodes()])
                ->setStatusCode(ResponseAlias:: HTTP_UNAUTHORIZED, __('auth.youAreRobot'));
        }


        $fields = $request->validate($rules);
        $authEntity = new AuthEntity($fields);
        unset($authEntity->token);
        unset($authEntity->action);

        $authEntity->deactivate()
            ->userAgent($request->userAgent())->ipAddress($request->getClientIp())
            ->setRole(RoleType::Member)->generatePassword()->createdAt()->generateActiveteToken();

        $signUpUserData = $authService->signUp($authEntity);

        $sharedConfig = new ModuleSharedConfig();

        $pusher = new Pusher(
            $sharedConfig->pusher['authKey'],
            $sharedConfig->pusher['secret'],
            $sharedConfig->pusher['appId'],
            ['cluster' => $sharedConfig->pusher['cluster'],
                'useTLS' => $sharedConfig->pusher['useTLS']]
        );
        $data['type'] = NotificationType::NewUser;
        $data['message'] = 'new user register';
        $data['counter'] = 1;
        $data['date'] = date('Y-m-d H:i:s', time());;
        $pusher->trigger('notification-channel', 'my-event', $data);

        return response()->json(['data' => $signUpUserData])
            ->setStatusCode(ResponseAlias::HTTP_OK, __('auth.singUp'));

    }


    public function signIn(Request $request, AuthService $authService)
    {


        if (loginVia($request->input('login') ?? $request->input('login')) == 'email') {

            $lineRule = 'required|email:rfc,dns';
        } else if (loginVia($request->input('login') ?? $request->input('login')) == 'phone') {

            $lineRule = 'required|min:9';
        } else {
            $lineRule = 'required';
        }

        $rules = [
            'login' => $lineRule,
            'password' => 'required',

        ];


        $fields = $request->validate($rules);

        $authEntity = new AuthEntity($fields);

        $authEntity->logInMode()
            ->userAgent($request->userAgent())->ipAddress($request->getClientIp());


        $data = $authService->signIn($authEntity);

        $authConfig = new  ModuleAuthConfig();
        return response()->json($data)
            ->withCookie($authConfig->jwt['name'], $data['jwt']['token'], $data['jwt']['expire'])
            ->withHeaders([$authConfig->jwt['name'], $data['jwt']['token']])
            ->setStatusCode(ResponseAlias::HTTP_OK, __('auth.singIn'));


    }

    public function signOut(Request $request, AuthService $authService)
    {

        $authConfig = new  ModuleAuthConfig();
        $authEntity = new AuthEntity(json_decode($request->request->get('user')));
        $authService->signOut($authEntity);

        return response()->json([])
            ->withCookie($authConfig->jwt['name'], null, 0)
            ->withHeaders([$authConfig->jwt['name'],null])
            ->setStatusCode(ResponseAlias::HTTP_OK, __('auth.singOut'));

    }


    public function isSignIn(Request $request)
    {

        return response()->json(['success' => true])
            ->setStatusCode(ResponseAlias::HTTP_OK, 'check is sign in');

    }

    public function forgot(Request $request, AuthService $authService)
    {
        $rules = [

            'login' => 'required',
            'action' => 'required',
            'token' => 'required',

        ];

        $fields = $request->validate($rules);

        $authConfig = new  \Modules\Auth\Config\ModuleAuthConfig();
        // ->setExpectedHostname($_SERVER['SERVER_NAME'])
        $recaptcha = new ReCaptcha($authConfig->captcha['secretKey']);
        $resp = $recaptcha->setExpectedAction($request->input('action'))
            ->setScoreThreshold(0.2)
            ->verify($request->input('token'), $_SERVER['REMOTE_ADDR']);
        // verify the response
        if (!$resp->isSuccess() && !$request->input('socialLogin')) {
            // spam submission
            // show error message
            return response()->json([
                'error' => $resp->getErrorCodes()])
                ->setStatusCode(ResponseAlias:: HTTP_UNAUTHORIZED, __('auth.youAreRobot'));
        }


        $authEntity = new AuthEntity($fields);
        $authEntity->logInMode(false)->ipAddress($request->getClientIp())
            ->userAgent($request->userAgent())->generateResetToken();
        unset($authEntity->token);
        unset($authEntity->action);
        $authService->forgot($authEntity);

        return response()->json()
            ->setStatusCode(ResponseAlias::HTTP_OK, __('auth.forgotDone'));
    }

    public function resetPasswordViaSms(Request $request, AuthService $authService)
    {


        $rules = [
            'resetToken' => 'required',
            'phone' => 'required',
            'password' => 'required|min:6|required_with:passwordConfirm|same:passwordConfirm',
            'passwordConfirm' => 'required',
        ];

        $fields = $request->validate($rules);


        $authEntity = new AuthEntity($fields);
        $authEntity->resetPassword()->generatePassword();
        $authService->resetPasswordViaSms($authEntity);


        return response()->json()->setStatusCode(ResponseAlias::HTTP_OK, __('auth.resetPasswordDone'));
    }

    public function resetPasswordViaEmail(Request $request, AuthService $authService)
    {


        $rules = [
            'resetToken' => 'required',
            'email' => 'required',
            'password' => 'required|min:6|required_with:passwordConfirm|same:passwordConfirm',
            'passwordConfirm' => 'required',
        ];

        $fields = $request->validate($rules);


        $authEntity = new AuthEntity($fields);
        $authEntity->resetPassword()->generatePassword();
        $authService->resetPasswordViaEmail($authEntity);


        return response()->json()->setStatusCode(ResponseAlias::HTTP_OK, __('auth.resetPasswordDone'));


    }

    public function activateAccountViaEmail(Request $request, AuthService $authService)
    {
        $rules = [
            'activeToken' => 'required',
            'email' => 'required|valid_email',
        ];
        $fields = $request->validate($rules);


        $authEntity = new AuthEntity($fields);
        $authEntity->activate();

        $authService->activateAccountViaEmail($authEntity);

        return response()->json()
            ->setStatusCode(ResponseAlias::HTTP_OK, __('auth.accountActivate'));

    }

    public function sendActivateCodeViaEmail(Request $request, AuthService $authService)
    {

        $rules = [
            'email' => 'required',
        ];

        $fields = $this->validate($rules);

        $authEntity = new AuthEntity($fields);
        $authEntity->generateActiveteToken()->deactivate()->activateExpiration();
        $authService->sendActivateCodeViaEmail($authEntity);

        return response()->json()
            ->setStatusCode(ResponseAlias::HTTP_OK, __('auth.emailActivationSend'));
    }

    public function activateAccountViaSms(Request $request, AuthService $authService)
    {
        $rules = [
            'activeToken' => 'required',
            'phone' => 'required|valid_email',
        ];
        $fields = $request->validate($rules);


        $authEntity = new AuthEntity($fields);
        $authEntity->activate();

        $authService->activateAccountViaSms($authEntity);

        return response()->json()
            ->setStatusCode(ResponseAlias::HTTP_OK, __('auth.accountActivate'));

    }

    public function sendActivateCodeViaSms(Request $request, AuthService $authService)
    {
        $rules = [
            'phone' => 'required',
        ];

        $fields = $this->validate($rules);

        $authEntity = new AuthEntity($fields);
        $authEntity->generateActiveteToken()->deactivate()->activateExpiration();
        $authService->sendActivateCodeViaSms($authEntity);

        return response()->json()
            ->setStatusCode(ResponseAlias::HTTP_OK, __('auth.smsActivationSend'));
    }
}
