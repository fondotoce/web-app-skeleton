<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HelloController extends AbstractController
{
    private array $messages = [
        ['message' => 'Hello', 'created' => '2025/06/12'],
        ['message' => 'Hi', 'created' => '2025/04/12'],
        ['message' => 'Bye!', 'created' => '2024/05/12'],
    ];
    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $em): Response
    {
//         $post = $em->getRepository(MicroPost::class)->find(6);
//         $comment = $post->getComments()->first();
//
//         $post->removeComment($comment);
//         $em->persist($post);
//         $em->flush();

//         $post->getComments()->count();

//         $comment = new Comment();
//         $comment->setText('Hello 2');
//         $comment->setPost($post);
//
//         $em->persist($comment);
//         $em->flush();

        // $post = $posts->find(19);
        // $comment = $post->getComments()[0];
        // $comment->setPost(null);
        // $comments->add($comment, true);

        // dd($post);

        // $user = new User();
        // $user->setEmail('email@email.com');
        // $user->setPassword('12345678');

        // $profile = new UserProfile();
        // $profile->setUser($user);
        // $profiles->add($profile, true);

        // $profile = $profiles->find(1);
        // $profiles->remove($profile, true);
        return $this->render(
            'hello/index.html.twig',
            [
                'messages' => $this->messages,
                'limit' => 3,
            ]
        );
//        return new Response(implode(', ', array_slice($this->messages, 0, $limit)));
    }

    #[Route('/messages/{id<\d+>}', name: 'app_show_one', methods: ['GET'])]
    public function showOne($id): Response
    {
        return $this->render(
            'hello/show_one.html.twig',
            [
                'message' => $this->messages[$id]
            ]
        );
//        return new Response($this->messages[$id]);
    }
}