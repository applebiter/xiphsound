<div class="page-header" id="banner">
  <div class="row">
    <div class="col-lg-8 col-md-7 col-sm-6">
      <h1>Editing: <?= ($sound->title) ? h($sound->title) : 'Untitled Sound' ?></h1>
    </div>
    <div class="col-lg-4 col-md-5 col-sm-6">
      <div class="sponsor">
      
      </div>
    </div>
  </div>
</div>

<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/sounds">Sounds</a></li>
  <li class="breadcrumb-item active">Editing: <?= ($sound->title) ? h($sound->title) : 'Untitled Sound' ?></li>
</ol>

<div class="bs-docs-section">
    <div class="row">
      <div class="col-lg-4">        
        <?php if ($albumArt): ?> 
        <?php echo $albumArt ?>
        <br/>
        <?php endif ?>
        <ul class="list-group"> 
          <li class="list-group-item list-group-item-action">
          	<?= $this->Html->link(__('View Details'), ['action' => 'view', $sound->id]) ?>
          </li>
          <li class="list-group-item list-group-item-action">
          	<?= $this->Form->postLink(__('Delete Sound'), ['action' => 'delete', $sound->id], ['confirm' => __('Are you sure you want to delete {0}?', $sound->filename)]) ?>
          </li>
          <li class="list-group-item list-group-item-action">
          	<?= $this->Html->link(__('List Sounds'), ['action' => 'index']) ?>
          </li>
          <li class="list-group-item list-group-item-action">
          	<?= $this->Html->link(__('New Sound'), ['action' => 'add']) ?>
          </li>  
        </ul>
      </div>
      <div class="col-lg-8">
        <?= $this->Form->create($sound) ?>
        <input type="hidden" name="id" value="<?= h($sound->id) ?>" />
        <fieldset>
          <div class="form-group">
            <label for="title">Title</label>
      		<input type="text" 
          		   class="form-control" 
          		   id="title" 
          		   name="title"
          		   value="<?= h($sound->title) ?>">
          </div>
          <div class="form-group">
            <label for="artist">Artist</label>
      		<input type="text" 
      		       class="form-control" 
      		       id="artist" 
      		       name="artist" 
      		       value="<?= h($sound->artist) ?>">
          </div>
          <div class="form-group">
            <label for="albumartist">Album Artist</label>
      		<input type="text" 
      		       class="form-control" 
      		       id="albumartist" 
      		       name="albumartist" 
      		       value="<?= h($sound->albumartist) ?>">
          </div>
          <div class="form-group">
            <label for="album">Album</label>
      		<input type="text" 
          		   class="form-control" 
          		   id="album" 
          		   name="album"
          		   value="<?= h($sound->album) ?>">
          </div>
          <div class="row">
              <div class="form-group col">
                <label for="tracknumber">Track Number</label>
          		<input type="text" 
              		   class="form-control" 
              		   id="tracknumber" 
              		   name="tracknumber" 
              		   placeholder="ex. 5/12" 
              		   value="<?= h($sound->tracknumber) ?>">
              </div>
              <div class="form-group col">
                <label for="discnumber">Disc Number</label>
          		<input type="text" 
              		   class="form-control" 
              		   id="discnumber" 
              		   name="discnumber" 
              		   placeholder="ex. 1/2" 
              		   value="<?= h($sound->discnumber) ?>">
              </div>
          </div>
          <div class="form-group">
            <label for="genre">Genre</label>
      		<input type="text" 
          		   class="form-control" 
          		   id="genre" 
          		   name="genre"
          		   value="<?= h($sound->genre) ?>">
          </div>
          <div class="row">
              <div class="form-group col">
                <label for="date">Year of Release</label>
          		<input type="text" 
              		   class="form-control" 
              		   id="year" 
              		   name="year"
              		   value="<?= h($sound->year) ?>">
              </div>
              <div class="form-group col">
                <label for="label">Label</label>
          		<input type="text" 
              		   class="form-control" 
              		   id="label" 
              		   name="label"
              		   value="<?= h($sound->label) ?>">
          </div>
          </div>
          <div class="form-group">
            <label for="copyright">Copyright</label>
      		<input type="text" 
          		   class="form-control" 
          		   id="copyright" 
          		   name="copyright"
          		   value="<?= h($sound->copyright) ?>">
          </div>
          <div class="form-group">
            <label for="composer">Composer</label>
      		<input type="text" 
          		   class="form-control" 
          		   id="composer" 
          		   name="composer"
          		   value="<?= h($sound->composer) ?>">
          </div>
          <div class="form-group">
            <label for="producer">Producer</label>
      		<input type="text" 
          		   class="form-control" 
          		   id="producer" 
          		   name="producer"
          		   value="<?= h($sound->producer) ?>">
          </div>
          <div class="form-group">
            <label for="engineer">Engineer</label>
      		<input type="text" 
          		   class="form-control" 
          		   id="engineer" 
          		   name="engineer"
          		   value="<?= h($sound->engineer) ?>">
          </div>
          <div class="form-group">
            <label for="comment">Comment</label>
      		<textarea class="form-control" 
          		      id="comment" 
          		      name="comment"
          		      rows="8"><?= h($sound->comment) ?></textarea>
          </div>
          <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" 
              		 class="custom-control-input" 
              		 id="writetofile" 
              		 name="writetofile" 
              		 checked="checked" 
              		 value="1">
              <label class="custom-control-label" for="writetofile">
                Write changes into the sound file as well as to the database
              </label>
            </div>
          </div>
        </fieldset>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <?= $this->Form->end() ?>
      </div>
    </div>
</div>
