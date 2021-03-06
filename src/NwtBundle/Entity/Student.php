<?php

namespace NwtBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="NwtBundle\Repository\StudentRepository")
 */
class Student
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
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your name must be at least {{ limit }} characters long",
     *      maxMessage = "Your name cannot be longer than {{ limit }} characters")
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @Assert\Length(
     *      min = 7,
     *      max = 50,
     *      minMessage = "Your name must be at least {{ limit }} characters long",
     *      maxMessage = "Your name cannot be longer than {{ limit }} characters")
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="Program", mappedBy="student")
     */
    private $programs;

    /**
     * @var string
     *
     * @ORM\Column(name="schoolLevel", type="string", length=255)
     */
    private $schoolLevel;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="idDocument", type="string", length=100)
     */
    private $idDocument;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="resume", type="string", length=255)
     */
    private $resume;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="diploma", type="string", length=255, nullable=true)
     */
    private $diploma;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setPrograms($programs)
    {
        if($programs){
            foreach ($programs as $p){
                $this->addProgram($p);
            }
        }

    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Student
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
     * Set schoolLevel
     *
     * @param string $schoolLevel
     *
     * @return Student
     */
    public function setSchoolLevel($schoolLevel)
    {
        $this->schoolLevel = $schoolLevel;

        return $this;
    }

    /**
     * Get schoolLevel
     *
     * @return string
     */
    public function getSchoolLevel()
    {
        return $this->schoolLevel;
    }

    /**
     * Set idDocument
     *
     * @param string $idDocument
     *
     * @return Student
     */
    public function setIdDocument($idDocument)
    {
        $this->idDocument = $idDocument;

        return $this;
    }

    /**
     * Get idDocument
     *
     * @return string
     */
    public function getIdDocument()
    {
        return $this->idDocument;
    }

    /**
     * Set resume
     *
     * @param string $resume
     *
     * @return Student
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set diploma
     *
     * @param string $diploma
     *
     * @return Student
     */
    public function setDiploma($diploma)
    {
        $this->diploma = $diploma;

        return $this;
    }

    /**
     * Get diploma
     *
     * @return string
     */
    public function getDiploma()
    {
        return $this->diploma;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add program
     *
     * @param \NwtBundle\Entity\Program $program
     *
     * @return Student
     */
    public function addProgram(\NwtBundle\Entity\Program $program)
    {
        $this->programs[] = $program;

        return $this;
    }

    /**
     * Remove program
     *
     * @param \NwtBundle\Entity\Program $program
     */
    public function removeProgram(\NwtBundle\Entity\Program $program)
    {
        $this->programs->removeElement($program);
    }

    /**
     * Get programs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrograms()
    {
        return $this->programs;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Student
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Student
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Student
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
