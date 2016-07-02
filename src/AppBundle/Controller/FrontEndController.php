<?php

namespace AppBundle\Controller;

use DateTime;
use Doctrine\DBAL\DBALException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Subscriber;
use AppBundle\Entity\Unsubscriber;
use AppBundle\Form\SubscriberType;
use AppBundle\Form\UnsubscriberType;
use Swift_Message;

class FrontEndController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $error = 0;
        try{
            $newSubscriber = new Subscriber();
            
            $form = $this->createForm(SubscriberType::class, $newSubscriber, array(
                    'action' => $this -> generateUrl('index'),
                    'method' => 'POST'
                ));
            
            $form->handleRequest($request);
            
            if($form->isValid() && $form->isSubmitted()) {
                $firstname = $form['firstname']->getData();
                $lastname = $form['lastname']->getData();
                $emailaddress = $form['emailaddress']->getData();
                $phone = $form['phone']->getData();
                $edulevel= $form['education_level_id']->getData();
                $agreeterms = $form['agreeterms']->getData();
                $agreeemails = $form['agreeemails']->getData();
                $agreepartners = $form['agreepartners']->getData();
                
                $hash = $this->mc_encrypt($newSubscriber->getEmailAddress(), $this->generateKey(16));
                
                $em = $this->getDoctrine()->getManager();
                
                //assigning data to variables
                $newSubscriber ->setFirstname($firstname);
                $newSubscriber ->setLastname($lastname);
                $newSubscriber ->setEmailAddress($emailaddress);
                $newSubscriber ->setPhone($phone);
                $newSubscriber ->setAge(-1);
                $newSubscriber ->setGender(-1);
                $newSubscriber ->setEducationLevelId($edulevel);
                $newSubscriber ->setResourceId(2);
                $newSubscriber ->setAgreeTerms($agreeterms);
                $newSubscriber ->setAgreeEmails($agreeemails);
                $newSubscriber ->setAgreePartners($agreepartners);
                $newSubscriber ->setHash($hash);
                
                //pusshing data through to the database
                $em->persist($newSubscriber);
                $em->flush();
                
                //create email
                $urlButton = $this->generateEmailUrl(($request->getLocale() === 'ru' ? '/ru/' : '/') . 'verify/' . $newSubscriber->getEmailAddress() . '?id=' . urlencode($hash));
                $message = Swift_Message::newInstance()
                    ->setSubject('SmartEduPics.com | Complete Registration')
                    ->setFrom(array('relaxstcom@gmail.com' => 'SmartEduPics Support Team'))
                    ->setTo($newSubscriber->getEmailAddress())
                    ->setContentType("text/html")
                    ->setBody($this->renderView('FrontEnd/emailSubscribe.html.twig', array(
                            'url' => $urlButton, 
                            'name' => $newSubscriber->getFirstname(),
                            'lastname' => $newSubscriber->getLastname(),
                            'email' => $newSubscriber->getEmailAddress()
                        )));

                //send email
                $this->get('mailer')->send($message);

                //generating successfull responce page
                return $this->redirect($this->generateUrl('thankureg'));
                
            }
            
        } catch (Exception $ex) {
            $error = 1;
        } catch(DBALException $e) {
            $error =1;
        }
        
        return $this->render('FrontEnd/index.html.twig', array(
            'form'=>$form->CreateView(),
            'error'=>$error
        ));
    }
    
    /**
     * @Route("terms", name="terms")
     */
    public function termsAction(Request $request)
    {
        return $this->render('FrontEnd/terms.html.twig');
    }
    
    /**
     * @Route("privacy", name="privacy")
     */
    public function privacyAction(Request $request)
    {
        return $this->render('FrontEnd/privacy.html.twig');
    }
    
    /**
    * @Route("thankureg", name="thankureg")
    */
    public function thankuregAction(Request $request)
    {
        return $this->render('FrontEnd/thankureg.html.twig');
    }
    
    /**
    * @Route("unsubscribe", name="unsubscribe")
    */
    public function unsubscribeAction(Request $request)
    {
        return $this->render('FrontEnd/unsubscribe.html.twig');
    }
    
    //controller specific functions
    
    private function generateKey($size) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = "";
        for($i = 0; $i < $size; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    
     private function mc_encrypt($encrypt, $key) {
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $passcrypt = trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, trim($encrypt), MCRYPT_MODE_ECB, $iv));
        $encode = base64_encode($passcrypt);
        return $encode;
    }
    
    private function generateEmailUrl($url) {
        return "http://localhost:8888" . $this->container->get('router')->getContext()->getBaseUrl() . $url;
    }
}