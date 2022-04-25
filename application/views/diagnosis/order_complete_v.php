	<div class="sub-head product pc">
		<div class="inner">
			<h2 class="h2"><?php echo $res['status'] == 'OK' ? '주문완료' : '결제실패'; ?></h2>
			
		</div>
	</div>
	
	<div class="inner">
		<div class="order-end">
        	<?php
				$total_price = $order['total_price'];
                if($order['order_type'] == 'subscribe') {
					$total_price = 0;
					foreach($subscribe['list'] as $row) {
						$total_price += $row['cit_subscribe_price'] * $row['qty'];
					}
				}
				if($res['status'] === 'OK') {
			?>
                    <div class="t1">주문이 정상적으로 접수되었습니다.</div>
                    <?php 
                        if($order['order_type'] == 'subscribe') {
                            echo '<div class="t2"><strong>배송시작일 ' . date('Y/m/d', strtotime($order['start_date'])) . '이후 ' . $order['delivery_period'] . '주 주기</strong>로 배송이 됩니다.</div>';
                        }
                    ?>
            <?php
				}
				else {
			?>
                    <div class="t1">주문하신 내역의 결제가 실패하였습니다.</div>
                    <div class="t2"><strong>실패사유 : <?php echo $res['res_msg']; ?></strong></div>'
                    <div class="t1">확인 후 다시 시도하시거나 고객센터로 연락주십시요.<br>(콜린디 고객센터 : 1661-6417)</div>'
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
				</colgroup>
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>상품정보</th>
						<th>상품가격</th>
						<th>수량</th>
                        <?php
							if($order['order_type'] == 'subscribe') {
								echo '<th>정기구독할인</th>';
							}
						?>
						<th>판매가</th>
					</tr>
				</thead>
				<tbody>
                <?php
					if($order['order_type'] == 'subscribe') {
						$list = $subscribe['list'];
					}
					else {
						$list = $order['list'];	
					}
					
					foreach($list as $row) {
						$unit_price = $row['cit_sale_price'];
						$dis = ($row['cit_price'] - $row['cit_subscribe_price']) * $row['qty'];
						if($order['order_type'] === 'subscribe') $unit_price = $row['cit_subscribe_price'];
				?>
					<tr>
						<td><div class="prd-thum"><img src="<?php echo CDN_URL . $row['cit_file_1']; ?>"></div></td>
						<td>
							<div class="prd-info">
								<strong><?php echo $row['cit_name']; ?></strong><?php echo $order['order_type'] === 'subscribe' ? '<span>구독상품</span>' : ''; ?>
								<div class="opt"><?php echo $row['cde_title']; ?></div>
							</div>
						</td>
						<td><?php echo number_format($row['cit_price']); ?>원</td>
						<td><?php echo $row['qty']; ?></td>
                        <?php
							if($order['order_type'] == 'subscribe') {
								echo '<td>' . number_format($dis) . '</td>';
							}
						?>
						<td><strong><?php echo number_format($unit_price * $row['qty']); ?>원</strong></td>
					</tr>
                <?php
					}
				?>
				</tbody>
			</table>
		</div>
		
		<!-- 모바일 전용 -->
		<div class="order-end-flip">
			<a href="#">
				<strong>주문내역</strong>
				<div class="info">
					<div class="t1"><?php echo $order['product_name']; ?></div>
					<div class="t2">총 결제금액 <?php echo number_format($total_price); ?>원</div>
				</div>
			</a>
		</div>
		<!-- // 모바일 전용 -->
		
		
		<div class="cart-list mobile" style="display: none">
			<ul>
                <?php
					$sum = 0;
					$sum_dis = 0;
					if($order['order_type'] == 'subscribe') {
						$list = $subscribe['list'];
					}
					else {
						$list = $order['list'];	
					}
					foreach($list as $row) {
						$unit_price = $row['cit_sale_price'];
						$dis = ($row['cit_price'] - $row['cit_subscribe_price']) * $row['qty'];
						if($order['order_type'] === 'subscribe') $unit_price = $row['cit_subscribe_price'];
						
						$sum += $row['cit_price'] * $row['qty'];
						$sum_dis += $dis;
				?>
				<li>
					<div class="img"><img src="<?php echo CDN_URL . $row['cit_file_1']; ?>"></div>
					<div class="info">
						<dl>
							<dt><strong><?php echo $row['cit_name']; ?></strong><?php echo $order['order_type'] === 'subscribe' ? '<span>구독상품</span>' : ''; ?></dt>
							<dd>
								<div class="opt"><?php echo $row['cde_title']; ?></div>
								<div class="price"><?php echo number_format($unit_price); ?> 원</div>
							</dd>
						</dl>
					</div>
				</li>
                <?php
					}
				?>
			</ul>			
		</div>
		
		
		<div class="cart-step">
			<!-- 1. 구독 -->
			<div class="step1">
				<div class="cart-price pc">
					<div class="orther">
						<dl>
							<dt>배송비</dt>
							<dd><?php echo number_format($order['delivery_price']); ?>원</dd>
						</dl>
                        <?php
							if($order['order_type'] === 'subscribe') {
						?>
						<dl>
							<dt>구독할인</dt>
							<dd><?php echo number_format($sum_dis); ?>원</dd>
						</dl>
                        <?php
							}
							else {
						?>
						<dl>
							<dt>포인트 </dt>
							<dd><?php echo ($order['use_point'] > 0 ? '-' : '') . number_format($order['use_point']); ?>P</dd>
						</dl>
                        <?php
							}
						?>
					</div>
					<div class="total">
						<dl>
							<dt>총 결제금액</dt>
							<dd><?php echo number_format($total_price);?> 원</dd>
						</dl>
					</div>
				</div>
				
				
				<div class="order-info">
                	<?php
						if($order['order_mem_type'] == 'guest') {
					?>
					<div class="box">
						<h4 class="h4-my">주문자</h4>
						<div class="info">
							<dl>
								<dt>이름</dt>
								<dd><?php echo $order['mem_username']; ?></dd>
							</dl>
							<dl>
								<dt>전화번호</dt>
								<dd><?php echo $order['mem_phone']; ?></dd>
							</dl>
							<dl>
								<dt>이메일</dt>
								<dd><?php echo $order['mem_email']; ?></dd>
							</dl>
							<div class="desc">* 주문자 정보로 주문 관련 정보가 <br class="pc">문자와 이메일로 발송 됩니다. </div>
						</div>
					</div>
                    <?php
						}
					?>
					<div class="box">
						<h4 class="h4-my">&nbsp;</h4>
						<div class="info">
							<dl>
								<dt>받는사람</dt>
								<dd><?php echo $order['recipient_name']; ?></dd>
							</dl>
							<dl>
								<dt>전화번호</dt>
								<dd><?php echo $order['recipient_phone']; ?></dd>
							</dl>
							<dl>
								<dt>받는주소</dt>
								<dd>(<?php echo $order['recipient_zip']; ?>)<?php echo $order['recipient_addr1']; ?> <?php echo $order['recipient_addr2']; ?></dd>
							</dl>
							<dl>
								<dt>배송요청사항</dt>
								<dd><?php echo $order['recipient_memo']; ?></dd>
							</dl>
						</div>
					</div>
                    <?php
						if($res['status'] === 'OK') {
					?>
					<div class="box">
						<h4 class="h4-my">결제금액</h4>
						<div class="info2">
							<dl>
								<dt>상품금액</dt>
								<dd><strong><?php echo number_format($sum); ?></strong> 원</dd>
							</dl>
							<dl>
								<dt>배송비</dt>
								<dd><strong><?php echo $order['delivery_price'] > 0 ? '+ ' . number_format($order['delivery_price']) : '0'; ?></strong> 원</dd>
							</dl>
							<?php
                                if($order['order_type'] === 'subscribe') {
                            ?>
							<dl>
								<dt>할인금액</dt>
								<dd><strong><?php echo $sum_dis > 0 ? '- ' . number_format($sum_dis) : '0'; ?></strong> 원</dd>
							</dl>
                            <?php
								}
								else {
							?>
							<dl>
								<dt>포인트 사용</dt>
								<dd><strong><?php echo ($order['use_point'] > 0 ? '-' : '') . number_format($order['use_point']); ?></strong> 원</dd>
							</dl>
                            <?php
								}
							?>
							<dl class="total">
								<dt>최종결제금액</dt>
								<dd><small><?php echo $order['order_type'] == 'subscribe' ? '정기결제' : ''; ?></small><strong><?php echo number_format($total_price); ?></strong> 원</dd>
							</dl>
						</div>
					</div>
					<?php
						if($order['order_mem_type'] != 'guest' && $order['payDevice'] == 'PC') {
					?>
                    <div class="box">
                    </div>
                    <?php
						}
					?>
					<div class="box">
						<h4 class="h4-my">결제정보</h4>
						<div class="info2">
                        <?php
							if($order['payMethod'] == 'Card' || $order['payMethod'] == 'CARD' || $order['payMethod'] == 'VCard' || $order['payMethod'] == 'Auth') {
						?>
							<dl>
								<dt>결제수단</dt>
								<dd>카드결제</dd>
							</dl>
							<dl>
								<dt>카드정보</dt>
								<dd><?php echo $order['card_name2'] != '' ? $order['card_name2'] . '/' : ''; ?><?php echo $order['card_num']; ?></dd>
							</dl>
                        <?php
							}
							else if($order['payMethod'] == 'VBank' || $order['payMethod'] == 'VBANK') {
						?>
							<dl>
								<dt>결제수단</dt>
								<dd>가상계좌(무통장)입금</dd>
							</dl>
							<dl>
								<dt>은행명</dt>
								<dd><?php echo $order['vbank_name']; ?></dd>
							</dl>
							<dl>
								<dt>계좌번호</dt>
								<dd><?php echo $order['vbank_num']; ?></dd>
							</dl>
							<dl>
								<dt>예금주명</dt>
								<dd><?php echo $order['vbank_owner']; ?></dd>
							</dl>
							<dl>
								<dt>입금기한</dt>
								<dd><?php echo date('Y.m.d', strtotime($order['vbank_date'])); ?></dd>
							</dl>
                        <?php	
							}
							else if($order['payMethod'] == 'DirectBank' || $order['payMethod'] == 'BANK') {
						?>
							<dl>
								<dt>결제수단</dt>
								<dd>실시간계좌이체</dd>
							</dl>
							<dl>
								<dt>은행명</dt>
								<dd><?php echo $order['bank_name']; ?></dd>
							</dl>
							<dl>
								<dt>계좌번호</dt>
								<dd><?php echo $order['bank_num']; ?></dd>
							</dl>
						<?php	
							}
						?>
						</div>
					</div>
                
					<?php
						}
					?>
				</div>
				<div class="btn-box-common1">
				<?php
					if($res['status'] === 'OK') {
						if(!empty($order['csu_id'])) {
							echo '<a href="/my/subscribe/detail/0?seq=' . $order['csu_id'] . '" class="btn btn-type2">주문 상세보기</a>';
						}
						else if(!empty($order['billing_order_id'])) {
							echo '<a href="/my/order/order_detail?seq=' . $order['billing_order_id'] . '" class="btn btn-type2">주문 상세보기</a>';
						}
						else {
							echo '<a href="/my/order/order_detail?seq=' . $order['order_id'] . '" class="btn btn-type2">주문 상세보기</a>';

						}
				?>
					<a href="/product/product_list" class="btn btn-type1">쇼핑 계속 하기</a>
               	<?php
					}
					else {
				?>
   					<a href="/diagnosis/detail?seq=<?php echo $cdg_id; ?>" class="btn btn-type1">다시 결제 하기</a>
                <?php
					}
				?>
				</div>
			</div>
			<!-- 1. // 구독 -->
			
		</div>
		
	</div>