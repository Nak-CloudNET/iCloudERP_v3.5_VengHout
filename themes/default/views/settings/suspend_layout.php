<script>
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    function drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        ev.target.appendChild(document.getElementById(data));
    }
</script>
<?php if ($Owner) {
    echo form_open('system_settings/suppend_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('suppend_layout'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('list_results'); ?></p>
                <div class="table-responsive drap_div" style="height: auto; border: 1px solid #ccc; padding: 10px; overflow: scroll;">
                    <?php
                    foreach($suspend as $rows)
                    {
                        ?>
                        <div draggable="true" ondragstart="drag(event)" style="height: 100%; border: 1px solid #ccc;" id="rom_ drag1 <?=$rows->name;?>" data="<?=$rows->id;?>" class="btn-info btn suspend-button btn_drag" value="1" type="button">
                            <span>Room <?=$rows->name?></span>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <br/>
                <label>Rearrange</label>
                <div class="table-responsive drop_div" id="div1" ondrop="drop(event)" ondragover="allowDrop(event)"" style="padding: 10px; height: auto; border: 1px solid #ccc; overflow: scroll;">
                    
                </div>
                <label><button class="btn btn-info save_drag_drop" type="button"><?=lang('save')?></button></label>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12" style="height: 400px; border: 1px solid #ccc; padding: 10px; overflow: scroll;">
                <div draggable="true" ondragstart="drag(event)"  style="padding: 15px; background: #ccc; height: 10px; width: ">
                    daasd
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
    <?php } ?>
<?php /* if ($action && $action == 'add') {
    echo '<script>$(document).ready(function(){$("#add").trigger("click");});</script>';
} */
?>
<script type="text/javascript">
    $("document").ready(function(){
        $(".save_drag_drop").on("click", function(e){
            var roomArr = [];
            $('.drop_div div').each(function(i){
                roomArr[i] = $(this).attr("data");
            });

            $.ajax({
                url : "<?= site_url('system_settings/suspend_layout') ?>",
                type : "post",
                data : { data : roomArr},
                success : function (){

                }
            });
        });
    });
</script>


