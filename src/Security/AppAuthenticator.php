<?php

namespace App\Security;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('username', '');

        $request->getSession()->set(Security::LAST_USERNAME, $username);

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser();
        //@intelephense-ignore-line
        if($user->getStatus()==0){
            return new RedirectResponse($this->urlGenerator->generate('create_password',['id'=>$user->getId()]));
        } else if( $user->getStatus()==1){
            if (in_array('ROLE_OPERATOR', $user->getRoles())) {
                return new RedirectResponse($this->urlGenerator->generate('app_manager'));
            } else if (in_array('ROLE_SUPERVISOR', $user->getRoles())) {
                return new RedirectResponse($this->urlGenerator->generate('app_superviseur'));
            } else if (in_array('ROLE_ADMIN', $user->getRoles())) {
                return new RedirectResponse($this->urlGenerator->generate('administration'));
            } else if (in_array('ROLE_VIEWER', $user->getRoles())) {
                return new RedirectResponse($this->urlGenerator->generate('app_resultat'));
            }
        } else if($user->getStatus()==2){
            return new RedirectResponse($this->urlGenerator->generate('app_bloqued'));
        }
       
       

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        //return new RedirectResponse($this->urlGenerator->generate('some_route'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
