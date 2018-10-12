<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Crud;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Crud controller.
 *
 * @Route("crud")
 */
class CrudController extends Controller
{
    /**
     * Lists all crud entities.
     *
     * @Route("/", name="crud_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cruds = $em->getRepository('AppBundle:Crud')->findAll();

        return $this->render('crud/index.html.twig', array(
            'cruds' => $cruds,
        ));
    }

    /**
     * Creates a new crud entity.
     *
     * @Route("/new", name="crud_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $crud = new Crud();
        $form = $this->createForm('AppBundle\Form\CrudType', $crud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crud);
            $em->flush();

            return $this->redirectToRoute('crud_show', array('id' => $crud->getId()));
        }

        return $this->render('crud/new.html.twig', array(
            'crud' => $crud,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a crud entity.
     *
     * @Route("/{id}", name="crud_show")
     * @Method("GET")
     */
    public function showAction(Crud $crud)
    {
        $deleteForm = $this->createDeleteForm($crud);

        return $this->render('crud/show.html.twig', array(
            'crud' => $crud,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing crud entity.
     *
     * @Route("/{id}/edit", name="crud_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Crud $crud)
    {
        $deleteForm = $this->createDeleteForm($crud);
        $editForm = $this->createForm('AppBundle\Form\CrudType', $crud);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('crud_edit', array('id' => $crud->getId()));
        }

        return $this->render('crud/edit.html.twig', array(
            'crud' => $crud,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a crud entity.
     *
     * @Route("/{id}", name="crud_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Crud $crud)
    {
        $form = $this->createDeleteForm($crud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($crud);
            $em->flush();
        }

        return $this->redirectToRoute('crud_index');
    }

    /**
     * Creates a form to delete a crud entity.
     *
     * @param Crud $crud The crud entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Crud $crud)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('crud_delete', array('id' => $crud->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
