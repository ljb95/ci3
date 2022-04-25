<form id="SendPayForm_web" name="" method="POST">
    <!-- 필수 -->
    <input type="hidden"  name="version" value="1.0" >
    <input type="hidden"  name="mid" value="" >
    <input type="hidden"  name="goodname" value="" >
    <input type="hidden"  name="oid" value="" >
    <input type="hidden"  name="price" value="" >
    <input type="hidden"  name="currency" value="WON" >
    <input type="hidden"  name="buyername" value="" >
    <input type="hidden"  name="buyertel" value="" >
    <input type="hidden"  name="buyeremail" value="" >
    <input type="hidden"  name="timestamp" value="" >
    <input type="hidden"  name="signature" value="" >
    <input type="hidden"  name="returnUrl" value="<?php echo $base_url ?>/recommend/payment_result" >
    <input type="hidden"  name="mKey" value="" >
    <!-- 기본 옵션 -->
    <input type="hidden" name="gopaymethod" value="card" >
    <input type="hidden" name="acceptmethod" value="billauth(card)" >
    <input type="hidden" name="payViewType" value="overlay" >
    <input type="hidden" name="offerPeriod" value="<?php echo date('Ymd') . date('Ymd', strtotime('+1 year')); ?>" />
    <input type="hidden" name="nointerest" value="" >
    <input type="hidden" name="quotabase" value="" >
    <input type="hidden" name="merchantData" value="" >
</form>

<script type="text/javascript" src="https://nsp.pay.naver.com/sdk/js/naverpay.min.js"></script>
<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<script>
    function fnCheckUser(frmObj) {
        $.ajax({
            type: 'POST',
            url: '/payment/ajaxMemberCheck',
            data: $(frmObj).serialize(),
            dataType: "json",
            success: function (data) {
                if (data.status == 'succ') {
                    fnPaymentRequest(frmObj);
                } else {
                    showAlert('error', data.msg);
                    $('input[name=' + data.target + ']').focus();
                }
            },
            error: function (data) {
                alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
            }
        });
    }

    function fnPaymentRequest(frmObj) {
        $('input[name=device_type]').val((isMobile() ? 'MO' : 'PC'));
        
        if(!$('input[name=terms]').is(':checked')){
                alert('이용약관 동의가 필요합니다.');
                $('input[name=terms]').focus();
                return false;
            }
            if(!$('input[name=private]').is(':checked')){
                alert('개인정보 수집 및 이용에 동의가 필요합니다.');
                $('input[name=private]').focus();
                return false;
            }
            if(!$('input[name=overage]').is(':checked')){
                alert('만 14세 이상만 결제가 가능합니다');
                $('input[name=overage]').focus();
                return false;
            }
        
        $.ajax({
            type: 'POST',
            url: '/recommend/payment_request',
            data: $(frmObj).serialize(),
            dataType: "json",
            success: function (data) {
                if (data.status == 'succ') {
                    $('#SendPayForm_web input[name=mid]').val(data.data.mid);
                    $('#SendPayForm_web input[name=goodname]').val(data.data.goodname);
                    $('#SendPayForm_web input[name=oid]').val(data.data.oid);
                    $('#SendPayForm_web input[name=price]').val(data.data.price);
                    $('#SendPayForm_web input[name=buyername]').val(data.data.buyername);
                    $('#SendPayForm_web input[name=buyertel]').val(data.data.buyertel);
                    $('#SendPayForm_web input[name=buyeremail]').val(data.data.buyeremail);
                    $('#SendPayForm_web input[name=timestamp]').val(data.data.timestamp);
                    $('#SendPayForm_web input[name=signature]').val(data.data.signature);
                    $('#SendPayForm_web input[name=mKey]').val(data.data.mKey);
                    $('#SendPayForm_web input[name=gopaymethod]').val(data.data.gopaymethod);
                    $('#SendPayForm_web input[name=acceptmethod]').val(data.data.acceptmethod);
                    $('#SendPayForm_web input[name=merchantData]').val(data.data.merchantData);

                    $('#SendPayForm_mobile input[name=P_INI_PAYMENT]').val(data.data.gopaymethod)
                    $('#SendPayForm_mobile input[name=P_MID]').val(data.data.mid)
                    $('#SendPayForm_mobile input[name=P_OID]').val(data.data.oid)
                    $('#SendPayForm_mobile input[name=P_GOODS]').val(data.data.goodname)
                    $('#SendPayForm_mobile input[name=P_AMT]').val(data.data.price)
                    $('#SendPayForm_mobile input[name=P_UNAME]').val(data.data.buyername)
                    $('#SendPayForm_mobile input[name=P_NOTI]').val(data.data.merchantData)
                    $('#SendPayForm_mobile input[name=P_RESERVED]').val(data.data.acceptmethod)
                    $('#SendPayForm_mobile input[name=P_VBANK_DT]').val(data.data.bank_dt)
                    $('#SendPayForm_mobile input[name=P_VBANK_TM]').val(data.data.bank_tm)

                    $('#SendPayForm_mobile_bill input[name=mid]').val(data.data.mid);
                    $('#SendPayForm_mobile_bill input[name=orderid]').val(data.data.oid);
                    $('#SendPayForm_mobile_bill input[name=price]').val(data.data.price);
                    $('#SendPayForm_mobile_bill input[name=goodname]').val(data.data.goodname);
                    $('#SendPayForm_mobile_bill input[name=buyername]').val(data.data.buyername);
                    $('#SendPayForm_mobile_bill input[name=buyertel]').val(data.data.buyertel);
                    $('#SendPayForm_mobile_bill input[name=buyeremail]').val(data.data.buyeremail);
                    $('#SendPayForm_mobile_bill input[name=timestamp]').val(data.data.timestamp2);
                    $('#SendPayForm_mobile_bill input[name=merchantreserved]').val(data.data.merchantData);
                    $('#SendPayForm_mobile_bill input[name=hashdata]').val(data.data.hashdata);

                    if (data.order_mem_type == 'guest') {
                        $.ajax({
                            method: 'POST',
                            async: false,
                            url: '/member/login_p',
                            data: {
                                mem_userid: $('input[name=mem_email]').val().trim(),
                                mem_password: $('input[name=mem_password]').val().trim()
                            }
                        });
                    }


                    if (data.data.gopaymethod == 'kakao') {

                        $.ajax({
                            url: "/diagnosis/kakao",
                            type: "POST",
                            data: {
                                cid: "<?php echo KAKAO_CID_SUBSCRIP; ?>",
                                pay_id: data.data.merchantData,
                                partner_order_id: data.data.oid,
                                partner_user_id: data.mem_id,
                                item_name: data.data.goodname,
                                quantity: data.quantity,
                                total_amount: 0,
                                vat_amount: 0,
                                tax_free_amount: 0,
                                approval_url: "https://dev.cleand.kr/recommend/payment_result?den_id="+data.den_id+"&merchantData=" + data.data.merchantData,
                                fail_url: "https://dev.cleand.kr/recommend/payment_result?den_id="+data.den_id+"&merchantData=" + data.data.merchantData,
                                cancel_url: "https://dev.cleand.kr/recommend/payment_result?den_id="+data.den_id+"&merchantData=" + data.data.merchantData,
                            },
                            success: function (data) {
                                const res = JSON.parse(data);
                                if (isMobile()) {
                                    location.href = res.next_redirect_mobile_url;
                                } else {
                                    location.href = res.next_redirect_pc_url;
                                }
                            }
                        });
                    } else if (data.data.gopaymethod == 'naver') {


                        var oPay = Naver.Pay.create({
                            "payType": 'recurrent',
                            "mode": "<?php echo NAVERPAY_MODE; ?>", // development or production
                            "clientId": "<?php echo NAVERPAY_CLIENTID; ?>", // clientId
                            "chainId": "<?php echo NAVERPAY_CHAINID_BILL; ?>"
                        });

                        oPay.open({
                            "actionType": "NEW",
                            "productCode": data.data.oid,
                            "productName": data.data.goodname,
                            "totalPayAmount": Number(data.data.price),
                            "returnUrl": "https://dev.cleand.kr/recommend/payment_result?den_id="+data.den_id+"&merchantData=" + data.data.merchantData
                        });
                    } else {


                        let cardNumber = $('input[name=card_number]').val();
                        let cardExpire = $('input[name=card_vaild]').val();
                        let regNo = $('input[name=card_birth]').val();
                        let cardPw = $('input[name=card_pwd]').val();

                        $.ajax({
                            url: "/payment/inicis",
                            type: "POST",
                            data: {
                                pid: data.data.merchantData,
                                cardNumber: cardNumber,
                                cardExpire: cardExpire,
                                regNo: regNo,
                                cardPw: cardPw
                            },
                            success: function () {
                                location.href = "https://dev.cleand.kr/recommend/payment_result?den_id="+data.den_id+"&merchantData=" + data.data.merchantData
                            }
                        });

                    }
                } else {
                    showAlert('error', data.msg);
                    $('input[name=' + data.target + ']').focus();
                }
            },
            error: function (data) {
                alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
            }
        });
    }

</script>