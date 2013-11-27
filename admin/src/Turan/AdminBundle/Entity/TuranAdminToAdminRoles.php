<?php

namespace Turan\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TuranAdminToAdminRoles
 *
 * @ORM\Table(name="turan_admin_to_admin_roles")
 * @ORM\Entity
 */
class TuranAdminToAdminRoles
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \TuranAdmin
     *
     * @ORM\ManyToOne(targetEntity="TuranAdmin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="turan_admin_id", referencedColumnName="id")
     * })
     */
    private $turanAdmin;

    /**
     * @var \TuranAdminRoles
     *
     * @ORM\ManyToOne(targetEntity="TuranAdminRoles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="turan_admin_roles_id", referencedColumnName="id")
     * })
     */
    private $turanAdminRoles;


}
