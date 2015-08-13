<?php
namespace Admin\Model;
class CouponModel extends CommonModel {

    /**
     * 按类型生成优惠券号
     * @param type $type 优惠券类型：1纯数字递增号，由9位数100000000开始；2字母+数字随机13位券号
     */
    public function generateCoupon($type){
        switch ($type) {
            case 1:
                $last_coupon = $this->where("object_id > 0")->order('id desc')->find();
                if($last_coupon){
                    $coupon = $last_coupon['coupon'] + 1;
                }else{
                    $coupon = 100000000;
                }
                break;

            default:
                do{
                    $coupon = rand_string(13, 5);
                    $exist = $this->where(array('coupon'=>$coupon))->find();
                }while($exist);
                break;
        }
        return $coupon;
    }
}