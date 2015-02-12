<div class="row">
    <div class="col-sm-6">
    <?php if(!isset($stories)) : ?>
        <div class="visible-xs">
                <div class="panel panel-turq">
                    <h2>No Records for this Culture</h2>
                    <h4>Why not add some using the form below.</h4>
                </div>
                <div class="panel panel-green-sea">
                <h4>Tip 1 - This Form is Fast</h4>
                <p>Every field in the form has defaults to speed up data entry. Try it - click submit now without entering any data</p>
                </div>
                <div class="panel panel-green-sea">
                    <h4>Tip 2 - Look Down</h4> 
                    <p>After you submit the form your records will appear below the form.</p>
                </div>
                </div>
    <?php endif; ?>
        <div class="panel noprint">
        <h2 class="panel-title">Add Record</h2>
<!-- Load the form -->
            <?php echo $task_data; ?>
        </div>

<script>
var surfaceArea = new Array(32, 95, 200, 401, 962, 2500, 7500, 17500, 22500, 962, 2827, 7854, 17671);
var smallVolCorrection = new Array(0.1, 0.2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)

function convert_unit()
{
	from_index = document.area.from_unit.selectedIndex;
	var fromArea = surfaceArea[from_index];
	var fromVol = smallVolCorrection[from_index];
	to_index = document.area.to_unit.selectedIndex;
	var toArea = surfaceArea[to_index];
	var toVol = smallVolCorrection[to_index];
    
    var suspensionVolume = document.area.denominator.value * fromVol * toVol;
	document.getElementById("vol_resuspend_media").innerHTML = Math.round(suspensionVolume * 100) / 100;	
	var conversionFactor = toArea / fromArea;
	document.getElementById("formula").innerHTML = conversionFactor;
	var suspension = document.area.numerator.value * conversionFactor * fromVol * toVol;
	document.getElementById("vol_suspension").innerHTML = Math.round(suspension * 100) / 100;
	var finalVessel = document.area.numb_final_flasks.value;
	document.getElementById("final_vessel").innerHTML = finalVessel;
	var finalVolume = document.area.finalCultureVolume.value; //dont alter this as this is altered on the form
	document.getElementById("vol_new_media").innerHTML = Math.round((finalVolume - suspension) * 100) / 100;
	var newFlasks = document.area.numb_final_flasks.value;
	document.getElementById("numb_initial_flasks").innerHTML = Math.ceil((suspension * newFlasks)/suspensionVolume);
	
	if(isNaN(document.length_con.from_value.value))
		document.getElementById("to_value").innerHTML = "Not a valid number.";
	else
		document.getElementById("to_value").innerHTML = factor * document.area.from_value.value;
}
</script>
    <div class="col-sm-6">
    <button type="button" class="btn btn-phenol btn-block" data-toggle="collapse" data-target="#calculator">Passage Calculator</button>
        <div class="collapse panel" id="calculator">
            <form class="grid-form" name="area">
                <fieldset>
                <div data-row-span="2">
                    <div data-field-span="1">
                        <label>Current Culture Vessel</label>
                            <select name=from_unit onChange="convert_unit()">
                            <option> 96 Well
                            <option> 48 Well
                            <option> 24 Well
                            <option> 12 Well
                            <option> 6 Well
                            <option> T25
                            <option> T75
                            <option> T175
                            <option> T225
                            <option> 35mm Dish
                            <option> 60mm Dish
                            <option> 100mm Dish
                            <option> 150mm Dish
                            </select>
                    </div>
                    <div data-field-span="1">
                        <label>Required Culture Vessel</label>
                        <select name=to_unit onChange="convert_unit()" >
                            <option> 96 Well
                            <option> 48 Well
                            <option> 24 Well
                            <option> 12 Well
                            <option> 6 Well
                            <option> T25
                            <option> T75
                            <option> T175
                            <option> T225
                            <option> 35mm Dish
                            <option> 60mm Dish
                            <option> 100mm Dish
                            <option> 150mm Dish
                            </select>
                    </div>
                </div>
                <p class="sr-only">Conversion Factor = <span id="formula">1</span></p>
                <div data-row-span="2">
                    <div data-field-span="1">
                        <label>Number of New Culture Vessels Required</label>
                        <input type="text" name="numb_final_flasks" value="1" size="2" maxlength="12">
                    </div>
                    <div data-field-span="1">
                        <label>Final Volume of Media Per Culture Vessel</label>
                        <input type="text" name="finalCultureVolume" value="1" size="12" maxlength="12">
                    </div>
                </div>
                <div data-row-span="1">
                    <div data-field-span="1">
                        <label>Split Ratio</label>
                        <input type="text" name="numerator" value="1" size="2" maxlength="5" style="width: auto">
                        <label style="color:#2C3E50">:</label>
                        <input type="text" name="denominator" value="1" size="2" maxlength="5" style="width: auto">
                    </div>
                </div>
            <input type=button class="btn btn-clouds" value="Calculate" onClick="convert_unit()">
            </fieldset>
            </form>

    <h3 style="color:#2C3E50">Result</h3>
    <p style="color:#2C3E50">1. Take <strong><Span id="numb_initial_flasks">0</span></strong> of your original culture vessels (well, flask or dish), 
    <br />2. After any enzyme treatments to remove adherent cells from the vessel(s) re-suspend all the cells in each vessel in a total of <strong><Span id="vol_resuspend_media">0</span></strong> mls fresh media.
    <br />3. Take <strong><Span id="vol_suspension">0</span></strong> ml of the suspension and place in one of <strong><Span id="final_vessel">0</span></strong> new vessel(s) with <strong><Span id="vol_new_media">0</span></strong> mls fresh media.</p>
        </div>
    </div>
        <div class="col-sm-6">
            <a href="<?php echo base_url('project/edit/'.$project_id); ?>" class="btn btn-default btn-block">Share This Culture</a>
        </div>
    </div>
<!-- Load the tasks -->
<?php if(isset($stories) || isset($tasks) || isset($tests) || isset($done)) { ?>
    <div class="col-sm-6" id="viewrecord">
        <?php
            if(isset($stories)) {
                $i = 0;
                //----------------usort function below orders the foreach loop by the date_created second level key
                usort($stories, function($x, $y)
                    //-----each post has a date_created time which can be used to sort. But using due date as it can be updated
                    //{return $x['date_created']<$y['date_created']?-1:$x['date_created']!=$y['date_created'];
                    {return $x['due_date']<$y['due_date']?-1:$x['due_date']!=$y['due_date'];
                    });
                //-----------------end of usort
                foreach (array_reverse($stories) as $value) {
                    $i++;
                    $this->load->view('templates/single_task', array('i' => $i, 'project' => $project_id, 'task' => $value, 'vial' => $vial_id, 'photo' => $photo));
                }
            }
            else { echo <<<LABEL
                <div class="hidden-xs">
                <div class="panel panel-turq">
                    <h2>No Records for this Culture.</h2>
                    <h4>Why not add some using the form on this page.</h4>
                </div>
                <div class="panel panel-green-sea">
                <h4>Tip 1 - This Form is Fast.</h4>
                <p>Every field in the form has defaults to speed up data entry. Try it - click submit now without entering any data</p>
                </div>
                <div class="panel panel-green-sea">
                    <h4>Tip 2 - Don't Worry About Mistakes</h4> 
                    <p>You can edit or delete individual records later if you make a mistake.</p>
                </div>
                </div>
LABEL;
}
 } ?>
    </div>
</div>
