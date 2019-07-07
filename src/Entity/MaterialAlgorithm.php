<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterialAlgorithmRepository")
 */
class MaterialAlgorithm
{
    const PATH_TO_ALGORITHMS_FOLDER = __DIR__ . '/../../public/uploads/algorithms';

    /**
     * Unmapped property to handle file uploads.
     */
    private $file;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        $this->_upload();
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    private function _upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $this->filename = $this->_generateFileName($this->title) .
            '.' . $this->getFile()->getClientOriginalExtension();

        $this->getFile()->move(
            self::PATH_TO_ALGORITHMS_FOLDER,
            $this->filename
        );

        $this->setFile(null);
    }

    private function _generateFileName(string $title): string
    {
        $geo2lat = [
            'ა' => 'a',
            'ბ' => 'b',
            'გ' => 'g',
            'დ' => 'd',
            'ე' => 'e',
            'ვ' => 'v',
            'ზ' => 'z',
            'თ' => 't',
            'ი' => 'i',
            'კ' => 'k',
            'ლ' => 'l',
            'მ' => 'm',
            'ნ' => 'n',
            'ო' => 'o',
            'პ' => 'p',
            'ჟ' => 'zh',
            'რ' => 'r',
            'ს' => 's',
            'ტ' => 't',
            'უ' => 'u',
            'ფ' => 'f',
            'ქ' => 'q',
            'ღ' => 'gh',
            'ყ' => 'k',
            'შ' => 'sh',
            'ჩ' => 'ch',
            'ც' => 'ts',
            'ძ' => 'dz',
            'წ' => 'ts',
            'ჭ' => 'tch',
            'ხ' => 'kh',
            'ჯ' => 'j',
            'ჰ' => 'h',
            ' ' => '_',
        ];

        $chars = preg_split('//u', $title, -1, PREG_SPLIT_NO_EMPTY);
        $filename = '';
        foreach ($chars as $char) {
            if (array_key_exists($char, $geo2lat)) {
                $filename .= $geo2lat[$char];
            } else if (ctype_alpha($char) || is_numeric($char)) {
                $filename .= strtolower($char);
            }
        }

        return $filename;
    }

}
