<div class="page-header" id="banner">
  <div class="row">
    <div class="col-lg-8 col-md-7 col-sm-6">
      <h1><?= ($sound->title) ? h($sound->title) : 'Untitled Sound Details' ?></h1>
    </div>
    <div class="col-lg-4 col-md-5 col-sm-6">
      <div class="sponsor">
      
      </div>
    </div>
  </div>
</div>

<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/sounds">Sounds</a></li>
  <li class="breadcrumb-item active"><?= ($sound->title) ? h($sound->title) : 'Untitled Sound Details' ?></li>
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
          	<?= $this->Html->link(__('Edit Sound'), ['action' => 'edit', $sound->id]) ?>
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
        <ul class="nav nav-tabs" id="viewtabs">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#details">Details</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#format">Format</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#system">System</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#comment">Comment</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#album">Album</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#artist">Artist</a>
          </li>
        </ul>
        <div id="myTabContent" class="tab-content">
          <div class="tab-pane fade show active" id="details">
            <figure>
                <figcaption>Listen to <strong><?= ($sound->title) ? h($sound->title) : 'this untitled song' ?>:</strong></figcaption>
                <br/>
                <audio
                    preload="auto" 
                    controls 
                    type="<?= h($sound->mimetype) ?>" 
                    src="/sounds/play/<?= h($sound->id) ?>">
                        Your browser does not support the
                        <code>audio</code> element.
                </audio>
            </figure>            
            <table class="table">               
                <?php if ($sound->title) : ?>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($sound->title) ?></td>
                </tr>
                <?php endif ?>
                <?php if ($sound->artist) : ?>
                <tr>
                    <th><?= __('Artist') ?></th>
                    <td><?= h($sound->artist) ?></td>
                </tr>
                <?php endif ?>
                <?php if ($sound->album) : ?>
                <tr>
                    <th><?= __('Album') ?></th>
                    <td><?= h($sound->album) ?></td>
                </tr>
                <?php endif ?>
                <?php if ($sound->duration_timecode) : ?>
                <tr>
                    <th><?= __('Duration') ?></th>
                    <td><?= h($sound->duration_timecode) ?></td>
                </tr>
                <?php endif ?>
                <?php if ($sound->tracknumber) : ?>
                <tr>
                    <th><?= __('Track Number') ?></th>
                    <td><?= h($sound->tracknumber) ?></td>
                </tr>
                <?php endif ?>
                <?php if ($sound->discnumber) : ?>
                <tr>
                    <th><?= __('Disc Number') ?></th>
                    <td><?= h($sound->discnumber) ?></td>
                </tr>
                <?php endif ?>
                <?php if ($sound->year) : ?>
                <tr>
                    <th><?= __('Year') ?></th>
                    <td><?= h($sound->year) ?></td>
                </tr>
                <?php endif ?>
                <?php if ($sound->label) : ?>
                <tr>
                    <th><?= __('Label') ?></th>
                    <td><?= h($sound->label) ?></td>
                </tr>
                <?php endif ?>
                <?php if ($sound->copyright) : ?>
                <tr>
                    <th><?= __('Copyright') ?></th>
                    <td><?= h($sound->copyright) ?></td>
                </tr>
                <?php endif ?>
                <?php if ($sound->composer) : ?>
                <tr>
                    <th><?= __('Composer') ?></th>
                    <td><?= h($sound->composer) ?></td>
                </tr>
                <?php endif ?>
                <?php if ($sound->producer) : ?>
                <tr>
                    <th><?= __('Producer') ?></th>
                    <td><?= h($sound->producer) ?></td>
                </tr>
                <?php endif ?>
                <?php if ($sound->engineer) : ?>
                <tr>
                    <th><?= __('Engineer') ?></th>
                    <td><?= h($sound->engineer) ?></td>
                </tr>
                <?php endif ?>
                <?php if ($sound->genre) : ?>
                <tr>
                    <th><?= __('Genre') ?></th>
                    <td><?= h($sound->genre) ?></td>
                </tr>
                <?php endif ?>
                <tr>
                    <th><?= __('Size') ?></th>
                    <td><?= $this->Number->toReadableSize($sound->size * 1000) ?></td>
                </tr>
                <?php if ($sound->beats_per_minute) : ?>
                <tr>
                    <th><?= __('Beats Per Minute') ?></th>
                    <td><?= h($sound->beats_per_minute) ?></td>
                </tr>
                <?php endif ?>
            </table>
          </div>
          <div class="tab-pane fade" id="format">
            <table class="table"> 
                <tr>
                    <th><?= __('Mimetype') ?></th>
                    <td><?= h($sound->mimetype) ?></td>
                </tr>
                <tr>
                    <th><?= __('Extension') ?></th>
                    <td><?= h($sound->extension) ?></td>
                </tr>
                <tr>
                    <th><?= __('Bits Per Sample') ?></th>
                    <td><?= h($sound->bits_per_sample) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sample Rate') ?></th>
                    <td><?= h($sound->samplerate) ?> Hz</td>
                </tr>
                <tr>
                    <th><?= __('Bitrate') ?></th>
                    <td><?= h($sound->bitrate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Channels') ?></th>
                    <td><?= h($sound->channels) ?></td>
                </tr>
            </table>
          </div>
          <div class="tab-pane fade" id="system">
            <table class="table"> 
                <tr>
                    <th><?= __('ID') ?></th>
                    <td><?= h($sound->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('UUID') ?></th>
                    <td><?= h($sound->uuid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Location') ?></th>
                    <td><?= h($sound->location) ?></td>
                </tr>
                <tr>
                    <th><?= __('Filename') ?></th>
                    <td><?= h($sound->filename) ?></td>
                </tr>
                <tr>
                    <th><?= __('Duration in Milliseconds') ?></th>
                    <td><?= h($sound->duration_milliseconds) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($sound->created->i18nFormat(\IntlDateFormatter::LONG, 'America/New_York', null)) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($sound->modified->i18nFormat(\IntlDateFormatter::LONG, 'America/New_York', null)) ?></td>
                </tr>
            </table>
          </div>
          <div class="tab-pane fade" id="comment">
            <?= $this->Text->autoParagraph(h($sound->comment)); ?>
          </div>
          <div class="tab-pane fade" id="album">
          	<?php if ($albumTracks) : ?> 
          	<div id="music_list">
              	<figure>
                    <figcaption>Listen to <strong><?= ($sound->album) ? h($sound->album) : 'this untitled album' ?>:</strong></figcaption>
                    <br/>
                    <audio preload="auto" controls>
                    	<?php foreach ($albumTracks as $track) : ?>
                        <source type="<?= h($track->mimetype) ?>" src="/sounds/play/<?= h($track->id) ?>">
                        <?php endforeach ?>
                            Your browser does not support the
                            <code>audio</code> element.
                    </audio>
                </figure> 
                <ul class="list-group">
                <?php foreach ($albumTracks as $track) : ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="/sounds/view/<?= h($track->id) ?>"><?= h($track->tracknumber) ?> - <?= h($track->title) ?></a> 
                    <audio
                        preload="auto" 
                        controls 
                        type="<?= h($track->mimetype) ?>" 
                        src="/sounds/play/<?= h($track->id) ?>">
                            Your browser does not support the
                            <code>audio</code> element.
                    </audio>
                  </li>
                <?php endforeach ?>
                </ul>
            </div>
            <?php endif ?>
          </div>
          <div class="tab-pane fade" id="artist">
            <?php if ($artistTracks) : ?> 
            <ul class="list-group">
            <?php foreach ($artistTracks as $track) : ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="/sounds/view/<?= h($track->id) ?>">
                	<small>Album: <?= h($track->album) ?></small><br/>
                	<?= h($track->tracknumber) ?> - <?= h($track->title) ?></a> 
                <audio
                    preload="auto" 
                    controls 
                    type="<?= h($track->mimetype) ?>" 
                    src="/sounds/play/<?= h($track->id) ?>">
                        Your browser does not support the
                        <code>audio</code> element.
                </audio>
              </li>
            <?php endforeach ?>
            </ul>
            <ul class="pagination">
                <?= $this->Paginator->first('<< ') ?>
                <?= $this->Paginator->prev('< ') ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(' >') ?>
                <?= $this->Paginator->last(' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter('pages') ?></p>
            <?php endif ?>
          </div>
        </div>
      </div>
    </div>
</div> 

<?php 
$this->Html->scriptStart(['block' => true]);
/* 
 * creates an album playlist
 */
echo "(function () {\n" . 
"\n" . 
"    // Playlist array\n" . 
"    var files = [\n";
if ($albumTracks)
{
    foreach ($albumTracks as $track) 
    {
        echo "\"/sounds/play/$track->id\",\n";
    }
}
echo "    ];\n" . 
"\n" . 
"    // Current index of the files array\n" . 
"    var i = 0;\n" . 
"\n" . 
"    // Get the audio element\n" . 
"    var music_player = document.querySelector(\"#music_list audio\");\n" . 
"\n" . 
"    // function for moving to next audio file\n" . 
"    function next() {\n" . 
"        // Check for last audio file in the playlist\n" . 
"        if (i === files.length - 1) {\n" . 
"            i = 0;\n" . 
"        } else {\n" . 
"            i++;\n" . 
"        }\n" . 
"\n" . 
"        // Change the audio element source\n" . 
"        music_player.src = files[i];\n" .
"        music_player.play();\n" .
"    }\n" . 
"\n" . 
"    // Check if the player is selected\n" . 
"    if (music_player === null) {\n" . 
"        throw \"Playlist Player does not exists ...\";\n" . 
"    } else {\n" . 
"        // Start the player\n" . 
"        music_player.src = files[i];\n" . 
"\n" . 
"        // Listen for the music ended event, to play the next audio file\n" . 
"        music_player.addEventListener('ended', next, false)\n" . 
"    }\n" . 
"\n" . 
"})();";
/* 
 * 
 */
if (isset($_GET['page'])) 
{
    echo "\n$('#viewtabs li:last-child a').tab('show');\n";
}
$this->Html->scriptEnd();
?> 

