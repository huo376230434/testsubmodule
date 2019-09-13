<?php

namespace App\Lib\Phone;

use App\Lib\Phone\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class PhoneCheckTrait
{



    public function senPhoneCode(Request $request)
    {
        //验证 参数
        try {

            $this->validate($request, [
                'phone_number' => 'required|min:11|max:11'
            ],[],[
                'phone_number' => "手机号"
            ]);
        } catch (ValidationException $e) {

            return [
                'status' => 'error',
                'msg' => $this->getJsonValidateMsg($e)
            ];
        }

//        验证间隔时间
        $yzm = session('YZM');
        $yzm_time = session('YZM_time');
        if($yzm_time+60 >time()){
            //太过于频繁
            return [
                'status' => 'failed',
                'msg' => '两次间隔应该大于60秒'
            ];
        }
//        准备内容

        $number = rand(100000, 999999);
        $c = "您的手机验证码为".$number."，验证码5分钟后失效，请勿向任何单位或个人泄露，谨防诈骗。";
       // $c = str_replace("{#number}",$number,$sms['content']);
        $phone_number = $request->input('phone_number');
        $phone_sms = new ChinaSms();

        $result = $phone_sms->sendPhoneMessage($phone_number,$c);
        $result = [
            'status' => 'success',
        ];
        info('yzmlog'.$number);
        if($result['status'] != 'success'){
            info($request->getClientIp().' '.$phone_number.' '.json_encode($result['msg']));
        }else{
            Session::put('YZM_time',time());
            Session::put('YZM',$number,30);
            Session::put('YZM_phone',$phone_number,30);
        }
        return $result;
    }



    //验证手机验证码是否正确
    protected function checkPhoneCode(Request $request){
        $short_message = trim($request->input('short_message'));
        $old_message = session('YZM');
        if($old_message && $old_message == $short_message){
            return true;
        }else{
            return false;
        }
    }
}
