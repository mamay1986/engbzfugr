[[ include TEMPLATE_PATH ~ "blocks/header.tpl"]]
<body onload="showPopup();">
<div class="fullwidthtop">
	[[ include TEMPLATE_PATH ~ "blocks/site_header.tpl"]]
</div>
<div id="body">
	/*facebook like*/
	[[raw]]
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1&appId=339698956065200";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
	[[endraw]]
	/*facebook like*/
	<div id="content">
		<div class="mbg8">
			[[if modul.index]]
				<img alt="1" src="/images/logo.png" style="float:left;margin-right:10px;">
			[[else]]
				<a href="/"><img alt="1" src="/images/logo.png" style="float:left;margin-right:10px;"></a>
			[[endif]]
			{configs.main_banner|raw}
			<div class="mbc marginleft140">
				<!-- Gismeteo informer START -->
				<link rel="stylesheet" type="text/css" href="http://www.gismeteo.ru/static/css/informer2/gs_informerClient.min.css">
				<div id="gsInformerID-j28D1bYg" class="gsInformer" style="width:200px;height:68px">
				  <div class="gsIContent">
				   <div id="cityLink">
					 <a href="http://www.gismeteo.ru/city/daily/5034/" target="_blank">Погода в Энгельсе</a>
				   </div>
				   <div class="gsLinks">
					 <table>
					   <tr>
						 <td>
						   <div class="leftCol">
							 <a href="http://www.gismeteo.ru" target="_blank">
							   <img alt="Gismeteo" title="Gismeteo" src="http://www.gismeteo.ru/static/images/informer2/logo-mini2.png" align="absmiddle" border="0" />
							   <span>Gismeteo</span>
							 </a>
						   </div>
						   <div class="rightCol">
							 <a href="http://www.gismeteo.ru/city/weekly/5034/" target="_blank">Прогноз на 2 недели</a>
						   </div>
						   </td>
						</tr>
					  </table>
					</div>
				  </div>
				</div>
				<div id="gismeteo_script">
					[[raw]]
					<script type="text/javascript">
						$(function(){
							var s = document.createElement('script');s.type='text/javascript';
							document.getElementById("gismeteo_script").appendChild(s);
							s.src='http://www.gismeteo.ru/ajax/getInformer/?hash=j28D1bYg';
						});
					</script>
					[[endraw]]
				</div>
				/*<script src="http://www.gismeteo.ru/ajax/getInformer/?hash=j28D1bYg" type="text/javascript"></script>*/
				<!-- Gismeteo informer END -->
			</div>
			<div style="clear:both;"></div>
		</div>
		
		[[ include TEMPLATE_PATH ~ "blocks/menu.tpl"]]
		
		<div class="left" [[if not modul.index]]style="width:747px;margin-right:13px;"[[endif]] >
			[[block center]]
			<h3 class="main">Главное</h3>
			[[for item in items_news_main]]
				[[if loop.index == 1 ]]
				<div class="bignews">
					[[if item.picture]]<a href="{item.full_url}"><img alt="1" src="/{news_obj.fileDirectory}{item.id}/379_181_{item.picture}"></a><br>[[endif]]
					<a href="{item.full_url}">{item.caption}</a><br>
					<p>
						{item.anons|raw}
					</p>
				</div>
				<div class="smallnews">
				[[else]]
					[[if loop.index == 2]]
						<div>
							[[if item.picture]]<a href="{item.full_url}"><img alt="1" src="/{news_obj.fileDirectory}{item.id}/100_80_{item.picture}"></a>[[endif]]
						</div>
					[[endif]]
					<div>
						<a href="{news_obj.getLinkPage(item.id)}">{item.caption}</a>
						&nbsp;&nbsp;<span class="date">{df('date','d.m.Y',item.date)}</span>
					</div>
				[[endif]]
			[[endfor]]
				</div>
			
			<div style="clear:both;height:13px;"></div>
			<div class="bigbanner">
				{configs.center_main_block|raw}
			</div>
			<a href="{site_obj.getLinkPage(2)}" class="big">Новости</a>
			<div class="line">
				<div class="info">
					<span>Анонс</span>
					<div class="bg"></div>
				</div>
				<div class="link">
					<a href="/novosti-ot-chitatelej/">Обо всех важных и интересных событиях города круглосуточно сообщайте нам</a>
				</div>
				<div style="clear:both;"></div> 
			</div>
			<div class="newslist">
				[[for item in items_news]]
					<div class="item [[if (loop.index0+1)%3==0]]mrg0[[endif]]">
						<div class="mbten">
						<div class="name">
								<a href="{news_obj.getLinkPage(item.parent)}"><span>{item.name_category}</span></a>
							</div>
							<div class="date">
								<span>{df('date','d.m.Y H:i',item.date)}</span>
							</div>
						</div>
						<a href="{item.full_url}">{item.caption}</a>
					</div>
					[[if (loop.index0+1)%3==0]]
						<div style="clear: both;"></div>
					[[endif]]
				[[endfor]]
				<div style="clear:both;"></div>
			</div>
			<a href="{site_obj.getLinkPage(4)}" class="big" style="margin-bottom:4px;">Афиша</a>
			<div class="catlist">
				<span>По категориям:&nbsp;&nbsp;&nbsp;</span>
				[[for item in items_meets_cats]]
					[[if loop.index0 != 0]]&nbsp;&nbsp;&nbsp;<img alt="" src="/images/mng2.png">&nbsp;&nbsp;&nbsp;[[endif]]
					<a href="{item.full_url}">{item.caption}</a>
				[[endfor]]
			</div>
			<div class="afisha">				
				<div class="datelist">
					<div class="button-datelist">
						<div class="leftbut"></div>
					</div>
					<div class="spisok">
						<div id="spisok-items-week-date" style="overflow: hidden;width: 2650px;">
						[[for item in calendar_meets]]
							<div id="item-week-date{loop.index}" onclick="getMeetsMain({item.date_full},{loop.index});" class="list [[if loop.first]]active[[endif]]">
								<span>{item.week}</span><br>
								<span>{item.day}</span>
							</div>
						[[endfor]]
						</div>
						<div style="clear:both;"></div>
					</div>
					<div class="button-datelist">
						<div class="rightbut"></div>
					</div>
				</div>
				<div id="meets_main">
					[[ include TEMPLATE_PATH ~ "meets/meets_main.tpl"]]
				</div>
				<div class="afiwa_more" id="afiwa_more">
					<a href="{site_obj.getLinkPage(4)}?filter[action]=search&filter[check]=true&filter[event_date]={df('date','d.m.Y',calendar_meets[0].date_full)}">Посмотреть все события</a>
				</div> 
				<div class="preloader" id="preloader_meets"><center><img src="/images/preloader.gif"></center></div> 
			</div>
			<a href="{site_obj.getLinkPage(5)}" class="big mbg4">Места</a>
			<div class="catlist">
				<span>По категориям:&nbsp;&nbsp;&nbsp;</span>
				[[for item in items_place_cats]]
					[[if loop.index0 != 0]]&nbsp;&nbsp;&nbsp;<img alt="" src="/images/mng2.png">&nbsp;&nbsp;&nbsp;[[endif]]
					<a href="{item.full_url}">{item.caption}</a>
				[[endfor]]
			</div>			
			<div class="mesta">
				[[for item in items_place_main]]
					<div class="mestaitem item">
						[[if item.picture]]<a href="{item.full_url}"><img class="floatleft" alt="{item.title}" src="/{place_obj.fileDirectory}{item.id}/80_80_{item.picture}"></a>[[endif]]					
						<a href="{item.full_url}">{item.caption}</a>
						<p>
							{item.addres}<br/>
							{item.phone}
						</p>
						<div style="clear:both;"></div>									
					</div>
				[[endfor]]
				<div style="clear:both;"></div>
			</div>
			<div class="icon">
				<a href="{place_obj.getLinkPage(5)}?maps=true">Посмотреть на интерактивной карте</a>
			</div>
			<div style="clear:both;"></div>
			[[endblock]]
		</div>
		<div class="right" [[if not modul.index]]style="width:221px;"[[endif]]>
			[[block right]]
			<a href="{site_obj.getLinkPage(9)}" class="big">Фоторепортажи</a>
			<div class="mbten posrel">
				[[for photo in items_photo]]
					[[if loop.index0 == 0]]
						<div class="floatleft posrel">
							<a href="{photo.full_url}" >
								<img title="{photo.caption}" alt="{photo.title}" src="/{photo_obj.fileDirectory}{photo.id}/200_160_{photo.picture}" />
							</a>
						</div>
					[[else]]
						<div style="float:right;margin-bottom:6px"> 
							<a href="{photo.full_url}"><img title="{photo.caption}" alt="{photo.title}" src="/{photo_obj.fileDirectory}{photo.id}/144_77_{photo.picture}"/></a>
						</div>
					[[endif]]
				[[endfor]]
				<div style="clear:both;"></div>
			</div>
			<a href="{site_obj.getLinkPage(12)}" class="big">Интервью</a>
			<div style="margin-bottom:15px;">
				[[for interv in items_interv]]
					<div class="interview [[if loop.index == 3]]mrg0[[endif]]">
						<a href="{interv.full_url}"><img alt="{interv.title}" src="/{interv_obj.fileDirectory}{interv.id}/112_169_{interv.picture}"></a>
						<a href="{interv.full_url}">{interv.caption}</a>
					</div>
				[[endfor]]
				<div style="clear:both;"></div>
			</div>
			
			<div class="">
				{configs.right_baner_block|raw}
			</div>
			
			[[if items_news_chitateli]]
			<a href="{news_obj.getLinkPage(configs.id_news_chitateli)}" class="big">Новости от читателей</a>
			<div class="mbten">
				[[for item in items_news_chitateli]]
					<div class="news [[if loop.last]]mrg0[[endif]]">
						[[if item.picture]]<a href="{item.full_url}"><img alt="{item.title}" src="/{news_obj.fileDirectory}{item.id}/100_80_{item.picture}"></a>[[endif]]
						<div class="date">
							<span>{df('date','d.m.Y',item.date)}</span>
						</div>
						<a href="{item.full_url}">{item.caption}</a>
					</div>
				[[endfor]]
				<div style="clear:both;"></div>
			</div>
			[[endif]]
			[[if items_news_obzor]]
			<a href="{news_obj.getLinkPage(configs.id_news_obzor)}" class="big">Обзоры</a>
			<div class="mbten">
				[[for item in items_news_obzor]]
					<div class="news [[if loop.last]]mrg0[[endif]]">
						[[if item.picture]]<a href="{item.full_url}"><img alt="{item.title}" src="/{news_obj.fileDirectory}{item.id}/100_80_{item.picture}"></a>[[endif]]
						<div class="date">
							<span>{df('date','d.m.Y',item.date)}</span>
						</div>
						<a href="{item.full_url}">{item.caption}</a>
					</div>
				[[endfor]]
				<div style="clear:both;"></div>
			</div>
			[[endif]]
			
			[[if main_comments]] 
				<a href="{site_obj.getLinkPage(1042)}" class="big">Комментарии</a>
				<div class="right_comments">
					<div class="main_comments">
					[[for item in main_comments]]
						<div class="item_comments">
							<div class="date">Дата комментария: {df('date','H:i d.m.Y',item.date)}</div>
							<div class="comment_caption">
								<a href="{site_obj.getLinkPage(item.page_id)}#comment{item.id}">{item.page_caption}</a> от <a href="mailto:{item.name}@engels.bz">{item.name}</a>
							</div>
							<div class="comment_text">
								<p>{item.text|raw}</p>
							</div>
						</div>
					[[endfor]]
					</div>
					<div style="clear:both;"></div>
				</div>
			[[endif]]
			<a href="{site_obj.getLinkPage(796)}" class="big">Объявления</a>
			<div class="rightblock">
				<div class="leftside">
					[[for item in items_advert_main]]
						[[if loop.index%4==0]]
							</div>
							<div class="rightside">
						[[endif]]
						<div class="block item mrg0">
							<div class="name">[[if item.type_advert==0]]Продажа[[elseif item.type_advert == 1]]Покупаю[[elseif item.type_advert == 2]]Аренда[[else]]Услуги[[endif]]</div>
							<div class="blockiteminfo">
								<a href="{item.full_url}">{item.caption}</a>
								[[if item.price]]<span class="price">{item.price} р.</span>[[endif]]
							</div>
						</div>
					[[endfor]]
				</div>
				<div style="clear:both;"></div>
			</div> 
			[[endblock]]
		</div>
		<div style="clear:both;"></div>
	</div>
	<div id="footer">
		<div class="fl">
			<img alt="" src="/images/footerlogo.png">
			<br/><br/>
			<p>{configs.email}</p>
			<p>{configs.phone1}</p>
			<p>{configs.phone2}</p>
		</div>
		[[for item in menu]]
			[[if not item.index]]
				<div class="fl [[if loop.last]]mrg0[[endif]]">
					<div class="firstlink">
						<a href="{item.full_url}">{item.caption}</a>
					</div>
					[[if item.child]]
					<div class="otherlink">
						[[for child in item.child]]
							[[if loop.index < 8]]
							<a href="{child.full_url}">{child.caption}</a>
							[[endif]]
						[[endfor]]
					</div>
					[[endif]]
				</div>
			[[endif]]
		[[endfor]]
		<div style="clear:both;"></div>
	</div>
</div>
<div class="fullwidthbottom">
	<div id="afterfooter">
		[[if configs.link_fb]]
			<div class="soc fb">
				<a href="{configs.link_fb}" target="_blank" class="s">Facebook</a>
			</div>
		[[endif]]
		[[if configs.link_tw]]
		<div class="soc tw">
			<a href="{configs.link_tw}"  target="_blank" class="s">Twitter</a>
		</div>
		[[endif]]
		[[if configs.link_vk]]
		<div class="soc vk">
			<a href="{configs.link_vk}"  target="_blank" class="s">Vkontakte</a>
		</div>
		[[endif]]
		<div class="counter">
			[[raw]]
			
			<!--LiveInternet counter--><script type="text/javascript"><!--
				document.write("<a href='http://www.liveinternet.ru/click' "+
				"target=_blank><img src='//counter.yadro.ru/hit?t14.9;r"+
				escape(document.referrer)+((typeof(screen)=="undefined")?"":
				";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
				screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
				";"+Math.random()+
				"' alt='' title='LiveInternet: показано число просмотров за 24"+
				" часа, посетителей за 24 часа и за сегодня' "+
				"border='0' width='88' height='31'><\/a>")
				//--></script><!--/LiveInternet-->
			</div>
			<div class="counter">
			<!-- Yandex.Metrika informer -->
			<a href="http://metrika.yandex.ru/stat/?id=16861387&amp;from=informer"
			target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/16861387/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
			style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:16861387,type:0,lang:'ru'});return false}catch(e){}"/></a>
			<!-- /Yandex.Metrika informer -->

			<!-- Yandex.Metrika counter -->
			<script type="text/javascript">
			(function (d, w, c) {
				(w[c] = w[c] || []).push(function() {
					try {
						w.yaCounter16861387 = new Ya.Metrika({id:16861387, enableAll: true, webvisor:true});
					} catch(e) { }
				});
				
				var n = d.getElementsByTagName("script")[0],
					s = d.createElement("script"),
					f = function () { n.parentNode.insertBefore(s, n); };
				s.type = "text/javascript";
				s.async = true;
				s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

				if (w.opera == "[object Opera]") {
					d.addEventListener("DOMContentLoaded", f);
				} else { f(); }
			})(document, window, "yandex_metrika_callbacks");
			</script>
			<noscript><div><img src="//mc.yandex.ru/watch/16861387" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
			<!-- /Yandex.Metrika counter -->
			[[endraw]]
		</div>
		<div class="by">
			<a href="http://future-group.ru" target="_blank" class="s">Создание сайтов</a><span> - Future</span>
		</div>
	</div>
</div>
[[ include TEMPLATE_PATH ~ "blocks/popup.tpl"]]
<div style="display:none;">
	{generate_page}
</div>
</body>
</html>