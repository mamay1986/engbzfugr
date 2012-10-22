<a name="comment{item.id}"></a>
<div class="com">
	<div class="date"><span>Дата комментария: {df('date','H:i d.m.Y',item.date)}</span></div>
	<div class="nam"><a href="mailto:{item.name}@engels.bz">{item.name}</a></div>
	<p>
		{item.text|raw}
	</p>
</div>