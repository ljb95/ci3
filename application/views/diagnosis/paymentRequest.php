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
    <input type="hidden"  name="returnUrl" value="<?php echo $base_url ?>/diagnosis/payment_result" >
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

<form id="SendPayForm_mobile" name="SendPayForm_mobile" method="post" accept-charset="euc-kr"> 
    <!-- 리턴받는 가맹점 URL 세팅 -->
    <input type="hidden" name="P_NEXT_URL" value="<?php echo $base_url ?>/diagnosis/payment_result_mo"> 
    <input type="hidden" name="P_NOTI_URL" value="<?php echo $base_url . NOTI_URL_MO ?>"> 
    <!-- 지불수단 선택 (신용카드,계좌이체,가상계좌,휴대폰) -->
    <input type="hidden" name="P_INI_PAYMENT" value="CARD"> 
    <!-- 복합/옵션 파라미터 -->
    <input type="hidden" name="P_RESERVED" value=""> <!-- 에스크로옵션 : useescrow=Y -->
    <input type="hidden" name="P_MID" value=""> <!-- 에스크로테스트 : iniescrow0, 모바일빌링(정기과금)은 별도연동필요 -->
    <input type="hidden" name="P_OID" value="">  
    <input type="hidden" name="P_GOODS" value=""> 
    <input type="hidden" name="P_AMT" value=""> 
    <input type="hidden" name="P_UNAME" value=""> 
    <input type="hidden" name="P_HPP_METHOD" value="">  
    <input type="hidden" name="P_NOTI" value="" >
    <input type="hidden" name="P_VBANK_DT" value="" >
    <input type="hidden" name="P_VBANK_TM" value="" >
</form> 

<form id="SendPayForm_mobile_bill" method="post" accept-charset="UTF-8" action="https://inilite.inicis.com/inibill/inibill_card.jsp"> 
    <!-- 리턴받는 가맹점 URL 세팅 -->
    <input type="hidden" name="mid" value="<?php echo MID0; ?>"> 
    <input type="hidden" name="authtype" value="D"> 
    <!-- 지불수단 선택 (신용카드,계좌이체,가상계좌,휴대폰) -->
    <input type="hidden" name="orderid" value=""> 
    <!-- 복합/옵션 파라미터 -->
    <input type="hidden" name="price" value="">
    <input type="hidden" name="goodname" value="">
    <input type="hidden" name="buyername" value="">  
    <input type="hidden" name="buyeremail" value=""> 
    <input type="hidden" name="buyertel" value=""> 
    <input type="hidden" name="returnurl" value="<?php echo $base_url . '/diagnosis/payment_result_mo_bill'; ?>"> 
    <input type="hidden" name="timestamp" value="">  
    <input type="hidden" name="period" value="">  
    <input type="hidden" name="period_custom" value="" >
    <input type="hidden" name="carduse" value="" >
    <input type="hidden" name="merchantreserved" value="" >
    <input type="hidden" name="hashdata" value="" >
</form> 
<!-- <script language="javascript" type="text/javascript" src="https://stgstdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script> -->
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
        $.ajax({
            type: 'POST',
            url: '/diagnosis/payment_request',
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
                
                
                console.log(data);
                return;
                if (data.data.gopaymethod == 'kakao') {
                        let cid = "<?php echo KAKAO_CID_EASYPAY; ?>";
                        let total_amount = data.data.price;
                        if (data.order_type == 'subscribe') {
                            cid = "<?php echo KAKAO_CID_SUBSCRIP; ?>";
                            total_amount = 0;
                        }
                        $.ajax({
                            url: "/diagnosis/kakao",
                            type: "POST",
                            data: {
                                cid: cid,
                                pay_id: data.data.merchantData,
                                partner_order_id: data.data.oid,
                                partner_user_id: data.data.buyername,
                                item_name: data.data.goodname,
                                quantity: data.quantity,
                                total_amount: total_amount,
                                vat_amount: 0,
                                tax_free_amount: 0,
                                approval_url: "https://dev.cleand.kr/diagnosis/payment_result?merchantData=" + data.data.merchantData,
                                fail_url: "https://dev.cleand.kr/diagnosis/payment_result?merchantData=" + data.data.merchantData,
                                cancel_url: "https://dev.cleand.kr/diagnosis"
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

                        var payType = 'normal';
                        if (data.order_type == 'subscribe') {
                            payType = "recurrent";
                        }

                        var oPay = Naver.Pay.create({
                            "payType": payType,
                            "mode": "development", // development or production
                            "clientId": "f07MEDKDQ478StuME1ea", // clientId
                            "chainId": "dkVsWi9VSEF4N0x"
                        });


                        if (data.order_type != 'subscribe') {
                            oPay.open({
                                "merchantUserKey": data.data.buyername,
                                "merchantPayKey": data.data.oid,
                                "productName": data.data.goodname,
                                "totalPayAmount": data.data.price,
                                "taxScopeAmount": data.data.price,
                                "taxExScopeAmount": "0",
                                "returnUrl": "https://dev.cleand.kr/diagnosis/payment_result?merchantData=" + data.data.merchantData
                            });
                        } else {
                            oPay.open({
                                "actionType": "NEW",
                                "productCode": data.data.oid,
                                "productName": data.data.goodname,
                                "totalPayAmount": data.data.price,
                                "returnUrl": "https://dev.cleand.kr/diagnosis/payment_result?merchantData=" + data.data.merchantData
                            });

                        }

                    } else if (isMobile()) {
                        if ($('input[name=order_type]').val() == 'starter') {

                            myform = document.SendPayForm_mobile;
                            myform.action = "https://mobile.inicis.com/smart/payment/";
                            myform.target = "_self";
                            myform.submit();
                        } else {
                            $('#SendPayForm_mobile_bill').submit();
                        }
                    } else {
                        INIStdPay.pay('SendPayForm_web');
                        
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