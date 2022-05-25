<?php
namespace App\Http\Controllers\Api\v1;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * 用户在 domeggook平台登录成功后，创建 token 并返回
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function auth(Request $request)
    {
        $datas = array();

        $dmm_id = 'testbuyercom';
        $token = $this->createToken($dmm_id);

        $datas['code'] = '';
        $datas['message'] = '';
        $datas['result'] = $token;

        return response()->json($datas);
    }

    /**
     * refresh token
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function refreshToken(Request $request)
    {
        $datas = array();
        $req = $request->only('refresh_token');

        if (!$req['refresh_token']) {
            $datas['code'] = 'E400101';
            $datas['message'] = 'refresh token 값을 입력하세요';
            return response()->json($datas);
        }

        try {
            //已过期的 token 解码时会进入 catch 里，因此不必重复验证 token 是否过期
            JWT::decode($req['refresh_token'], new Key(cfg('api_key'), cfg('jwt_alg')));

            $exists = DB::table('token')->select(['dmm_id'])->where('refresh_token', $req['refresh_token'])->first();
            if (!$exists) {
                $datas['state'] = 'E400102';
                $datas['msg'] = 'invalid refresh token';
                return response()->json($datas);
            }

            $token = $this->createToken($exists->dmm_id);
            $datas['code'] = '';
            $datas['message'] = '';
            $datas['result'] = $token;
        } catch (SignatureInvalidException $e) {  // 签名不正确
            $datas['code'] = 'E400103';
            $datas['message'] = $e->getMessage();
        } catch (BeforeValidException $e) {  // 签名在某个时间点之后才能用
            $datas['code'] = 'E400104';
            $datas['message'] = $e->getMessage();
        } catch (ExpiredException $e) {  // token过期
            $datas['code'] = 'E400105';
            $datas['message'] = $e->getMessage();
        } catch (Exception $e) {  // 其他错误
            $datas['code'] = 'E400106';
            $datas['message'] = $e->getMessage();
        }

        return response()->json($datas);
    }

    /**
     * 创建 token 保存数据库并返回
     * @param $dmm_id
     * @return array
     */
    private function createToken($dmm_id)
    {
        /*
         * sub Subject - This holds the identifier for the token (defaults to user id)
         * iat Issued At - When the token was issued (unix timestamp)
         * exp Expiry - The token expiry date (unix timestamp)
         * nbf Not Before - The earliest point in time that the token can be used (unix timestamp)
         * iss Issuer - The issuer of the token (defaults to the request url)
         * jti JWT Id - A unique identifier for the token (md5 of the sub and iat claims)
         * aud Audience - The intended audience for the token (not required by default)
         */
        $payload = array(
            'sub' => $dmm_id,
            'iat' => time(),
            'iss' => 'https://domemedb.domeggook.com/',
            'jti' => md5(uniqid()),
        );

        $access_token_payload = $payload;
        $access_token_payload['exp'] = $payload['iat'] + cfg('access_token_lifetime');

        $refresh_token_payload = $payload;
        $refresh_token_payload['exp'] = $payload['iat'] + cfg('refresh_token_lifetime');

        $access_token = JWT::encode($access_token_payload, cfg('api_key'), cfg('jwt_alg'));
        $refresh_token = JWT::encode($refresh_token_payload, cfg('api_key'), cfg('jwt_alg'));

        $exists = DB::table('token')->where('dmm_id', $dmm_id)->first();

        if ($exists) {
            DB::table('token')->where('dmm_id', $dmm_id)->update(['access_token'=>$access_token, 'refresh_token'=>$refresh_token]);
        } else {
            DB::table('token')->insert(['dmm_id'=>$dmm_id, 'access_token'=>$access_token, 'refresh_token'=>$refresh_token]);
        }

        $datas = [
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
            'token_type' => 'bearer'
        ];

        return $datas;
    }
}
