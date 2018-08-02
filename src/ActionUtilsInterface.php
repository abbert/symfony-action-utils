<?php
namespace Skrepr\ActionUtils;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

interface ActionUtilsInterface
{
    /**
     * @param string $view
     * @param array $parameters
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(string $view, array $parameters = []): Response;

    /**
     * @param string $view
     * @param array $parameters
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderView(string $view, array $parameters = []): string;

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param string $type
     * @param null $data
     * @param array $options
     * @return FormInterface
     */
    public function createForm(string $type, $data = null, array $options = []): FormInterface;

    /**
     * Creates and returns a form builder instance.
     *
     * @param null $data
     * @param array $options
     * @return FormBuilderInterface
     */
    public function createFormBuilder($data = null, array $options = []): FormBuilderInterface;

    /**
     * Adds a flash message to the current session for type.
     *
     * @param string $type
     * @param string $message
     */
    public function addFlash(string $type, string $message);

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
    public function generateUrl(string $route, array $parameters = array(), int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string;

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $url
     * @param int $status
     * @return RedirectResponse
     */
    public function redirect(string $url, int $status = 302): RedirectResponse;

    /**
     * Returns a RedirectResponse to the given route with the given parameters.
     *
     * @param string $route
     * @param array $parameters
     * @param int $status
     * @return RedirectResponse
     */
    public function redirectToRoute(string $route, array $parameters = array(), int $status = 302): RedirectResponse;

    /**
     * @return Registry
     */
    public function getDoctrine(): Registry;

    /**
     * Get a user from the Security Token Storage.
     *
     * @return mixed
     * @throws \LogicException If SecurityBundle is not available
     * @see TokenInterface::getUser()
     */
    public function getUser();
}
