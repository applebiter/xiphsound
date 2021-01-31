<?php
declare(strict_types=1);

namespace App\Form;

use App\Model\Entity\Sound;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Exception;

class UploadForm extends Form
{
    protected $Sounds;
    
    /**
     * 
     * {@inheritDoc}
     * @see \Cake\Form\Form::_buildSchema()
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema->addField('directory', 'string')
                      ->addField('createdir', ['type' => 'boolean'])
                      ->addField('metadata', ['type' => 'boolean'])
                      ->addField('comments', ['type' => 'boolean'])
                      ->addField('bpm', ['type' => 'boolean'])
                      ->addField('overwrite', ['type' => 'boolean'])
                      ->addField('filename', ['type' => 'array']);
    }
    
    /**
     * 
     * @param Validator $validator
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator->requirePresence('directory', true, "The path to an upload directory is required.")
                  ->scalar('directory', "Incorrect data type.")
                  ->notEmptyString('directory', 'No directory was given.')
                  ->requirePresence('filename', true, 'There were no uploaded files.')
                  ->isArray('filename');
        
        return $validator;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Cake\Form\Form::_execute()
     */
    protected function _execute(array $data): bool
    {
        $this->Sounds = TableRegistry::getTableLocator()->get('Sounds');
        $data = [
            'directory' => $data['directory'],
            'createdir' => (isset($data['createdir']) && $data['createdir']) ? true : false,
            'metadata' => (isset($data['metadata']) && $data['metadata']) ? true : false,
            'comments' => (isset($data['comments']) && $data['comments']) ? true : false,
            'bpm' => (isset($data['bpm']) && $data['bpm']) ? true : false,
            'overwrite' => (isset($data['overwrite']) && $data['overwrite']) ? true : false,
            'filename' => $data['filename']
        ];
        
        if (!file_exists($data['directory'])) 
        {
            if ($data['createdir']) 
            {
                if (!mkdir($data['directory'], 0775, true))
                {
                    return false;
                } 
            }
            
            return false;
        }
        elseif (!is_writable($data['directory'])) 
        {
            return false;
        }
        
        $existingFiles = $this->getDirectoryContents($data['directory']);
        
        if (!isset($data['filename']) || empty($data['filename'])) 
        {
            return false;
        }
        
        foreach ($data['filename'] as $uploaded) 
        {
            if (!$uploaded->geterror()) 
            {
                if (!$this->isValidUploadedXiphFile($uploaded)) 
                {
                    return false;
                }
                
                if (!in_array($uploaded->getClientFilename(), $existingFiles)) 
                {
                    $path = $data['directory'] . DS . $uploaded->getClientFilename();
                    
                    $uploaded->moveTo($path);
                } 
                else if ($data['overwrite'])
                {
                    $path = $data['directory'] . DS . $uploaded->getClientFilename();
                    
                    if (!unlink($path)) 
                    {
                        return false;
                    }
                    
                    $uploaded->moveTo($path);
                }
            }
        }
        
        $this->scanDirectory($data['directory'], $data);
        
        return true;
    }
    
    /**
     * getDirectoryContents method validates the given directory and scans its
     * contents into an array, which is returned.
     *
     * @param string $directory
     * @throws Exception
     * @return array
     */
    protected function getDirectoryContents($directory)
    {
        if (!is_readable($directory))
        {
            throw new Exception('Unreadable directory given: ' . $directory, 1);
        }
        
        $dirty = scandir($directory);
        $files = [];
        
        if (is_array($dirty) && count($dirty))
        {
            foreach ($dirty as $file)
            {
                if ('.' != $file && '..' != $file)
                {
                    $files[] = $file;
                }
            }
        }
        
        return $files;
    }
    
    /**
     * isValidUploadedXiphFile method
     *
     * @param \Laminas\Diactoros\UploadedFile $uploaded
     * @throws Exception
     * @return string|boolean
     */
    protected function isValidUploadedXiphFile($uploaded)
    {
        $tmpName = $uploaded->getStream()->getMetadata('uri');
        $filename = addcslashes($uploaded->getClientFilename(), '$');        
        $arr = explode('.', $filename);
        $extension = count($arr) ? strtolower(array_pop($arr)) : 'nope';
        
        if (in_array($extension, ['flac', 'ogg']))
        {
            $mimetype = shell_exec("file -b --mime-type -m /usr/share/misc/magic \"$tmpName\"");
            $mimetype = $mimetype ? trim($mimetype) : $mimetype;
            
            if ($mimetype && strstr($mimetype, $extension))
            {
                return $mimetype;
            }
        }
        
        return false;
    }
    
    /**
     * isValidXiphFile method takes a filename and determines whether the file
     * is a valid xiph sound file. If it is, the mime type is returned. If it is
     * not a valid xiph sound file, false is returned.
     *
     * @param string $filename
     * @throws Exception
     * @return string|boolean
     */
    protected function isValidXiphFile($filename)
    {
        if (!is_readable($filename))
        {
            throw new Exception('File or subdirectory not readable: ' . $filename, 2);
        }
        
        if (is_file($filename))
        {
            $file = basename($filename);
            $fileArr = explode('.', $file);
            $extension = count($fileArr) ? strtolower(array_pop($fileArr)) : 'nope';
            
            if (in_array($extension, ['flac', 'ogg']))
            {
                $filename = addcslashes($filename, '$');
                $mimetype = shell_exec("file -b --mime-type -m /usr/share/misc/magic \"$filename\"");
                $mimetype = $mimetype ? trim($mimetype) : $mimetype;
                
                if ($mimetype && strstr($mimetype, $extension))
                {
                    return $mimetype;
                }
            }
        }
        
        return false;
    }
    
    /**
     * scanDirectory method takes a string representing a path to a directory,
     * and it takes an optional array of parameters. The full list of parameters
     * and their default values is as follows:
     *
     *     [
     *         'recurse'  => true, // scan subdirectories recursively
     *         'metadata' => true, // fetch file metadata from the filesystem
     *         'comments' => true, // fetch embedded tags (ex: artist, title)
     *         'bpm'      => true, // fetch the beats per minute of the track
     *         'playlist' => true  // create playlists in directories for sounds
     *     ]
     *
     * Every file found and determined to be either a FLAC or Ogg Vorbis sound
     * file will be added to the database. The options determine which kinds of
     * data to save to the database. By default, all of the metadata and
     * comments are stored, beats-per-minute is calculated and stored, and a
     * playlist is written in every directory where valid sound files are found.
     *
     * @param string $directory
     * @param array $options
     * @throws Exception
     */
    protected function scanDirectory($directory, Array $options = [])
    {
        $files = [];
        
        foreach ($this->getDirectoryContents($directory) as $file)
        {
            $filename = $directory . DS . $file;
            
            if ($mimetype = $this->isValidXiphFile($filename))
            {
                $files[$filename] = $mimetype;
            }
        }
        
        if (count($files))
        {
            foreach ($files as $filename => $mimetype)
            {
                $sound = $this->Sounds->newEmptyEntity();
                $sound->location  = dirname($filename);
                $sound->filename  = basename($filename);
                
                $exists = $this->Sounds->find()
                ->where([
                    'location' => $sound->location,
                    'filename' => $sound->filename
                ])
                ->first();
                
                if ($exists)
                {
                    continue;
                }
                
                $sound->mimetype  = $mimetype;
                $extArr           = explode('.', $sound->filename);
                $ext              = array_pop($extArr);
                $sound->extension = $ext ? trim($ext) : $ext;
                $sound->size      = (filesize($filename)) ? filesize($filename) : 0;
                
                if ($options['metadata'])
                {
                    $sound = $this->setFileMetadata($sound);
                }
                
                if ($options['comments'])
                {
                    switch ($mimetype)
                    {
                        case 'audio/flac':                            
                            $sound = $this->setFlacMetadata($sound);                            
                            break;
                            
                        case 'audio/ogg':                            
                            $sound = $this->setOggMetadata($sound);                            
                            break;
                    }
                }
                
                if ($options['bpm'])
                {
                    $sound = $this->setBeatsPerMinute($sound) ;
                }
                
                if (!$this->Sounds->save($sound))
                {
                    throw new Exception('Unable to save file: ' . $sound->filename, 3);
                }
            }
        }
    }
    
    /**
     * setFileMetadata method fetches certain, generic file metadata from the
     * xiph sound file, and uses the metadata to populate properties in the
     * Sound entity, which is then returned.
     *
     * @param \App\Model\Entity\Sound $sound
     * @return \App\Model\Entity\Sound
     */
    protected function setFileMetadata(Sound $sound)
    {
        $filename = addcslashes($sound->location . DS . $sound->filename, '$');
        $sox      = shell_exec('which sox');
        $sox      = $sox ? trim($sox) : $sox;
        
        if ($sox)
        {
            $info = shell_exec("$sox --info \"$filename\"");
            $infoArr = $info ? explode("\n", trim($info)) : $info;
            
            if ($info)
            {
                foreach ($infoArr as $element)
                {
                    if (strpos($element, 'Channels') !== false)
                    {
                        $arr = explode(':', $element);
                        $channels = array_pop($arr);
                        $sound->channels = $channels ? intval(trim($channels)) : 0;
                    }
                    
                    if (strpos($element, 'Bit Rate') !== false)
                    {
                        $arr = explode(':', $element);
                        $bitrate = array_pop($arr);
                        $sound->bitrate = $bitrate ? trim($bitrate) : null;
                    }
                    
                    if (strpos($element, 'Sample Rate') !== false)
                    {
                        $arr = explode(':', $element);
                        $samplerate = array_pop($arr);
                        $sound->samplerate = $samplerate ? intval(trim($samplerate)) : 0;
                    }
                    
                    if (strpos($element, 'Precision') !== false)
                    {
                        $arr = explode(':', $element);
                        $bits = array_pop($arr);
                        $bits = $bits ? trim($bits) : $bits;
                        $sound->bits_per_sample = $bits ? intval(rtrim($bits, '-bit')) : null;
                    }
                    
                    if (strpos($element, 'Duration') !== false)
                    {
                        $val = ltrim(stristr($element, ':'), ':');
                        $arr = explode('=', $val);
                        $duration_timecode = array_shift($arr);
                        $sound->duration_timecode = $duration_timecode ? trim($duration_timecode) : null;
                    }
                }
            }
        }
        
        $mediainfo = shell_exec('which mediainfo');
        $mediainfo = $mediainfo ? trim($mediainfo) : $mediainfo;
        
        if ($mediainfo)
        {
            $milliseconds = shell_exec("$mediainfo --Inform=\"Audio;%Duration%\" \"$filename\"");
            $milliseconds = $milliseconds ? trim($milliseconds) : $milliseconds;
            
            if ($milliseconds)
            {
                $sound->duration_milliseconds = $milliseconds;
            }
        }
        
        return $sound;
    }
    
    /**
     * setFlacMetadata uses the command-line program metaflac to extract
     * metadata from flac file and then uses that data to populate some
     * properties in the Sound entity.
     *
     * @param Sound $sound
     * @return \App\Model\Entity\Sound
     */
    protected function setFlacMetadata(Sound $sound)
    {
        $filename = addcslashes($sound->location . DS . $sound->filename, '$');
        $metaflac = shell_exec('which metaflac');
        $metaflac = $metaflac ? trim($metaflac) : $metaflac;
        
        if ($metaflac)
        {
            $genre = shell_exec("$metaflac --show-tag=genre \"$filename\"");
            $sound->genre = $genre ? substr(trim(ltrim(stristr($genre, '='), '=')), 0, 150) : $genre;
            
            $title = shell_exec("$metaflac --show-tag=title \"$filename\"");
            $sound->title = $title ? substr(trim(ltrim(stristr($title, '='), '=')), 0, 150) : $title;
            
            $albumartist = shell_exec("$metaflac --show-tag=albumartist \"$filename\"");
            $sound->albumartist = $albumartist ? substr(trim(ltrim(stristr($albumartist, '='), '=')), 0, 150) : $albumartist;
            
            $album = shell_exec("$metaflac --show-tag=album \"$filename\"");
            $sound->album = $album ? substr(trim(ltrim(stristr($album, '='), '=')), 0, 150) : $album;
            
            $tracknumber = shell_exec("$metaflac --show-tag=tracknumber \"$filename\"");
            $sound->tracknumber = $tracknumber ? substr(trim(ltrim(stristr($tracknumber, '='), '=')), 0, 150) : $tracknumber;
            
            $trackno = shell_exec("$metaflac --show-tag=trackno \"$filename\"");
            $sound->tracknumber = (!$tracknumber && $trackno) ? substr(trim(ltrim(stristr($trackno, '='), '=')) , 0, 150): $sound->tracknumber;
            
            $discnumber = shell_exec("$metaflac --show-tag=discnumber \"$filename\"");
            $sound->discnumber = $discnumber ? substr(trim(ltrim(stristr($discnumber, '='), '=')), 0, 150) : $discnumber;
            
            $discno = shell_exec("$metaflac --show-tag=discno \"$filename\"");
            $sound->discnumber = (!$discnumber && $discno) ? substr(trim(ltrim(stristr($discno, '='), '=')) , 0, 150): $sound->discnumber;
            
            $artist = shell_exec("$metaflac --show-tag=artist \"$filename\"");
            $sound->artist = $artist ? substr(trim(ltrim(stristr($artist, '='), '=')), 0, 150) : $artist;
            
            $performer = shell_exec("$metaflac --show-tag=performer \"$filename\"");
            $sound->artist = (!$artist && $performer) ? substr(trim(ltrim(stristr($performer, '='), '=')), 0, 150) : $sound->artist;
            
            $year = shell_exec("$metaflac --show-tag=year \"$filename\"");
            $sound->year = $year ? substr(trim(ltrim(stristr($year, '='), '=')), 0, 150) : $year;
            
            $date = shell_exec("$metaflac --show-tag=date \"$filename\"");
            $sound->year = (!$year && $date) ? substr(trim(ltrim(stristr($date, '='), '=')), 0, 150) : $sound->year;
            
            $label = shell_exec("$metaflac --show-tag=label \"$filename\"");
            $sound->label = $label ? substr(trim(ltrim(stristr($label, '='), '=')), 0, 150) : $label;
            
            $copyright = shell_exec("$metaflac --show-tag=copyright \"$filename\"");
            $sound->copyright = $copyright ? substr(trim(ltrim(stristr($copyright, '='), '=')), 0, 150) : $copyright;
            
            $composer = shell_exec("$metaflac --show-tag=composer \"$filename\"");
            $sound->composer = $composer ? substr(trim(ltrim(stristr($composer, '='), '=')), 0, 150) : $composer;
            
            $producer = shell_exec("$metaflac --show-tag=producer \"$filename\"");
            $sound->producer = $producer ? substr(trim(ltrim(stristr($producer, '='), '=')), 0, 150) : $producer;
            
            $engineer = shell_exec("$metaflac --show-tag=engineer \"$filename\"");
            $sound->engineer = $engineer ? substr(trim(ltrim(stristr($engineer, '='), '=')), 0, 150) : $engineer;
            
            $comment = shell_exec("$metaflac --show-tag=comment \"$filename\"");
            $sound->comment = $comment ? trim(ltrim(stristr($comment, '='), '=')) : $comment;
        }
        
        return $sound;
    }
    
    /**
     * setOggMetadata uses the command-line program ogginfo to extract metadata
     * from the sound file, and then uses that data to populate some of the
     * properties in the Sound entity.
     *
     * @param Sound $sound
     * @return \App\Model\Entity\Sound
     */
    protected function setOggMetadata(Sound $sound)
    {
        $filename = addcslashes($sound->location . DS . $sound->filename, '$');
        $ogginfo  = shell_exec('which ogginfo');
        $ogginfo  = $ogginfo ? trim($ogginfo) : $ogginfo;
        
        if ($ogginfo)
        {
            $tags = shell_exec("$ogginfo \"$filename\"");
            $tags = $tags ? stristr($tags, 'User comments section follows...') : $tags;
            
            if ($tags)
            {
                $tags = stristr($tags, 'Vorbis stream 1:', true);
                $tags = $tags ? trim($tags) : $tags;
                $tagArr = explode("\n", $tags);
                
                array_shift($tagArr);
                
                if (count($tagArr))
                {
                    foreach ($tagArr as $tag)
                    {
                        $tag = $tag ? trim($tag) : $tag;
                        
                        if (stristr($tag, 'genre') !== false)
                        {
                            $genre = stristr($tag, '=');
                            $sound->genre = $genre ? substr(ltrim($genre, '='), 0, 150) : $genre;
                        }
                        
                        if (stristr($tag, 'title') !== false)
                        {
                            $title = stristr($tag, '=');
                            $sound->title = $title ? substr(ltrim($title, '='), 0, 150) : $title;
                        }
                        
                        if (stristr($tag, 'albumartist') !== false)
                        {
                            $albumartist = stristr($tag, '=');
                            $sound->albumartist = $albumartist ? substr(ltrim($albumartist, '='), 0, 150) : $albumartist;
                        }
                        
                        if (stristr($tag, 'album') !== false)
                        {
                            $album = stristr($tag, '=');
                            $sound->album = $album ? substr(ltrim($album, '='), 0, 150) : $album;
                        }
                        
                        if (stristr($tag, 'tracknumber') !== false)
                        {
                            $tracknumber = stristr($tag, '=');
                            $sound->tracknumber = $tracknumber ? substr(ltrim($tracknumber, '='), 0, 150) : $tracknumber;
                        }
                        
                        if (stristr($tag, 'trackno') !== false)
                        {
                            $trackno = stristr($tag, '=');
                            $sound->tracknumber = (!$sound->tracknumber && $trackno) ? substr(ltrim($trackno, '='), 0, 150) : $sound->tracknumber;
                        }
                        
                        if (stristr($tag, 'discnumber') !== false)
                        {
                            $discnumber = stristr($tag, '=');
                            $sound->discnumber = $discnumber ? substr(ltrim($discnumber, '='), 0, 150) : $discnumber;
                        }
                        
                        if (stristr($tag, 'discno') !== false)
                        {
                            $discno = stristr($tag, '=');
                            $sound->discnumber = (!$sound->discnumber && $discno) ? substr(ltrim($discno, '='), 0, 150) : $sound->tracknumber;
                        }
                        
                        if (stristr($tag, 'artist') !== false)
                        {
                            $artist = stristr($tag, '=');
                            $sound->artist = $artist ? substr(ltrim($artist, '='), 0, 150) : $artist;
                        }
                        
                        if (stristr($tag, 'performer') !== false)
                        {
                            $performer = stristr($tag, '=');
                            $sound->artist = (!$sound->artist && $performer) ? substr(ltrim($performer, '='), 0, 150) : $sound->artist;
                        }
                        
                        if (stristr($tag, 'year') !== false)
                        {
                            $year = stristr($tag, '=');
                            $sound->year = $year ? substr(ltrim($year, '='), 0, 150) : $year;
                        }
                        
                        if (stristr($tag, 'date') !== false)
                        {
                            $date = stristr($tag, '=');
                            $sound->year = (!$sound->year && $date) ? substr(ltrim($date, '='), 0, 150) : $sound->year;
                        }
                        
                        if (stristr($tag, 'label') !== false)
                        {
                            $label = stristr($tag, '=');
                            $sound->label = $label ? substr(ltrim($label, '='), 0, 150) : $label;
                        }
                        
                        if (stristr($tag, 'copyright') !== false)
                        {
                            $copyright = stristr($tag, '=');
                            $sound->copyright = $copyright ? substr(ltrim($copyright, '='), 0, 150) : $copyright;
                        }
                        
                        if (stristr($tag, 'composer') !== false)
                        {
                            $composer = stristr($tag, '=');
                            $sound->composer = $composer ? substr(ltrim($composer, '='), 0, 150) : $composer;
                        }
                        
                        if (stristr($tag, 'producer') !== false)
                        {
                            $producer = stristr($tag, '=');
                            $sound->producer = $producer ? substr(ltrim($producer, '='), 0, 150) : $producer;
                        }
                        
                        if (stristr($tag, 'engineer') !== false)
                        {
                            $engineer = stristr($tag, '=');
                            $sound->engineer = $engineer ? substr(ltrim($engineer, '='), 0, 150) : $engineer;
                        }
                        
                        if (stristr($tag, 'comment') !== false)
                        {
                            $comment = stristr($tag, '=');
                            $sound->comment = $comment ? substr(ltrim($comment, '='), 0, 150) : $comment;
                        }
                    }
                }
            }
        }
        
        return $sound;
    }
    
    /**
     * setBeatsPerMinute uses the command-line program bpm-tag to determine the
     * beats per minute for the given file, then sets that information in the
     * "beats_per_minute" property of the Sound entity.
     *
     * @param Sound $sound
     * @return \App\Model\Entity\Sound
     */
    protected function setBeatsPerMinute(Sound $sound)
    {
        $filename = addcslashes($sound->location . DS . $sound->filename, '$');
        $bpmTag   = shell_exec('which bpm-tag');
        $bpmTag   = $bpmTag ? trim($bpmTag) : $bpmTag;
        
        if ($bpmTag)
        {
            $bpm = shell_exec("$bpmTag -f -n \"$filename\" 2>&1");
            $bpm = $bpm ? trim($bpm) : $bpm;
            
            if ($bpm)
            {
                $offset = strrpos($bpm, ':');
                
                if ($offset !== false)
                {
                    $bpm = substr($bpm, $offset + 1);
                    $bpm = $bpm ? trim($bpm, "BPM") : $bpm;
                    
                    $sound->beats_per_minute = $bpm;
                }
            }
        }
        
        return $sound;
    }
}