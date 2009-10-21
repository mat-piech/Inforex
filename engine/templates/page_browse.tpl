{include file="inc_header.tpl"}
{include file="inc_menu.tpl"}

<td style="vertical-align: top; background: ; border: 1px solid rgb(68, 68, 68); background: linen">
<div class="filter_menu">
	 		
	<div class="total_count"><small>liczba raportów:</small><br/>{$total_count}</div>
	<h2>Szukaj <small>w tytule</small></h2>
	<form action="index.php?page=browse">
		<input type="text" name="search" value="{$search}" style="width: 150px"/>
		<input type="hidden" name="page" value="browse"/> 
		<input type="submit" value="szukaj"/>
	</form>
	{if $search}
		<small><a href="index.php?page=browse&amp;search=">anuluj</a></small>
	{/if}
{if !$IS_RELEASE}
	<h2>Status</h2>
	<ul>
	{foreach from=$statuses item="status"}
		<li><a href="index.php?page=browse&amp;status={$status.link}"{if $status.selected} class="selected"{/if}>{$status.name|default:"<i>brak</i>"}</a>&nbsp;({$status.count})</li>
	{/foreach}
	</ul>
{/if}	
	<h2>Typ zdarzenia</h2>
	<ul>
	{foreach from=$types item="type"}
		<li><a href="index.php?page=browse&amp;type={$type.link}"{if $type.selected} class="selected"{/if}>{$type.name|default:"<i>brak</i>"}</a>&nbsp;({$type.count})</li>
	{/foreach}
	</ul>
{if !$IS_RELEASE}	
	<h2>Rok</h2>
	<ul>
	{foreach from=$years item="year"}
		<li><a href="index.php?page=browse&amp;year={$year.link}"{if $year.selected} class="selected"{/if}>{$year.year}</a>&nbsp;({$year.count})</li>
	{/foreach}
	</ul>
	<h2>Miesiąc</h2>
	<ul>
	{foreach from=$months item="month"}
		<li><a href="index.php?page=browse&amp;month={$month.link}"{if $month.selected} class="selected"{/if}>{$month.month}</a>&nbsp;({$month.count})</li>
	{/foreach}
	</ul>

	<h2>Adnotacje</h2>
	<ul>
	{foreach from=$annotations item="annotation"}
		<li><a href="index.php?page=browse&amp;annotation={$annotation.link}"{if $annotation.selected} class="selected"{/if}>{$annotation.name}</a>&nbsp;({$annotation.count})</li>
	{/foreach}
	</ul>
{/if}
</div>

</td>

<td class="table_cell_content">

<table style="width: 100%">
	<tr style="border: 1px solid #999;">
		<th>Lp.</th>
		<th>Id</th>
		<th>Nazwa&nbsp;raportu</th>
		<th>Typ&nbsp;raportu</th>
		{* <th>Status</th> *}
		{* <th colspan="2"> </th>*}
	</tr>
{foreach from=$rows item=r name=list}
	<tr class="row_{if ($smarty.foreach.list.index%2==0)}even{else}odd{/if}{if $r.status==2}_ok{/if}">
		<td style="text-align: right">{$smarty.foreach.list.index+$from}.</td>
		<td><b>{$r.id}</b></td>
		<td><a href="index.php?page=report&amp;id={$r.id}">{$r.title}</a></td>
		<td style="{if $r.type==1}color: #777;{/if}; text-align: center;">{$r.type_name|default:"---"|replace:" ":"&nbsp;"}</td>
		{* <td style="{if $r.status==1}color: #777;{/if}; text-align: center;">{($r.status_name|default:"---")}</td> *}
		{*
		<td>{if $r.status==2}<div style="width: 10px; height: 10px; background: #3366FF"> </div>
			{else}<div style="width: 10px; height: 10px; background: #ddd"> </div>{/if}</td>
		<td>{if $r.formated==1}<div style="width: 10px; height: 10px; background: orange"> </div>
			{else}<div style="width: 10px; height: 10px; background: #ddd"> </div>{/if}</td>
		*}
	</tr>
{/foreach}
</table>

<hr/>

<div id="pagging">
Liczba raportów: <b>{$total_count}</b>, Strony:
{section name=foo loop=$pages}
    <a {if $p==$smarty.section.foo.iteration-1} class="active"{/if}href="index.php?page=browse&amp;p={$smarty.section.foo.iteration-1}">{$smarty.section.foo.iteration}</a>
{/section}
</div>

{include file="inc_footer.tpl"}