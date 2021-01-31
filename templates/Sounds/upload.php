<div class="page-header" id="banner">
  <div class="row">
    <div class="col-lg-8 col-md-7 col-sm-6">
      <h1>Upload One or More Files</h1>
    </div>
    <div class="col-lg-4 col-md-5 col-sm-6">
      <div class="sponsor">
      
      </div>
    </div>
  </div>
</div>

<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/sounds">Sounds</a></li>
  <li class="breadcrumb-item"><a href="/sounds/add">Add</a></li>
  <li class="breadcrumb-item active">Upload One or More Files</li>
</ol>

<div class="bs-docs-section">
    <div class="row">
      <div class="col-lg-6">        
        <?= $this->Form->create(null, ['type' => 'file', 'id' => 'uploadForm']) ?>
        <fieldset>
          <div class="form-group">
            <label for="filename">Select file(s) for upload</label>
            <input type="file" 
                   class="form-control-file" 
                   id="filename" 
                   name="filename[]" 
                   aria-describedby="fileHelp" 
                   multiple="multiple" 
                   accept="audio/*,.flac,.ogg">
          </div>
          <div class="form-group">
            <label for="directory">Full path to the upload directory</label>
      		<input type="text" 
      		       class="form-control" 
      		       id="directory" 
      		       name="directory" 
      		       aria-describedby="directoryHelp" 
      		       placeholder="/path/to/upload/directory">
      		<small id="directoryHelp" 
      		       class="form-text text-warning">
      		    The webserver must have read-write access to the directory
      		</small>
          </div>
          <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" 
              		 class="custom-control-input" 
              		 id="createdir" 
              		 name="createdir" 
              		 checked="checked" 
              		 value="1">
              <label class="custom-control-label" for="createdir">
                Create directory if it does not exist
              </label>
            </div>
          </div>
          <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" 
              		 class="custom-control-input" 
              		 id="overwrite" 
              		 name="overwrite" 
              		 checked="checked" 
              		 value="1">
              <label class="custom-control-label" for="overwrite">
                Overwrite existing files having the same name
              </label>
            </div>
          </div>
          <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" 
              		 class="custom-control-input" 
              		 id="metadata" 
              		 name="metadata" 
              		 checked="checked" 
              		 value="1">
              <label class="custom-control-label" for="metadata">
                Include file metadata
              </label>
            </div>
          </div>
          <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" 
              		 class="custom-control-input" 
              		 id="comments" 
              		 name="comments" 
              		 checked="checked" 
              		 value="1">
              <label class="custom-control-label" for="comments">
                Include embedded comments/tags
              </label>
            </div>
          </div>
          <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" 
              		 class="custom-control-input" 
              		 id="bpm" 
              		 name="bpm" 
              		 checked="checked" 
              		 value="1">
              <label class="custom-control-label" for="bpm">
                Include calculated beats-per-minute rate
              </label>
            </div>
          </div>
        </fieldset>
        <button type="submit" class="btn btn-primary">Upload Sound(s)</button>
        <?= $this->Form->end() ?>
      </div>
      <div class="col-lg-6">
        <p class="lead text-danger"> 
          If the "Create directory if it does not exist" option is on, the 
          webserver will try to create any directory path you have given it 
          which does not exist, so double-check your spelling to make sure your 
          files are actually uploaded to the directory you want!
        </p> 
        <p> 
          Sound files can be quite large, and this feature is going to be 
          limited by the PHP configuration settings on your host machine. The 
          most important configuration settings for the file upload feature are 
          the upper limits on the size of POST data and the size of uploaded 
          files, as well as the number of files that can be uploaded at once. 
        </p> 
      </div>
    </div>
</div>

<?php 
$this->Html->scriptStart(['block' => true]);
echo "$(document).ready(function()\n" .
"{\n" .
"    $(\"body\").prepend('<div id=\"overlay\" class=\"ui-widget-overlay\" style=\"z-index: 1001; display: none; width: 100%;\"></div>');\n" . 
"    $(\"body\").prepend(\"<div id='PleaseWait' style='display: none; text-align: center;'><img src='/img/spinner.gif'/><br/>This might take a while...<br/>Please do not close this tab or navigate away.<br/><br/></div>\");\n" . 
"});\n" . 
"$('#uploadForm').submit(function()\n" . 
"{\n" . 
"    $(\"#overlay, #PleaseWait\").show();\n" . 
"\n" .
"    return true; \n" . 
"});\n";
$this->Html->scriptEnd();
?> 
