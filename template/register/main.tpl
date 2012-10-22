[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]] 

[[block center]]
	
	[[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]    
	   
    <div class="names">
		<span>{modul.caption}</span>
	</div>
    
    {item.text|raw}

	[[if registration_true]]
		<p class="h1">
		{configs.register_site|raw}<br/>
			Ваша персональная почта <a target="_blank" href="/ya_mail.php?login={user_params.login}" class="h1">{request.login}@{domain}</a> .
		</p>
		
	[[else]]
		[[if error]]
			<span class="error">
			[[for er in error]]
				{er}<br/>
			[[endfor]]
			</span>
		[[endif]]
		<div class="register">
			<form method="post" name="registration" onsubmit="javascript: document.registration.submit(); return false;">
			<input type="hidden" name="action" value="register"> 
			<div class="form">
				<table>
					<tr>
						<td class="name_form">Логин:</td>
						<td><input type="text" name="login" value="{request.login}"/></td>
					</tr>
					<tr>
						<td class="name_form">Пароль:</td>
						<td><input type="password" name="password" /></td>
					</tr>
					<tr>
						<td class="name_form">Повторите пароль:</td>
						<td><input type="password" name="password_succed" /></td>
					</tr>
					<tr>
						<td></td>
						<td align="right">
							<button class="button" type="submit">
								<span class="button-left">
									<span class="button-right">
										<span class="button-text">
											<span>Регистрация</span>
										</span>
									</span>
								</span>
							</button>	
						</td>
					</tr>
				</table>
			</div>
				
				
			</form>
		</div>
	[[endif]]
[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]