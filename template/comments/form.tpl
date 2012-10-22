[[if user.id]]
	/*[[if send_comment]]
		<div class="textarea">
			<center>Комментарий скоро будет опубликован на сайте.</center><br/>
		</div>
	[[else]]*/
		[[if error.comment]]
			<div class="error">
				Ошибки:
				[[for er in error.comment]]
					{er}<br/>
				[[endfor]]
			</div>
		[[endif]]
		<form method="post" action="#form_comments" onsubmit="javascript: document.form_comments.submit(); return false;" name="form_comments">
			<input type="hidden" name="action" value="comments">

			<div class="textarea">
				<textarea name="text">{request.text}</textarea>
				<div class="textareainfo">
					<input class="submit" type="submit" value="Оставить комментарий">
					/*<div class="soc">
						<input type="checkbox">&nbsp;&nbsp;<img alt="1" src="/images/fb.jpg" style="width:16px;">
					</div>
					<div class="soc">
						<input type="checkbox">&nbsp;&nbsp;<img alt="1" src="/images/vk.jpg">
					</div>*/
					<div class="captcha clf">
    					<span>Защита от роботов:</span>
						<img width="60" height="18" src="/getpicture.php" alt="Защита от роботов" title="Защита от роботов" />
						<input type="text" id="faq_captcha" class="text" name="picode">
					</div>
				</div>
			</div>
		</form>
	/*[[endif]]*/
[[else]]
	<div class="textarea">
		<center>Для возможности отправки коментариев <a href="#">войдите</a> или <a href="/registracija/">зарегистрируйтесь</a>.</center><br/>
	</div>
[[endif]]