<div id="dialog" title="Błąd" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
		<span class="message"></span>
	</p>
	<p><i><a href="">Refresh page.</a></i></p>
</div>

<table style="width: 100%; margin-top: 5px;">
	<tr>
		<td style="vertical-align: top"> 
			<div class="column" id="widget_text">
				<div class="ui-widget ui-widget-content ui-corner-all">			
				<div class="ui-widget ui-widget-header ui-helper-clearfix ui-corner-all">Document content:</div>
					<div id="content" style="padding: 5px; white-space: pre-wrap" class="annotations scrolling">{$content_inline|format_annotations}</div>
				</div>
			</div>
		</td>
		<td style="width: 280px; vertical-align: top;" id="cell_annotation_add">
			<div class="column" id="widget_annotation">
				<div class="ui-widget ui-widget-content ui-corner-all fixonscroll">			
				<div class="ui-widget ui-widget-header ui-helper-clearfix ui-corner-all">Annotation pad:</div>
					<div style="padding: 5px;" class="annotations scrolling">
						<input type="radio" name="default_annotation" id="default_annotation_zero" style="display: none;" value="" checked="checked"/>
					    {foreach from=$annotation_types item=set key=k name=groups}					    
					    	<div>
					    		&raquo; <a href="" label="#gr{$smarty.foreach.groups.index}" class="toggle_cookie"><b>{$k}</b> <small style="color: #777">[show/hide]</small></a>
					    	</div>
					    	<div id="gr{$smarty.foreach.groups.index}">
					    		<ul style="margin: 0px; padding: 0 30px">
									{foreach from=$set item=set key=set_name name=subsets}
					    			{if $set_name != "none"}
										<li>
					    				<a href="#" class="toggle_cookie" label="#gr{$smarty.foreach.groups.index}s{$smarty.foreach.subsets.index}"><b>{$set_name}</b> <small style="color: #777">[show/hide]</small></a>
										<ul style="padding: 0px 10px; margin: 0px" id="gr{$smarty.foreach.groups.index}s{$smarty.foreach.subsets.index}">
									{/if}					
									{foreach from=$set item=type}
										<li>
											<div>
												<input type="radio" name="default_annotation" value="{$type.name}" style="vertical-align: text-bottom" title="quick annotation &mdash; adds annotation for every selected text"/>
												<span class="{$type.name}"><a href="#" type="button" value="{$type.name}" class="an" style="color: #555" title="{$type.description}">{$type.name}</a></span>
											</div>
										</li>
									{/foreach}
					    			{if $set_name != "none"}
										</ul>
										</li>
									{/if}
									{/foreach}
								</ul>		
							</div>
						{/foreach}
					<span id="add_annotation_status"></span>
					<input type="hidden" id="report_id" value="{$row.id}"/>
					</div>
				</div>
			</div>		
		</td>
		
		<td style="width: 270px; vertical-align: top; display: none;" id="cell_annotation_edit">
			<div class="ui-widget ui-widget-content ui-corner-all" style="background: PeachPuff">			
			<div class="ui-widget ui-widget-header ui-helper-clearfix ui-corner-all">Dane adnotacji:</div>
				<table style="font-size: 8pt">
					<tr>
						<th style="text-align: right">Text:</th>
						<td id="annotation_text">-</td>
					</tr>
					<tr>
						<th style="text-align: right">Zakres:</th>
						<td id="annotation_range">-</td>
					</tr>
					<tr>
						<th style="text-align: right">Typ:</th>
						<td>{$select_annotation_types}<span id="annotation_redo_type"></span></td>
					</tr>
					<tr>
						<th></th>
						<td>
							<input type="button" value="zapisz" id="annotation_save" disabled="true"/>
							<input type="button" value="anuluj" id="annotation_redo" disabled="true"/>
							<input type="button" value="usuń" id="annotation_delete" disabled="true"/>
						</td>
					</tr>
				</table>
			</div>
			<div class="ui-state-highlight ui-corner-all ui-state-error" id="block_message" style="display: none; margin: 2px 0">
				<p>
					<span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span>
					Możliwość wstawiania anotacji jest zablokowana &mdash; <b><span id="block_reason"></span></b>
				</p>
			</div>
		</td>
	</tr>
</table>
</div>
