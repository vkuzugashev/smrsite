<?php

namespace ITSPS\SmRecordBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use ITSPS\SmRecordBundle\Entity\User;

class AdminController extends Controller
{
     /**
     * @Route("/admin", name="adminpage")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/admin/User/Select", name="adminUserSelectAction")
     * @Method({"GET"})
     * @return type
     */
    public function selectAction()
    {        
        $em =  $this->getDoctrine()->getManager();
        $users = $em->getRepository('SmRecordBundle:User')->findAll(); 
        $rows = array();
        foreach($users as $row){
            $rows[]=array(
                'id'=>$row->getId(),
                'createtime'=> $row->getCreatetime() ? $row->getCreatetime()->format('d.m.Y') : '',
                'username'=> $row->getUsername(),                
                'email'=> $row->getEmail(),                
                'isactive'=>$row->getIsActive(),
                'description'=>$row->getDescription(),
            );
        }
        $response = new JsonResponse();
        $response->setData(array('rows'=>$rows, 'rowcount'=>count($rows)));     
        return $response; 
    }
    
 /**
     * @Route("/admin/user/edit", name="adminUserEditAction")
     * @Method({"GET"})
     * @Template()
     */
    public function editAction(Request $request) {
        $id = $request->query->getInt('id');
        $em = $this->getDoctrine()->getManager();
        if($id > 0){
            $data = $em->getRepository('SmRecordBundle:User')->find($id);
            if($data==null){
                throw $this->createNotFoundException('Объект не найден id='+$id);
            }            
        } else {
            $data = new User();
            $data->setUsername('Имя ...');
            $data->setCreatetime(new \DateTime('now'));
        }
        $row=array(
            'id'=>$data->getId(),
            'username'=>$data->getUsername(),
            'password'=>$data->getPassword(),
            'email'=>$data->getEmail(),
            'createtime'=>($data->getCreatetime() ? $data->getCreatetime()->format('d.m.Y') : ''),
            'description'=>$data->getDescription(),
            'isactive'=>$data->getIsActive(),
        );
        $response = new JsonResponse();
        $response->setData(array('row'=>$row, 'error'=>false));
        return $response;                
    }

    /**
     * @Route("/admin/user/edit", name="adminUserUpdateAction")
     * @Method({"POST"})
     * @Template()
     */
    public function updateAction(Request $request) {
        try {
            //Получим переменные формы
            $id = $request->request->getInt('id');
            $username = $request->request->get('username');
            $password = $request->request->get('password');
            $email = $request->request->get('email');
            $description = $request->request->get('description');
            $isactive = $request->request->getBoolean('isactive');
            if($password==null){
                $response = new JsonResponse();
                $response->setData(array('error'=>true, 'message'=>'Требуется указать пароль!'));
                return $response;                 
            }
            //Создадим EntityManager
            $em = $this->getDoctrine()->getManager();
            if($id > 0){
                //найдём запись в БД
                $data = $em->getRepository('SmRecordBundle:User')->find($id);
                if($data==null){
                    throw $this->createNotFoundException('Объект не найден id='+$id);
                    $response = new JsonResponse();
                    $response->setData(array('error'=>true, 'message'=>'Ошибка сохранения! Объект не найден id='+$id));
                    return $response;     
                }
            } else {
                $data = new \ITSPS\SmRecordBundle\Entity\User();
                $data->setCreatetime(new \DateTime("now"));
            }
            $data->setUsername($username);
            $data->setPassword($password);
            $data->setEmail($email);
            $data->setDescription($description);
            $data->setIsActive($isactive);
            
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
     * @Route("/admin/user/delete", name="adminUserDeleteAction")
     * @Method({"POST"})
     * @Template()
     */
    public function deleteAction(Request $request) {
        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        if($id!=0){
            $data = $em->getRepository('SmRecordBundle:User')->find($id);
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
