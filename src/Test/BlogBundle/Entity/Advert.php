<?php

namespace Test\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Advert
 *
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="Test\BlogBundle\Repository\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Advert
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    //Advert propriétaire d'image relation OneToOne avec Image
    /**
     * @ORM\OneToOne(targetEntity="Test\BlogBundle\Entity\Image", cascade={"persist"})
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="Test\BlogBundle\Entity\Category", cascade={"persist"})
     * @ORM\JoinTable(name="advert_category")
     */
    private $categories;
    // Advert propriétaie de la relation manyToMany

// relation Bidirectionelle
    /**
     * @ORM\OneToMany(targetEntity="Test\BlogBundle\Entity\Application", mappedBy="advert")
     */
    private $applications;
    //une annonce est liée à plusieurs applications, nous somme côté inverse (OneToMany)
    //mappedBy correspond à l'attribut de l'entité propriétaire qui pointe vers l'entité inverse
    //c'est le private advert de l'entité application

//les callbacks
    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private$updatedAt;

    /**
     * @ORM\Column(name="nb_applications", type="integer")
     */
    private $nbApplications = 0;

//extension
    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->categories = new ArrayCollection();
        $this->applications = new ArrayCollection();
    }

// évènement PreUpdate    
    /**
     * @ORM\PreUpdate
     */
    public function updateDate(){
        $this->setUpdateAt(new \DateTime());
    }

    public function increaseApplication()
    {
        $this->nbApplications++;
    }

    public function decreaseApplication()
    {
        $this->nbApplications--;
    }


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Advert
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set image
     *
     * @param \Test\BlogBundle\Entity\Image $image
     *
     * @return Advert
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Test\BlogBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }
    

    /**
     * Add category
     *
     * @param \Test\BlogBundle\Entity\Category $category
     *
     * @return Advert
     */
    public function addCategory(Category $category)
    {
        // Ici, on utilise l'ArrayCollection comme un tableau
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Test\BlogBundle\Entity\Category $category
     */
    public function removeCategory(Category $category)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        //ici on récupère une liste de categories
        return $this->categories;
    }

    /**
     * Add application
     *
     * @param \Test\BlogBundle\Entity\Application $application
     *
     * @return Advert
     */
    public function addApplication(Application $application)
    {
        $this->applications[] = $application;
        //on lie annonce à application dans le cas d'une ManyToMany
        $application->setAdvert($this);

        return $this;
    }

    /**
     * Remove application
     *
     * @param \Test\BlogBundle\Entity\Application $application
     */
    public function removeApplication(Application $application)
    {
        $this->applications->removeElement($application);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApplications()
    {
        return $this->applications;
    }

      /**
   * @param \DateTime $updatedAt
   */
  public function setUpdatedAt(\Datetime $updatedAt = null)
  {
      $this->updatedAt = $updatedAt;
  }
  /**
   * @return \DateTime
   */
  public function getUpdatedAt()
  {
      return $this->updatedAt;
  }
  /**
   * @param integer $nbApplications
   */
  public function setNbApplications($nbApplications)
  {
      $this->nbApplications = $nbApplications;
  }
  /**
   * @return integer
   */
  public function getNbApplications()
  {
      return $this->nbApplications;
  }
}
