<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Club;
use App\Form\ClassroomType;
use App\Form\ClubType;
use App\Repository\ClassroomRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    /**
     * @Route ("/listClassroom", name="listClassroom")
     */
    public function list(){
        $list=$this->getDoctrine()->getRepository(classroom::class)->findAll();
        return $this->render('Classroom/list.html.twig',[
            'list'=>$list
        ]);
    }
    /**
     * @Route("/addClassroom",name="addClassroom")
     */
    public function addClassroom (Request $request){

        $classroomObject= new Classroom();
        $form=$this->createForm(ClassroomType::class,$classroomObject);

        $form=$form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($classroomObject);
            $em->flush();
        }
        return $this->render('classroom/addClassroom.html.twig',[
            'form'=>$form->createView()
        ]);

    }
    /**
     * @Route("/editClassroom/{id}", name="editClassroom")
     */
    public function edit(ClassroomRepository $repository, $id, Request $request)
    {
        $classroom = $repository->find($id);
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->add('Update',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("listClassroom");
        }
        return $this->render('classroom/editClassroom.html.twig',
                [
                    'f'=>$form->createView()
                ]);
    }

    /**
     * @Route ("deleteClassroom/{id}", name="deleteClassroom")
     */
    public function delete(ClassroomRepository $repository, $id){
        $classroom = $repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($classroom);
        $em->flush();
        return $this->redirectToRoute("listClassroom");
    }

    /**
     * @Route("/Classroom", name="Classroom")
     */
    public function index(): Response
    {
        return $this->render('Classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
}
