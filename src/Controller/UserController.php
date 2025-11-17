<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[Route('/user/new', name: 'app_user_new')]
    public function new(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        if (null === $user->getAddress()) {
            $user->setAddress(new Address());
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                dump($form->getErrors(true, false));
                exit;
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $cropped = $request->request->get('avatar_cropped');
            if ($cropped) {
                $user->setAvatar($cropped);
            } else {
                $avatarFile = $form->get('avatar')->getData();
                if ($avatarFile instanceof UploadedFile) {
                    $mime = $avatarFile->getMimeType() ?? 'application/octet-stream';
                    $content = file_get_contents($avatarFile->getPathname());
                    $dataUrl = sprintf('data:%s;base64,%s', $mime, base64_encode($content));
                    $user->setAvatar($dataUrl);
                }
            }

            $plain = $form->get('plainPassword')->getData();
            if ($plain) {
                $user->setPassword($hasher->hashPassword($user, $plain));
            }

            $addr = $user->getAddress();
            $allEmpty = $addr
                && !trim((string) $addr->getCountry())
                && !trim((string) $addr->getCity())
                && !trim((string) $addr->getPostCode())
                && !trim((string) $addr->getStreet());

            if ($allEmpty) {
                $user->setAddress(null);
            }

            if (!$user->getRoles()) {
                $user->setRoles(['ROLE_USER']);
            }

            $user->setCreatedAt(new \DateTimeImmutable());
            $em->persist($user);
            $em->flush();

            return $this->redirect('localhost:5173/');
        }

        return $this->render('user/new.html.twig', ['form' => $form->createView()]);
    }
}
