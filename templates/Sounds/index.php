<?php  
 /**
 * ReadableRunningTime takes an integer representing milliseconds and converts
 * it into a more cognizable form for human readers.
 *
 * @param integer $secs
 * @return string
 */
function readableRunningTime(int $secs)
{
    $bit = [
        ' year'   => $secs / 31556926 % 12,
        ' week'   => $secs / 604800 % 52,
        ' day'    => $secs / 86400 % 7,
        ' hour'   => $secs / 3600 % 24,
        ' minute' => $secs / 60 % 60,
        ' second' => $secs % 60
    ];
    
    foreach ($bit as $k => $v)
    {
        if ($v > 1)
        {
            $ret[] = $v . $k . 's';
        }
        
        if ($v == 1)
        {
            $ret[] = $v . $k;
        }
    }
    
    array_splice($ret, count($ret)-1, 0, 'and');
    
    $ret[] = 'of total running time';
    
    return join(' ', $ret);
}
?>
<div class="page-header" id="banner">
  <div class="row">
    <div class="col-lg-12 text-center">
      <h1>Sounds on <span class="text-muted"><?= h($_SERVER['SERVER_NAME']) ?></span></h1>
      <?php if ($total): ?>
      <small class="text-info"><?= h(readableRunningTime($total / 1000)) ?></small>
      <?php endif ?>
    </div>
  </div>
</div>

<div class="bs-docs-section">
    <?= $this->Html->link(__('Add New Sound'), ['action' => 'add'], ['class' => 'btn btn-secondary btn-sm float-right']) ?>
    <h3>
    	<?= $this->Paginator->counter('range') ?>
    	<?= (isset($_GET['term']) && !empty($_GET['term'])) ? ' matching "' . h($_GET['term']) . '"' : '' ?>
    </h3>
    
    <form method="get" action="/">
    	<div class="row">  
    		<div class="form-group col">
              <input type="text" 
                     class="form-control" 
                     name="term" 
                     placeholder="Search for..." 
                     value="<?= (isset($_GET['term'])) ? h($_GET['term']) : '' ?>">  
            </div> 
            <div class="form-group col"> 
              <select class="form-control" name="tag">
                <option value="all"<?= (isset($_GET['tag']) && "all" == $_GET['tag']) ? " selected=\"selected\"" : '' ?>>All Tag Fields</option>
                <option value="title"<?= (isset($_GET['tag']) && "title" == $_GET['tag']) ? " selected=\"selected\"" : '' ?>>Title</option>
                <option value="albumartist"<?= (isset($_GET['tag']) && "albumartist" == $_GET['tag']) ? " selected=\"selected\"" : '' ?>>Album Artist</option>
                <option value="artist"<?= (isset($_GET['tag']) && "artist" == $_GET['tag']) ? " selected=\"selected\"" : '' ?>>Artist</option>
                <option value="album"<?= (isset($_GET['tag']) && "album" == $_GET['tag']) ? " selected=\"selected\"" : '' ?>>Album</option>
                <option value="label"<?= (isset($_GET['tag']) && "label" == $_GET['tag']) ? " selected=\"selected\"" : '' ?>>Label</option>
                <option value="year"<?= (isset($_GET['tag']) && "year" == $_GET['tag']) ? " selected=\"selected\"" : '' ?>>Year</option>
                <option value="producer"<?= (isset($_GET['tag']) && "producer" == $_GET['tag']) ? " selected=\"selected\"" : '' ?>>Producer</option>
                <option value="engineer"<?= (isset($_GET['tag']) && "engineer" == $_GET['tag']) ? " selected=\"selected\"" : '' ?>>Engineer</option>
                <option value="composer"<?= (isset($_GET['tag']) && "composer" == $_GET['tag']) ? " selected=\"selected\"" : '' ?>>Composer</option>
                <option value="genre"<?= (isset($_GET['tag']) && "genre" == $_GET['tag']) ? " selected=\"selected\"" : '' ?>>Genre</option>
              </select>
            </div>
            <div class="form-group col">
            	<button type="submit" class="btn btn-primary btn-block">Search</button>  
            </div>       
        </div>
    </form>
    
    <table class="table table-hover table-striped table-sm">
        <thead>
            <tr>
                <th scope="col" class="text-center"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col" class="text-center"><?= $this->Paginator->sort('artist') ?></th>
                <th scope="col" class="text-center"><?= $this->Paginator->sort('album') ?></th>
                <th scope="col" class="text-center"><?= $this->Paginator->sort('duration_timecode', 'Duration') ?></th>
                <th scope="col" class="text-center"><?= $this->Paginator->sort('genre') ?></th>
                <th scope="col" class="text-center"><?= $this->Paginator->sort('year') ?></th>
                <th scope="col" class="text-center"><?= $this->Paginator->sort('beats_per_minute', 'BPM') ?></th>
                <th scope="col" class="text-center"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sounds as $sound): ?>
            <tr>
                <td class="align-middle"><?= h($sound->title) ?></td>
                <td class="align-middle text-center"><?= h($sound->artist) ?></td>
                <td class="align-middle text-center"><?= h($sound->album) ?></td>
                <td class="align-middle text-center"><?= h($sound->duration_timecode) ?></td>
                <td class="align-middle text-center"><?= h($sound->genre) ?></td>
                <td class="align-middle text-center"><?= h($sound->year) ?></td>
                <td class="align-middle text-center"><?= h($sound->beats_per_minute) ?></td>
                <td class="align-middle text-center" style="white-space: nowrap;">
                	<a href="/sounds/view/<?= h($sound->id) ?>" title="Review Details"><img src="/img/ui/quaver.svg" alt="view" style="width:24px;" /></a>
                	<a href="/sounds/edit/<?= h($sound->id) ?>" title="Edit Sound"><img src="/img/ui/adjustment.svg" alt="edit" style="width:24px;" /></a>
                    <?= $this->Form->postLink('<img src="/img/ui/remove.svg" alt="delete" style="width:24px;" title="Delete Sound" />', [ 
                        'action' => 'delete', 
                        $sound->id], 
                        [ 
                            'confirm' => __('Are you sure you want to delete this sound? This will only delete the database entry, and will leave the file itself untouched.'), 
                            'escape' => false
                        ]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	
    <div>
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('First')) ?>
            <?= $this->Paginator->prev('< ' . __('Previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Next') . ' >') ?>
            <?= $this->Paginator->last(__('Last') . ' >>') ?>
        </ul>
        <p>
        	<?= $this->Paginator->counter('pages') ?>
        	<?= (isset($_GET['term']) && !empty($_GET['term'])) ? ' matching "' . h($_GET['term']) . '"' : '' ?>	
        </p>
    </div>
</div>
