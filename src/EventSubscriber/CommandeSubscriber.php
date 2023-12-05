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
    private $entityManager;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
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
    $content .= "Montant total : " . $commande->getTotal() . " EUR\n" . "<br><br>";
       
    $details = $this->entityManager->getRepository(Detail::class)->findBy(['commande' => $commande]);
    
    var_dump($details);  // Ajoutez cette ligne pour afficher les détails dans les logs Symfony

    $content .= "Plats commandés :\n" . "<br><br>";
    
    foreach ($details as $detail) {
        $plat = $detail->getPlats();

        if ($plat) {
            $content .= "Nom du plat : " . $plat->getLibelle() . "\n" . "<br>";
            $content .= "Quantité : " . $detail->getQuantite() . "\n" . "<br>";
            $content .= "Prix unitaire : " . $plat->getPrix() . " EUR\n" . "<br>";
            $content .= "Sous-total : " . ($detail->getQuantite() * $plat->getPrix()) . " EUR\n" . "<br><br>";
        }
    }
    

    return $content;
}

}