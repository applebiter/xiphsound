<?php 
$errors = $form->getErrors();
$directoryErrors = isset($errors['directory']) ? $errors['directory'] : null;
?>

<div class="page-header" id="banner">
  <div class="row">
    <div class="col-lg-8 col-md-7 col-sm-6">
      <h1>Scan a Directory</h1>
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
  <li class="breadcrumb-item active">Scan a Directory</li>
</ol>

<div class="bs-docs-section">
    <div class="row">
      <div class="col-lg-6">
        <?= $this->Form->create($form, ['id' => 'scandirForm']) ?>
        <fieldset>
          <div class="form-group">
            <label for="directory">Full path to the music or sound file directory</label>
      		<input type="text" 
      		       class="form-control" 
      		       id="directory" 
      		       name="directory" 
      		       aria-describedby="directoryHelp" 
      		       placeholder="/path/to/music/directory">
      		<small id="directoryHelp" 
      		       class="form-text text-warning">
      		    The webserver must have read access to the directory
      		</small>
      		<?php if ($directoryErrors): ?>
      		<?php foreach ($directoryErrors as $error): ?>
      		<span class="text-danger"><?= h($error) ?></span><br/>
      		<?php endforeach ?>
      		<?php endif ?>
          </div>
          <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" 
              		 class="custom-control-input" 
              		 id="recurse" 
              		 name="recurse" 
              		 checked="checked" 
              		 value="1">
              <label class="custom-control-label" for="recurse">
                Scan subdirectories
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
        <button type="submit" class="btn btn-primary">Scan</button>
        <?= $this->Form->end() ?>
      </div>
      <div class="col-lg-6">
        <p class="lead text-info"> 
          This action will allow you to choose a directory containing valid 
          sound files and, optionally, nested subdirectories also containing 
          sound files.
        </p> 
        <p> 
          Valid sound file types:
        </p> 
        <ul> 
          <li>FLAC (audio/flac) having the .flac file type extension</li>
          <li>Ogg Vorbis (audio/ogg) having the .ogg file type extension</li>
        </ul>
        <p> 
          <strong>File metadata</strong> refers to basic, low-level information 
          about the file that you would like to store in the database alongside 
          the filename and location, such as playback duration, bits-per-sample, 
          bitrate, number of channels, and sample rate.
        </p> 
        <p> 
          <strong>Embedded comments/tags</strong> carry information about the 
          sound in the file, such as the artist's name, song title, date of 
          release, genre, the album it's from, the engineer, producer, label, 
          and track number.
        </p> 
        <p> 
          Optionally calculate the <strong>beats-per-minute rate</strong> of the 
          sound files. This provides another useful data point to store with the 
          other collected sound file information, but it does take noticeably 
          longer to scan a music directory than if beats-per-minute were not 
          being calculated. 
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
"$('#scandirForm').submit(function()\n" . 
"{\n" . 
"    $(\"#overlay, #PleaseWait\").show();\n" . 
"\n" .
"    return true; \n" . 
"});\n";
$this->Html->scriptEnd();
?> 
