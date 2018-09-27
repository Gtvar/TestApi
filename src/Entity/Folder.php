<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\FolderGetByPath;

/**
 * @ApiResource(
 *     itemOperations={
 *        "delete",
 *        "get"={"method"="GET", "path"="/folders/{id}", "requirements"={"id"="\d+"}},
 *        "getByPath"={
 *          "method"="GET",
 *          "path"="/folders/path/{path}",
 *          "requirements"={"path"=".+"},
 *          "controller"=FolderGetByPath::class,
 *          "swagger_context": {
 *              "parameters" = {
 *                {
 *                   "name" = "path",
 *                   "in" = "path",
 *                   "required" = "true",
 *                   "type" = "string"
 *                }
 *              }
 *          }
 *        }
 *     },
 *     attributes={
 *        "normalization_context"={"groups"={"read"}},
 *        "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\FolderRepository")
 */
class Folder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=4096, nullable=true)
     *
     * @ApiProperty()
     * @Groups({"read", "write"})
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Folder", inversedBy="folders")
     * @ORM\JoinColumn(nullable=true)
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="folder", orphanRemoval=true)
     *
     * @Groups({"read"})
     */
    private $files;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Folder", mappedBy="parent")
     */
    private $folders;

    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->folders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get Parent
     *
     * @return Folder|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set Parent
     *
     * @param Folder $parent
     *
     * @return Folder
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setFolder($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
            // set the owning side to null (unless already changed)
            if ($file->getFolder() === $this) {
                $file->setFolder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Folder[]
     */
    public function getFolders(): Collection
    {
        return $this->folders;
    }

    public function addFolder(Folder $folder): self
    {
        if (!$this->folders->contains($folder)) {
            $this->folders[] = $folder;
            $folder->setParent($this);
        }

        return $this;
    }

    public function removeFolder(Folder $folder): self
    {
        if ($this->folders->contains($folder)) {
            $this->folders->removeElement($folder);
            // set the owning side to null (unless already changed)
            if ($folder->getParent() === $this) {
                $folder->setParent(null);
            }
        }

        return $this;
    }
}
