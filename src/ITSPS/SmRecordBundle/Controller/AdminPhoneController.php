<?php

namespace ITSPS\SmRecordBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use ITSPS\SmRecordBundle\Entity\User;
use ITSPS\SmRecordBundle\Entity\UserPhone;

class AdminPhoneController extends Controller
{
     /**
     * @Route("/admin/Phone", name="adminPhonePage")
     * @Template("@SmRecord/Admin/phones.html.twig")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    { 
        //Передача пользователя в шаблон
        $id = $request->query->get('id'); 
        return array('userid'=>$id);
    }

    /**
     * @Route("/admin/phone/select", name="adminPhoneSelectAction")
     * @Method({"GET"})
     */
    public function selectAction(Request $request)
    {        
        $userid = $request->query->get('userid');

        $em =  $this->getDoctrine()->getManager();
        $phones = $em->getRepository('SmRecordBundle:UserPhone')->findByUserid($userid); 
        $rows = array();
        foreach($phones as $row){
            $rows[]=array(
                'id'=>$row->getId(),
                'phone'=> $row->getPhone(),                
                'description'=>$row->getDescription(),
                'isrec'=>$row->getIsrec(),
                'hourfrom'=>$row->getHourfrom(),
                'hourto'=>$row->getHourto()
             );
        }
        $response = new JsonResponse();
        $response->setData(array('rows'=>$rows, 'rowcount'=>count($rows)));     
        return $response; 
    }
    
 /**
     * @Route("/admin/phone/edit", name="adminPhoneEditAction")
     * @Method({"GET"})
     */
    public function editAction(Request $request) {
        $id = $request->query->getInt('id');
        $userid = $request->query->getInt('userid');
        $em = $this->getDoctrine()->getManager();
        if($id > 0){
            $data = $em->getRepository('SmRecordBundle:UserPhone')->find($id);
            if($data==null){
                throw $this->createNotFoundException('Объект не найден id='+$id);
            }            
        } else {
            $data = new UserPhone();
            $data->setPhone('');
            $data->setIsrec(0);
            $data->setHourfrom(8);
            $data->setHourto(14);
        }
        $row=array(
            'id'=>$data->getId(),
            'userid'=>$userid,
            'phone'=>$data->getPhone(),
            'description'=>$data->getDescription(),
            'isrec'=>$data->getIsrec(),
            'hourfrom'=>$data->getHourfrom(),
            'hourto'=>$data->getHourto(),
        );
        $response = new JsonResponse();
        $response->setData(array('row'=>$row, 'error'=>false));
        return $response;                
    }

    /**
     * @Route("/admin/phone/edit", name="adminPhoneUpdateAction")
     * @Method({"POST"})
     * @Template()
     */
    public function updateAction(Request $request) {
        try {
            //Получим переменные формы
            $id = $request->request->getInt('id');
            $userid = $request->request->getInt('userid');
            $phone = $request->request->get('phone');
            $description = $request->request->get('description');
            $isrec = $request->request->get('isrec');
            $hourfrom = $request->request->get('hourfrom');
            $hourto = $request->request->get('hourto');
            
            
            if($phone==null){
                $response = new JsonResponse();
                $response->setData(array('error'=>true, 'message'=>'Требуется указать номер!'));
                return $response;                 
            }
            //Создадим EntityManager
            $em = $this->getDoctrine()->getManager();
            if($id > 0){
                //найдём запись в БД
                $data = $em->getRepository('SmRecordBundle:UserPhone')->find($id);
                if($data==null){
                    throw $this->createNotFoundException('Объект не найден id='+$id);
                    $response = new JsonResponse();
                    $response->setData(array('error'=>true, 'message'=>'Ошибка сохранения! Объект не найден id='+$id));
                    return $response;     
                }
            } else {
                $data = new \ITSPS\SmRecordBundle\Entity\UserPhone();
            }
            $data->setUserid($userid);
            $data->setPhone($phone);
            $data->setDescription($description);
            $data->setIsrec($isrec);
            $data->setHourfrom($hourfrom);
            $data->setHourto($hourto);
            
            if($id == 0){
                $em->persist($data);
            }
            $em->flush();

            //создадим директорию для звонков
//            $dir = 'catalog/element'.$data->getId();
//            if(!is_dir($dir)){
//                mkdir($dir,0700);
//            }

            $response = new JsonResponse();
            $response->setData(array('error'=>false, 'message'=>'Запись успешно сохранена!'));
            return $response;     

        } catch(\Exception $e) {
            $response = new JsonResponse();
            $response->setData(array('error'=>true, 'message'=>'Ошибка сохранения записи! '));     
            return $response; 
        }
    }
    
    /**
     * @Route("/admin/phone/delete", name="adminPhoneDeleteAction")
     * @Method({"POST"})
     * @Template()
     */
    public function deleteAction(Request $request) {
        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        if($id!=0){
            $data = $em->getRepository('SmRecordBundle:UserPhone')->find($id);
        }
        if($data==null){
            $response = new JsonResponse();
            $response->setData(array('error'=>true, 'message'=>'Ошибка удаления записи, элемент не найден!'));
            return $response;     
        }            
//        foreach($data->getPhones() as $row){
//            $em->remove($row);
//        //Удалим директорию со звонками для номера
//        $dir = 'catalog/element'.$id;
//        if(is_dir($dir)) { 
//            $objects = scandir($dir); 
//            foreach ($objects as $object) { 
//                if ($object != "." && $object != "..") { 
//                    unlink($dir."/".$object); 
//                } 
//            }
//            rmdir($dir); 
//        } 
//        }
        $em->remove($data);
        $em->flush();
        
        $response = new JsonResponse();
        $response->setData(array('error'=>false, 'message'=>'Запись успешно удалена!'));
        return $response;     
    }    
    
}
