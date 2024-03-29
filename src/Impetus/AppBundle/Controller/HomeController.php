<?php

/*
 * This file is part of the ImpetusAppBundle
 *
 * (c) Ryan Lewis <lewis.ryan.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Impetus\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\DependencyInjection\ContainerAware;


class HomeController extends Controller {
    public function getAction() {
        //if (!$this->get('templating')->exists($template)) {
        //    return new Response('<html><body>page not found</body></html>');
            //throw new NotFoundHttpException("The specified page could not be found.");
        //}

        return $this->render('ImpetusAppBundle:Pages:home.html.twig', array('page' => 'home'));
    }
}