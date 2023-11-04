<?php

namespace App\Perun\Entity;

use App\Perun\Repository\DataTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_DataTypes")
 * @ORM\Entity(repositoryClass=DataTypeRepository::class)
 */
class DataType
{
    const AIRCRAFT_TYPES = [
        'A-10C_2',
        'AV8BNA',
        'FA-18C_hornet',
        'F-16C_50',
        'F-14B',
        'M-2000C',
        'F-14B_2',
        'F-5E-3',
        'Hercules',
        'AJS37',
        'P-47D-30',
        'C-101CC',
        'MiG-21Bis',
        'P-51D',
        'JF-17',
        'Su-27',
        'SpitfireLFMkIX',
        'A-4E-C',
        'Su-25T',
        'MiG-29A',
        'F-15C',
        'Hercules_2',
        'A-10C',
        'L-39C',
        'Bf-109K-4',
        'L-39ZA',
        'MiG-19P',
        'MiG-15bis',
        'F-86F Sabre',
        'C-101EB',
        'Yak-52',
        'MiG-29S',
        'T-45',
        'T-45_2',
        'Su-33',
        'F-14A-135-GR',
        'TF-51D',
        'Yak-52_2',
        'A-10A',
        'FW-190A8',
        'J-11A',
        'P-51D-30-NA',
        'Su-25',
        'Christen Eagle II',
        'P-47D-40',
        'MosquitoFBMkVI',
        'FW-190D9',
        'L-39C_2',
        'L-39ZA_2',
        'F-14A-135-GR_2',
        'Su-30SM',
        'Su-30SM_2',
        'Su-30MKM',
        'C-101CC_2',
        'I-16',
        'SpitfireLFMkIXCW',
        'Mirage-F1CE',
        'MiG-29G',
        'Bronco-OV-10A',
        'Mirage-F1EE',
        'M-2000D',
        'F-15ESE',
        'F-15ESE_2',
    ];

    const HELICOPTERS_TYPES = [
        'Ka-50',
        'Mi-8MT',
        'SA342M',
        'UH-1H',
        'UH-1H_2',
        'SA342L',
        'UH-1H_3',
        'UH-1H_4',
        'SA342Minigun',
        'SA342Mistral',
        'SA342M_2',
        'SA342L_2',
        'UH-60L',
        'AH-64D_BLK_II',
        'AH-64D_BLK_II_2',
        'Mi-24P_3',
        'Ka-50_3',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="pe_DataTypes_id")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=100, name="pe_DataTypes_name", nullable=false)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="datetime", name="pe_DataTypes_update", options={"default": "CURRENT_TIMESTAMP"})
     */
    private ?\DateTime $updated = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTime $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}
