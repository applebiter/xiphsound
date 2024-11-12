<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Sound Entity
 *
 * @property int $id
 * @property string $uuid
 * @property string $location
 * @property string|null $filename
 * @property string $mimetype
 * @property string $extension
 * @property string $size
 * @property string|null $duration_timecode
 * @property int|null $duration_milliseconds
 * @property string|null $bits_per_sample
 * @property string|null $bitrate
 * @property string $channels
 * @property string $samplerate
 * @property string|null $beats_per_minute
 * @property string|null $genre
 * @property string|null $title
 * @property string|null $albumartist
 * @property string|null $album
 * @property string|null $tracknumber
 * @property string|null $discnumber
 * @property string|null $artist
 * @property string|null $year
 * @property string|null $label
 * @property string|null $copyright
 * @property string|null $composer
 * @property string|null $producer
 * @property string|null $engineer
 * @property string|null $comment
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Sound extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'uuid' => true,
        'location' => true,
        'filename' => true,
        'mimetype' => true,
        'extension' => true,
        'size' => true,
        'duration_timecode' => true,
        'duration_milliseconds' => true,
        'bits_per_sample' => true,
        'bitrate' => true,
        'channels' => true,
        'samplerate' => true,
        'beats_per_minute' => true,
        'genre' => true,
        'title' => true,
        'albumartist' => true,
        'album' => true,
        'tracknumber' => true,
        'discnumber' => true,
        'artist' => true,
        'year' => true,
        'label' => true,
        'copyright' => true,
        'composer' => true,
        'producer' => true,
        'engineer' => true,
        'comment' => true,
        'created' => true,
        'modified' => true,
    ];
}
