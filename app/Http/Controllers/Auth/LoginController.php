<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use  Socialite;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * 获取用户资料并登录
     *
     * @param Request $request
     * @param OauthUser $oauthUserModel
     * @param $service
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallback(Request $request, OauthUser $oauthUserModel, $service)
    {
        // 定义各种第三方登录的type对应的数字
        $type = [
            'qq' => 1,
            'weibo' => 2,
            'github' => 3
        ];
        // 获取用户资料
        $user = Socialite::driver($service)->user();
        // 组合存入session中的值
        $sessionData = [
            'user' => [
                'name' => $user->nickname,
                'type' => $type[$service],
            ]
        ];
        // 查找此用户是否已经登录过
        $countMap = [
            'type' => $type[$service],
            'openid' => $user->id
        ];
        $oldUserData = $oauthUserModel->select('id', 'login_times', 'is_admin', 'email')
            ->where($countMap)
            ->first();
        // 如果已经存在;则更新用户资料  如果不存在;则插入数据
        if ($oldUserData) {
            $userId = $oldUserData->id;
            $editMap = [
                'id' => $userId
            ];
            $editData = [
                'name' => $user->nickname,
                'access_token' => $user->token,
                'last_login_ip' => $request->getClientIp(),
                'login_times' => $oldUserData->login_times+1,
            ];
            // 更新数据
            $oauthUserModel->updateData($editMap, $editData);
            // 组合session中要用到的数据
            $sessionData['user']['id'] = $userId;
            $sessionData['user']['email'] = $oldUserData->email;
            $sessionData['user']['is_admin'] = $oldUserData->is_admin;
        } else {
            $data = [
                'type' => $type[$service],
                'name' => $user->nickname,
                'openid' => $user->id,
                'access_token' => $user->token,
                'last_login_ip' => $request->getClientIp(),
                'login_times' => 1,
                'email' => ''
            ];
            // 新增数据
            $userId = $oauthUserModel->storeData($data);
            // 组合头像地址
            $avatarPath = '/uploads/avatar/'.$userId.'.jpg';
            // 更新头像
            $editMap = [
                'id' => $userId
            ];
            $editData = [
                'avatar' => $avatarPath
            ];
            $oauthUserModel->updateData($editMap, $editData);
            // 组合session中要用到的数据
            $sessionData['user']['id'] = $userId;
            $sessionData['user']['email'] = '';
            $sessionData['user']['is_admin'] = 0;
        }

        $avatarPath = public_path('uploads/avatar/'.$userId.'.jpg');
        try {
            // 下载最新的头像到本地
            $client = new Client();
            $client->request('GET', $user->avatar, [
                'sink' => $avatarPath
            ]);
        } catch (ClientException $e) {
            // 如果下载失败；则使用默认图片
            copy(public_path('uploads/avatar/default.jpg'), $avatarPath);
        }

        $sessionData['user']['avatar'] = url('uploads/avatar/'.$userId.'.jpg');
        // 将数据存入session
        session($sessionData);
        // 如果session没有存储登录前的页面;则直接返回到首页
        return redirect(session('targetUrl', url('/')));
    }
}
