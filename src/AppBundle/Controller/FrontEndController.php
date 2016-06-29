<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontEndController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('FrontEnd/index.html.twig');
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
  
}