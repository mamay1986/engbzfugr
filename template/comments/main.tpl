<div style="clear: both;"></div>
<div class="commentary">
	<span class="big">Комментарий:</span>
	[[if comments]]
		<div id="comments" class="comments">
		[[for item in comments]]
			[[ include TEMPLATE_PATH ~ "comments/item.tpl"]]
		[[endfor]]
		</div>
		[[if is_more_link]]
			<div id="block_more_comments">
				<div class="preloader" id="preloader_comment"><center><img src="/images/preloader.gif"></center></div>
				<div id="more_comments">
					<a onclick="$('#preloader_comment').show();xajax_moreComments({include_param_id_comment},{limit_comment},2);return false;" href="javascript: return false;">Еще комментарии</a>
				</div>
			</div>
		[[endif]]
	[[endif]]	
	<a name="form_comments"></a>
	[[ include TEMPLATE_PATH ~ "comments/form.tpl"]]
	
</div>