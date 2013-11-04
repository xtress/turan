<?php

namespace Turan\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TuranAdminRoles
 *
 * @ORM\Table(name="turan_admin_roles")
 * @ORM\Entity
 */
class TuranAdminRoles
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;


}
