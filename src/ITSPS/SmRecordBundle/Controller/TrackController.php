<?php

namespace ITSPS\SmRecordBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ITSPS\SmRecordBundle\Entity\Record;
use ITSPS\SmRecordBundle\Entity\Loc;
use Symfony\Component\HttpFoundation\JsonResponse;

class TrackController extends Controller
{
     /**
     * @Route("/track", name="tackpage")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/track/setloc", name="setloc")
     * @Method({"POST"})
     */
    public function locAction(Request $request)
    {   
        $logger = $this->get('logger');

        $logger->warn('PHONE=' . $request->request->get('PHONE'));
        $logger->warn('LAT=' . $request->request->get('LAT'));
        $logger->warn('LAT=' . $request->request->get('LON'));

        $dt = $request->request->get('DT');
        $phone = $request->request->get('PHONE');
        $lat = $request->request->get('LAT');
	$lat = str_replace(',', '.', $lat);
        $lon = $request->request->get('LON');
	$lon = str_replace(',', '.', $lon);


        $em =  $this->getDoctrine()->getManager();
        $phonerecord = $em->getRepository("SmRecordBundle:UserPhone")->findByPhone($phone);

        //Проверим что разрешено загружать запись        
        if(count($phonerecord) > 0){
            $userid=$phonerecord[0]->getUserid();
            if($dt!="0"){
                try{
                    $row = new Loc();        
                    $row->setDt(\DateTime::createFromFormat('YmdHis', $dt));
                    $row->setLat(floatval($lat));
                    $row->setLon(floatval($lon));
                    $row->setPhone($phone);
                    $row->setUserid($userid);
                    $em->persist($row);
                    $em->flush();     
                }
                catch(\Exception $ex){
                    $logger->error('Loc not saved: '.$ex->getMessage());
                }
            }
            else{
                $logger->warn('Uncknow format date: '.$startdt);
            }
        }
        else
            $logger->warn('Not registered phone: '.$phone);
        
        $response = new JsonResponse();
        return $response; 
    }

    /**
     * @Route("/track/getloc", name="getloc2")
     * @Method({"GET"})
     */
    public function getLocAction(Request $request)
    {   
        $logger = $this->get('logger');

        $logger->warn('PHONE=' . $request->get('PHONE'));

        $dt = $request->get('DT');
        $phone = $request->get('PHONE');

        $em =  $this->getDoctrine()->getManager();
        $query = $em->createQuery('select o from SmRecordBundle:Loc o '
                    . 'where o.dt between :p1 and :p2 and o.phone = :p3 order by o.dt');
        $query->setParameter('p1',\DateTime::createFromFormat('d.m.Y H:i:s', $dt.' 00:00:00'));
        $query->setParameter('p2',\DateTime::createFromFormat('d.m.Y H:i:s', $dt.' 00:00:00')->add(new \DateInterval('P1D')));
        $query->setParameter('p3', $phone);
        $logger->warn('SQL: '.$query->getSql());
        $locs = $query->getResult();

        $rows = array();
        foreach($locs as $loc){
		$rows[]=array(
			'DT'=>$loc->getDt()->format('d.m.Y H:i:s'),
			'LAT'=>$loc->getLat(),
			'LON'=>$loc->getLon()
	        );
	}

        $response = new JsonResponse();
	$response->setData($rows);
        return $response; 
    }


    
}

