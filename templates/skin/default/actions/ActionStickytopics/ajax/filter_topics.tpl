{if $aTopics}
<table class="table">
	<thead>
		<tr>
			<td width="18px" style="text-align: center">+</td>
			<td>{$aLang.stickytopics_st_title}</td>
			<td>ID</td>
		</tr>
	</thead>

	<tbody>
		{foreach from=$aTopics item=oItem}
		<tr>
			<td style="text-align: center;"><div class="stickytopics_admin"><ul>
			<li class="add"><a href="#" title="{$aLang.stickytopics_st_action_add}" onClick="lsStickyStream.addSticky({$blog_id},{$oItem->getId()}); return false;"></a></li>
			</ul></div>
			</td>
			<td>{$oItem->getTitle()|escape:html}</td>
			<td>{$oItem->getId()}</td>
		</tr>
		{/foreach}
	</tbody>
</table>
{else} {$aLang.stickytopics_data_not_found} {/if}
