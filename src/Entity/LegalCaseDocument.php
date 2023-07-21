<?php

namespace App\Entity;

use App\Repository\LegalCaseDocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: LegalCaseDocumentRepository::class)]
class LegalCaseDocument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
	
	// NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'customer_document_file', fileNameProperty: 'fileName')]
	#[Assert\File(maxSize: '2M', mimeTypes: ['image/jpeg', 'image/png', 'application/pdf', 'application/x-pdf'])]
    private ?File $file = null;
	
	#[ORM\Column(nullable: true)]
   	private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $preferredName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $path = null;

    #[ORM\ManyToOne(inversedBy: 'legalCaseDocuments')]
    private ?LegalCase $legalCase = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $fileName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPreferredName(): ?string
    {
        return $this->preferredName;
    }

    public function setPreferredName(?string $preferredName): self
    {
        $this->preferredName = $preferredName;

        return $this;
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

    public function getLegalCase(): ?LegalCase
    {
        return $this->legalCase;
    }

    public function setLegalCase(?LegalCase $legalCase): self
    {
        $this->legalCase = $legalCase;

        return $this;
    }
	
	/**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $file
     */
	public function setFile(?File $file = null): void
    {
        $this->file = $file;
         
        if (null !== $file) 
		{
        	// It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
           	$this->updatedAt = new \DateTimeImmutable();
         }
     }
	
	public function getFile(): ?File
   	{
      	return $this->file;
   	}

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }
}
