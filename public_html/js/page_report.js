$(function(){
	$("#edit").markItUp(mySettings);
	$(".token").removeAttr('title');
});

function deleteEventSlot(handler){
	var $eventHandler = $(handler);
	var slotId = $eventHandler.prev().prev().prev().attr('slotid');
	var eventId = $("#eventDetailsId").text();
	var xPosition = $("#flagsContainer").offset().left-$(window).scrollLeft();
	var yPosition = $("#flagsContainer").offset().top - $(window).scrollTop();
	
	
	
	$dialogBox = 
		$('<div class="deleteDialog annotations">Czy usunąć slot #'+slotId+'?</div>')
		.dialog({
			modal : true,
			title : 'Potwierdzenie usunięcia',
			buttons : {
				Cancel: function() {
					$dialogBox.dialog("close");
				},
				Ok : function(){
					jQuery.ajax({
						async : false,
						url : "index.php",
						dataType : "json",
						type : "post",
						data : { 
							ajax : "report_delete_event_slot", 
							slot_id : slotId
						},				
						success : function(data){
							ajaxErrorHandler(data,
								function(){							
									$('#eventSlotsTable td[slotid="'+slotId+'"]').parent().remove();
									$slotCount = $('#eventTable a[eventid="'+eventId+'"]').parent().next().next().next();
									$slotCount.text(parseInt($slotCount.text())-1);
									cancelAddAnnotation();
									$dialogBox.dialog("close");
								},
								function(){
									$dialogBox.dialog("close");
									deleteEventSlot(handler);
								}
							);								
						}
					});	
				
				}
			},
			close: function(event, ui) {
				$dialogBox.dialog("destroy").remove();
				$dialogBox = null;
			}

		});
		$dialogBox.dialog("option", "position",[xPosition- $dialogBox.width(), yPosition]);	
}


//report flags management
$(function(){
	$("span.corporaFlag").click(function(){
	});
	
	$("span.corporaFlag").click(function(){
		setFlag($(this));
	});
});

function setFlag($element){
	var xPosition = $("#flagsContainer").offset().left - $(window).scrollLeft() + $("#flagsContainer").width() - 25;
	var yPosition = $("#flagsContainer").offset().top - $(window).scrollTop() + $("#flagsContainer").height();		
	$dialogBox = $($("#flagStates").html()).dialog({
		modal : true,
		title : 'Change state',
		width : '200px', 
		buttons : {
			Cancel: function() {
				$dialogBox.dialog("close");
			}
		},
		close: function(event, ui) {
			$dialogBox.dialog("destroy").remove();
			$dialogBox = null;
		}			
	});
	$dialogBox.dialog("option", "position",[xPosition-$dialogBox.width(), yPosition]);
	$dialogBox.find("span.flagState").click(function(){
		$flag = $(this);
		var _data = { 
			ajax : "report_set_report_flags", 
			report_id : $element.attr('report_id'),
			cflag_id : $element.attr('cflag_id'),
			flag_id : $(this).attr('flag_id')
		}
		$.ajax({
			async : false,
			url : "index.php",
			dataType : "json",
			type : "post",
			data : _data,				
			success : function(data){
				ajaxErrorHandler(data,
					function(){ 
						$element.children("img:first").attr('src','gfx/flag_'+_data.flag_id+'.png');
						$element.attr('title',$element.attr('title').split(":")[0]+": "+$flag.attr('title'));
						$dialogBox.dialog("close");
					}, 
					function(){
						$dialogBox.dialog("close");
						setFlag($element);
					}
				);
			}
		});
	});
	

}