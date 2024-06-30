<?php
/**
 * Login form authenticator.
 */

namespace App\Security;

use App\Service\UserServiceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class Login form authenticator.
 */
class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private const LOGIN_ROUTE = 'app_login';
    private const DEFAULT_ROUTE = 'recipe_index';

    /**
     * Contructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     * @param UserServiceInterface  $userService
     * @param TranslatorInterface   $translator
     */
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator, private readonly UserServiceInterface $userService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Override to change the request conditions that have to be
     * matched in order to handle the login form submit.
     *
     * This default implementation handles all POST requests to the
     * login path (@param Request $request Request.
     *
     * @return bool Type.
     * @see getLoginUrl()).
     */
    public function supports(Request $request): bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route') && $request->isMethod('POST');
    }

    /**
     * Create a passport for the current request.
     *
     * The passport contains the user, credentials and any additional information
     * that has to be checked by the Symfony Security system. For example, a login
     * form authenticator will probably return a passport containing the user, the
     * presented password and the CSRF token value.
     *
     * You may throw any AuthenticationException in this method in case of error (e.g.
     * a UserNotFoundException when the user cannot be found).
     *
     * @param Request $request Request.
     *
     * @return Passport Passport.
     */
    public function authenticate(Request $request): Passport
    {
        $email = (string) $request->request->get('email', '');
        $user = $this->userService->findUserByEmail($email);

        if (!$user) {
            $message = $this->translator->trans('message.user_not_found');
            throw new CustomUserMessageAuthenticationException($message);
        }

        if ($user->isBlocked) {
            $message = $this->translator->trans('message.account_blocked');
            throw new CustomUserMessageAuthenticationException($message);
        }

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials((string) $request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', (string) $request->request->get('_csrf_token')),
            ]
        );
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     *
     * @param Request        $request      Request.
     * @param TokenInterface $token        Token.
     * @param string         $firewallName Firewall.
     *
     * @return RedirectResponse|null Redircet Response.
     */
    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): ?RedirectResponse {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate(self::DEFAULT_ROUTE));
    }

    /**
     * Return the URL to the login page.
     *
     * @param Request $request Request
     *
     * @return string Type.
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
