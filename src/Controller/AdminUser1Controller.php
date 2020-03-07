<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User1;
use App\Service\Pagination;
use App\Form\Registration1Type;
use App\Repository\User1Repository;
use App\Form\AdminRegistration1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUser1Controller extends AbstractController
{
    /**
     * @Route("/admin/user1/{page<\d+>?1}", name="admin_user1_index")
     */
    public function index(User1Repository $repo, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(User1::class)
            ->setCurrentPage($page)
            ->setLabelOrder('enterpriseName')
            ->setOrder('ASC');

        return $this->render('admin/user1/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet de créer un utilisateur
     *
     * @Route("/admin/user1/create", name="admin_user1_create")
     * 
     * @param User1 $user
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function create(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User1();
        $form = $this->createForm(AdminRegistration1Type::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());

            $userRole = new Role();
            $userRole->setTitle($user->getRole());
            $manager->persist($userRole);

            $user->setHash($hash)
                ->addUserRole($userRole);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le Compte utilisateur <strong> {$user->getEnterpriseName()}</strong> a été crée avec succès. !"
            );

            return $this->redirectToRoute('admin_user1_index');
        }
        return $this->render('admin/user1/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un Utilisateur
     * 
     * @Route("/admin/user1/{id}/delete", name="admin_user1_delete")
     *
     * @param User1 $user
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete(User1 $user, EntityManagerInterface $manager)
    {
        $_user = $user->getEnterpriseName();

        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "La suppression de l'utilisateur <strong>{$_user}</strong> a été effectuées avec succès !"
        );

        return $this->redirectToRoute("admin_user1_index");
    }
}
