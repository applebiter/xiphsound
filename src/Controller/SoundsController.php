<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Sound;
use App\Form\ScandirForm;
use App\Form\UploadForm;
use Cake\Core\Configure;
use Exception;

/**
 * Sounds Controller
 *
 * @property \App\Model\Table\SoundsTable $Sounds
 * @method \App\Model\Entity\Sound[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SoundsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $total = $this->Sounds->find()
                              ->select(['total' => 'sum(duration_milliseconds)'])
                              ->first()
                              ->total;
        
        if (isset($_GET['term']) && isset($_GET['tag']))
        {
            $params = [];
            $params['term'] = $_GET['term'];
            $params['tag'] = $_GET['tag'];
            $sounds = $this->paginate($this->Sounds->find('search', $params));
        } 
        else 
        {
            $this->paginate = [
                'limit' => 100,
                'order' => [
                    'Sounds.location' => 'asc',
                    'Sounds.tracknumber' => 'asc'
                ]
            ];            
            $sounds = $this->paginate($this->Sounds);
        }
        
        $this->set(compact('sounds', 'total'));
        $this->viewBuilder()->setOption('serialize', ['sounds', 'total']);
    }

    /**
     * View method
     *
     * @param string|null $id Sound id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sound = $this->Sounds->get($id);
        $albumArt = $this->getAlbumArt($sound);
        $albumTracks = $this->Sounds->find('album', ['location' => $sound->location]);
        $artistTracks = $this->paginate($this->Sounds->find('artist', ['artist' => $sound->artist]));

        $this->set(compact('sound', 'albumArt', 'albumTracks', 'artistTracks'));
        $this->viewBuilder()->setOption('serialize', ['sound', 'albumArt', 'albumTracks', 'artistTracks']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        
    }
    
    /**
     * Theme method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function theme($theme = null)
    {
        $markfile = Configure::read('Xiphsound.ThemeMarker');
        $themes = ['cerulean', 'cosmo', 'cyborg', 'darkly', 'flatly', 'journal',
                   'litera', 'lumen', 'lux', 'materia', 'minty', 'pulse', 
                   'sandstone', 'simplex', 'sketchy', 'slate', 'solar', 
                   'spacelab', 'superhero', 'united', 'yeti'
        ];
        
        if ($theme && in_array($theme, $themes)) 
        {
            file_put_contents($markfile, $theme, LOCK_EX);
        }
        
        return $this->redirect($this->referer());
    }

    /**
     * Edit method
     *
     * @param string|null $id Sound id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sound = $this->Sounds->get($id);
        $albumArt = $this->getAlbumArt($sound);
        
        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            $sound = $this->Sounds->patchEntity($sound, $this->request->getData());
            
            if ($this->Sounds->save($sound)) 
            {
                $this->Flash->success(__('The sound has been saved.'));

                return $this->redirect(['action' => 'edit', $id]);
            }
            
            $this->Flash->error(__('The sound could not be saved. Please, try again.'));
        }
        
        $this->set(compact('sound', 'albumArt'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sound id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $sound = $this->Sounds->get($id);
        
        if ($this->Sounds->delete($sound)) 
        {
            $this->Flash->success(__('The sound has been deleted.'));
        } 
        else 
        {
            $this->Flash->error(__('The sound could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Upload method 
     * 
     * @return \Cake\Http\Response
     */
    public function upload() 
    {
        $form = new UploadForm();
        
        if ($this->request->is(['patch', 'post', 'put']))
        {
            if ($form->execute($this->request->getData()))
            {
                $this->Flash->success('The file or files were successfully uploaded.');
                
                return $this->redirect(['action' => 'index']);
            }
            else
            {
                $this->Flash->error('An error occurred while processing your request.');
            }
        }
        
        $this->set('form', $form);
    }
    
    /**
     * Scandir method takes form parameters and executes the directory-scanning 
     * algorithm in the form. 
     * 
     * @return \Cake\Http\Response
     */
    public function scandir()
    {
        $form = new ScandirForm();
        
        if ($this->request->is(['patch', 'post', 'put']))
        {
            if ($form->execute($this->request->getData())) 
            {
                $this->Flash->success('The directory was successfully scanned.');
                
                return $this->redirect(['action' => 'index']);
            } 
            else 
            {
                $this->Flash->error('An error occurred while processing your request.');
            }
        }
        
        $this->set('form', $form);
    }
    
    /**
     * Play method 
     * 
     * @param int|null $id
     */
    public function play($id = null) 
    {
        $sound = $this->Sounds->get($id);
        $file = $sound->location . DS . $sound->filename;
        $response = $this->response->withFile($file);
        
        return $response;
    }
    
    /**
     * GetDirectoryContents method validates the given directory and scans its
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
     * GetAlbumArt takes a Sound entity and extracts its location. It then scans 
     * that directory for image files that might be purposefully put there as 
     * album art. If an image is found, it is converted into a base64-encoded 
     * string and written in a data URI that will be embedded directly into the 
     * final output.
     * 
     * @param Sound $sound
     * @return string|NULL
     */
    protected function getAlbumArt(Sound $sound) 
    {
        $files = $this->getDirectoryContents($sound->location);
        $image = null;
        $mime = null;
        
        foreach ($files as $file)
        {
            $filename = $sound->location . DS . $file;
            
            switch ($file)
            {
                case 'cover.jpg':
                case 'Cover.jpg':
                case 'front.jpg':
                case 'Front.jpg':
                    
                    $mime = 'image/jpeg';                    
                    $image = file_get_contents($filename);
                    
                    break;
                    
                case 'cover.png':
                case 'Cover.png':
                case 'front.png':
                case 'Front.png':
                    
                    $mime = 'image/png';
                    $image = file_get_contents($filename);
                    
                    break;
                    
                case 'cover.gif':
                case 'Cover.gif':
                case 'front.gif':
                case 'Front.gif':
                    
                    $mime = 'image/gif';
                    $image = file_get_contents($filename);
                    
                    break;
            }
        }
        
        if ($image) 
        {
            $base64 = base64_encode($image);
            $dataUri = 'data:' . $mime . ';base64,' . $base64;
            
            return "<img src=\"{$dataUri}\" alt=\"Album Art\" style=\"width:100%;\" />";
        }
        
        return null;
    }
}
