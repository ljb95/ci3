
<div class="sub-head product dendist">
    <div class="inner">
        <h2 class="h2">치과추천</h2>
        <div class="tabs">
            <div>

            </div>
        </div>
    </div>
</div>
<?php
$timestamp = strtotime("+3 months");
$next_date = date("Y-m-d", $timestamp);
$next_date_result = explode("-",$next_date);

if($_POST['cmall_item_deatil'] == 'S')
{
	$cmall_item_deatil_type = "4줄";
}
else if($_POST['cmall_item_deatil'] == 'M')
{
	$cmall_item_deatil_type = "5줄";
}
else if($_POST['cmall_item_deatil'] == 'L')
{
	$cmall_item_deatil_type = "6줄";
}

?>
<div class="recomd_wrap subscribe">
    <h3>고객님의 <br/><strong>정기구매 플랜</strong>입니다.</h3>
    <div class="inner">
        <div>
        <h4 class="bg_title btn btn-type1">구독하기</h4>
        <ul class="pord_list">
            <li>
                <h5>추천 칫솔</h5>
                <p><img src="<?php echo CDN_URL . $item1['cit_file_1'] ?>" alt="칫솔세트"/></p>
                <span><?php echo $cmall_item_deatil_type;?> / <?php echo $item1['cit_name']?> 3EA</span>
            </li>
            <li>
                <h5>추천 치약</h5>
                <p><img src="<?php echo CDN_URL . $item2['cit_file_1'] ?>" alt="치약세트"/></p>
                <span><?php echo $item2['cit_name']?> 3EA</span>
            </li>
        </ul>
        <ul class="color_list">
            <li class="white" title="하얀색"></li>
            <li class="gray" title="회색"></li>
            <li class="black" title="검은색"></li>
            <li class="blue" title="파랑색"></li>
        </ul>
            <p class="desc another">색상은 랜덤 발송됩니다.<br/>
            치과진단 패키지 구성은 1인 기준 <strong>3개월 치</strong> 입니다.
            <span>(배송 주기 및 일자는 언제든지 자유롭게 변경가능합니다)</span>
        </p>
			<?php
			$tot_cit_sale_price = ($item1['cit_sale_price'] * 3) + ($item2['cit_sale_price']  * 3);
			$tot_cit_subscribe_price = ($item1['cit_subscribe_price']  * 3) + ($item2['cit_subscribe_price']  * 3);
			$per = round(($tot_cit_sale_price-$tot_cit_subscribe_price)/$tot_cit_sale_price* 100);
			?>
        <div class="total_price">
            <span class="left"><b>총 2개 상품 </b><strong class="percent"><?php echo $per;?>%</strong></span>
            <span class="right"><b class="posi">결제 금액 </b><em class="discount"><?php echo number_format($tot_cit_sale_price)?>원</em> <strong class="price"><?php echo number_format($tot_cit_subscribe_price)?>원</strong></span>
        </div>
           </div>
    </div>
    <a href="javascript:f.submit();" class="btn btn-type1" id="">정기배송 구독하기</a>
    <p class="desc">주문 내용을 확인하였으며,<br/>
        구독서비스 가입 및 추후 구독 결제에 동의합니다.</p>
</div>

<form method="post" action="./recommend_calendar" name="f">
        <input type="hidden" name="den_id" value="<?php echo $_POST['den_id']; ?>" />
	<input type="hidden" name="cmall_item" value="<?php echo $_POST['cmall_item']?>">
	<input type="hidden" name="cmall_item_deatil" value="<?php echo $_POST['cmall_item_deatil']?>">
	<input type="hidden" name="cmall_item2" value="<?php echo $_POST['cmall_item2']?>">
</form>
