<?php

namespace App\Http\Controllers\Auth;

use URL;
use Auth;
use App\Models\User;
use Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class OAuthController extends Controller
{
    /**
     * OAuthController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $service = $request->route('service');
        // 因为发现有恶意访问回调地址的情况 此处限制允许使用的第三方登录方式
        $type = [
            'qq',
            'weixin'
        ];
        if (!empty($service) && !in_array($service, $type)) {
            return abort(404);
        }
    }

    /**
     * oauth跳转
     *
     * @param Request $request
     * @param $service
     * @return mixed
     */
    public function redirectToProvider(Request $request, $service)
    {
        // 记录登录前的url
        $data = [
            'targetUrl' => URL::previous()
        ];
        session($data);
        return Socialite::driver($service)->redirect();
    }

    /**
     * 获取用户资料并登录
     *
     * @param Request $request
     * @param $service
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallback($service)
    {

        $oauthUser = Socialite::driver($service)->stateless()->user();

//        $user = $driver->user();
        switch (strtolower($service)) {
            case 'weixin':
                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }

                $userAttributes = [
                    'weixin_unionid' => $unionid,
                    'weixin_openid' => $oauthUser->getId(),
                ];
                break;

            case 'qq':
                $user = User::where('qq_openid', $oauthUser->getId())->first();
                $userAttributes = [
                    'qq_openid' => $oauthUser->getId(),
                ];
                break;


        }
        // 没有用户且未登录，默认创建一个用户
        if (!$user && !Auth::check()) {
            $user = User::create(array_merge([
                'name' => $oauthUser->getNickname(),
                'avatar' => $oauthUser->getAvatar(),
            ],$userAttributes));
        }else if(!$user && Auth::check()){
            // 没有用户但是已登录，默认绑定
            $user = Auth::user();
            $user->update($userAttributes);
            return redirect()->route('users.show',$user->id)->with('success', '绑定成功.');
        }else if($user && Auth::check() && ($user->id != Auth::user()->id) ){
            return redirect()->route('users.show',$user->id)->with('danger', '已绑定过其它用户，不能重复绑定.');
        }else if( $user && Auth::check() && ($user->id == Auth::user()->id) ){
            return redirect()->route('users.show',$user->id)->with('danger', '已绑定，无需重复绑定.');
        }

        # 登录用户
        Auth::login($user);
        return redirect()->route('users.edit',$user->id);
    }
    public function unbind($type){

        if ($this->checkOauthType($type)) {
            return abort(404);
        }

        $user = Auth::user();

        if(!$user->email){
            return redirect()->route('users.edit',$user->id)->with('danger', '绑定邮箱后才能解绑.');
        }

        switch (strtolower($type)) {
            case 'weixin':
                $userAttributes = [
                    'weixin_unionid' => null,
                    'weixin_openid' => null,
                ];
                break;
            case 'qq':
                $userAttributes = [
                    'qq_openid' => null,
                ];
                break;
        }

        # 用户保存
        $user->update($userAttributes);
        return redirect()->route('user.show',$user->id)->with('success', '解绑成功.');
    }




}
