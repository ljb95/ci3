<style>
    .subscribe_alarm {
        display: block;
        font-size: 22px;
        font-weight: 700;
        padding:10px 0;
    }
    #subscribe_dis {
        padding-right:170px;
    }
    @media (max-width: 1023px) {
        .subscribe_alarm {
            font-size: 16px;
        }
        #subscribe_dis {
            padding-right:0;
        }
    }
</style>
<div class="sub-head product pc">
    <div class="inner">
        <h2 class="h2">장바구니</h2>

    </div>
</div>

<?php
if (count($cart) > 0) {
    ?>	
    <form id="frmCart" onSubmit="return false;">
        <div class="inner">
            <div class="tabs1">
                <?php
                if ($cart_type == 'subscribe') {
                    ?>
                    <a href="javascript:void(0);" class="active">정기구독</a>
                    <a href="/cart/cart_list?type=item">1회구매</a>
                    <?php
                } else {
                    ?>
                    <a href="/cart/cart_list?type=subscribe">정기구독</a>
                    <a href="javascript:void(0);" class="active">1회구매</a>
                    <?php
                }
                ?>
            </div>
            <div class="table1 cart-type mb30 pc">
                <table>
                    <colgroup>
                        <col style="width:170px">
                        <col style="">
                        <col style="width:140px">
                        <col style="width:180px">
                        <col style="width:180px">
                        <col style="width:100px">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>상품정보</th>
                            <th>상품가격</th>
                            <th>수량</th>
                            <?php
                            if ($cart_type === 'subscribe') {
                                echo '<th>정기구독할인</th>';
                            }
                            ?>
                            <th>판매가</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_price = 0;
                        $total_dis = 0;
                        $product_price = 0;
                        $free_val = ($cart_type == 'subscribe' ? 15000 : 20000);
                        if ($cart_type === 'subscribe')
                            $delivery_price = 0;
                        foreach ($cart as $row) {
                            $unit_price = $row['is_sale'] === 'y' ? $row['cit_sale_price'] : $row['cit_price'];
                            $dis = 0;
                            if ($cart_type === 'subscribe') {
                                $unit_price = $row['cit_subscribe_price'];
                                $dis = ($row['cit_sale_price'] - $row['cit_subscribe_price']) * $row['qty'];
                            }
                            ?>
                            <tr>
                                <td><div class="prd-thum"><img src="<?php echo CDN_URL . $row['cit_file_1']; ?>"></div></td>
                                <td>
                                    <input type="hidden" name="cct_id[]" value="<?php echo $row['cct_id']; ?>" />
                                    <input type="hidden" name="cit_id[]" value="<?php echo $row['cit_id']; ?>" />
                                    <input type="hidden" name="cit_name[]" value="<?php echo $row['cit_name']; ?>" />
                                    <input type="hidden" name="product_code[]" value="<?php echo $row['product_code']; ?>" />
                                    <input type="hidden" name="barcode_no[]" value="<?php echo $row['barcode_no']; ?>" />
                                    <input type="hidden" name="cde_id[]" value="<?php echo $row['cde_id']; ?>" />
                                    <input type="hidden" name="cde_title[]" value="<?php echo $row['cde_title']; ?>" />
                                    <input type="hidden" name="cit_price[]" value="<?php echo $row['cit_price']; ?>" />
                                    <input type="hidden" name="cit_sale_price[]" value="<?php echo $row['cit_sale_price']; ?>" />
                                    <input type="hidden" name="cit_subscribe_price[]" value="<?php echo $row['cit_subscribe_price']; ?>" />
                                    <input type="hidden" name="unit_price[]" value="<?php echo $unit_price; ?>" />
                                    <div class="prd-info">
                                        <strong><?php echo $row['cit_name']; ?><?php echo $cart_type == 'subscribe' ? '<span>구독상품</span>' : ''; ?></strong>
                                        <div class="opt"><?php echo $row['cde_title']; ?></div>
                                    </div>
                                </td>
                                <td><?php echo number_format($unit_price); ?> 원</td>
                                <td>
                                    <div class="length">
                                        <button class="btn-minus" onclick="javascript:fnChangeQty('<?php echo $row['cct_id']; ?>', -1);"></button>
                                        <input type="text" class="inp" name="qty[]" id="qty_<?php echo $row['cct_id']; ?>" value="<?php echo $row['qty']; ?>" readonly />
                                        <button class="btn-plus" onclick="javascript:fnChangeQty('<?php echo $row['cct_id']; ?>', 1);"></button>
                                    </div>

                                </td>
                                <?php
                                if ($cart_type === 'subscribe') {
                                    echo '<td>' . number_format($dis) . '원</td>';
                                }
                                ?>
                                <td><strong><?php echo number_format($unit_price * $row['qty']); ?>원</strong></td>
                                <td><button class="btn-common-del" onclick="javascript:fnDeleteItem('<?php echo $row['cct_id']; ?>');"><i class="xi-close-thin"></i></button></td>
                            </tr>
                            <?php
                            $total_price += $unit_price * $row['qty'];
                            $product_price += $unit_price * $row['qty'];
                            $total_dis += $dis;
                        }
                        if ($total_price >= $free_val) {
                            $delivery_price = 0;
                        }
                        $total_price += $delivery_price;
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="cart-list mobile">
                <ul>
                    <?php
                    foreach ($cart as $row) {
                        $unit_price = $row['cit_sale_price'];
                        $dis = 0;
                        if ($cart_type === 'subscribe') {
                            $unit_price = $row['cit_subscribe_price'];
                            $dis = $row['cit_sale_price'] - $row['cit_subscribe_price'];
                        }
                        ?>
                        <li>
                            <div class="img"><img src="<?php echo CDN_URL . $row['cit_file_1']; ?>"></div>
                            <div class="info">
                                <dl>
                                    <dt><strong><?php echo $row['cit_name']; ?></strong><?php echo $cart_type == 'subscribe' ? '<span>구독상품</span>' : ''; ?></dt>
                                    <dd>
                                        <div class="opt"><?php echo $row['cde_title']; ?></div>
                                        <div class="price"><?php echo number_format($unit_price); ?> 원</div>
                                    </dd>
                                </dl>
                                <div class="length">
                                    <button class="btn-minus" onclick="javascript:fnChangeQty('<?php echo $row['cct_id']; ?>', -1);"></button>
                                    <input type="text" class="inp" name="mo_qty[]" value="<?php echo $row['qty']; ?>" readonly />
                                    <button class="btn-plus" onclick="javascript:fnChangeQty('<?php echo $row['cct_id']; ?>', 1);"></button>
                                </div>
                                <button class="btn-common-del" onclick="javascript:fnDeleteItem('<?php echo $row['cct_id']; ?>');"><i class="xi-close-thin"></i></button>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                </ul>			
            </div>


            <div class="cart-step">
                <!-- 1. 구독 -->
                <div class="step3">
                    <div class="cart-price">
                        <div class="orther">
                            <?php
                            if (!empty($user) && $cart_type !== 'subscribe') {
                                ?>
                                <dl>
                                    <dt>할인쿠폰선택</dt>
                                    <dd style="width:60%">
                                        <select class="select" id="coupon_list" style="width:100%; border-radius:0">
                                            <?php
                                            if (empty($coupon)) {
                                                echo '<option value="">사용가능한 쿠폰이 없습니다</option>';
                                            } else {
                                                echo '<option value="" disabled selected>사용가능 쿠폰' . count($coupon) . '장/보유' . $couponcnt . '장</option>';
                                                echo '<option value="">선택안함</option>';
                                                foreach ($coupon as $row) {
                                                    echo '<option value="' . $row['ccl_id'] . '" a="' . $row['price_type'] . '" b="' . $row['ccp_val'] . '" c="' . $row['ccp_type'] . '" d="' . $row['product_id'] . '">' . $row['ccp_name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </dd>
                                </dl>
                                <?php
                            }
                            ?>
                            <?php
                            if ($cart_type !== 'subscribe') {
                                ?>
                                <dl>
                                    <dt>배송비</dt>
                                    <dd id="show_delivery_price"><?php echo number_format($delivery_price); ?>원</dd>
                                </dl>
                                <?php
                            }
                            if ($cart_type == 'subscribe') {
                                ?>
                                <dl>
                                    <dt>구독할인</dt>
                                    <dd id="subscribe_dis">-<?php echo number_format($total_dis); ?>원</dd>
                                </dl>
                                <?php
                            }

                            if (!empty($user) && $cart_type !== 'subscribe') {
                                ?>
                                <dl>
                                    <dt>포인트 <a href="#" class="btn-under"  data-toggle="modal" data-target="#modalUsepoint">적용</a></dt>
                                    <dd id="show_use_point">0P</dd>
                                </dl>
                                <dl>
                                    <dt>쿠폰 사용</dt>
                                    <dd id="show_use_coupon">0원</dd>
                                </dl>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="total">
                            <dl>
                                <dt>총 결제금액</dt>
                                <dd id="show_total_price"><?php echo number_format($total_price); ?>원</dd>
                            </dl>
                        </div>
                    </div>

                    <?php
                    if ($cart_type == 'subscribe') {
                        $data['total_price'] = $total_price;
                        if (isset($user)) {
                            $this->load->view('cart/cart_member_subscribe', $data);
                        } else {
                            $this->load->view('cart/cart_guest_subscribe', $data);
                        }
                    } else {
                        if (isset($user)) {
                            $this->load->view('cart/cart_member_item');
                        } else {
                            $this->load->view('cart/cart_guest_item');
                        }
                    }
                    ?>
                </div>
                <!-- 1. // 구독 -->

            </div>

        </div>
        <input type="hidden" name="use_point" value="0" />
        <input type="hidden" name="use_coupon" value="0" />
        <input type="hidden" name="use_coupon_id" value="" />
        <input type="hidden" name="use_coupon_type" value="" />
        <input type="hidden" name="set_delivery" value="<?php echo $cart_type == 'subscribe' ? 0 : $delivery_price; ?>" />
        <input type="hidden" name="cart_type" value="<?php echo $cart_type; ?>" />
        <input type="hidden" name="order_mem_type" value="<?php echo isset($user) ? 'member' : 'guest'; ?>" />
        <input type="hidden" name="mem_id" value="<?php echo isset($user) ? $user['mem_id'] : '0'; ?>" />
        <input type="hidden" name="product_price" value="<?php echo $product_price; ?>" />
        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>" />
        <input type="hidden" name="device_type" value="" />
        <input type="hidden" id="org_total_price" value="<?php echo $total_price; ?>" />
        <input type="hidden" name="pay_type" value="" />
    </form>
    <?php
    $this->load->view('common/paymentRequest');
    $this->load->view('common/usepointPopup');
} else {
    ?>
    <div class="inner">
        <div class="tabs1">
    <?php
    if ($cart_type == 'subscribe') {
        ?>
                <a href="javascript:void(0);" class="active">정기구독</a>
                <a href="/cart/cart_list?type=item">1회구매</a>
                <?php
            } else {
                ?>
                <a href="/cart/cart_list?type=subscribe">정기구독</a>
                <a href="javascript:void(0);" class="active">1회구매</a>
                <?php
            }
            ?>
        </div>
        <div class="none-cart">
            <strong>장바구니에 담긴 상품이 없습니다.</strong>
            <p><a href="/diagnosis" style="text-decoration:underline !important; text-underline-position: under;">몇 가지 건강설문을 통해 나만의 칫솔</a>을 찾아보세요.</p>
        </div>

        <div class="btn-box-common1">
            <a href="/my/order/order_list" class="btn btn-type2">주문 상세보기</a>
            <a href="/product/product_list" class="btn btn-type1">쇼핑 계속 하기</a>
        </div>

    </div>
    <?php
}
?>
<!-- // inner -->

<script>
    $(document).ready(function (e) {
        $('.payment-kind .list button.btn2').on('click', function () {
            $('.payment-kind .list button.btn2').removeClass('active');
            $(this).addClass('active');
            $('input[name=pay_type]').val($(this).attr('a'));
        });
    });
    function fnChangeQty(seq, val)
    {
        if (val < 0 && $("#qty_" + seq).val() <= 1) {
            return;
        }
        $.ajax({
            type: 'POST',
            url: '/cart/ajaxChangeQty',
            data: {seq: seq, qty: val},
            dataType: "json",
            success: function (data) {
                if (data.status == 'succ') {
                    location.reload();
                } else {
                    showAlert('error', data.msg);
                }
            },
            error: function (data) {
                alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
            }
        });
    }

    function fnDeleteItem(seq)
    {
        var msg = {msg: '삭제하시겠습니까?', confirm: '삭제', cancel: '취소'}
        showConfirm(msg, function () {
            $.ajax({
                type: 'POST',
                url: '/cart/ajaxDeleteCartItem',
                data: {seq: seq},
                dataType: "json",
                success: function (data) {
                    if (data.status == 'succ') {
                        location.reload();
                    } else {
                        showAlert('error', data.msg);
                    }
                },
                error: function (data) {
                    alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
                }
            });
        });
    }

    var timer1 = null;
    function fnSendAuth() {
        clearInterval(timer1);
        cnt = 180;

        $.ajax({
            url: "/common/ajaxSendAuth",
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {'mem_phone': $('input[name=mem_phone]').val()},
            success: function (res, textStatus, jqXHR) {
                if (res.status == 'succ') {
                    $('#sms_auth_wrap').show();
                    $('#auth_btn1').html('다시요청');
                    timer1 = setInterval(function () { //실행할 스크립트 
                        cnt--;

                        var div = parseInt(cnt / 60);
                        var mod = cnt % 60;

                        $('#auth_timer').html(div + ':' + (mod < 10 ? '0' : '') + mod);
                        if (cnt <= 0) {
                            clearInterval(timer1);
                        }
                    }, 1000);
                } else {
                    showAlert('error', res.msg);
                }
            },
            error: function (request, status, error) {
                alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
            }
        });
    }

    function fnCheckAuth() {
        $.ajax({
            url: "/common/ajaxCheckAuth",
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {'mem_phone': $('input[name=mem_phone]').val(),
                'auth_number': $('input[name=auth_number]').val()},
            success: function (res, textStatus, jqXHR) {
                if (res.status == 'succ') {
                    $('input[name=auth]').val($('input[name=mem_phone]').val());
                    clearInterval(timer1);
                    $('#auth_timer').hide();
                    $('#auth_btn2').hide();
                    showAlert('success', res.msg);
                } else {
                    showAlert('error', res.msg);
                }
            },
            error: function (request, status, error) {
                alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
            }
        });
    }

    function fnCalcTotalPrice() {
        var total_price = parseInt($('input[name=product_price]').val());
        var delivery_price = parseInt($('input[name=set_delivery]').val());

        var point = parseInt($('input[name=use_point]').val());
        var coupon = parseInt($('input[name=use_coupon]').val());
        if ($('input[name=use_coupon_type]').val() == '3') {
            coupon = 0;
        }

        var set_price = total_price + delivery_price - point - coupon;
        $('#show_total_price').html(commify(set_price) + '원');
        $('input[name=total_price]').val(set_price);
    }

    $(document).ready(function (e) {
        $('input[name=mem_phone]').on('keyup', function () {
            clearInterval(timer1);
            $('input[name=auth]').val('');
            $('input[name=auth_number]').val('');
            $('#sms_auth_wrap').hide();
            $('#auth_btn1').html('인증');
            $('#auth_timer').show();
            $('#auth_btn2').show();
        });

        $('#coupon_list').on('change', function () {
            if ($(this).val() == '') {
                $('input[name=use_coupon]').val('0');
                $('input[name=use_coupon_id]').val('');
                $('input[name=use_coupon_type]').val('');
                $('#show_use_coupon').html('0원');
            } else {
                var ccp_type = $('#coupon_list option:selected').attr('c');
                var product = $('#coupon_list option:selected').attr('d').split(',');
                var price_type = $('#coupon_list option:selected').attr('a');
                var price_val = $('#coupon_list option:selected').attr('b');
                var val = '0';
                if (price_type == '1') {
                    if (ccp_type == '1') {
                        var sum = 0;
                        $('input[name="cit_id[]"]').each(function (index, element) {
                            var bExists = false;
                            for (var i = 0; i < product.length; i++) {
                                if (product[i] == $(this).val()) {
                                    bExists = true;
                                    break;
                                }
                            }
                            if (bExists) {
                                sum += parseInt($('input[name="unit_price[]"]').eq(index).val()) * parseInt($('input[name="qty[]"]').eq(index).val());
                            }
                        });
                        console.log(sum);
                        val = Math.round(sum * price_val / 100);
                    } else {
                        var product_price = parseInt($('input[name=product_price]').val());
                        val = Math.round(product_price * price_val / 100);
                    }
                } else {
                    val = price_val;
                }
                $('input[name=use_coupon_id]').val($(this).val());
                $('input[name=use_coupon_type]').val(ccp_type);
                $('input[name=use_coupon]').val(val);
                if (ccp_type == '3') {
                    $('#show_use_coupon').html('포인트지급 ' + val + '원');
                } else {
                    $('#show_use_coupon').html('-' + commify(val) + '원');
                }
            }
            fnCalcTotalPrice();
        });
    });

    function fnCheckDelivery()
    {
        var delivery_price = parseInt($('input[name=set_delivery]').val());
        if (delivery_price == 0)
            return;

        var code = $('input[name=zipcode]').val();

        $.ajax({
            url: "/common/ajaxDeliveryPrice",
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {'code': code},
            success: function (res, textStatus, jqXHR) {
                if (res.status == 'succ') {
                    if (res.data !== '') {
                        $('input[name=set_delivery]').val(res.data);
                        $('#show_delivery_price').html(commify(res.data) + '원');
                        fnCalcTotalPrice();
                    } else {
                        $('input[name=set_delivery]').val('<?php echo $cart_type == 'subscribe' ? 0 : $delivery_price; ?>');
                        $('#show_delivery_price').html(commify('<?php echo $cart_type == 'subscribe' ? 0 : $delivery_price; ?>') + '원');
                        fnCalcTotalPrice();
                    }
                } else {
                    showAlert('error', res.msg);
                }
            },
            error: function (request, status, error) {
                alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
            }
        });
    }
</script>