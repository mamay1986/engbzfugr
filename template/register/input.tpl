[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]] 

[[block center]]
	
	[[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]
	
    <div class="names">
		<span>{modul.caption}</span>
	</div>
    
    {item.text|raw} 
	
	
	
	[[if user.id]]
		
	[[else]]
		[[if error]]
			<span class="error">
			[[for er in error]]
				{er}<br/>
			[[endfor]]
			</span>
		[[endif]]
		<div class="register">
			<form method="post" name="input_in_site" onsubmit="javascript: document.input_in_site.submit(); return false;">
			<input type="hidden" name="action" value="login"> 
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
						<td></td>
						<td align="right">
							<button class="button" type="submit">
								<span class="button-left">
									<span class="button-right">
										<span class="button-text">
											<span>Войти</span>
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
		<br/>
	[[endif]]
	<span><a class="h1" href="/registracija/">Регистрация</a></span>

[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]