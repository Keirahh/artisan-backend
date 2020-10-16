<?php

namespace App\csv;

use App\Entity\Location;
use App\Entity\LocationCity;
use App\Entity\LocationDepartement;
use App\Entity\LocationRegion;
use App\Entity\LocationZip;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class ImportCsv
 * @package App\csv
 */
class ImportCsv
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var
     */
    private $zip_repo;
    /**
     * @var
     */
    private $city_repo;
    /**
     * @var
     */
    private $departement_repo;
    /**
     * @var
     */
    private $region_repo;


    /**
     * ImportCsv constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->zip_repo = $this->manager->getRepository('App\Entity\LocationZip');
        $this->city_repo = $this->manager->getRepository('App\Entity\LocationCity');
        $this->departement_repo = $this->manager->getRepository('App\Entity\LocationDepartement');
        $this->region_repo = $this->manager->getRepository('App\Entity\LocationRegion');
    }


    /**
     * @param $entity
     * @param $value
     * @return null
     * @throws Exception
     */
    private function getExisting($entity, $value)
    {

        $fields = [
            0 => [
                'repo' => $this->zip_repo,
                'field' => 'zip'
            ],
            1 => [
                'repo' => $this->city_repo, 
                'field' => 'name'
            ],
            3 => [
                'repo' => $this->departement_repo, 
                'field' => 'name'
            ],
            4 => [
                'repo' => $this->region_repo, 
                'field' => 'name'
            ],
        ];

        if(array_key_exists($entity, $fields))
        {
            $repo = $fields[$entity]['repo'];

            if($repo)
            {
                $rst = $repo->findOneBy( [$fields[$entity]['field'] => $value] );

                return $rst;
            }
            else 
            {
                throw new Exception('No repository found for entity => ' . $entity );
            }
        }

        return null;

        //throw new Exception('No match in fields array for entity => ' . $entity );

    }

    /**
     * @return int
     * @throws Exception
     */
    public function importCsv()
    {
        if (isset($_POST["import"])) 
        {
            $fileName = $_FILES["file"]["tmp_name"];
            if ($_FILES["file"]["size"] > 0) 
            {
                $file = fopen($fileName, "r");
                $count = 0;
                
                while (($row = fgetcsv($file, 10000, ";"))) 
                {
                    $location = new Location();
                        
                    foreach ($row as $index => $col) 
                    {
                        if($index !== 2)
                        {
                            switch ($index)
                            {
                                case 0 :
                                    if( ! $this->getExisting($index, $col) )
                                    {
                                        $zip = new LocationZip();
                                        $zip->setZip($col);
                                                        
                                        $this->manager->persist($zip);
                                        $this->manager->flush();   
                                    }
                                    else
                                    {
                                        $zip = $this->getExisting($index, $col);
                                    }

                                    $location->setZip($zip);
                                break;

                                case 1 :
                                    if( ! $this->getExisting($index, $col) )
                                    {
                                        $city = new LocationCity();
                                        $city->setName($col);
                                                                
                                        $this->manager->persist($city);
                                        $this->manager->flush();   
                                    }
                                    else
                                    {
                                        $city = $this->getExisting($index, $col);
                                    }

                                    $location->setCity($city);
                    
                                break;

                                case 3 :
                                    if( ! $this->getExisting($index, $col) )
                                    {
                                        $departement = new LocationDepartement();
                                        $departement->setCode($row[2]);
                                        $departement->setName($col);
                
                                        $this->manager->persist($departement);
                                        $this->manager->flush();   
                                    }
                                    else
                                    {
                                        $departement = $this->getExisting($index, $col);
                                    }

                                    $location->setDepartement($departement);

                                break;

                                case 4 :
                                    if( ! $this->getExisting($index, $col) )
                                    {
                                        $region = new LocationRegion();
                                        $region->setName($col);
    
                                        $this->manager->persist($region);
                                        $this->manager->flush();   
                                    }
                                    else
                                    {
                                        $region = $this->getExisting($index, $col);
                                    }
                                    
                                    $location->setRegion($region);

                                break;
                            }
                        }
    
                        // array_push($array, $this->getExisting($index, $col));
                        
                    }

                    $location_repo = $this->manager->getRepository('App\Entity\Location');
                    $existing = $location_repo->findOneBy([
                        'zip' => $zip, 
                        'city' => $city, 
                        'departement' => $departement, 
                        'region' => $region
                        ]);

                    if( ! $existing )
                    {
                        $this->manager->persist($location);
                        $this->manager->flush();
                        $count++;
                    }
                }

                return $count;
            }
        }
    }
}

