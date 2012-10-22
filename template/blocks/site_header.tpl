<div id="header">
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
	<div class="date">
		<img alt="1" src="/images/mng.png">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>{time_new}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img alt="1" src="/images/mng.png">
	</div>
	<div class="curs">
		<span class="s">Курс валют:&nbsp;&nbsp;&nbsp;</span>
		<div class="val">
			<span>$&nbsp;{usd_valuta}</span>
		</div>
		<div class="val">
			<span>€&nbsp;{eur_valuta}</span>
		</div>
	</div>
	<div class="mail">
		[[if user.id]]
			/*<span>Почта на @engels.bz</span>
			&nbsp;&nbsp;&nbsp;
			<a target="_blank" href="/ya_mail.php?login={user_params.login}" class="l">Войти</a>
			&nbsp;&nbsp;&nbsp;*/
			<a href="?action=logout" class="l">Выйти</a>&nbsp;&nbsp;&nbsp;<span class="black_color">:&nbsp;&nbsp;&nbsp;Почта</span>
		[[else]]
			<span>Заведи почту @engels.bz</span>
			&nbsp;&nbsp;&nbsp;
			<a href="/registracija/" class="l">Регистрация</a>
			&nbsp;&nbsp;&nbsp;
			<img alt="1" src="/images/mng.png">
			&nbsp;&nbsp;&nbsp;
			<a href="#" class="a">Почта</a>
		[[endif]]
	</div>
	<div class="form">
		[[if user.id]]
			 <br/>
			 <div class="login_message"><a target="_blank" href="/ya_mail.php?login={user_params.login}">{user_params.login}@engels.bz </a></div>
			 <div class="no_read_message"><a href="/ya_mail.php?login={user_params.login}">{user_params.message_new} Новых писем </a></div>
			 <div class="write_message"><a href="/ya_mail.php?login={user_params.login}" class="l">Написать письмо</a></div>
			 <div>&nbsp;</div>
		[[else]]
		<form onsubmit="javascript: document.login_site.submit(); return false;" method="post" name="login_site" action="">
			<input type="hidden" name="action" value="login">
			<input title="Логин" value="" name="login" class="input fieldfocus" type="text">
			<input title="Пароль" value="" class="input fieldfocus" name="password" type="password">
			<a href="/registracija/" class="floatleft">Регистрация</a>
			/*<input type="submit" value="&nbsp;&nbsp;Войти&nbsp;&nbsp;" class="submit">*/
			<button class="button login" type="submit">
				<span class="button-left">
					<span class="button-right">
						<span class="button-text">
							<span>Войти</span>
						</span>
					</span>
				</span>
			</button>
		</form>
		[[endif]]
	</div>
	<div style="clear:both;"></div>
</div>