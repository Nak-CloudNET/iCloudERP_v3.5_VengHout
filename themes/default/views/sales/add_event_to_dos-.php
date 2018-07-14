<div class="modal-dialog modal-lg" style="width:1000px; height: auto;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h2 class="modal-title" id="myModalLabel"><?= lang('event_to_do_title'); ?></h2>
        </div>
		<div class="modal-body" id="mytab">
			<ul class="nav nav-tabs">
				<li class="active" onclick="openTab(event, 'active')">
					<a data-toggle="tab" href="#event" id="iEvent"><?= lang('event') ?></a>
				</li>
				<li onclick="openTab(event, 'to_do')"><a data-toggle="tab" href="#to_do" id="i2Do"><?= lang('to_do') ?></a></li>
			</ul>
			<div class="tab-content">
				<div id="event" class="tab-pane fade in active event">
				
				</div>
			</div>
		</div>
	</div>
</div>
<?= $modal_js ?>
<script type="text/javascript">
	function openTab(evt, cityName) {
		var events = $('#event');
		var to_do = $('#to_do');
		
		if(cityName =="active"){
			to_do.each(function(){
				var att = $(this).find('.allAll');
				if(att){
					att.removeAttr('required');
					att.validate().resetForm();
					att.removeClass("has-error");
				}
			});
			events.each(function(){
				var att = $(this).find('.allAll');
				if(att){
					att.prop('required',true);
					att.attr('required', 'required');
				}
			});
			
		}else{
			
			to_do.each(function(){
				var att = $(this).find('.allAll');
				if(att){
					att.prop('required',true);
					att.attr('required', 'required');
				}
			});
			events.each(function(){
				var att = $(this).find('.allAll');
				if(att){
					att.removeAttr('required');
					att.validate().resetForm();
					att.removeClass("has-error");
				}
			});
		}
	}
</script>