<?php
namespace Skrepr\ActionUtils;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig_Environment;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormFactoryInterface;

class ActionUtils implements ActionUtilsInterface
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        Twig_Environment $twig,
        FormFactoryInterface $formFactory,
        Session $session,
        RouterInterface $router,
        Registry $doctrine,
        TokenStorageInterface $tokenStorage
    ) {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->session = $session;
        $this->router = $router;
        $this->doctrine = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string $view
     * @param array $parameters
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(string $view, array $parameters = []): Response
    {
        $response = $this->twig->render($view, $parameters);
        return new Response($response);
    }

    /**
     * @param string $view
     * @param array $parameters
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderView(string $view, array $parameters = array()): string
    {
        return $this->twig->render($view, $parameters);
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param string $type
     * @param null $data
     * @param array $options
     * @return FormInterface
     */
    public function createForm(string $type, $data = null, array $options = array()): FormInterface
    {
        return $this->formFactory->create($type, $data, $options);
    }

    /**
     * Creates and returns a form builder instance.
     *
     * @param null $data
     * @param array $options
     * @return FormBuilderInterface
     */
    public function createFormBuilder($data = null, array $options = array()): FormBuilderInterface
    {
        return $this->formFactory->createBuilder(FormType::class, $data, $options);
    }

    /**
     * Adds a flash message to the current session for type.
     *
     * @param string $type
     * @param string $message
     */
    public function addFlash(string $type, string $message)
    {
        $this->session->getFlashBag()->add($type, $message);
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @see UrlGeneratorInterface
     *
     * @param string $route
     * @param array $parameters
     * @param int $referenceType
     * @return string
     */
    public function generateUrl(string $route, array $parameters = array(), int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $url
     * @param int $status
     * @return RedirectResponse
     */
    public function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Returns a RedirectResponse to the given route with the given parameters.
     *
     * @param string $route
     * @param array $parameters
     * @param int $status
     * @return RedirectResponse
     */
    public function redirectToRoute(string $route, array $parameters = array(), int $status = 302): RedirectResponse
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    /**
     * @return Registry
     */
    public function getDoctrine(): Registry
    {
        return $this->doctrine;
    }

    /**
     * Get a user from the Security Token Storage.
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     *
     * @final
     */
    public function getUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }
}
