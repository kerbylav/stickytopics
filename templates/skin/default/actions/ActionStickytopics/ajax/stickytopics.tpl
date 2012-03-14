{if $aTopics}
<table class="table">
	<thead>
		<tr>
			<td width="100px">{$aLang.stickytopics_st_action}</td>
			<td>{$aLang.stickytopics_st_title}</td>
			<td>ID</td>
		</tr>
	</thead>

	<tbody>
		{foreach from=$aTopics item=oItem name=fr}
		{assign var=tid value=$oItem->getId()}
		<tr>
			<td><div class="stickytopics_admin"><ul>
			{if $smarty.foreach.fr.first}<li class="empty"><a href="#" onClick="return false;" title="{$aLang.stickytopics_st_action_up}">&nbsp;</a></li>{else}<li class="up"><a href="#" onClick="lsStickyStream.orderSticky({$oItem->getId()},'up'); return false;">&nbsp;</a></li>{/if}
			{if $smarty.foreach.fr.last}<li class="empty"><a href="#" onClick="return false;" title="{$aLang.stickytopics_st_action_down}">&nbsp;</a></li>{else}<li class="down"><a href="#" onClick="lsStickyStream.orderSticky({$oItem->getId()},'down'); return false;">&nbsp;</a></li>{/if}
			<li {if ($aInactivates[$tid])}class="activate"{else}class="deactivate"{/if}><a href="#" title="{if ($aInactivates[$tid])}{$aLang.stickytopics_st_action_activate}{else}{$aLang.stickytopics_st_action_deactivate}{/if}" onClick="lsStickyStream.toggleSticky({$oItem->getId()}{if ($aInactivates[$tid])},1{else},0{/if}); return false;"></a></li>
			<li class="empty"><a href="#" onClick="return false;">&nbsp;</a></li>
			<li class="delete"><a href="#" onClick="if (confirm('{$aLang.stickytopics_st_action_unstick_confirm}')) lsStickyStream.deleteSticky({$oItem->getId()},{$blog_id}); return false;"  title="{$aLang.stickytopics_st_action_unstick}">&nbsp;</a></li>
			</ul></div></td>
			<td {if ($aInactivates[$tid])}style="text-decoration: line-through;"{/if}>{$oItem->getTitle()|escape:html}</td>
			<td>{$oItem->getId()}</td>
		</tr>
		{/foreach}
	</tbody>
</table>
{else} {$aLang.stickytopics_data_not_found} {/if}
