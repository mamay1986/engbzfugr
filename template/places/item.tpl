[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]

    [[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]
	<!--
	  jCarousel library
	-->
	<script type="text/javascript" src="/js/jquery.jcarousel.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/styles/tango/skin.css" />
    <script type="text/javascript">
		{place_script|raw}
	</script>
    <div class="names">
		<span>{item.caption}</span>
	</div>
    <div class="story">
		[[if item.picture]]<img align="left" style="float:left;" src="/{places_obj.fileDirectory}{item.id}/406__{item.picture}">[[endif]]
		
		<div>
			<ul class="dop-params">
				<li class="rating"><strong>Рейтинг:</strong> <div class="block-rating" id="div-stars-update">{rating_show|raw}</div></li>
				[[if item.dop_params.addres]]<li><strong>Адрес:</strong> <a href="#adress">{item.dop_params.addres}</a></li>[[endif]]
				[[if item.dop_params.date_work]]<li><strong>Время работы:</strong> {item.dop_params.date_work}</li>[[endif]]
				[[if item.dop_params.phone]]<li><strong>Телефон:</strong> {item.dop_params.phone}</li>[[endif]]
				[[if item.dop_params.email]]<li><strong>Email:</strong> {item.dop_params.email}</li>[[endif]]
				[[if item.dop_params.web]]<li><strong>Web сайт:</strong> {item.dop_params.web}</li>[[endif]]
				[[if item.dop_params.wifi]]<li><strong>Wi-Fi:</strong> [[if item.dop_params.wifi=='1']]Да[[else]]Нет[[endif]]</li>[[endif]]
				[[if item.dop_params.bron_cherez_engels]]<li><strong>Бронь через Engels.bz:</strong> [[if item.dop_params.bron_cherez_engels=='1']]Да[[else]]Нет[[endif]]</li>[[endif]]
				[[if item.dop_params.kitchen]]<li><strong>Кухня:</strong> {item.dop_params.kitchen}</li>[[endif]]
				[[if item.dop_params.average_chek]]<li><strong>Средний счет:</strong> {item.dop_params.average_chek}</li>[[endif]]
				[[if item.dop_params.business_lunch]]<li><strong>Бизнес ланч:</strong> [[if item.dop_params.business_lunch=='1']]Да[[else]]Нет[[endif]]</li>[[endif]]
				[[if item.dop_params.banket]]<li><strong>Банкет:</strong> [[if item.dop_params.banket=='1']]Да[[else]]Нет[[endif]]</li>[[endif]]
				[[if item.dop_params.capacity]]<li><strong>Вместимость (кол-во чел.):</strong> {item.dop_params.capacity}</li>[[endif]]
				[[if item.dop_params.steam]]<li><strong>Парная:</strong> {item.dop_params.steam}</li>[[endif]]
				[[if item.dop_params.pool]]<li><strong>Бассейн:</strong> {item.dop_params.pool}</li>[[endif]]
				[[if item.dop_params.restroom]]<li><strong>Комната отдыха:</strong> {item.dop_params.restroom}</li>[[endif]]
				[[if item.dop_params.music]]<li><strong>Музыка:</strong> {item.dop_params.music}</li>[[endif]]
				[[if item.dop_params.residents]]<li><strong>Резиденты:</strong> {item.dop_params.residents}</li>[[endif]]
				[[if item.dop_params.num_dance_flors]]<li><strong>Кол-во танцполов:</strong> {item.dop_params.num_dance_flors}</li>[[endif]]
				[[if item.dop_params.num_track]]<li><strong>Кол-во дорожек:</strong> {item.dop_params.num_track}</li>[[endif]]
				[[if item.dop_params.type_billiards]]<li><strong>Вид бильярда:</strong> {item.dop_params.type_billiards}</li>[[endif]]
				[[if item.dop_params.num_tables]]<li><strong>Кол-во столов:</strong> {item.dop_params.num_tables}</li>[[endif]]
				[[if item.dop_params.more_services]]<li><strong>Доп. услуги:</strong> {item.dop_params.more_services}</li>[[endif]]
			</ul>
		</div>
		
		<div style="clear:both;"></div>
		
		{item.text|raw}
		
		[[set tags = item.tags]]
		[[ include TEMPLATE_PATH ~ "blocks/tags.tpl"]]
		
		[[if photos]]
			<div class="imglist fotoimg">
				<ul id="carouse-gallery" class="jcarousel-skin-tango" >
					[[for photo in photos]]
						<li>
							<a href="/images/galleries/{photo.id_catalog}/1024_{photo.image}" class="show" title="{photo.title}">
								<img src="/images/galleries/{photo.id_catalog}/thumbs/{photo.image}" alt="" />
							</a>
						</li>
					[[endfor]]
				</ul>
				[[raw]]
				<script type="text/javascript">
					$('#carouse-gallery').jcarousel({ 
						auto: 4,
						wrap: 'circular'
					});
				</script>
				[[endraw]]
			</div>
		[[endif]]
		<div style="margin-bottom:20px;">
			[[ include TEMPLATE_PATH ~ "blocks/block_social_like.tpl"]]
		</div>
		[[if items_meets]]
			<h3>Афиша:</h3>
			<ul class="place_photo_report">
			[[for item_meet in items_meets]]
				<li><a href="{site_obj.getLinkPage(item_meet.id)}">{item_meet.caption}</a> [[if item_meet.date]]( {df('date','d.m.Y H:i',item_meet.date)} [[if item_meet.date_from]]- {df('date','d.m.Y H:i',item_meet.date_from)}[[endif]] )[[endif]]</li>
			[[endfor]]
			</ul>
		[[endif]]
		
		
		[[if items_photo_report]]
			<h3>Фоторепортажи:</h3>
			<ul class="place_photo_report">
			[[for item_photo in items_photo_report]]
				<li><a href="{site_obj.getLinkPage(item_photo.id)}">{item_photo.caption}</a></li>
			[[endfor]]
			</ul>
		[[endif]]
		
		[[ include TEMPLATE_PATH ~ "comments/main.tpl"]]
		<a name="adress"></a>
		<div class="map-places">
			<div id="map-content">
				<div id="map_canvas" style="position:absolute; z-index:1;"></div>
				<script type="">initialize();addPlace(array_place,true,true);</script>
				[[raw]]
				<script type="text/javascript">
					(function() {
						makeScrollable('map_canvas', function(delta) {
						  ;
						});
					})();
				</script>
				[[endraw]]
			</div>
		</div>
	</div>
[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]