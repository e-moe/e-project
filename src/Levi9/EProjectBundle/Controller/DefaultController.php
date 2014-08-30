<?php

namespace Levi9\EProjectBundle\Controller;

use Levi9\EProjectBundle\Entity\Row;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package Levi9\EProjectBundle\Controller
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $rows = $this->getRows();
        return array('rows' => $rows);
    }

    /**
     * @Route("/add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $row = new Row();
        $form = $this->createFormBuilder($row)
            ->add('name')
            ->add('type')
            ->add('count')
            ->add('save', 'submit', array('label' => 'Add Battery'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $this->getDoctrine()->getManager()->persist($row);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('levi9_eproject_default_index'));
        }

        return array('form' => $form->createView());
    }

    /**
     * Get sorted list of all rows
     *
     * @return array|\Levi9\EProjectBundle\Entity\Row[]
     */
    protected function getRows()
    {
        return $this->getRepository()->findBy(
            array(),
            array('count' => 'DESC')
        );
    }

    /**
     * Get Row repository
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository('Levi9EProjectBundle:Row');
    }
}
