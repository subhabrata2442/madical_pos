<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\RoleWisePermission;
use App\Models\RoleSubPermission;
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
		
        //dd($roles);
        if (!Auth::check())
            return redirect()->route('auth.login');
			
        $user = Auth::user();

		$is_store	= 'N';
		$admin_type	= Session::get('admin_type');
		if($admin_type!=1){
			$is_store	= 'Y';
		}

		$segments = request()->segments();
		$slug=reset($segments).'-'.$segments[1].'-'.$segments[2];
		//echo $slug;exit;

		if($is_store=='Y'){
			$user_id	= $user->id;
			$role_id	= $user->role;
			$status		= $user->status;

			if($status!=1){
				Session::flush();
				Auth::logout();
				return redirect()->route('auth.login');
			}

			$role_wise_permission_result = RoleSubPermission::where('slug',$slug)->first();
			$current_page_permision_id		= isset($role_wise_permission_result->role_id)?$role_wise_permission_result->role_id:'0';
			$current_page_permision_type_id	= isset($role_wise_permission_result->type_id)?$role_wise_permission_result->type_id:'0';

			
			
			$current_page_roll_id=isset($roles[0])?$roles[0]:'0';
			$role_wise_permission_result = RoleWisePermission::where('branch_id',$user_id)->where('role_id',$current_page_roll_id)->where('permission_id',$current_page_permision_id)->where('type_id',$current_page_permision_type_id)->get();
			
			//echo '<pre>';print_r($role_wise_permission_result);exit;
			if(count($role_wise_permission_result)>0){
				return $next($request);
			}else{
				return redirect()->route('auth.permission_denied');
			}
		}else{
			return $next($request);
		} 
    }
}