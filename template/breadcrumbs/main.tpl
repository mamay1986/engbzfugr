<div class="speedbar">
	<a href="/" >Главная</a>
	[[for b in breadcrubs]]
		[[if loop.last]]
			 / <span>{b.caption}</span>
		[[else]]
			/ <a href="{b.link}" >{b.caption}</a> 
		[[endif]]
	[[endfor]]
</div>