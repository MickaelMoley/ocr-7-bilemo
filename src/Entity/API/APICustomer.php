<?php

namespace App\Entity\API;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\API\APICustomerRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ORM\Entity(repositoryClass=APICustomerRepository::class)
 * @OA\Schema()
 * 
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "api_user_customers_collection_get",
 *          parameters = { 
 *              "id" = "expr(object.getApiUser().getId())", 
 *          }   
 *      )
 * )
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "api_user_customers_item_get",
 *          parameters = { 
 *              "id" = "expr(object.getApiUser().getId())", 
 *              "idCustomer" = "expr(object.getId())", 
 *          }   
 *      )
 * )
  * @Hateoas\Relation(
 *      "create",
 *      href = @Hateoas\Route(
 *          "api_user_customers_post",
 *          parameters = { 
 *              "id" = "expr(object.getApiUser().getId())", 
 *          }   
 *      )
 * )
 */
class APICustomer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("get")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("get")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("get")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("get")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("get")
     */
    private $civility;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Groups("get")
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups("get")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups("get")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=APIUser::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $apiUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCivility(): ?string
    {
        return $this->civility;
    }

    public function setCivility(?string $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getApiUser(): ?APIUser
    {
        return $this->apiUser;
    }

    public function setApiUser(?APIUser $apiUser): self
    {
        $this->apiUser = $apiUser;

        return $this;
    }
}
