<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *      fields = {"email"},
 *         message="Un compte est déjà exsistant à cette adresse Email !"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *      message = "Cette adresse Email '{{ value }}' n'est pas valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit contenir 8 caractères minimum")
     * @Assert\EqualTo(propertyPath="confirm_password", message="Les mots de passe ne correspondent pas")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Les mots de passe ne correspondent pas")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /* 
        Pour pouvoir encoder le mot de passe, il faut que notre Entité User implémente l'interface UserInterface
        Cette interface contient des méthodes que nous sommes obligé de déclarer :
        getPassword(), getUsername(), getRoles(), getSalt(), et eraseCredentials()
    */    

    // cette méthode est uniquement déstinée à nettoyer les mots de passe en texte brut éventuellement stoké
    public function eraseCredentials()
    {

    }
    
    // renvoie la chaine de caractéres non encodée que l'utilisateur a saisi, qui a été utilisé à l'origine pour encoder le 
    //mot de passe 
    public function getSalt()
    { 

    }

    //cette méthode renvoi un tableau de chaine de caractérers ou sont stokés les rôles accordés à l'utilisateur
    public function getRoles()
    {
        //return ['ROLE_USER'];
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
