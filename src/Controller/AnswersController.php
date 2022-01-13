<?php

namespace App\Controller;


use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswersType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class AnswersController extends AbstractController
{
    #[Route('questions/{slug}/answers_new', name: 'answers_new')]
    public function new(Question $question, Request $request, EntityManagerInterface $entityManager) : Response
    {
        $answer = new Answer();
        $answer->setQuestion($question);
        $form = $this->createForm(AnswersType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirectToRoute('app_question_show', ['slug' => $question->getSlug()]);
        }
        return $this->render('answers/index.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('answers/{id}/vote/{direction}', name: 'answers_vote')]
    public function vote(Answer $answer, $direction, EntityManagerInterface $entityManager){
        $currentVote = $answer->getVotes();
        if ($direction == 'up'){
            $currentVote += 1;
            $answer->setVotes($currentVote);
        } else {
            $currentVote -= 1;
            $answer->setVotes($currentVote);
        }
        $entityManager->flush();

        return $this->json([
            'votes' => $answer->getVotes(),
        ]);
    }

}
