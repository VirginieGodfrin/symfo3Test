<?php

namespace Test\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Test\BlogBundle\Repository\ImageRepository")
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    private $file;

    

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    public function getFile(){
        return $this->file;
    }

    public function setFile(UploadedFile $file = null){
        $this->file = $file;
    }

    public function upload(){

        if(null === $this->file){
            return;
        }
    //recup du nom original du fichier
        $name = $this->file->getClientOriginalName();
    //move: déplace le fichier dans le repertoire de notre choix
        $this->file->move($this->getUploadRootDir(), $name);
        
        $this->url = $name;
        $this->alt = $name; 
    }

    public function getUploadDir(){
        // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
            return 'uploads/img';
    }

    public function getUploadRootDir(){
        // On retourne le chemin relatif vers l'image pour notre code PHP
            return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
}
