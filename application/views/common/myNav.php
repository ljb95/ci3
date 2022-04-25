<?php
	$menu = $this->uri->segment(1, '');
	$sub = $this->uri->segment(2, '');
?>
			<aside>
				<ul>
					<li <?php echo $menu == 'my' && $sub == 'home' ? 'class="active" ' : ''; ?>>
                    	<a href="/my/home"><i class="ic1"></i>마이홈</a>
                    </li>
					<li <?php echo $menu == 'my' && $sub == 'subscribe' ? 'class="active" ' : ''; ?>>
                    	<a href="/my/subscribe/subscribe_list"><i class="ic2"></i>구독관리</a>
                    </li>
					<li <?php echo $menu == 'my' && $sub == 'order' ? 'class="active" ' : ''; ?>>
                    	<a href="/my/order"><i class="ic3"></i>주문관리</a>
                    </li>
					<li <?php echo $menu == 'my' && $sub == 'make' ? 'class="active" ' : ''; ?>>
                    	<a href="/my/make"><i class="ic4"></i>활동관리</a>
                    </li>
					<li <?php echo $menu == 'my' && $sub == 'survey' ? 'class="active" ' : ''; ?>>
                    	<a href="/my/survey"><i class="ic5"></i>진단/혜택</a>
                    </li>
					<li <?php echo $menu == 'my' && $sub == 'user' ? 'class="active" ' : ''; ?>>
                    	<a href="/my/user"><i class="ic6"></i>나의 정보</a>
                    </li>
				</ul>
			</aside>
