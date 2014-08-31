<?php

namespace Levi9\EProjectBundle\Controller;

use Levi9\EProjectBundle\Entity\Row;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $stat = $this->getStatistics();
        return array(
            'stat' => $stat,
            'reset' => $form = $this->getResetForm()->createView()
        );
    }

    /**
     * @Route("/add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $row = new Row();
        $form = $this->getAddForm($row);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($row);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('levi9_eproject_default_index'));
        }

        return array('form' => $form->createView());
    }

    /**
     * Get form for adding new row
     *
     * @param Row $row
     * @return \Symfony\Component\Form\Form
     */
    protected function getAddForm(Row $row)
    {
        return $this->createFormBuilder($row)
            ->add('type')
            ->add('count', 'integer', array('attr' => array('min' => 1)))
            ->add('name')
            ->add('add', 'submit', array('label' => 'Add Battery'))
            ->getForm();
    }

    /**
     * Get form for statistics reset
     *
     * @return \Symfony\Component\Form\Form
     */
    protected function getResetForm()
    {
        return $this->createFormBuilder()
            ->add('reset', 'submit', array('label' => 'Reset all data'))
            ->setAction($this->generateUrl('levi9_eproject_default_reset'))
            ->getForm();
    }

    /**
     * @Route("reset")
     * @Method({"POST"})
     */
    public function resetAction(Request $request)
    {
        $form = $this->getResetForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $q = $em->createQuery('delete from Levi9EProjectBundle:Row');
            $numDeleted = $q->execute();
            return $this->redirect($this->generateUrl('levi9_eproject_default_index'));
        }
    }

    /**
     * Get collected batteries statistic
     *
     * @return array
     */
    protected function getStatistics()
    {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select('SUM(row.count) as cnt, row.type')
            ->from('Levi9EProjectBundle:Row', 'row')
            ->groupBy('row.type')
            ->orderBy('cnt', 'DESC');
        return $qb->getQuery()->getResult();
    }
}
