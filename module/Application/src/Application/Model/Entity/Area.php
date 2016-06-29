<?php 
namespace Application\Model\Entity;
use Doctrine\ORM\Mapping as ORM;

/***
 * @author biyaosu
 * @ORM\Entity
 * @ORM\Table(name="Area")
 */
class Area {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(stragety="AUTO")
	 */
	protected $id;
	
	/**
     * @ORM\Column(type="string")
     */
	protected $name;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $description;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $deleted;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $updateTime;
	
	
	public function __get($property)
	{
		return $this->$property;
	}
	
	public function __set($property, $value)
	{
		$this->$property = $value;
	}
	
	/**
	 * Convert the object to an array.
	 *
	 * @return array
	 */
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set name
	 *
	 * @param string $name
	 * @return Area
	 */
	public function setName($name)
	{
		$this->name = $name;
	
		return $this;
	}
	
	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Set description
	 *
	 * @param string $description
	 * @return Area
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	
		return $this;
	}
	
	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}
	
	/**
	 * Set deleted
	 *
	 * @param boolean $deleted
	 * @return Area
	 */
	public function setDeleted($deleted)
	{
		$this->deleted = $deleted;
	
		return $this;
	}
	
	/**
	 * Get deleted
	 *
	 * @return boolean
	 */
	public function getDeleted()
	{
		return $this->deleted;
	}
	
	/**
	 * Set updateTime
	 *
	 * @param \DateTime $updateTime
	 * @return Area
	 */
	public function setUpdateTime($updateTime)
	{
		$this->updateTime = $updateTime;
	
		return $this;
	}
	
	/**
	 * Get updateTime
	 *
	 * @return \DateTime
	 */
	public function getUpdateTime()
	{
		return $this->updateTime;
	}
	
}

?>