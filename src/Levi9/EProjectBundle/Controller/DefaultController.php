<?php

namespace Levi9\EProjectBundle\Controller;

use Levi9\EProjectBundle\Entity\Row;
use Levi9\EProjectBundle\Form\RowType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * FYI: You could defane route name: @Route("/", name="bla_bla_bla")
     * Then it will be more transparent, where is "levi9_eproject_default_index".
     * Because you can search in project for "bla_bla_bla" and find the target action.
     * 
     * @Route("/", name="batteries")
     * @Template()
     */
    public function indexAction()
    {
        $stat = $this->getRowRepository()->getStatistics();
        return array(
            'stat' => $stat,
            'reset' => $form = $this->getResetForm()->createView()
        );
    }

    /**
     * @Route("/add", name="batteries_add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(new RowType());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($form->getData());
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('batteries'));
        }

        return array('form' => $form->createView());
    }

    /**
     * Get form for statistics reset
     *
     * @return \Symfony\Component\Form\Form
     */
    protected function getResetForm()
    {
        return $this->createFormBuilder()
            ->add('reset', 'submit', array('label' => 'index.form.reset'))
            ->setAction($this->generateUrl('batteries_reset'))
            ->getForm();
    }

    /**
     * @Route("/reset", name="batteries_reset")
     * @Method({"POST"})
     */
    public function resetAction(Request $request)
    {
        $form = $this->getResetForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $numDeleted = $this->getRowRepository()->removeAll();
            return $this->redirect($this->generateUrl('batteries'));
        }
    }

    /**
     * Get Doctirne Roe=w repository
     *
     * @return \Levi9\EProjectBundle\Entity\RowRepository
     */
    protected function getRowRepository()
    {
        return $this->getDoctrine()->getRepository('Levi9EProjectBundle:Row');
    }
}
