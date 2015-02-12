<?php echo $error;?>

<?php echo form_open_multipart('upload/do_upload');?>
<p>this is the right one</p>
<input type="file" name="userfile" size="20" />

<br /><br />

<input type="submit" value="upload" />

</form>