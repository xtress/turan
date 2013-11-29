<?php


namespace Admin\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
//use Omlook\UserBundle\Form\CoreUserType;
use Admin\MainBundle\Entity\Admin;
use Entity\CoreUserIndivid;
use Entity\CoreUserLegal;

use Admin\MainBundle\Helpers\securityHelper;

#errors
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Form\Exception\NotValidException;



class RegisterController extends Controller
{
    public function indexAction()
    {
//        $user = $this->get('security.context')->getToken()->getUser();

//        if ($user instanceof CoreUser && $user != 'anon.') {
//            return $this->redirect($this->generateUrl('_indexpage'));
//        }
        
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder(new Admin);
        $salt = securityHelper::SaltGenerator();
        var_dump($salt, $encoder->encodePassword("1oiHLzx70y", $salt));exit;
//        $form = $this->createForm(new AdminType());
//        return $this->render('OmlookUserBundle:Register:register.html.twig', array( 'form' => $form->createView() ));
    }

    //OMLOOKTODO: Подравнять, что бы в базу сохраняло
    public function saveAction(Request $request)
    {

       $form = $this->createForm(new CoreUserType());
       $individ = new CoreUserIndivid();
       $legal = new CoreUserLegal();
       $form->bindRequest($request);
       $user = $form->getData();
       $result = null;

       if ($form->isValid()) {

            $salt = securityHelper::SaltGenerator();
            $confirmation_token = securityHelper::SaltGenerator();

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder(new CoreUser);
            $encoded_pass = $encoder->encodePassword($user->getPassword(), $salt);
            $decoded_pass = $user->getPassword();

            $user->setPassword($encoded_pass);
            $user->setEnabled(false);
            $user->setLocked(false);
            $user->setIsFullReg(false);
            $user->setSalt($salt);
            $user->setLastLoginIp($this->get('request')->getClientIp());
            $user->setConfirmationToken($confirmation_token);
            $user->setRegDate(new \DateTime());

            $length     = 10;
            $chars      = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
            $numChars   = strlen($chars);
            $string     = '';

            for ($i = 0; $i < $length; $i++) {
                $string .= substr($chars, rand(1, $numChars) - 1, 1);
            }
            $user->setMailPassword($string);

            $em = $this->getDoctrine()->getEntityManager();
            $result =  $em->getRepository("\Entity\CoreUser")->findOneBy(array('login' => $user->getLogin()));


            //Проверка есть ли такой пользователь или нет
            if($result!=null){
             $this->get('session')->setFlash('notice', $this->get('translator')->trans('UB_USER_ALREADY_EXIST', array('%user_name%' => $user->getLogin())) );
             return $this->render('OmlookUserBundle:Register:register.html.twig', array( 'form' => $form->createView()));
            }

             try {
                 $user_role = $em->getRepository("\Entity\CoreUserRoles")->findOneById(1);
                 $user->getUserRoles()->add($user_role);

                 $em->persist($user);
                 $em->flush();

                 if ($user->getUserType()->getId() === '1') {

                    $user->setUserData($individ);
                    $data = $user->getUserData();
                    $data->setImg('uploads/users/no-image.jpg');
                    $data->setName('');
                    $data->setFamilyName('');
                    $data->setSex('1');
                    $data->setBirthday(new \DateTime());
                    $data->setUser($user);

                } else {

                    $user->setUserData($legal);
                    $data = $user->getUserData();
                    $data->setLogo('uploads/users/no-image.jpg');
                    $data->setName('');
                    $data->setFamilyName('');
                    $data->setSex('1');
                    $data->setBirthday(new \DateTime());
                    $data->setUser($user);
                    $data->setJobPosition('');
                    $data->setJobDepartament('');
                    $data->setOrganizationName('');
                    $data->setBusinessSpheare('');
                    $data->setBossName('');
                    $data->setStuffCount('');
                    $data->setOrgRegDate(new \DateTime());
                    $data->setOrgRegNumber('');

                }
                 $em->persist($user);
                 $em->flush();
                 //var_dump($user);exit;
//                 $user_role = $em->getRepository("OmlookUserBundle:CoreUserRoles")->findOneById(1);
//                 //$user->getUserRoles()->add($user_role);
//                 //var_dump($user_role);Exit;
//                 $role_to_user = new CoreRolesToUser();
//                 $role_to_user->setCoreUser($user);
//                 $role_to_user->setCoreUserRoles($user_role);
//                 $em->persist($role_to_user);
//                 $em->flush();

               // Выслать мыло с сылкой на подтверждение регистрации!
               $this->SendRegistrationEmail($user, $decoded_pass);

             } catch (\PDOException $e) {
                $this->get('session')->setFlash('notice', "Error #1!");
                //var_dump($e->getMessage());exit;
                return $this->render('OmlookUserBundle:Register:register.html.twig', array('form' => $form->createView()) );
             }

        }  else {
            $this->get('session')->setFlash('error', $this->get('translator')->trans('UB_REGISTRATION_FORM_INVALID'));
            return $this->render('OmlookUserBundle:Register:register.html.twig', array('form' => $form->createView()) );
        }
     $this->get('session')->setFlash('notice', $this->get('translator')->trans('UB_REGISTRATION_SUCCESS') );
     return $this->redirect($this->generateUrl('login'));
    }

    // Подтверждение регистрации
    public function confirmationAction($login,$confirmation_token)
    {
     $message  = '';
     $username = '';

     $repository = $this->getDoctrine()->getRepository('\Entity\CoreUser');
     $user = $repository->findOneBy(array('login' =>$login, 'confirmationToken' => $confirmation_token));

     if($user!=null){
        if($user->getEnabled()==true){
          $message = $this->get('translator')->trans('UB_CONFIRMATION_NO_NEEDS'); $username = '';
        }else{

        $em = $this->getDoctrine()->getEntityManager();
        $user->setEnabled(true);
        $em->persist($user);
        $em->flush();
        $message = $this->get('translator')->trans(' UB_CONFIRMATION_READY'); $username = $login;
        }
     }else{

        throw new NotValidException('The confirmation token \''.$confirmation_token.'\' is not valid, user was not found in the database!');

     }

    return $this->render('OmlookUserBundle:Register:confirmation.html.twig', array('message' => $message, 'username'=> $username ));
  }

  function SendRegistrationEmail($user, $pass){
    $host = $this->getRequest()->server->get('HTTP_HOST');
    $mailer = $this->get('mailer');
    $message = \Swift_Message::newInstance()
            ->setSubject('Omlook Registration')
            ->setFrom('admin@omlook.com')
            ->setTo($user->getEmail())
            ->setBody($this->renderView('OmlookUserBundle:Register:email_confirmation.html.twig', array('login' => $user->getLogin(),'token'=>$user->getConfirmationToken(), 'password' => $pass, 'host' => $host)),'text/html');
    $mailer->send($message);
  }
//
}
