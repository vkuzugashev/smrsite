<?php

namespace ITSPS\SmRecordBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ITSPS\SmRecordBundle\Entity\Record;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
     /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/select", name="selectAction")
     * @Method({"GET"})
     * @return type
     */
    public function selectAction(Request $request)
    {
        $offset = mktime(0,0,0,1,1,1970);        
        $startdt = $request->query->get('startdt');
        $stopdt = $request->query->get('stopdt');
        $phone = $request->query->get('phone');
        $remotephone=$request->query->get('remotephone');
        
        $em =  $this->getDoctrine()->getManager();
        $users = $em->getRepository("SmRecordBundle:User")->findByUsername($this->getUser()->getUsername());
        if(count($users) > 0){
            $userid=$users[0]->getId();
            $query = $em->createQuery('select o from SmRecordBundle:Record o '
                    . 'where o.startdt between :p1 and :p2 and o.phone like :p3 and o.remotephone like :p4 and o.userid=:p5 order by o.startdt');
            $query->setParameter('p1',\DateTime::createFromFormat('Y-m-d H:i:s', $startdt.' 00:00:00'));
            $query->setParameter('p2',\DateTime::createFromFormat('Y-m-d H:i:s', $stopdt.' 00:00:00')->add(new \DateInterval('P1D')));
            $query->setParameter('p3', '%'.$phone.'%');
            $query->setParameter('p4', '%'.$remotephone.'%');
            $query->setParameter('p5', $userid);
            $elements = $query->getResult();
        }
        else
            $elements = array();
        $rows = array();
        foreach($elements as $row){
            $rows[]=array(
                'startdt'=> $row->getStartdt() ? $row->getStartdt()->format('d.m.Y H:i:s') : '',
                'duration'=> $row->getDuration()==0 ? '' : date('H:i:s',  ($row->getDuration()/1000) + $offset),                
                'direction'=>$row->getDirection(),
                'unanswered'=>$row->getUnanswered(),
                'phone'=>$row->getPhone(),
                'remotephone'=>$row->getRemotephone(),
                'recordfile'=>'/records' . strrchr($row->getRecordfile(),'/'),
            );
        }
        $response = new JsonResponse();
        $response->setData(array('rows'=>$rows, 'rowcount'=>count($rows)));     
        return $response; 
    }

    /**
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction() {

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            );
    }    
    
    
    /**
     * @Route("/smrecord/sr", name="smrecord")
     * @Method({"POST"})
     */
    public function smRecordSaveAction(Request $request)
    {   
        $em =  $this->getDoctrine()->getManager();
        $startdt = $request->request->get('STARTDT');
        $stopdt = $request->request->get('STOPDT');
        $unanswered = $request->request->getInt('UNANSWERED');
        $duration = $request->request->getInt('DURATION');
        $direction = $request->request->getInt('DIRECTION');
        $phone = $request->request->get('PHONE');
        $remotephone = $request->request->get('REMOTE_PHONE');   
        
        $phonerecord = $em->getRepository("SmRecordBundle:UserPhone")->findByPhone($phone);

        //Проверим что разрешено загружать запись        
        if(count($phonerecord) > 0){
            $userid=$phonerecord[0]->getUserid();
            if($startdt!="0" /*&& substr($startdt,0,4)=="2016" && substr($startdt,0,5)!="2016-"*/){
                try{
                    $row = new Record();        
                    $row->setStartdt(\DateTime::createFromFormat('YmdHis', $startdt));
                    if($duration != 0 && $stopdt!=null){
                        $row->setStopdt(\DateTime::createFromFormat('YmdHis', $stopdt));
                    }
                    $row->setDuration($duration);
                    $row->setDirection($direction);
                    $row->setUnanswered($unanswered);
                    $row->setPhone($phone);
                    $row->setRemotephone($remotephone);
                    $row->setUserid($userid);
                    $row->setFilesize(0);
                    $em->persist($row);
                    $em->flush();     
                }
                catch(Exception $ex){
                    $logger = $this->get('logger');
                    $logger->error('Ошибка сохранения: '.$ex->getMessage());
                }
            }
            else{
                $logger = $this->get('logger');
                $logger->info('Неверный формат даты: '.$startdt);
            }
        }
        $response = new JsonResponse();
        $response->setData(array('result'=>'OK'));     
        return $response; 
    }
    
     /**
     * @Route("/smrecord/sr2", name="smrecord2")
     * @Method({"POST"})
     * 
     */
    public function smRecordSave2Action(Request $request)
    {        
        $em =  $this->getDoctrine()->getManager();
        $startdt = $request->request->get('STARTDT');
        $stopdt = $request->request->get('STOPDT');
        $unanswered = $request->request->getInt('UNANSWERED');
        $duration = $request->request->getInt('DURATION');
        $direction = $request->request->getInt('DIRECTION');
        $phone = $request->request->get('PHONE');
        $remotephone = $request->request->get('REMOTE_PHONE');                       
        $recordfile = $request->request->get('RECORD_FILE');
        
        $phonerecord = $em->getRepository("SmRecordBundle:UserPhone")->findByPhone($phone);
        
        //Проверим что разрешено загружать запись
        if(count($phonerecord) > 0){
            $userid=$phonerecord[0]->getUserid();
            if($startdt!="0" /*&& substr($startdt,0,4)=="2016" && substr($startdt,0,5)!="2016-"*/){
                try{
                    $row = new Record();
                    $row->setStartdt(\DateTime::createFromFormat('YmdHis', $startdt));
                    if($duration != 0 && $stopdt!=null){
                        $row->setStopdt(\DateTime::createFromFormat('YmdHis', $stopdt));
                    }
                    $row->setDuration($duration);
                    $row->setDirection($direction);
                    $row->setUnanswered($unanswered);
                    $row->setPhone($phone);
                    $row->setRemotephone($remotephone);
                    $row->setRecordfile($recordfile);
                    $row->setUserid($userid);
                    $row->setFilesize(0);
                    //загрузка файла
                    foreach ($_FILES as $key => $value)
                    {
                        if ($value['error'] == 4) {
                            continue; // Skip file if any error found
                        }  
                        $dir = 'records';
                        if(!is_dir($dir)){
                            mkdir($dir,0700);
                        }
                        $file = $dir.'/'.$value['name'];
                        move_uploaded_file($value['tmp_name'], $file);
                        $row->setFilesize($value['size']);
                    }                       
                    $em->persist($row);
                    $em->flush();
                }
                catch(Exception $ex){
                    $logger = $this->get('logger');
                    $logger->info('Ошибка сохранения данных: '.$ex->getMessage());                    
                }
            }
            else{
                $logger = $this->get('logger');
                $logger->info('Неверный формат даты: '.$startdt);
            }
        }            
        
        $response = new JsonResponse();
        $response->setData(array('result'=>'OK'));     
        return $response; 
    }
    
    
    
}
