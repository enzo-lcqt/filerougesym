<?php 

namespace App\EventSubscriber;

use App\Entity\Commande;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Entity\Plats;
use App\Entity\Detail;
use Doctrine\ORM\EntityManagerInterface;

class CommandeSubscriber implements EventSubscriber
{
    private $mailer;
    private $parameterBag;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $parameterBag,)
    {
        $this->mailer = $mailer;
        $this->parameterBag = $parameterBag;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // On vérifie si l'entité est une instance de Commande
        if (!$entity instanceof Commande) {
            return;
        }

        // Envoie l'e-mail de confirmation
        $this->sendConfirmationEmail($entity);

        
    }

 private function sendConfirmationEmail(Commande $commande)
{
    // Récupère l'adresse e-mail de destination depuis les paramètres
    $toEmail = $this->parameterBag->get('confirmation_email');

    // Construit le contenu de l'e-mail
    $emailContent = $this->buildEmailContent($commande);

    // Envoie l'e-mail
    $email = (new Email())
        ->from('enzo@gmail.com')
        ->to($toEmail)
        ->subject('Confirmation de commande')
        ->html($emailContent);

    $this->mailer->send($email);
}

private function buildEmailContent(Commande $commande)
{
    // Construit le contenu de l'e-mail avec les détails de la commande
    $content = "Commande confirmée avec succès.\n" . "<br><br>";
    $content .= "Détails de la commande :\n" . "<br><br>";
    $content .= "Date : " . $commande->getDateCommande()->format('Y-m-d H:i:s') . "\n" . "<br><br>";
    $content .= "Montant total : " . $commande->getTotal() . " EUR\n";

    

    

    return $content;
}

}