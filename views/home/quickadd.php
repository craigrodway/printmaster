<h2>Install consumable</h2>

<p>Click the printer, and the available consumables will be shown. Click the consumable name to install it.</p>

<div class="box"><div>

	<form action="install.php" method="get" id="quickinstall">
		<input type="hidden" name="redirect" value="<?php echo fURL::get() ?>" />
		<input type="hidden" name="action" value="install" />
		<input type="hidden" name="printer_id" value="" />
		<input type="hidden" name="consumable_id" value="" />
	</form>
		
	<table class="form">
	
		<tr>
			
			<td align="center"><label for="printer_id"><strong>Printer</strong></label></td>
			<td align="center"><label><strong>Consumable</strong></label></td>
			
		</tr>
		
		<tr>
			
			<td class="input" width="50%">
			
				<div> <!-- style="height:300px; overflow-y: scroll;">-->
					<input type="search" id="printer_search" placeholder="Search for a printer..." style="width: 100%; padding: 4px; margin: 0 0 10px 0">
					<ul class="printerlist"><?php
						foreach($printers as $p){
							printf('<li class="printer-%s"><a href="#" model="%d" printer="%d">%s</a></li>',
								($p->colour == 1) ? 'colour' : 'mono',
								$p->model_id,
								$p->id,
								$p->name
							);
						}
					?></ul>
				</div>
			
			</td>
			
			<td class="input" width="50%">
				<div id="buttoncontainer" style="text-align:center"></div>
			</td>
			
		</tr>
	
	</table>

</div></div>


<script type="text/javascript">
var consumables = <?php echo $models_consumables_json; ?>;

// Printer search
$('#printer_search').fastLiveFilter('.printerlist', {
	callback: function(total) {
		if (total == 1) {
			//;
			setTimeout('$(".printerlist li:visible a").trigger("click")', 250);
		}
	}
}).change(function() {
	if ($(this).val() == "") {
		// Clear container's previous contents
		$('#buttoncontainer').html("");
	}
}).focus();


// Printer list click
$('ul.printerlist li a').click(function(){
	
	// Clear container's previous contents
	$('#buttoncontainer').html("");
	
	// Get model ID and append to text
	var model = "model_" + $(this).attr("model");
	// Get model node of JSON object
	var cnode = consumables[model];
	
	// If there is a node for this model
	if(cnode){
		
		// Yes - loop through consumables and create buttons
		for(var i = 0; i < cnode.length; i++){
			createButton(cnode[i].id, cnode[i].name, cnode[i].colours, cnode[i].qty, cnode[i].status);
		}
		
		if (i > 1) {
			var sel_btn = $("<button id='install-selected'>Install Selected</button>");
			sel_btn.click(installSelected);
			$("#buttoncontainer").append(sel_btn);
			$("#buttoncontainer .con-check").show();
			
			
		}
		
	} else {
		
		// No consumables - inform user
		$('#buttoncontainer').html("There are no consumables defined for this model of printer.");
	
	}
	
	// Update the hidden form with the ID of the printer that was chosen
	$('input[name=printer_id]').val($(this).attr("printer"));
	
	// Update the list UI to indicate which printer is currently selected
	$('ul.printerlist li a').removeClass("current");
	$(this).addClass("current");
	
	// Cancel default click event
	return false;
	
});


// Create a consumable button
createButton = function(id, name, colours, qty, status){
	
	// Text is based on name and quantity
	var text = name + " (" + qty + ") ";
	var ordered = "";
	if(status == '0'){
		ordered = "On Order";
	}
	
	if(colours.length == 1){
		var colour = colours[0];
	}
	
	// Create button element
	var check = $('<input type="checkbox" class="con-check" value="' + id + '">');
	var btn = $('<input type="button" class="btn btn_colour_' + colour + '" value="' + text + ordered + '" consumable="' + id + '">');
	
	// Attach event to button click
	if(qty > 0){
		// Only allow installation if there is a quantity
		btn.click(function(){ installConsumable($(this)); });
	} else {
		// Show a message if none of these are in stock
		btn.click(function(){ alert('This consumable is not in stock.'); return false; });
	}
	
	var br = $('<br>');
	
	// Append element to container
	$('#buttoncontainer').append(check).append(btn).append(br);
	
};

// Event when installing a consumable
installConsumable = function(e){
	// Get ID attribute from element and update hidden form element
	$('input[type=hidden][name=consumable_id]').val(e.attr("consumable"));
	// Submit the form
	$('form#quickinstall').submit();
}

installSelected = function() {
	// Get the selected items
	var $checks = $(this).siblings("input[class=con-check]:checked");
	var $form = $('form#quickinstall');
	
	$checks.each(function(el) {
		var id = $(this).val();
		$form.append($("<input>").attr("type", "hidden").attr("name", "consumable_ids[]").val(id));
	});
	
	// Submit the form
	$form.submit();
}
</script>