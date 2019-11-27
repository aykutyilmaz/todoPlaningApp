<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TasksRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TaskEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $level;

    /**
     * @ORM\Column(type="smallint")
     */
    private $duration;
    
    /**
     * @ORM\Column(type="string", nullable = false)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    protected $updated;


    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist(): void
    {
        $this->created = new \DateTime('now');
    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        $this->updated = new \DateTime('now');
    }
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return int|null
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }
    
    /**
     * @param int $level
     * @return TaskEntity
     */
    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }
    
    /**
     * @return int|null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }
    
    /**
     * @param int $duration
     * @return TaskEntity
     */
    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @return string|null
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
}
