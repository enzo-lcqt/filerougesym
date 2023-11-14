<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {
    }

    // Envoie un e-mail de confirmation avec le lien pour vérifier l'adresse e-mail
    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email): void
    {
        // Génère les composants nécessaires pour la signature du lien
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail()
        );

        // Obtient le contexte de l'e-mail
        $context = $email->getContext();

        // Ajoute les composants de la signature au contexte de l'e-mail
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        // Met à jour le contexte de l'e-mail
        $email->context($context);

        // Envoie l'e-mail
        $this->mailer->send($email);
    }

    /**
     * Valide la confirmation de l'adresse e-mail et met à jour l'utilisateur dans la base de données.
     *
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        // Valide la confirmation de l'adresse e-mail en utilisant les composants de la signature
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        // Met à jour le statut de vérification de l'utilisateur
        $user->setIsVerified(true);

        // Persiste les changements dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}