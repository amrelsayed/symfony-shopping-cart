<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartRepository")
 */
class Cart
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $user_id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total_price;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\CartProduct", mappedBy="cart")
    */
    private $items;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\CartType", inversedBy="carts")
    */
    private $type;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->total_price;
    }

    public function setTotalPrice(float $total_price): self
    {
        $this->total_price = $total_price;

        return $this;
    }

    /**
    * @retrun Collection|CartProduct[]
    */
    public function getItems(): collection
    {
        return $this->items;
    }

    public function getType(): ?CartType
    {
        return $this->type;
    }

    public function setType(?CartType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
