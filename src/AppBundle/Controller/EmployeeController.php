<?php

namespace AppBundle\Controller;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployeeController extends Controller
{
     
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        return $this->render('pages/index.html.twig');
    }
    /**
     * @Route("/users", name="users")
     */
    public function userAction(Request $request)
    {
        $data = $this->getDoctrine()->getManager();
        $values= $data->getRepository('AppBundle:User')->findAll();  
        return $this->render('pages/user.html.twig', array('values' => $values));
    }
     /**
     * @Route("/users/create", name="newEmployee")
     */
    public function createAction(Request $request)
    {
        $data = new User;
        $form = $this->createFormBuilder($data)
            ->add('name', TextType::class)
            ->add('mobile', TextType::class)
            ->add('city', TextType::class)
            ->add('salary', TextType::class)
            ->add('Save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $name = $form['name']->getData();
            $mobile = $form['mobile']->getData();
            $city = $form['city']->getData();
            $salary = $form['salary']->getData();

            $data->setName($name);
            $data->setMobile($mobile);
            $data->setCity($city);
            $data->setSalary($salary);
            
            $em= $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            $this->addFlash('Message', "Record Inserted");
            return $this->redirectToRoute('users');
        }

        return $this->render('pages/create.html.twig', [
            'form' => $form->createView()]);
    }

      /**
     * @Route("/users/edit/{id}", name="editEmployee")
     */
    public function editAction($id, Request $request)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $user->setName($user->getName());
        $user->setMobile($user->getMobile());
        $user->setCity($user->getCity());
        $user->setSalary($user->getSalary());
        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('mobile', TextType::class)
            ->add('city', TextType::class)
            ->add('salary', TextType::class)
            ->add('Update', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $name = $form['name']->getData();
            $mobile = $form['mobile']->getData();
            $city = $form['city']->getData();
            $salary = $form['salary']->getData();
            
            $em= $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')->find($id);
            
            $user->setName($name);
            $user->setMobile($mobile);
            $user->setCity($city);
            $user->setSalary($salary);
            
            $em->flush();
            $this->addFlash('Message', "Record  Updated");
            return $this->redirectToRoute('users');
        }
        return $this->render('pages/update.html.twig', [
            'form' => $form->createView()]);
    }

     /**
     * @Route("/users/delete/{id}", name="deleteEmployee")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);
        $em->remove($user);
        $em->flush();
        $this->addFlash('Message', "Record  Deleted");
            return $this->redirectToRoute('users');
    }
    

}
