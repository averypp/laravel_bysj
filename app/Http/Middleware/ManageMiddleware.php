<?php

namespace App\Http\Middleware;

use App\Model\Manage\SystemLog;
use App\Tools\Tools;
use Closure;

class ManageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ('/Manage/login'!=$request->getPathInfo() && !$request->session()->has('admin_id')) {
            return redirect('Manage/login')->with('error','请先登录');
        }

        if ('/Manage/login'==$request->getPathInfo() && $request->session()->has('admin_id')){
            return redirect('Manage/index');
        }

        if ('/Manage/lock'!=$request->getPathInfo() && $request->session()->has('lock')){
            return redirect('Manage/lock');
        }

        $auths = ['/Manage/index'];
        if ($request->session()->has('admin_auths')){
            $auths = $request->session()->get('admin_auths');
        }
        if ('/Manage/login'!=$request->getPathInfo() && !in_array($request->getPathInfo(),$auths)){
//            var_dump($auths);
//            var_dump($request->getPathInfo());die;
//            $request->session()->forget('admin_id');
//            $request->session()->forget('admin_account');
//            $request->session()->forget('admin_group_id');
//            $request->session()->forget('admin_auths');
//            return redirect('Manage/login')->with('error','非法操作');
        }

        $adminId = $request->session()->get('admin_id');
        $url = Tools::getUrl();
        $ip = $_SERVER['REMOTE_ADDR'];
        $p = explode('/',$request->getPathInfo());
        $module = isset($p[1]) ? $p[1] : '';
        $controller = isset($p[2]) ? $p[2] : '';
        $action = isset($p[3]) ? $p[3] : '';
        $remark = isset($p[4]) ? '附加参数：'.$p[4] : '';

        $systemLogData = [
            'admin_id'=>intval($adminId),
            'module'=>$module,
            'controller'=>$controller,
            'action'=>$action,
            'ip'=>$ip,
            'url'=>$url,
            'remark'=>$remark,
        ];
        SystemLog::create($systemLogData);

        return $next($request);
    }
}
