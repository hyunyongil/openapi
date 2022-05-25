<?php
namespace App\Http\Middleware;

use App\Http\Requests\Request;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\DB;

class VerifyAccessToken
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
	    $datas = array();
	    $req = $request->only('token');

	    if (!$req || !$req['token']) {
	        $datas['code'] = 'E400001';
	        $datas['message'] = 'invalid access token';
	        return response()->json($datas);
        }

	    $token = $req['token'];

        try {
            //已过期的 token 解码时会进入 catch 里，因此不必重复验证 token 是否过期
            JWT::decode($token, new Key(cfg('api_key'), cfg('jwt_alg')));

            $exists = DB::table('token')->select(['dmm_id'])->where('access_token', $token)->first();
            if (!$exists) {
                $datas['state'] = 'E400001';
                $datas['msg'] = 'invalid access token';
                return response()->json($datas);
            }
        } catch (SignatureInvalidException $e) {  // 签名不正确
            $datas['code'] = 'E400002';
            $datas['message'] = $e->getMessage();
            return response()->json($datas);
        } catch (BeforeValidException $e) {  // 签名在某个时间点之后才能用
            $datas['code'] = 'E400003';
            $datas['message'] = $e->getMessage();
            return response()->json($datas);
        } catch (ExpiredException $e) {  // token过期
            $datas['code'] = 'E400004';
            $datas['message'] = $e->getMessage();
            return response()->json($datas);
        } catch (Exception $e) {  // 其他错误
            $datas['code'] = 'E400005';
            $datas['message'] = $e->getMessage();
            return response()->json($datas);
        }

		return $next($request);
	}
}
