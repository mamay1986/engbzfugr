<div class="menu">
	[[for item in menu]]
		<div class="lvl [[if item.status]]active[[endif]]">
			<a href="/[[if not item.index]]{item.redir}/[[endif]]" class="alvl1">{item.caption}</a>
			[[if item.child]]
				<div class="lvl2">
					[[for child in item.child]]
						<a href="/{item.redir}/{child.redir}/" class="alvl2">{child.caption}</a>
					[[endfor]]
				</div>
			[[endif]]
		</div>
	[[endfor]]
	/*<div class="search">
		<div class="but"></div>
		<input type="text" value="Введите запрос">
	</div>*/
	<div style="clear:both;"></div>
</div>