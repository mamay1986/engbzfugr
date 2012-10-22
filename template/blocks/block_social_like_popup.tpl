<div class="social-button">
	<div class="social-like">
		<div class="facebook social-item">
			[[raw]]
				<div class="fb-like" data-href="http://engels.bz" data-send="true" data-width="450" data-show-faces="true"></div>
			[[endraw]]
		</div>
		<div class="vkontakte social-item">
			[[raw]]
				<!-- Put this script tag to the <head> of your page -->
				<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?52"></script>

				<script type="text/javascript">
				  VK.init({apiId: 3114573, onlyWidgets: true});
				</script>

				<!-- Put this div tag to the place, where the Like block will be -->
				<div id="vk_like_popup"></div>
				<script type="text/javascript">
				VK.Widgets.Like("vk_like_popup", {type: "button", verb: 1});
				</script>
			[[endraw]] 
		</div>
		<div class="twitter social-item">
			[[raw]]
				<a href="https://twitter.com/share" class="twitter-share-button" data-lang="ru">Твитнуть</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			[[endraw]]
		</div>
		<div class="googleplus social-item">
			[[raw]]
			<!-- Поместите этот тег туда, где должна отображаться кнопка +1. -->
			<g:plusone size="medium"></g:plusone>

			<!-- Поместите этот вызов функции отображения в соответствующее место. -->
			<script type="text/javascript">
			  window.___gcfg = {lang: 'ru'};

			  (function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
			[[endraw]]
		</div>
		<div class="mir-mail social-item">
			[[raw]]
			<a target="_blank" class="mrc__plugin_uber_like_button" href="http://connect.mail.ru/share" data-mrc-config="{'cm' : '1', 'ck' : '1', 'sz' : '20', 'st' : '1', 'tp' : 'combo'}">Нравится</a>
			<script src="http://cdn.connect.mail.ru/js/loader.js" type="text/javascript" charset="UTF-8"></script>
			[[endraw]]
		</div>
		<div class="livejournal social-item">
			[[raw]]
				<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
				<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="yaru,lj"></div> 
			[[endraw]]
		</div>
	</div>
</div>