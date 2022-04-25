<div class="subscrip">
    <div class="t1">슬기로운 양치생활 - <br class="mobile">클린디 정기구독</div>
    <div class="t2">정기구독으로 클린디 서비스를 쉽고 편하게! <br class="mobile">배송비에 진단 이벤트 1,000P <br class="mobile">추가 할인까지! <br>슬기로운 양치생활의 기회를 <br class="mobile">놓치지 마세요! </div>
    <div class="btns">
        <a href="/cart/change_cart" class="btn btn-type1 btn-m">정기구독 혜택받기</a>
    </div>
</div>
<div class="btn-box-common1" id="subscribe_1">
    <a href="/product/product_list" class="btn btn-type2">제품 추가</a>
    <a href="javascript:void(0);" class="btn btn-type1" id="btn_step1">주문하기</a>
</div>
<div class="col-box" id="subscribe_3" style="display:none">
    <div>
        <input type="hidden" name="mem_phone" value="<?php echo $user['mem_phone']; ?>" >
        <input type="hidden" name="mem_name" value="<?php echo $user['mem_username']; ?>">
        <input type="hidden" name="mem_email" value="<?php echo $user['mem_email']; ?>" >
        <input type="hidden" name="mem_password" value="">
        <?php
        if (isset($delivery)) {
            ?>
            <div class="default">
                <h4>배송지</h4>
                <div>
                    <a href="#" class="btn-under" data-toggle="modal" data-target="#modalBaesongList">다른 배송지 선택</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="#" class="btn-under" data-toggle="modal" data-target="#modalBaesong">신규 배송지 등록</a>
                </div>
            </div>
            <div class="my-addr">
                <p id="show_recipient"><?php echo $delivery['recipient_name']; ?></p>
                <p id="show_recipient_tel"><?php echo $delivery['recipient_phone']; ?></p>
                <p class="gray9" id="show_recipient_addr"><?php echo '(' . $delivery['zipcode'] . ')' . $delivery['road_addr'] . ' ' . $delivery['detail_addr']; ?></p>
                <p class="gray9" id="show_recipient_memo"><?php echo $delivery['memo']; ?></p>
                <input type="hidden" name="recipient_name" value="<?php echo $delivery['recipient_name']; ?>">
                <input type="hidden" name="recipient_phone" value="<?php echo $delivery['recipient_phone']; ?>" >
                <input type="hidden" name="zipcode" value="<?php echo $delivery['zipcode']; ?>"  /> 
                <input type="hidden" name="road_addr" value="<?php echo $delivery['road_addr']; ?>"  />
                <input type="hidden" name="jibun_addr" value="<?php echo $delivery['jibun_addr']; ?>">
                <input type="hidden" name="addr2" value="<?php echo $delivery['detail_addr']; ?>" />
                <input type="hidden" name="memo" value="<?php echo $delivery['memo']; ?>" />
                <input type="hidden" id="mde_id" value="<?php echo $delivery['mde_id']; ?>" />
            </div>
            <?php
        } else {
            ?>
            <!-- 배송지 선택 -->
            <div class="default">
                <h4>배송지</h4>
            </div>
            <div class="refund-addr">
                <div class="inp-box1">
                    <input type="text" class="inp1 block" placeholder="수령인" name="recipient_name" value="">
                </div>
                <div class="inp-box1">
                    <input type="text" class="inp1 block" placeholder="수령인연락처" name="recipient_phone" value="" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="11">
                </div>
                <div class="inp-box2 btw addres">
                    <input type="text" class="inp1 " name="zipcode" value="" placeholder="우편번호" readonly /> 
                    <a href="#" onclick="javascript:execDaumPostcode2($('input[name=zipcode]'), $('input[name=road_addr]'), $('input[name=jibun_addr]'), fnCheckDelivery); return false;" class="btn-under">주소찾기</a>
                </div>
                <div class="inp-box1">
                    <input type="text" class="inp1 block" name="road_addr" value="" placeholder="기본주소" readonly />
                    <input type="hidden" name="jibun_addr" value="">
                </div>
                <div class="inp-box1">
                    <input type="text" class="inp1 block" name="addr2" value="" placeholder="상세주소" />
                </div>
                <div class="inp-box1">
                    <input type="text" class="inp1 block" name="memo" value="" placeholder="배송요청사항" />
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <div>
        <div class="payment-kind">
            <h5>결제수단 선택</h5>
            <div class="list mb40">
                <div class="mb10"><button class="btn2 pay2" a="direct"><span>실시간계좌이체</span></button></div>
                <div class="mb10"><button class="btn2 pay3" a="card"><span>신용/체크카드</span></button></div>
                <div class="mb10"><button class="btn2 pay4" a="kakao"><span>카카오페이</span></button></div>
                <div class="mb10"><button class="btn2 pay5" a="naver"><span>네이버페이</span></button></div>
                <div class="mb10"><button class="btn2 pay6" a="onlyssp"><span>삼성페이</span></button></div>
            </div>
        </div>

        <div class="agree-wrap">
            <div class="mb20"><label class="label-checkbox"><input type="checkbox" class="checkbox" id="AllCheck"><em></em><span><strong>전체동의, 선택항목도 포함합니다</strong></span></label></div>
            <div class="in">
                <div class="mb20"><label class="label-checkbox"><input type="checkbox" name="terms" class="checkbox"><em></em><span>(필수) 이용약관에 모두 동의 합니다. </span></label></div>
                <div class="mb20"><label class="label-checkbox"><input type="checkbox" name="private" class="checkbox"><em></em><span> (필수) 개인정보 수집 및 이용에 동의 합니다.  </span></label></div>
                <div class="view">
                    <dl>
                        <dt>서비스 이용약관</dt>
                        <dd><a href="#">내용보기</a></dd>
                    </dl>
                    <dl>
                        <dt>개인정보 수집 및 이용 동의</dt>
                        <dd><a href="#">내용보기</a></dd>
                    </dl>
                </div>
                <div class="mb20"><label class="label-checkbox"><input type="checkbox" name="overage" class="checkbox"><em></em><span>(필수) 본인은 만 14세 이상입니다. </span></label></div>
                <div class="mb20"><label class="label-checkbox"><input type="checkbox" name="event" class="checkbox"><em></em><span>(선택) 정보/이벤트 메일, SMS수신에 동의합니다.  </span></label></div>
                <div class="msg"><p>* SMS, 이메일 수신에  동의해주시면 클린디 맞춤 알림을 받아 보실 수 있습니다.</p> </div>
            </div>
        </div>

        <button class="btn btn-type1 block" id="btn_step3">주문하기</button>
    </div>
</div>

<?php $this->load->view('common/deliveryPopup'); ?>
<script>
    $(document).ready(function (e) {
        $('#AllCheck').on('change', function () { 
             var checked = $(this).prop('checked');
             $('input[name="terms"]').prop('checked', checked);
             $('input[name="private"]').prop('checked', checked);
             $('input[name="overage"]').prop('checked', checked);
             $('input[name="event"]').prop('checked', checked);
        });
        $('#btn_step1').on('click', function () {
            $('.btn-plus').hide();
            $('.btn-minus').hide();
            $('#subscribe_1').hide();
            $('#subscribe_3').show();
            $('.tabs1').hide();
        });

        $('#btn_step3').on('click', function () {
<?php
if (empty($delivery))
    echo 'fnAddDeliveryAddress();';
else
    echo 'fnPaymentRequest($("#frmCart"));';
?>
        });

        $('.payment-kind .pay3').on('click', function () {
            $('.pay-card-info').addClass('show');
        });
        $('.payment-kind .pay3').parent('div').siblings().find('.btn2').on('click', function () {
            $('.pay-card-info').removeClass('show');
        });
    });

    function fnSetDelivery(data)
    {
        $('input[name=recipient_name]').val(data.recipient_name);
        $('input[name=recipient_phone]').val(data.recipient_phone);
        $('input[name=zipcode]').val(data.zipcode);
        $('input[name=road_addr]').val(data.road_addr);
        $('input[name=jibun_addr]').val(data.jibun_addr);
        $('input[name=addr2]').val(data.detail_addr);
        $('input[name=memo]').val(data.memo);

        $('#show_recipient').html(data.recipient_name);
        $('#show_recipient_tel').html(data.recipient_phone);
        $('#show_recipient_addr').html('(' + data.zipcode + ')' + data.road_addr + ' ' + data.detail_addr);
        $('#show_recipient_memo').html(data.memo);

        fnCheckDelivery();
    }

    function fnAddDeliveryAddress()
    {
        $.ajax({
            type: 'POST',
            url: '/cart/ajaxAddAddress',
            data: {mem_id: '<?php echo $user['mem_id']; ?>',
                recipient_name: $('input[name=recipient_name]').val(),
                recipient_phone: $('input[name=recipient_phone]').val(),
                zipcode: $('input[name=zipcode]').val(),
                road_addr: $('input[name=road_addr]').val(),
                jibun_addr: $('input[name=jibun_addr]').val(),
                detail_addr: $('input[name=addr2]').val(),
                memo: $('input[name=memo]').val(),
                is_default: 'y'},
            dataType: "json",
            success: function (data) {
                if (data.status == 'succ') {
                    fnPaymentRequest($("#frmCart"));
                } else {
                    showAlert('error', data.msg);
                }
            },
            error: function (data) {
                alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
            }
        });
    }
</script>