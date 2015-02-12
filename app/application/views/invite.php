
<div class="row">
    <div class="col-xs-12 col-sm-6">
    <?php if($success == '1') : ?>
    <div class="alert alert-success">
        <p><strong>Invite Sent.</strong> Feel free to send another.</p>
    </div>
    <?php endif; ?>
        <div class="panel" style="padding-top:30px; padding-bottom:30px;">
            <p>Use this form to invite a lab mate to join your lab group.</p>
            <p>Once you lab mates have joined you will see them in the sharing options. All your data stays private by default.</p>
            <hr/>
            <?php echo validation_errors('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>'); ?>
            <?php if(isset($email_error)) : ?>
                <div class="alert alert-danger"><?php echo $email_error; ?><button type="button" class="close" data-dismiss="alert">×</button></div>
            <?php endif; ?>
            <form class="form-horizontal" method="post" action="<?php echo base_url('invite/validate'); ?>">
            <div class="form-group">
                <label for="name" class="col-sm-3 control-label">Their Name</label>
                <div class="col-sm-9">
                    <input type="text" autofocus class="form-control" name="name" id="name" placeholder="Joe" />
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-3 control-label">Their email</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="email" id="email" placeholder="joe@bloggs.com" />
                </div>
            </div>
            <div class="hidden">
                <label class="control-label" for="user_name">User Name</label>
                <div class="controls">
                    <?php echo form_hidden('user_name', $name); ?>
                </div>
                <label class="control-label" for="lab">Lab</label>
                <div class="controls">
                    <?php echo form_hidden('lab', $lab); ?>
                </div>
            </div>
            <!--<div class="form-group">
                <label for="labgroup" class="col-sm-3 control-label">CellKulture Lab Group Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="labgroup" id="labgroup" placeholder="Prof A's Lab" value="<?php echo set_value('email', $email); ?>" />
                    <span class="help-block">Your current lab name will be shown by default. You can change it before sending the invite but this will also affect any cultures you are currently sharing</span>
                </div>
            </div>-->
            <div class="form-group">
            <div class="col-sm-6 col-sm-offset-3">
                <button type="submit" name="save" class="btn btn-lg btn-phenol btn-block">Send email</button>
            </div>
            </div>
            </form>
        </div>
    </div>
    

    
    <div class="col-xs-12 col-sm-6">
        <div class="panel">
            <h3>email Preview</h3>
            <hr>
            <p>Dear <span id=original></span>,</p>
            <p><strong><?php echo $name; ?></strong> would like to invite you to join the lab group <strong>"<?php echo $lab; ?>"</strong> on CellKulture.com</p>
            <p>To join this lab group simply click the button below</p>
            <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <p><button class="btn btn-block btn-turq">Join <?php echo $lab; ?></button></p>
            </div>
            </div>
            <p class="concrete">Regardless of your lab group your data always remains private by default. Once you have joined a lab group you can share as much or as little data as you like</p>
            
            <p>Regards<br />The CellKulture Team</p>
            <img src="<?php echo base_url('/assets/img/toplogo.png'); ?>">
            
       </div>
    </div>
</div>

<script>
/* A convenience function to reverse a string, and 
 * one to set the content of an element.
 */
 
function reverse(s){return s.split('').reverse().join('')}
 
function set(el,text){
 while(el.firstChild)el.removeChild(el.firstChild);
 el.appendChild(document.createTextNode(text))}
 
/* setupUpdater will be called once, on page load.
 */
 
function setupUpdater(){
 var input=document.getElementsByTagName('input')[0]

   , orig=document.getElementById('original')
   , oldText=input.value
   , timeout=null;
 
/* handleChange is called 50ms after the user stops 
   typing. */
 function handleChange(){
  var newText=input.value;
  if (newText==oldText) return; else oldText=newText;

  set(orig, newText);
 }
 
/* eventHandler is called on keyboard and mouse events.
   If there is a pending timeout, it cancels it.
   It sets a timeout to call handleChange in 50ms. */
 function eventHandler(){
  if(timeout) clearTimeout(timeout);
  timeout=setTimeout(handleChange, 50);
 }
 
 input.onkeydown=input.onkeyup=input.onclick=eventHandler;
}
 
setupUpdater();
document.getElementsByTagName('input')[0].focus();
</script>
            
 
