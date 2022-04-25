
<div class="sub-head product dendist">
    <div class="inner">
        <h2 class="h2">치과추천</h2>
        <div class="tabs">
            <div>

            </div>
        </div>
    </div>
</div>

    <div class="recomd_wrap">
        <h3>
        <?php if (!empty($mem_userid)) { 
          echo $user['mem_username'].'님, <br/>';
        } ?>
            <strong><?php echo $dentist_info['den_name']; ?></strong>에서 추천받은 진단팩을 선택하세요.</h3>
        <form action="./recommend_subscribe" method="post">
        <div class="recomd_list">
            <strong class="tit_list">칫솔 모 선택</strong>
            <div class="swiper">
                <ul class="fir_box swiper-wrapper">
                <li class="on swiper-slide">
                    <button type="button" onclick="cmall_item_sel('1');">
                        <span class="thum"><img src="https://jchxmduxhqmv7754513.cdn.ntruss.com/prod/product/thumbnail/칫솔/기능모 메인.jpg"></span>
                        <span class="info">기능모</span>
                    </button>
                </li>
                <li class="swiper-slide">
                    <button type="button" onclick="cmall_item_sel('2');">
                        <span class="thum"><img src="https://jchxmduxhqmv7754513.cdn.ntruss.com/prod/product/thumbnail/칫솔/탄력모 메인.jpg"></span>
                        <span class="info">탄력모</span>
                    </button>
                </li>

                <li class="swiper-slide">
                    <button type="button" onclick="cmall_item_sel('3');">
                        <span class="thum"><img src="https://jchxmduxhqmv7754513.cdn.ntruss.com/prod/product/thumbnail/칫솔/미세모 메인.jpg"></span>
                        <span class="info">미세모</span>
                    </button>
                </li>

                <li class="swiper-slide">
                    <button type="button" onclick="cmall_item_sel('4');">
                        <span class="thum"><img src="https://jchxmduxhqmv7754513.cdn.ntruss.com/prod/product/thumbnail/칫솔/초미세모 메인.jpg"></span>
                        <span class="info">초극세모</span>
                    </button>
                </li>
            </ul>
            </div>
            <strong class="tit_list">칫솔 줄 수 선택</strong>
            <div class="swiper">
                <ul class="size  swiper-wrapper">
                    <li class="on swiper-slide">
                        <button type="button" onclick="cmall_item_deatil_sel('S');">
                            <span class="thum"><img src="../../res/img/product/brush-s.png" alt="s사이즈"/></span>
                            <span class="txt">S</span>
                        </button>
                    </li>
                    <li class="swiper-slide">
                        <button type="button" onclick="cmall_item_deatil_sel('M');">
                            <span class="thum"><img src="../../res/img/product/brush-m.png" alt="m사이즈"/></span>
                            <span class="txt">M</span>
                        </button>

                    </li>
                    <li class="swiper-slide">
                        <button type="button" onclick="cmall_item_deatil_sel('L');">
                            <span class="thum"><img src="../../res/img/product/brush-l.png" alt="l사이즈"/></span>
                            <span class="txt">L</span>
                        </button>
                    </li>
                </ul>
                </div>
            <strong class="tit_list">치약 선택</strong>
            <div class="swiper">
            <ul class=" swiper-wrapper">
                <li class="on swiper-slide">
                    <button type="button" onclick="cmall_item_sel2('5');">
                        <span class="thum"><img src="https://jchxmduxhqmv7754513.cdn.ntruss.com/prod/product/thumbnail/치약 100g/치약-활짝 main.jpg"></span>
                        <span class="info">활짝 (충치예방)</span>
                    </button>
                </li>
                <li class="swiper-slide">
                    <button type="button" onclick="cmall_item_sel2('7');">
                        <span class="thum"><img src="https://jchxmduxhqmv7754513.cdn.ntruss.com/prod/product/thumbnail/치약 100g/치약-살짝 main.jpg"></span>
                        <span class="info">살짝 (잇몸)</span>
                    </button>
                </li>
                <li class="swiper-slide">
                    <button type="button" onclick="cmall_item_sel2('8');">
                        <span class="thum"><img src="https://jchxmduxhqmv7754513.cdn.ntruss.com/prod/product/thumbnail/치약 100g/치약-달짝 main.jpg"></span>
                        <span class="info">달짝 (시린이)</span>
                    </button>
                </li>
                <li class="swiper-slide">
                    <button type="button" onclick="cmall_item_sel2('6');">
                        <span class="thum"><img src="https://jchxmduxhqmv7754513.cdn.ntruss.com/prod/product/thumbnail/치약 100g/치약-반짝 main.jpg"></span>
                        <span class="info">반짝 (미백)</span>
                    </button>
                </li>
            </ul>
                </div>
<!--            <button type="button" class="btn_send btn btn-type1" id="" onclick="location.href='/recommend/recommend_subscribe'">선택완료</button>-->
			<button type="submit" class="btn_send btn btn-type1" id="" >선택완료</button>
        </div>

		<input type="hidden" name="cmall_item" value="1">
		<input type="hidden" name="cmall_item_deatil" value="S">
		<input type="hidden" name="cmall_item2" value="5">
                <input type="hidden" name="den_id" value="<?php echo $_GET['req']; ?>">

		</form>
</div>



<script>
	function cmall_item_sel(_val)
	{
		$('input[name="cmall_item"]').val(_val);
	}

	function cmall_item_deatil_sel(_val)
	{
		$('input[name="cmall_item_deatil"]').val(_val);
	}

	function cmall_item_sel2(_val)
	{
		$('input[name="cmall_item2"]').val(_val);
	}

    $(document).ready(function(){
        $('.recomd_list ul li').on('click', function(){
           let $this = $(this);
            $this.siblings('li').removeClass('on');
            $this.addClass('on');
        });
    })
</script>
<script>
    var ww = $(window).width();
    var mySwiper = undefined;

    function initSwiper() {
      if (ww < 740 && mySwiper == undefined) {
          mySwiper = new Swiper(".swiper", {
          slidesPerView: 'auto',
        });
      } else if (ww >= 740 && mySwiper != undefined) {
          mySwiper.destroy();
          mySwiper = undefined;
      }
    };

    initSwiper();

    $(window).on('resize', function () {
      ww = $(window).width();
      initSwiper();
    });  

</script>
