<?php

namespace App\Entity\API;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\API\APICustomerRepository;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=APICustomerRepository::class)
 * @Serializer\ExclusionPolicy("all")
 * @OA\Schema()
 *
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "api_user_customers_collection_get",
 *     		parameters = {
 *              "id" = "expr(object.getApiUser().getId())"
 *          }
 *      )
 * )
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "api_user_customers_item_get",
 *          parameters = {
 *              "id" = "expr(object.getApiUser().getId())",
 *     			"customerId" = "expr(object.getId())"
 *          }
 *      )
 * )
 * @Hateoas\Relation(
 *      "create",
 *      href = @Hateoas\Route(
 *          "api_user_customers_post",
 *      	parameters = {
 *              "id" = "expr(object.getApiUser().getId())"
 *          }
 *      )
 * )
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "api_user_customers_item_delete",
 *      	parameters = {
 *              "id" = "expr(object.getApiUser().getId())",
 *     			"customerId" = "expr(object.getId())"
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
	 * @Serializer\Groups({"get"})
	 * @Serializer\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"get"})
	 * @Serializer\Expose()
	 * @Assert\NotBlank()
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @Serializer\Groups({"get"})
	 * @Serializer\Expose()
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @Serializer\Groups({"get"})
	 * @Serializer\Expose()
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @Serializer\Groups({"get"})
	 * @Serializer\Expose()
     */
    private $civility;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
	 * @Serializer\Groups({"get"})
	 * @Serializer\Expose()
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime_immutable")
	 * @Serializer\Groups({"get"})
	 * @Serializer\Expose()
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
	 * @Serializer\Groups({"get"})
	 * @Serializer\Expose()
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
