<?php

namespace Bibliotheque\UserBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;



class Enquiry
{
    protected $name;

    protected $email;

    protected $subject;

    protected $body;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank());

        $metadata->addPropertyConstraint('email', new Assert\Email());

        $metadata->addPropertyConstraint('subject', new Assert\NotBlank());
        $metadata->addPropertyConstraint('subject', new Assert\Length(array(
            'min'   => 5,
            'max'   => 50,
            'minMessage' => 'Votre sujet doit avoir un minimum de 0 caractères.',
            'maxMessage' => 'Votre sujet doit avoir un maximum de 50 caractères.',
        )));

        $metadata->addPropertyConstraint('body', new Assert\Length(array(
            'min'   => 50,
            'max'   => 500,
            'minMessage' => 'Votre message doit avoir au moins 50 caractères.',
            'maxMessage' => 'Votre message doit avoir au maximum 500 caractères.',
        )));
    }

}