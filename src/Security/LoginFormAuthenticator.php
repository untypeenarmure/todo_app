<?php

namespace App\Security;

use App\Service\MailerService;
use App\Repository\TaskRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    /**
     * Undocumented variable
     *
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * Undocumented variable
     *
     * @var MailerService
     */
    private $mailer;

    /**
     * Undocumented variable
     *
     * @var TaskRepository
     */
    private $repository;

    /**
     * Undocumented variable
     *
     * @var EntityManagerInterface
     */
    private $manager;



    public function __construct(UrlGeneratorInterface $urlGenerator, MailerService $mailer, TaskRepository $repository, EntityManagerInterface $manager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->repository = $repository;
        $this->mailer = $mailer;
        $this->manager = $manager;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $mailUser = $request->request->get('email');
        $username = explode('@', $mailUser)[0];
        $now = new DateTime();
        $tasks = $this->repository->findAll();

        $msg = "";

        foreach ($tasks as $task) {
            $diffdate = $now->diff($task->getDueAt());
            $parameters = [
                'username' => $username,
                'task' => $task,
                'msg' => $msg
            ];
            
            if ($diffdate->days <= 2 && ($now < $task->getDueAt())) {
                $msg = " arrive à échéance le ";
                
                $this->mailer->sendEmail(
                'Attention derrière toi !',
                $mailUser,
                $mailUser,
                'emails/alert.html.twig',
                $parameters);
            }
            else if($now > $task->getDueAt()) {
                $msg = " est arrivée à échéance";
                
                $this->mailer->sendEmail(
                'Attention derrière toi !',
                $mailUser,
                $mailUser,
                'emails/alert.html.twig',
                $parameters);
            }
        }

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate('task_listing'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
