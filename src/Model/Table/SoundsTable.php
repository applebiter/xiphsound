<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use App\Model\Entity\Sound;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sounds Model
 *
 * @method \App\Model\Entity\Sound newEmptyEntity()
 * @method \App\Model\Entity\Sound newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Sound[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Sound get($primaryKey, $options = [])
 * @method \App\Model\Entity\Sound findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Sound patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Sound[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Sound|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sound saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sound[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sound[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sound[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sound[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SoundsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('sounds');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->uuid('uuid')
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('location')
            ->maxLength('location', 255)
            ->requirePresence('location', 'create')
            ->notEmptyString('location');

        $validator
            ->scalar('filename')
            ->maxLength('filename', 255)
            ->allowEmptyFile('filename');

        $validator
            ->scalar('mimetype')
            ->maxLength('mimetype', 150)
            ->requirePresence('mimetype', 'create')
            ->notEmptyString('mimetype');

        $validator
            ->scalar('extension')
            ->maxLength('extension', 150)
            ->requirePresence('extension', 'create')
            ->notEmptyString('extension');

        $validator
            ->scalar('size')
            ->maxLength('size', 150)
            ->allowEmptyString('size');

        $validator
            ->scalar('duration_timecode')
            ->maxLength('duration_timecode', 150)
            ->allowEmptyString('duration_timecode');
            
        $validator
            ->scalar('duration_milliseconds')
            ->maxLength('duration_milliseconds', 150)
            ->allowEmptyString('duration_milliseconds');

        $validator
            ->scalar('bits_per_sample')
            ->maxLength('bits_per_sample', 150)
            ->allowEmptyString('bits_per_sample');

        $validator
            ->scalar('bitrate')
            ->maxLength('bitrate', 150)
            ->allowEmptyString('bitrate');

        $validator
            ->scalar('channels')
            ->maxLength('channels', 150)
            ->allowEmptyString('channels');

        $validator
            ->scalar('samplerate')
            ->maxLength('samplerate', 150)
            ->allowEmptyString('samplerate');

        $validator
            ->scalar('beats_per_minute')
            ->maxLength('beats_per_minute', 150)
            ->allowEmptyString('beats_per_minute');

        $validator
            ->scalar('genre')
            ->maxLength('genre', 150)
            ->allowEmptyString('genre');

        $validator
            ->scalar('title')
            ->maxLength('title', 150)
            ->allowEmptyString('title');
            
        $validator
            ->scalar('albumartist')
            ->maxLength('albumartist', 150)
            ->allowEmptyString('albumartist');

        $validator
            ->scalar('album')
            ->maxLength('album', 150)
            ->allowEmptyString('album');
            
        $validator
            ->scalar('tracknumber')
            ->maxLength('tracknumber', 150)
            ->allowEmptyString('tracknumber');
            
        $validator
            ->scalar('discnumber')
            ->maxLength('discnumber', 150)
            ->allowEmptyString('discnumber');

        $validator
            ->scalar('artist')
            ->maxLength('artist', 150)
            ->allowEmptyString('artist');

        $validator
            ->scalar('year')
            ->maxLength('year', 150)
            ->allowEmptyString('year');

        $validator
            ->scalar('label')
            ->maxLength('label', 150)
            ->allowEmptyString('label');
            
        $validator
            ->scalar('copyright')
            ->maxLength('copyright', 150)
            ->allowEmptyString('copyright');
            
        $validator
            ->scalar('composer')
            ->maxLength('composer', 150)
            ->allowEmptyString('composer');

        $validator
            ->scalar('producer')
            ->maxLength('producer', 150)
            ->allowEmptyString('producer');

        $validator
            ->scalar('engineer')
            ->maxLength('engineer', 150)
            ->allowEmptyString('engineer');

        $validator
            ->scalar('comment')
            ->allowEmptyString('comment');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['uuid']), ['errorField' => 'uuid']);

        return $rules;
    }
    
    /**
     * BeforeMarshal method 
     * 
     * @param EventInterface $event
     * @param ArrayObject $data
     * @param ArrayObject $options
     */
    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options) 
    {
        if (isset($data['writetofile']) && $data['writetofile']) 
        {
            unset($data['writetofile']);
            
            $id = filter_var($data['id'], FILTER_VALIDATE_INT, ['min_range' => 1]);            
            $sound = $this->get($id);
            $filename = addcslashes($sound->location . DS . $sound->filename, '$');
            
            switch ($sound->mimetype) 
            {
                case 'audio/flac': 
                    
                    $metaflac = shell_exec('which metaflac');
                    $metaflac = $metaflac ? trim($metaflac) : $metaflac;
                    
                    if ($metaflac)
                    {
                        $command = "$metaflac ";
                        
                        $genre = $data['genre'] ? addcslashes(trim($data['genre']), '$') : "";
                        $command .= "--set-tag=\"genre=$genre\" ";
                        
                        $title = $data['title'] ? addcslashes(trim($data['title']), '$') : "";
                        $command .= "--set-tag=\"title=$title\" ";
                        
                        $albumartist = $data['albumartist'] ? addcslashes(trim($data['albumartist']), '$') : "";
                        $command .= "--set-tag=\"albumartist=$albumartist\" ";
                        
                        $album = $data['album'] ? addcslashes(trim($data['album']), '$') : "";
                        $command .= "--set-tag=\"album=$album\" ";
                        
                        $tracknumber = $data['tracknumber'] ? addcslashes(trim($data['tracknumber']), '$') : "";
                        $command .= "--set-tag=\"tracknumber=$tracknumber\" ";
                        
                        $discnumber = $data['discnumber'] ? addcslashes(trim($data['discnumber']), '$') : "";
                        $command .= "--set-tag=\"discnumber=$discnumber\" ";
                        
                        $artist = $data['artist'] ? addcslashes(trim($data['artist']), '$') : "";
                        $command .= "--set-tag=\"artist=$artist\" ";
                        
                        $year = $data['year'] ? addcslashes(trim($data['year']), '$') : "";
                        $command .= "--set-tag=\"year=$year\" ";
                        
                        $label = $data['label'] ? addcslashes(trim($data['label']), '$') : "";
                        $command .= "--set-tag=\"label=$label\" ";
                        
                        $copyright = $data['copyright'] ? addcslashes(trim($data['copyright']), '$') : "";
                        $command .= "--set-tag=\"copyright=$copyright\" ";
                        
                        $composer = $data['composer'] ? addcslashes(trim($data['composer']), '$') : "";
                        $command .= "--set-tag=\"composer=$composer\" ";
                        
                        $producer = $data['producer'] ? addcslashes(trim($data['producer']), '$') : "";
                        $command .= "--set-tag=\"producer=$producer\" ";
                        
                        $engineer = $data['engineer'] ? addcslashes(trim($data['engineer']), '$') : "";
                        $command .= "--set-tag=\"engineer=$engineer\" ";
                        
                        $comment = $data['comment'] ? addcslashes(trim($data['comment']), '$') : "";
                        $command .= "--set-tag=\"comment=$comment\" ";
                        
                        $command .= "\"$filename\"";
                        
                        shell_exec($command);
                    }
                    
                    break;
                    
                case 'audio/ogg': 
                    
                    $vorbiscomment = shell_exec('which vorbiscomment');
                    $vorbiscomment = $vorbiscomment ? trim($vorbiscomment) : $vorbiscomment;
                    
                    if ($vorbiscomment)
                    {
                        $command = "$vorbiscomment -w ";
                        
                        $genre = $data['genre'] ? addcslashes(trim($data['genre']), '$') : "";
                        $command .= "-t \"genre=$genre\" ";
                        
                        $title = $data['title'] ? addcslashes(trim($data['title']), '$') : "";
                        $command .= "-t \"title=$title\" ";
                        
                        $albumartist = $data['albumartist'] ? addcslashes(trim($data['albumartist']), '$') : "";
                        $command .= "-t \"albumartist=$albumartist\" ";
                        
                        $album = $data['album'] ? addcslashes(trim($data['album']), '$') : "";
                        $command .= "-t \"album=$album\" ";
                        
                        $tracknumber = $data['tracknumber'] ? addcslashes(trim($data['tracknumber']), '$') : "";
                        $command .= "-t \"tracknumber=$tracknumber\" ";
                        
                        $discnumber = $data['discnumber'] ? addcslashes(trim($data['discnumber']), '$') : "";
                        $command .= "-t \"discnumber=$discnumber\" ";
                        
                        $artist = $data['artist'] ? addcslashes(trim($data['artist']), '$') : "";
                        $command .= "-t \"artist=$artist\" ";
                        
                        $year = $data['year'] ? addcslashes(trim($data['year']), '$') : "";
                        $command .= "-t \"year=$year\" ";
                        
                        $label = $data['label'] ? addcslashes(trim($data['label']), '$') : "";
                        $command .= "-t \"label=$label\" ";
                        
                        $copyright = $data['copyright'] ? addcslashes(trim($data['copyright']), '$') : "";
                        $command .= "-t \"copyright=$copyright\" ";
                        
                        $composer = $data['composer'] ? addcslashes(trim($data['composer']), '$') : "";
                        $command .= "-t \"composer=$composer\" ";
                        
                        $producer = $data['producer'] ? addcslashes(trim($data['producer']), '$') : "";
                        $command .= "-t \"producer=$producer\" ";
                        
                        $engineer = $data['engineer'] ? addcslashes(trim($data['engineer']), '$') : "";
                        $command .= "-t \"engineer=$engineer\" ";
                        
                        $comment = $data['comment'] ? addcslashes(trim($data['comment']), '$') : "";
                        $command .= "-t \"comment=$comment\" "; 
                        
                        $command .= "\"$filename\"";
                        
                        shell_exec($command);
                    }
                    
                    break;
            }
        }
    }
    
    /**
     * Assign a UUID to the sound
     * 
     * @param Event $event
     * @param Sound $sound
     * @param ArrayObject $options
     */
    public function beforeSave(Event $event, Sound $sound, ArrayObject $options) 
    {
        if ($sound->isNew()) 
        {
            $sound->uuid = $this->guidv4();
        }
    }
    
    /**
     * Generates a valid UUID v4 
     * 
     * @return string
     */
    public function guidv4() 
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    
    /**
     * FindSearch method 
     * 
     * @param Query $query
     * @param array $options
     * @return \Cake\ORM\Query
     */
    public function findSearch(Query $query, array $options)
    {
        $term = filter_var($options['term'], FILTER_SANITIZE_STRING);
        $tag = filter_var($options['tag'], FILTER_SANITIZE_STRING);
        
        switch ($tag) 
        {
            case 'all': 
                
                $query = $this->find('all', [ 
                    'conditions' => [ 
                        'OR' => [ 
                            'Sounds.title LIKE' => '%'.$term.'%', 
                            'Sounds.artist LIKE' => '%'.$term.'%',
                            'Sounds.albumartist LIKE' => '%'.$term.'%',
                            'Sounds.album LIKE' => '%'.$term.'%',
                            'Sounds.year LIKE' => '%'.$term.'%',
                            'Sounds.label LIKE' => '%'.$term.'%',
                            'Sounds.composer LIKE' => '%'.$term.'%',
                            'Sounds.producer LIKE' => '%'.$term.'%',
                            'Sounds.engineer LIKE' => '%'.$term.'%'
                        ]
                    ] 
                ]);
                
                break;
                
            case 'title':                
                $query = $query->where(['Sounds.title LIKE' => '%'.$term.'%']);                
                break;
                
            case 'albumartist':
                $query = $query->where(['Sounds.albumartist LIKE' => '%'.$term.'%']);
                break;
                
            case 'artist':                
                $query = $query->where(['Sounds.artist LIKE' => '%'.$term.'%']);                
                break;
                
            case 'album':                
                $query = $query->where(['Sounds.album LIKE' => '%'.$term.'%']);                
                break;
                
            case 'year':                
                $query = $query->where(['Sounds.year LIKE' => '%'.$term.'%']);                
                break;
                
            case 'label':                
                $query = $query->where(['Sounds.label LIKE' => '%'.$term.'%']);                
                break;
                
            case 'composer':                
                $query = $query->where(['Sounds.composer LIKE' => '%'.$term.'%']);                
                break;
                
            case 'producer':                
                $query = $query->where(['Sounds.producer LIKE' => '%'.$term.'%']);                
                break;
                
            case 'engineer':                
                $query = $query->where(['Sounds.engineer LIKE' => '%'.$term.'%']);                
                break;
        }
        
        return $query;
    }
    
    /**
     * FindAlbum method 
     * 
     * @param Query $query
     * @param array $options
     * @return \Cake\ORM\Query
     */
    public function findAlbum(Query $query, array $options) 
    {
        $query = $query->where(['Sounds.location' => $options['location']])
        ->order(['Sounds.tracknumber' => 'ASC']);
        
        return $query;
    }
    
    /**
     * FindArtist method
     *
     * @param Query $query
     * @param array $options
     * @return \Cake\ORM\Query
     */
    public function findArtist(Query $query, array $options)
    {
        $query = $query->where(['Sounds.artist LIKE' => '%'.$options['artist'].'%'])
        ->order([ 
            'Sounds.location' => 'ASC', 
            'Sounds.tracknumber' => 'ASC'
        ]);
        
        return $query;
    }
}
