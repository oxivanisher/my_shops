<?php

namespace MyShops\Entity;

/**
 * Represents a shop for the My Shops package.
 *
 * @\Doctrine\ORM\Mapping\Entity(
 * )
 * @\Doctrine\ORM\Mapping\Table(
 *     name="Shops",
 *     options={"comment": "Shops of the My Shops package"}
 * )
 */
class Shop
{
    /**
     * Create a new instance.
     *
     * @param string $name the shop name
     * @param bool $enabled is the shop enabled (published)?
     * @param string $location of the shop
     * @param string $url of the shop
     * @param string $products of the shop
     * @param string $comment of the shop
     *
     * @return static
     */
    public static function create($name, $enabled = true, $location = "", $url = "", $products = "", $comment = "")
    {
        $result = new static();
        $result->name = (string) $name;
        $result->enabled = (bool) $enabled;
        $result->location = (string) $location;
        $result->url = (string) $url;
        $result->products = (string) $products;
        $result->comment = (string) $comment;

        return $result;
    }

    /**
     * Initializes the instance.
     */
    protected function __construct()
    {
    }

    /**
     * The shop identifier.
     *
     * @\Doctrine\ORM\Mapping\Column(type="integer", options={"unsigned": true, "comment": "Shop identifier"})
     * @\Doctrine\ORM\Mapping\Id
     * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
     *
     * @var int|null
     */
    protected $id;

    /**
     * Get the shop identifier.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The shop name.
     *
     * @\Doctrine\ORM\Mapping\Column(type="string", length=255, nullable=false, options={"comment": "Shop name"})
     *
     * @var string
     */
    protected $name;

    /**
     * Get the shop name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the shop name.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setName($value)
    {
        $this->name = (string) $value;

        return $this;
    }

    /**
     * Is the shop enabled (published)?
     *
     * @\Doctrine\ORM\Mapping\Column(type="boolean", nullable=false, options={"comment": "Is the shop enabled (published)?"})
     *
     * @var bool
     */
    protected $enabled;

    /**
     * Is the shop enabled (published)?
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Is the shop enabled (published)?
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setIsEnabled($value)
    {
        $this->enabled = (bool) $value;

        return $this;
    }

    /**
     * The shop location.
     *
     * @\Doctrine\ORM\Mapping\Column(type="string", length=255, nullable=false, options={"comment": "Shop location"})
     *
     * @var string
     */
    protected $location;

    /**
     * Get the shop location.
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set the shop location.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setLocation($value)
    {
        $this->location = (string) $value;

        return $this;
    }

    /**
     * The shop products.
     *
     * @\Doctrine\ORM\Mapping\Column(type="string", length=255, nullable=false, options={"comment": "Shop products"})
     *
     * @var string
     */
    protected $products;

    /**
     * Get the shop products.
     *
     * @return string
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set the shop products.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setProducts($value)
    {
        $this->products = (string) $value;

        return $this;
    }

    /**
     * The shop comment.
     *
     * @\Doctrine\ORM\Mapping\Column(type="string", length=255, nullable=false, options={"comment": "Shop comment"})
     *
     * @var string
     */
    protected $comment;

    /**
     * Get the shop comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the shop comment.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setComment($value)
    {
        $this->comment = (string) $value;

        return $this;
    }

    /**
     * The shop url.
     *
     * @\Doctrine\ORM\Mapping\Column(type="string", length=255, nullable=false, options={"comment": "Shop url"})
     *
     * @var string
     */
    protected $url;

    /**
     * Get the shop url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the shop url.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setUrl($value)
    {
        $this->url = (string) $value;

        return $this;
    }
}
