<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {   
        return $this->render('question/homepage.html.twig');
    }

    /**
     * @Route("/questions/new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->getData();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('app_question_show', ['slug' => $question->getSlug()]);
        }

        return $this->render('question/new.html.twig', ['form' => $form->createView(),]);
//        $question = new Question();
//        $question->setName('Ou sont les toilettes ?');
//        $question->setSlug('ou-sont-les-toilettes');
//        $question->setQuestion("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.");
//        $question->setAskedAt(new \DateTimeImmutable());
//
//        $entityManager->persist($question);
//        $entityManager->flush();

//        return new Response('Enregistrement de la question: '.$question->getSlug(). ' id: '.$question->getId());

    }

    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show(Question $question)
    {
        return $this->render('question/show.html.twig', ['question' => $question]);
    }

    /**
     * @Route("/questions/{slug}/edit", name="app_question_edit")
     */
    public function edit(Question $question, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }

        return $this->render('question/edit.html.twig', [
            'form' => $form->createView(),
            'question'=> $question,
        ]);
    }

    /**
     * @Route("/questions/{slug}/delete", name="app_question_delete")
     */
    public function delete(Question $question, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($question);
        $entityManager->flush();

        return $this->redirectToRoute('app_homepage');
    }
}

