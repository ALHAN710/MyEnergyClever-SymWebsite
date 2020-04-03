<?php

namespace App\Controller;

use DateTime;
use App\Entity\Role;
use App\Entity\User1;
use Cocur\Slugify\Slugify;
use App\Service\Pagination;
use App\Form\Registration1Type;
use App\Repository\User1Repository;
use App\Form\AdminRegistration1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
        $slugify = new Slugify();
        $form = $this->createForm(AdminRegistration1Type::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());

            $userRole = new Role();
            $userRole->setTitle($user->getRole());
            $date = new DateTime(date('Y-m-d H:i:s'));
            $user->setCreatedAt($date);


            $manager->persist($userRole);

            $user->setHash($hash)
                ->addUserRole($userRole);

            /** @var UploadedFile $avatarFile */
            $avatarFile = $form->get('avatar')->getData();

            // this condition is needed because the 'avatar' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugify->slugify($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();

                // Move the file to the directory where avatars are stored
                try {
                    $avatarFile->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $user->setAvatar($newFilename);
            }

            /** @var UploadedFile $enterpriseLogoFile */
            $enterpriseLogoFile = $form->get('enterpriseLogo')->getData();

            // this condition is needed because the 'enterpriseLogo' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($enterpriseLogoFile) {
                $originalFilename = pathinfo($enterpriseLogoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugify->slugify($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $enterpriseLogoFile->guessExtension();

                // Move the file to the directory where enterpriseLogos are stored
                try {
                    $enterpriseLogoFile->move(
                        $this->getParameter('logo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'enterpriseLogoFilename' property to store the PDF file name
                // instead of its contents
                $user->setEnterpriseLogo($newFilename);
            }

            $manager->persist($user);
            $manager->flush();

            /*$this->addFlash(
                'success',
                "Compte utilisateur crée. Veuillez vous connecter !"
            );

            return $this->redirectToRoute('account_login2');*/

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
